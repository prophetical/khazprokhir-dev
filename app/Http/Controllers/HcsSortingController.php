<?php

namespace App\Http\Controllers;

use App\Models\HcsSorting;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HcsSortingController extends Controller
{
    public function index(Request $request)
    {
        // 0. Ambil daftar tahun dan emisi untuk dropdown
        $availableYears = DB::table('hcs_receivings')->distinct()->whereNotNull('tahun_anggaran')->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');
        $availableEmissions = DB::table('hcs_receivings')->distinct()->whereNotNull('emisi')->orderBy('emisi', 'desc')->pluck('emisi');

        // 1. Siapin Query untuk Grup yang Tersedia beserta Filternya
        $query = Pack::whereNull('hcs_sorting_id')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id');

        if ($request->filled('pecahan')) {
            $query->where('hcs_receivings.pecahan', $request->pecahan);
        }
        if ($request->filled('batch')) {
            $query->where('packs.batch', 'like', '%'.$request->batch.'%');
        }
        if ($request->filled('seri')) {
            $query->where('packs.seri', 'like', '%'.$request->seri.'%');
        }
        if ($request->filled('tahun_anggaran')) {
            $query->where('hcs_receivings.tahun_anggaran', $request->tahun_anggaran);
        }
        if ($request->filled('emisi')) {
            $query->where('hcs_receivings.emisi', $request->emisi);
        }

        $AvailableGroups = $query->select('hcs_receivings.pecahan', 'packs.batch', 'packs.seri', DB::raw('count(*) as total_pack'))
            ->groupBy('hcs_receivings.pecahan', 'packs.batch', 'packs.seri')
            ->orderBy('total_pack', 'desc')
            ->paginate(20)->withQueryString();

        // 2. Bikin Ringkasan per Pecahan
        $summaryQuery = Pack::whereNull('hcs_sorting_id')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id');

        if ($request->filled('tahun_anggaran')) {
            $summaryQuery->where('hcs_receivings.tahun_anggaran', $request->tahun_anggaran);
        }
        if ($request->filled('emisi')) {
            $summaryQuery->where('hcs_receivings.emisi', $request->emisi);
        }

        $summaryData = $summaryQuery->select('hcs_receivings.pecahan', DB::raw('count(*) as total_pack'))
            ->groupBy('hcs_receivings.pecahan')
            ->pluck('total_pack', 'hcs_receivings.pecahan')
            ->toArray();

        $expectedPecahan = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $summaries = [];
        $totalAllPacks = 0;
        foreach ($expectedPecahan as $p) {
            $count = $summaryData[$p] ?? 0;
            $summaries[$p] = $count;
            $totalAllPacks += $count;
        }

        return view('hcs-sorting.index', compact('AvailableGroups', 'summaries', 'totalAllPacks', 'availableYears', 'availableEmissions'));
    }

    public function create(Request $request)
    {
        $pecahan = $request->query('pecahan');
        $batch = $request->query('batch');
        $seri = $request->query('seri');

        if (! $pecahan || ! $batch || ! $seri) {
            return redirect()->route('hcs-sorting.index')->with('error', 'Silahkan pilih grup data terlebih dahulu.');
        }

        // Ambil semua pack untuk grup spesifik ini (baik yang udah disortir maupun belum, maks 100)
        // Kita perlu tau status masing-masing pack 1-100.
        $packsData = Pack::join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.pecahan', $pecahan)
            ->where('packs.batch', $batch)
            ->where('packs.seri', $seri)
            ->where('packs.pack_number', '<=', 100)
            ->select('packs.*', 'packs.supplier as pack_supplier', 'hcs_receivings.pecahan', 'hcs_receivings.emisi', 'hcs_receivings.tahun_anggaran')
            ->get();

        $emisi = $packsData->first()->emisi ?? null;
        $tahun_anggaran = $packsData->first()->tahun_anggaran ?? '2025';
        $packsData = $packsData->keyBy('pack_number');

        return view('hcs-sorting.create', compact('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran', 'packsData'));
    }

    public function store(Request $request)
    {
        $isManual = $request->has('is_manual');

        $request->validate([
            'pecahan' => 'required',
            'batch' => 'required',
            'seri' => 'required',
            'emisi' => 'required',
            'tahun_anggaran' => 'required',
            'supplier' => 'required|in:Rikyet,Cutpack',
            'petugas_1' => 'required',
            'petugas_2' => 'nullable',
            'tanggal' => 'required|date',
            'gilir' => 'required',
            'selected_packs' => 'required|array|min:'.($isManual ? '1' : '4'),
        ]);

        $selectedPacks = $request->selected_packs;
        sort($selectedPacks);

        if (! $isManual) {
            // Validasi: Harus dalam kelompok berisi 4 dan berurutan (Boundary & Multiples of 4)
            $contiguousBlocks = [];
            $currentBlock = [];
            foreach ($selectedPacks as $packNum) {
                $packNum = (int) $packNum;
                if (empty($currentBlock)) {
                    $currentBlock[] = $packNum;
                } else {
                    $lastNum = end($currentBlock);
                    if ($packNum == $lastNum + 1) {
                        $currentBlock[] = $packNum;
                    } else {
                        $contiguousBlocks[] = $currentBlock;
                        $currentBlock = [$packNum];
                    }
                }
            }
            if (! empty($currentBlock)) {
                $contiguousBlocks[] = $currentBlock;
            }

            foreach ($contiguousBlocks as $block) {
                if (count($block) % 4 !== 0) {
                    throw ValidationException::withMessages([
                        'selected_packs' => 'Pack yang dipilih harus berurutan dan berkelipatan 4 (contoh: 1-4, 5-8, dll).',
                    ]);
                }

                // Pastikan juga kelompoknya dimulai dari batas kelipatan 4 yang benar (1, 5, 9, 13...)
                if (($block[0] - 1) % 4 !== 0) {
                    throw ValidationException::withMessages([
                        'selected_packs' => 'Posisi awal pack yang dipilih tidak valid. Harus dimulai dari kelipatan yang benar (misal: 1, 5, 9, dst).',
                    ]);
                }
            }
        }

        // Pastiin gak ada pack terpilih yang ternyata udah disortir duluan
        $alreadySorted = Pack::where('batch', $request->batch)
            ->where('seri', $request->seri)
            ->whereIn('pack_number', $selectedPacks)
            ->whereNotNull('hcs_sorting_id')
            ->exists();

        if ($alreadySorted) {
            throw ValidationException::withMessages([
                'selected_packs' => 'Salah satu atau lebih pack yang dipilih sudah disortir sebelumnya.',
            ]);
        }

        $jumlahPack = count($selectedPacks);
        $jumlahBilyet = Pack::where('batch', $request->batch)
            ->where('seri', $request->seri)
            ->whereIn('pack_number', $selectedPacks)
            ->sum('jumlah');

        DB::beginTransaction();

        try {
            $sorting = HcsSorting::create([
                'pecahan' => $request->pecahan,
                'batch' => $request->batch,
                'seri' => $request->seri,
                'emisi' => $request->emisi,
                'tahun_anggaran' => $request->tahun_anggaran,
                'supplier' => $request->supplier,
                'packs_selected' => $selectedPacks,
                'jumlah_pack' => $jumlahPack,
                'jumlah_bilyet' => $jumlahBilyet,
                'petugas_1' => $request->petugas_1,
                'petugas_2' => $request->petugas_2,
                'tanggal_sortir' => $request->tanggal,
                'gilir' => $request->gilir,
                'created_by' => auth()->id(),
            ]);

            // Update data pack-nya
            Pack::where('batch', $request->batch)
                ->where('seri', $request->seri)
                ->whereIn('pack_number', $selectedPacks)
                ->update(['hcs_sorting_id' => $sorting->id]);

            DB::commit();

            return redirect()->route('hcs-sorting.index')->with('success', 'Data penyortiran berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }
}
