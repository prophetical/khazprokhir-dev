<?php

namespace App\Http\Controllers;

use App\Models\DetailPengemasan;
use App\Models\Pack;
use App\Models\Pengemasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengemasanController extends Controller
{
    public function index(Request $request)
    {
        $readyGroupsAll = $this->findReadyToPackageGroups($request->all());

        // Manual Pagination for array
        $currentPage = $request->input('page', 1);
        $perPage = 20;
        $currentItems = $readyGroupsAll->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $readyGroups = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $readyGroupsAll->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        $missingGaps = $this->detectMissingDusGaps();

        return view('pengemasan.index', compact('readyGroups', 'missingGaps'));
    }

    public function data(Request $request)
    {
        $query = Pengemasan::with(['user', 'packs']);

        // Handle Filter Pecahan
        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }

        // Handle Filter Range Tanggal
        if ($request->filled('tanggal_awal')) {
            $query->where('tanggal_pengemasan', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal_pengemasan', '<=', $request->tanggal_akhir);
        }

        // Handle Filter Gilir
        if ($request->filled('gilir')) {
            $query->where('gilir', $request->gilir);
        }

        // Handle Search General
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                return $q->where('pecahan', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        return $userQ->where('name', 'like', "%{$search}%");
                    }
                    );
            });
        }

        // Handle Search Dus Spesifik
        if ($request->filled('search_dus') && is_numeric($request->search_dus)) {
            $searchDus = (int) $request->search_dus;
            $query->where('dus_awal', '<=', $searchDus)
                ->where('dus_akhir', '>=', $searchDus);
        }

        // Handle Order
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if ($sortColumn === 'petugas') {
            $query->join('users', 'pengemasans.created_by', '=', 'users.id')
                ->orderBy('users.name', $sortDirection)
                ->select('pengemasans.*');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $pengemasans = $query->paginate(20)->withQueryString();

        $missingGaps = $this->detectMissingDusGaps();

        return view('pengemasan.data', compact('pengemasans', 'sortColumn', 'sortDirection', 'missingGaps'));
    }

    public function export(Request $request)
    {
        $query = Pengemasan::with(['user', 'packs']);

        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                return $q->where('pecahan', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        return $userQ->where('name', 'like', "%{$search}%");
                    }
                    );
            });
        }

        if ($request->filled('search_dus') && is_numeric($request->search_dus)) {
            $searchDus = (int) $request->search_dus;
            $query->where('dus_awal', '<=', $searchDus)
                ->where('dus_akhir', '>=', $searchDus);
        }

        $sortColumn = $request->input('sort', 'tanggal_pengemasan');
        $sortDirection = $request->input('direction', 'desc');

        if ($sortColumn === 'petugas') {
            $query->join('users', 'pengemasans.created_by', '=', 'users.id')
                ->orderBy('users.name', $sortDirection)
                ->select('pengemasans.*');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $filename = 'data_pengemasan_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Gilir', 'Thn Anggaran', 'Thn Emisi', 'Pecahan', 'Batch', 'Seri', 'Pack Awal', 'Pack Akhir', 'Jml Pack', 'Total Bilyet', 'Dus', 'Dus Awal', 'Dus Akhir', 'Petugas']);

            $query->chunk(100, function ($pengemasans) use ($file) {
                foreach ($pengemasans as $row) {
                    fputcsv($file, [
                        $row->tanggal_pengemasan->format('Y-m-d'),
                        $row->gilir,
                        $row->tahun_anggaran,
                        $row->tahun_emisi,
                        $row->pecahan,
                        $row->batch,
                        $row->seri,
                        $row->pack_awal,
                        $row->pack_akhir,
                        $row->jumlah_pack,
                        $row->total_bilyet,
                        $row->jumlah_dus,
                        $row->dus_awal,
                        $row->dus_akhir,
                        $row->user->name ?? '-',
                    ]);

                }
            }
            );

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = Pengemasan::with(['user', 'packs']);

        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                return $q->where('pecahan', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        return $userQ->where('name', 'like', "%{$search}%");
                    }
                    );
            });
        }

        if ($request->filled('search_dus') && is_numeric($request->search_dus)) {
            $searchDus = (int) $request->search_dus;
            $query->where('dus_awal', '<=', $searchDus)
                ->where('dus_akhir', '>=', $searchDus);
        }

        $sortColumn = $request->input('sort', 'tanggal_pengemasan');
        $sortDirection = $request->input('direction', 'desc');

        if ($sortColumn === 'petugas') {
            $query->join('users', 'pengemasans.created_by', '=', 'users.id')
                ->orderBy('users.name', $sortDirection)
                ->select('pengemasans.*');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $pengemasans = $query->get();

        return view('pengemasan.print', compact('pengemasans'));
    }

    private function findReadyToPackageGroups(array $filters = [])
    {
        // Ambil semua pack yang sudah disortir tapi belum dikemas
        $query = Pack::whereNotNull('hcs_sorting_id')
            ->whereNull('id_pengemasan')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->select(
                'packs.pack_number',
                'packs.batch',
                'packs.seri',
                'hcs_receivings.pecahan',
                'hcs_receivings.emisi',
                'hcs_receivings.tahun_anggaran'
            );

        // Terapkan filter
        if (! empty($filters['pecahan'])) {
            $query->where('hcs_receivings.pecahan', $filters['pecahan']);
        }
        if (! empty($filters['tahun_anggaran'])) {
            $query->where('hcs_receivings.tahun_anggaran', $filters['tahun_anggaran']);
        }
        if (! empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('packs.batch', 'like', "%{$s}%")
                    ->orWhere('packs.seri', 'like', "%{$s}%")
                    ->orWhere('hcs_receivings.pecahan', 'like', "%{$s}%");
            });
        }

        $packs = $query
            ->orderBy('hcs_receivings.tahun_anggaran')
            ->orderBy('hcs_receivings.emisi')
            ->orderBy('hcs_receivings.pecahan')
            ->orderBy('packs.batch')
            ->orderBy('packs.seri')
            ->orderBy('packs.pack_number')
            ->get();

        $grouped = [];
        foreach ($packs as $pack) {
            $key = "{$pack->tahun_anggaran}|{$pack->emisi}|{$pack->pecahan}|{$pack->batch}|{$pack->seri}";
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'tahun_anggaran' => $pack->tahun_anggaran,
                    'emisi' => $pack->emisi,
                    'pecahan' => $pack->pecahan,
                    'batch' => $pack->batch,
                    'seri' => $pack->seri,
                    'numbers' => [],
                ];
            }
            $grouped[$key]['numbers'][] = $pack->pack_number;
        }

        $readyGroups = [];

        foreach ($grouped as $group) {
            $numbers = $group['numbers'];
            if (count($numbers) < 1) {
                continue;
            }

            $contiguousBlocks = [];
            $currentBlock = [];

            foreach ($numbers as $num) {
                if (empty($currentBlock)) {
                    $currentBlock[] = $num;
                } else {
                    $last = end($currentBlock);
                    if ($num == $last + 1) {
                        $currentBlock[] = $num;
                    } else {
                        $contiguousBlocks[] = $currentBlock;
                        $currentBlock = [$num];
                    }
                }
            }
            if (! empty($currentBlock)) {
                $contiguousBlocks[] = $currentBlock;
            }

            foreach ($contiguousBlocks as $block) {
                // Jangan pecah per 4. Tapi kita harus pastikan bahwa block ini minimal 4.
                // Jika ingin Kemas Semua, pastikan bisa kelipatan 4.
                // Namun, validasi form sudah memastikan harus kelipatan 4 ketika store.
                // Untuk "Kemas Semua", kita bisa batasi block akhir agar selalu kelipatan 4
                // dari pack_awal, ATAU biarkan index menampilkan keseluruhan range, dan "Kemas Semua"
                // akan mengirimkan range maksimal yang merupakan kelipatan 4.

                $totalInBlock = count($block);

                // Jika total pack dalam range berurutan ini kurang dari 1, lewati
                if ($totalInBlock < 1) {
                    continue;
                }

                $packIds = Pack::where('batch', $group['batch'])
                    ->where('seri', $group['seri'])
                    ->whereNotNull('hcs_sorting_id')
                    ->whereNull('id_pengemasan')
                    ->whereIn('pack_number', $block)
                    ->pluck('id');

                $hasBuntut = ($totalInBlock % 4 !== 0) || Pack::whereIn('id', $packIds)->where('jumlah', '<', 45000)->exists();

                $readyGroups[] = [
                    'tahun_anggaran' => $group['tahun_anggaran'],
                    'emisi' => $group['emisi'],
                    'pecahan' => $group['pecahan'],
                    'batch' => $group['batch'],
                    'seri' => $group['seri'],
                    'pack_awal' => $block[0],
                    'pack_akhir' => end($block),
                    'jumlah_pack' => $totalInBlock,
                    'has_buntut' => $hasBuntut,
                ];
            }
        }

        return collect($readyGroups);
    }

    public function create(Request $request)
    {
        $lastDus = DetailPengemasan::whereHas('pengemasan', function ($query) use ($request) {
            if ($request->has('pecahan')) {
                $query->where('pecahan', $request->pecahan);
            }
            if ($request->has('tahun_anggaran')) {
                $query->where('tahun_anggaran', $request->tahun_anggaran);
            }
            if ($request->has('tahun_emisi')) {
                $query->where('tahun_emisi', $request->tahun_emisi);
            }
        })->orderBy('no_dus', 'desc')
            ->first();

        $lastNumber = $lastDus ? $lastDus->no_dus : 0;

        $packsData = Pack::where('batch', $request->batch)
            ->where('seri', $request->seri)
            ->whereNotNull('hcs_sorting_id')
            ->whereNull('id_pengemasan')
            ->get(['pack_number', 'jumlah']);

        return view('pengemasan.create', [
            'auto_fill' => $request->all(),
            'last_number' => $lastNumber,
            'packsData' => $packsData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pengemasan' => 'required|date',
            'gilir' => 'required',
            'tahun_anggaran' => 'required',
            'tahun_emisi' => 'required|digits:4',
            'pecahan' => 'required',
            'batch' => 'required',
            'seri' => 'required',
            'selected_chunks' => 'required_without:selected_packs|array',
            'selected_packs' => 'required_without:selected_chunks|array',
            'is_manual' => 'nullable|boolean',
            'dus_awal' => $request->boolean('is_manual') ? 'required|numeric|min:1' : 'nullable',
            'dus_akhir' => $request->boolean('is_manual') ? 'required|numeric|gte:dus_awal' : 'nullable',
        ]);

        $packNumbers = [];
        $parsedChunks = [];

        // Gabungkan dari selected_chunks (range) dan selected_packs (satuan)
        $selectedChunksInput = $request->input('selected_chunks', []);
        $selectedPacksInput = $request->input('selected_packs', []);

        // sort by starting pack
        usort($selectedChunksInput, function ($a, $b) {
            $aStart = (int) explode('-', $a)[0];
            $bStart = (int) explode('-', $b)[0];

            return $aStart <=> $bStart;
        });

        foreach ($selectedChunksInput as $chunkStr) {
            $parts = explode('-', $chunkStr);
            if (count($parts) == 2) {
                $cAwal = (int) $parts[0];
                $cAkhir = (int) $parts[1];
                $parsedChunks[] = ['awal' => $cAwal, 'akhir' => $cAkhir];
                for ($i = $cAwal; $i <= $cAkhir; $i++) {
                    $packNumbers[] = $i;
                }
            }
        }

        foreach ($selectedPacksInput as $pNum) {
            $pNum = (int) $pNum;
            $packNumbers[] = $pNum;
            $parsedChunks[] = ['awal' => $pNum, 'akhir' => $pNum];
        }

        $packNumbers = array_unique($packNumbers);

        $jumlahPack = count($packNumbers);

        if (! $request->boolean('is_manual_sisa')) {
            if ($jumlahPack <= 0 || $jumlahPack % 4 !== 0) {
                $field = $request->has('selected_chunks') ? 'selected_chunks' : 'selected_packs';
                throw ValidationException::withMessages([
                    $field => "Jumlah pack ($jumlahPack) tidak valid. Jumlah pack harus kelipatan 4 untuk dikemas (1 module pengemasan = 4 pack). Hubungi admin jika ingin mengemas sisa pack.",
                ]);
            }
        }

        $packAwal = min($packNumbers);
        $packAkhir = max($packNumbers);

        $packs = Pack::where('packs.batch', $request->batch)
            ->where('packs.seri', $request->seri)
            ->whereIn('packs.pack_number', $packNumbers)
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.pecahan', $request->pecahan)
            ->where('hcs_receivings.emisi', $request->tahun_emisi)
            ->where('hcs_receivings.tahun_anggaran', $request->tahun_anggaran)
            ->select('packs.*')
            ->get();

        if ($packs->count() !== $jumlahPack) {
            $field = $request->has('selected_chunks') ? 'selected_chunks' : 'selected_packs';
            throw ValidationException::withMessages([
                $field => 'Terdapat ketidaksesuaian jumlah pack dengan yang dipilih. Beberapa pack mungkin tidak tersedia atau identitas berbeda.',
            ]);
        }

        foreach ($packs as $pack) {
            $field = $request->has('selected_chunks') ? 'selected_chunks' : 'selected_packs';
            if (is_null($pack->hcs_sorting_id)) {
                throw ValidationException::withMessages([
                    $field => "Pack nomor {$pack->pack_number} belum selesai disortir. Harap selesaikan penyortiran.",
                ]);
            }
            if (! is_null($pack->id_pengemasan)) {
                throw ValidationException::withMessages([
                    $field => "Pack nomor {$pack->pack_number} sudah tercatat dalam riwayat pengemasan sebelumnya.",
                ]);
            }
        }

        if ($request->boolean('is_manual_sisa')) {
            $manualDetails = $request->input('manual_details', []);
            if (empty($manualDetails)) {
                throw ValidationException::withMessages(['manual_details' => 'Detail dus manual harus diisi jika mode Kemas Sisa Pack aktif.']);
            }
            $jumlahDus = count($manualDetails);
        } else {
            $jumlahDus = ($jumlahPack / 4) * 9;
        }

        if ($request->boolean('is_manual')) {
            $nomorDusAwal = (int) $request->dus_awal;
            $nomorDusAkhir = (int) $request->dus_akhir;

            // Validasi 1: jumlah dus sesuai range input manual
            if (($nomorDusAkhir - $nomorDusAwal + 1) !== $jumlahDus) {
                throw ValidationException::withMessages([
                    'dus_awal' => "Range nomor dus tidak sesuai dengan jumlah dus hasil pengemasan. Harus tepat $jumlahDus dus untuk $jumlahPack pack ini.",
                ]);
            }
        } else {
            // Mode Auto: Ambil nomor dus terakhir dari identitas yang sama
            $lastDus = DetailPengemasan::whereHas('pengemasan', function ($query) use ($request) {
                $query->where('pecahan', $request->pecahan)
                    ->where('tahun_anggaran', $request->tahun_anggaran)
                    ->where('tahun_emisi', $request->tahun_emisi);
            })->orderBy('no_dus', 'desc')
                ->first();

            if ($request->boolean('is_manual_sisa')) {
                // Untuk sisa pack, nomor dus awal dan akhir diambil dari input manual details
                $sudahAdaNoDus = array_column($manualDetails, 'no_dus');
                $nomorDusAwal = min($sudahAdaNoDus);
                $nomorDusAkhir = max($sudahAdaNoDus);
            } else {
                $nomorDusAwal = $lastDus ? $lastDus->no_dus + 1 : 1;
                $nomorDusAkhir = $nomorDusAwal + $jumlahDus - 1;
            }
        }

        // Validasi 2: Cek apakah ada nomor dus di range ini yang sudah dipakai (Berlaku untuk Manual & Auto)
        if ($request->boolean('is_manual_sisa')) {
            $sudahAdaNoDus = array_column($manualDetails, 'no_dus');
            $usedDusExists = DetailPengemasan::whereHas('pengemasan', function ($query) use ($request) {
                $query->where('pecahan', $request->pecahan)
                    ->where('tahun_anggaran', $request->tahun_anggaran)
                    ->where('tahun_emisi', $request->tahun_emisi);
            })->whereIn('no_dus', $sudahAdaNoDus)->exists();
        } else {
            $usedDusExists = DetailPengemasan::whereHas('pengemasan', function ($query) use ($request) {
                $query->where('pecahan', $request->pecahan)
                    ->where('tahun_anggaran', $request->tahun_anggaran)
                    ->where('tahun_emisi', $request->tahun_emisi);
            })->whereBetween('no_dus', [$nomorDusAwal, $nomorDusAkhir])->exists();
        }

        if ($usedDusExists) {
            throw ValidationException::withMessages([
                'dus_awal' => 'Satu atau lebih nomor dus sudah digunakan pada pengemasan lain untuk pecahan, tahun emisi, dan tahun anggaran yang sama.',
            ]);
        }

        $totalBilyet = $packs->sum('jumlah');

        DB::beginTransaction();
        try {
            $pengemasan = Pengemasan::create([
                'tanggal_pengemasan' => $request->tanggal_pengemasan,
                'gilir' => $request->gilir,
                'tahun_anggaran' => $request->tahun_anggaran,
                'tahun_emisi' => $request->tahun_emisi,
                'pecahan' => $request->pecahan,
                'batch' => $request->batch,
                'seri' => $request->seri,
                'pack_awal' => $packAwal,
                'pack_akhir' => $packAkhir,
                'jumlah_pack' => $jumlahPack,
                'jumlah_dus' => $jumlahDus,
                'total_bilyet' => $totalBilyet,
                'dus_awal' => $nomorDusAwal,
                'dus_akhir' => $nomorDusAkhir,
                'created_by' => auth()->id(),
            ]);

            Pack::whereIn('id', $packs->pluck('id'))->update(['id_pengemasan' => $pengemasan->id]);

            // [NEW] Kunci data penyortiran terkait
            $sortingIds = $packs->pluck('hcs_sorting_id')->filter()->unique();
            if ($sortingIds->isNotEmpty()) {
                \App\Models\HcsSorting::whereIn('id', $sortingIds)->update(['status_kunci_pengemasan' => 1]);
            }

            if ($request->boolean('is_manual_sisa')) {
                foreach ($manualDetails as $detail) {
                    DetailPengemasan::create([
                        'id_pengemasan' => $pengemasan->id,
                        'no_dus' => $detail['no_dus'],
                        'pack_awal' => $detail['pack_awal'] ?? $packAwal,
                        'pack_akhir' => $detail['pack_akhir'] ?? $packAkhir,
                        'seri_awal' => $detail['seri_awal'] ?? $request->seri,
                        'seri_akhir' => $detail['seri_akhir'] ?? $request->seri,
                        'batch' => $request->batch,
                        'jumlah_bilyet' => $detail['jumlah_bilyet'] ?? 0,
                    ]);
                }
            } else {
                $this->generateDetailPengemasan($pengemasan, $request->seri, $nomorDusAwal, $request->batch, $parsedChunks, $packs);
            }

            DB::commit();

            return redirect()->route('pengemasan.index')->with('success', 'Data pengemasan berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage())->withInput();
        }
    }

    private function generateDetailPengemasan($pengemasan, $seriRaw, $startNumber, $batch, $parsedChunks, $packs)
    {
        // Pola pembacaan seri: format ideal [AAA]-[BBB][No]
        // Contoh: RJ-MJ9 -> AAA = RJ, BBB = MJ
        $parts = explode('-', $seriRaw);
        $seriAwalPrefix = $parts[0] ?? '';

        $seriAkhirFull = $parts[1] ?? '';
        // Ekstrak huruf dari seri akhir (menghilangkan angka di belakang)
        preg_match('/^[A-Za-z]+/', $seriAkhirFull, $matches);
        $seriAkhirPrefix = $matches[0] ?? $seriAwalPrefix;

        if (empty($seriAwalPrefix) || empty($seriAkhirPrefix)) {
            throw new \Exception("Format seri $seriRaw tidak sesuai dengan standar konvensi pengemasan.");
        }

        // Pola 1: seriAwal + A -> seriAwal + U
        $pola1Awal = $seriAwalPrefix.'A';
        $pola1Akhir = $seriAwalPrefix.'U';

        // Pola 2: seriAkhir + A -> seriAkhir + U
        $pola2Awal = $seriAkhirPrefix.'A';
        $pola2Akhir = $seriAkhirPrefix.'U';

        // Pola 3: seriAwal + V -> seriAkhir + Z
        $pola3Awal = $seriAwalPrefix.'V';
        $pola3Akhir = $seriAkhirPrefix.'Z';

        $currentNoDus = $startNumber;

        foreach ($parsedChunks as $chunk) {
            $p1 = $chunk['awal'];
            $p2 = $p1 + 1;
            $p3 = $p1 + 2;
            $p4 = $p1 + 3;

            // Hitung total bilyet untuk chunk ini saja (4 pack)
            $chunkPackNumbers = [$p1, $p2, $p3, $p4];
            $chunkBilyet = $packs->whereIn('pack_number', $chunkPackNumbers)->sum('jumlah');

            // Standard bilyet per dus adalah total chunk / 9
            // Jika 180.000, maka 20.000. Jika buntut, sesuaikan.
            $bilyetPerDus = floor($chunkBilyet / 9);
            $sisaBilyet = $chunkBilyet % 9;

            // Dus 1: Pack 1-4, Pola 3
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p1, $p4, $pola3Awal, $pola3Akhir, $batch, $dusBilyet);

            // Dus 2: Pack 1, Pola 2
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p1, $p1, $pola2Awal, $pola2Akhir, $batch, $dusBilyet);

            // Dus 3: Pack 1, Pola 1
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p1, $p1, $pola1Awal, $pola1Akhir, $batch, $dusBilyet);

            // Dus 4: Pack 2, Pola 2
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p2, $p2, $pola2Awal, $pola2Akhir, $batch, $dusBilyet);

            // Dus 5: Pack 2, Pola 1
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p2, $p2, $pola1Awal, $pola1Akhir, $batch, $dusBilyet);

            // Dus 6: Pack 3, Pola 2
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p3, $p3, $pola2Awal, $pola2Akhir, $batch, $dusBilyet);

            // Dus 7: Pack 3, Pola 1
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p3, $p3, $pola1Awal, $pola1Akhir, $batch, $dusBilyet);

            // Dus 8: Pack 4, Pola 2
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p4, $p4, $pola2Awal, $pola2Akhir, $batch, $dusBilyet);

            // Dus 9: Pack 4, Pola 1
            $dusBilyet = $bilyetPerDus + ($sisaBilyet > 0 ? 1 : 0);
            if ($sisaBilyet > 0) {
                $sisaBilyet--;
            }
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p4, $p4, $pola1Awal, $pola1Akhir, $batch, $dusBilyet);

        }
    }

    private function createDusRow($id_pengemasan, $noDus, $packAwal, $packAkhir, $seriAwal, $seriAkhir, $batch, $jumlahBilyet)
    {
        DetailPengemasan::create([
            'id_pengemasan' => $id_pengemasan,
            'no_dus' => $noDus,
            'pack_awal' => $packAwal,
            'pack_akhir' => $packAkhir,
            'seri_awal' => $seriAwal,
            'seri_akhir' => $seriAkhir,
            'batch' => $batch,
            'jumlah_bilyet' => $jumlahBilyet,
        ]);
    }

    public function show($id)
    {
        $pengemasan = Pengemasan::with(['detailPengemasans', 'user'])->findOrFail($id);

        return view('pengemasan.show', compact('pengemasan'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pengemasan = Pengemasan::findOrFail($id);

            // 1. Ambil id penyortiran dari pack yang terikat dengan pengemasan ini
            $sortingIds = Pack::where('id_pengemasan', $pengemasan->id)->pluck('hcs_sorting_id')->filter()->unique();

            // 2. Lepas relasi pack dari pengemasan ini
            Pack::where('id_pengemasan', $pengemasan->id)->update(['id_pengemasan' => null]);

            // 3. Evaluasi ulang status penguncian penyortiran
            //    Jika sebuah sesi sortir (HcsSorting) sudah tidak memiliki pack yang DIKEMAS lagi,
            //    maka gembok / lock-nya bisa dibuka (status_kunci_pengemasan = 0)
            foreach ($sortingIds as $sId) {
                // Adakah satupun pack dari sesi sortir ini yang masih bersatus terkemas?
                $packMasihDikemas = Pack::where('hcs_sorting_id', $sId)
                    ->whereNotNull('id_pengemasan')
                    ->exists();

                if (! $packMasihDikemas) {
                    \App\Models\HcsSorting::where('id', $sId)->update(['status_kunci_pengemasan' => 0]);
                }
            }

            // 4. Hapus detail dus yang dihasilkan dari pengemasan ini
            DetailPengemasan::where('id_pengemasan', $pengemasan->id)->delete();

            // 5. Hapus riwayat pengemasannya
            $pengemasan->delete();

            DB::commit();

            return redirect()->route('pengemasan.data')->with('success', 'Data pengemasan berhasil dihapus. Data penyortiran yang terkait telah dibuka kembali/bisa diedit.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus: '.$e->getMessage());
        }
    }

    /**
     * Deteksi gap/celah nomor dus yang hilang per identitas (Pecahan, TA, TE).
     */
    private function detectMissingDusGaps(): array
    {
        $detailList = DB::table('detail_pengemasans')
            ->join('pengemasans', 'detail_pengemasans.id_pengemasan', '=', 'pengemasans.id')
            ->select('pengemasans.pecahan', 'pengemasans.tahun_anggaran', 'pengemasans.tahun_emisi', 'detail_pengemasans.no_dus')
            ->orderBy('pengemasans.pecahan')
            ->orderBy('pengemasans.tahun_anggaran')
            ->orderBy('pengemasans.tahun_emisi')
            ->orderBy('detail_pengemasans.no_dus')
            ->get();

        $groupedDus = [];
        foreach ($detailList as $d) {
            $key = $d->pecahan.'|'.$d->tahun_anggaran.'|'.$d->tahun_emisi;
            if (! isset($groupedDus[$key])) {
                $groupedDus[$key] = [];
            }
            $groupedDus[$key][] = $d->no_dus;
        }

        $missingGaps = [];
        foreach ($groupedDus as $key => $numbers) {
            [$pecahan, $ta, $te] = explode('|', $key);
            $expected = 1;
            $missingRanges = [];

            foreach ($numbers as $num) {
                if ($num > $expected) {
                    $startGap = $expected;
                    $endGap = $num - 1;
                    $missingRanges[] = ($startGap == $endGap) ? $startGap : $startGap.'-'.$endGap;
                }
                if ($num >= $expected) {
                    $expected = $num + 1;
                }
            }

            if (! empty($missingRanges)) {
                $missingGaps[] = [
                    'pecahan' => $pecahan,
                    'tahun_anggaran' => $ta,
                    'tahun_emisi' => $te,
                    'ranges' => implode(', ', $missingRanges),
                ];
            }
        }

        return $missingGaps;
    }
}
