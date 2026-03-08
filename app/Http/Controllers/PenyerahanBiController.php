<?php

namespace App\Http\Controllers;

use App\Models\PenyerahanBi;
use App\Models\Pengemasan;
use Illuminate\Http\Request;

class PenyerahanBiController extends Controller
{
    /**
     * Laporan Penyerahan ke BI dengan filter, sort, search, dan pagination.
     */
    public function index(Request $request)
    {
        $query = PenyerahanBi::with('user');

        if ($request->filled('pecahan'))
            $query->where('pecahan', $request->pecahan);
        if ($request->filled('tahun_anggaran'))
            $query->where('tahun_anggaran', $request->tahun_anggaran);
        if ($request->filled('tahun_emisi'))
            $query->where('tahun_emisi', $request->tahun_emisi);
        if ($request->filled('nomor_ba'))
            $query->where('nomor_ba', 'like', '%' . $request->nomor_ba . '%');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ba', 'like', "%{$search}%")
                    ->orWhere('pecahan', 'like', "%{$search}%")
                    ->orWhere('tahun_anggaran', 'like', "%{$search}%");
            });
        }
        if ($request->filled('tanggal_awal'))
            $query->whereDate('tanggal_penyerahan', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir'))
            $query->whereDate('tanggal_penyerahan', '<=', $request->tanggal_akhir);

        $sortCol = $request->input('sort', 'tanggal_penyerahan');
        $sortDir = $request->input('direction', 'desc');
        $allowed = ['tanggal_penyerahan', 'nomor_ba', 'pecahan', 'tahun_emisi', 'tahun_anggaran', 'nomor_dus_awal', 'jumlah_dus', 'jumlah_bilyet', 'status_data'];
        if (!in_array($sortCol, $allowed))
            $sortCol = 'tanggal_penyerahan';

        $query->orderBy($sortCol, $sortDir);

        $penyerahans = $query->paginate(20)->withQueryString();
        $missingWarnings = $this->getIncompletePenyerahanWarnings();

        return view('penyerahan-bi.index', compact('penyerahans', 'missingWarnings'));
    }

    /**
     * Form input penyerahan.
     */
    public function create()
    {
        $missingWarnings = $this->getIncompletePenyerahanWarnings();
        return view('penyerahan-bi.create', compact('missingWarnings'));
    }

    /**
     * Menghitung daftar nomor dus yang belum ada di pengemasans
     * untuk setiap record penyerahan yang statusnya Belum Lengkap.
     * Re-cek secara dinamis (bukan dari nilai status_data tersimpan).
     *
     * @return \Illuminate\Support\Collection  koleksi array per penyerahan
     */
    private function getIncompletePenyerahanWarnings(): \Illuminate\Support\Collection
    {
        // Ambil semua penyerahan yang mungkin belum lengkap
        $candidates = PenyerahanBi::all();
        $warnings = collect();

        foreach ($candidates as $p) {
            // Kumpulkan semua nomor dus dalam range penyerahan ini
            $rangeRequested = range($p->nomor_dus_awal, $p->nomor_dus_akhir);

            // Cari pengemasan yang memenuhi identitas + overlap range
            $pengemasans = Pengemasan::where('pecahan', $p->pecahan)
                ->where('tahun_emisi', $p->tahun_emisi)
                ->where('tahun_anggaran', $p->tahun_anggaran)
                ->where('dus_awal', '<=', $p->nomor_dus_akhir)
                ->where('dus_akhir', '>=', $p->nomor_dus_awal)
                ->get(['dus_awal', 'dus_akhir']);

            // Kumpulkan nomor yang sudah ada
            $existingNums = collect();
            foreach ($pengemasans as $pkg) {
                foreach (range($pkg->dus_awal, $pkg->dus_akhir) as $n) {
                    $existingNums->push($n);
                }
            }
            $existingNums = $existingNums->unique()->values();

            // Cari yang belum ada
            $missing = collect($rangeRequested)->filter(fn($n) => !$existingNums->contains($n))->values();

            if ($missing->isEmpty()) {
                // Jika sekarang sudah lengkap tapi status masih Belum Lengkap, update
                if ($p->status_data === 'Belum Lengkap') {
                    $p->update(['status_data' => 'Lengkap']);
                }
                continue;
            }

            // Masih belum lengkap
            if ($p->status_data === 'Lengkap') {
                $p->update(['status_data' => 'Belum Lengkap']);
            }

            // Format missing dus menjadi ranges yang lebih ringkas (misal: 1-5, 8, 10-12)
            $missingRanges = $this->formatNomorDusToRanges($missing->toArray());

            $warnings->push([
                'id' => $p->id,
                'nomor_ba' => $p->nomor_ba,
                'pecahan' => $p->pecahan,
                'tahun_anggaran' => $p->tahun_anggaran,
                'tahun_emisi' => $p->tahun_emisi,
                'nomor_range' => $p->nomor_dus_awal . '–' . $p->nomor_dus_akhir,
                'missing_count' => $missing->count(),
                'missing_ranges' => $missingRanges,
            ]);
        }

        return $warnings;
    }

    /**
     * Mengubah array nomor menjadi representasi range ringkas.
     * Contoh: [1,2,3,5,8,9] → "1–3, 5, 8–9"
     */
    private function formatNomorDusToRanges(array $nums): string
    {
        if (empty($nums))
            return '';
        sort($nums);
        $ranges = [];
        $start = $nums[0];
        $prev = $nums[0];

        for ($i = 1; $i < count($nums); $i++) {
            if ($nums[$i] === $prev + 1) {
                $prev = $nums[$i];
            }
            else {
                $ranges[] = $start === $prev ? $start : "{$start}–{$prev}";
                $start = $nums[$i];
                $prev = $nums[$i];
            }
        }
        $ranges[] = $start === $prev ? $start : "{$start}–{$prev}";

        return implode(', ', $ranges);
    }

    /**
     * API: Cek apakah range nomor dus sudah pernah diinput untuk identitas yang sama.
     */
    public function checkDuplicate(Request $request)
    {
        $pecahan = $request->pecahan;
        $tahunEmisi = $request->tahun_emisi;
        $tahunAnggaran = $request->tahun_anggaran;
        $noAwal = (int)$request->nomor_dus_awal;
        $noAkhir = (int)$request->nomor_dus_akhir;

        if (!$pecahan || !$tahunEmisi || !$tahunAnggaran || !$noAwal || !$noAkhir || $noAkhir < $noAwal) {
            return response()->json(['duplicate' => false]);
        }

        // Cek overlap range di tabel penyerahan_bi
        $overlap = PenyerahanBi::where('pecahan', $pecahan)
            ->where('tahun_emisi', $tahunEmisi)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('nomor_dus_awal', '<=', $noAkhir)
            ->where('nomor_dus_akhir', '>=', $noAwal)
            ->first();

        if ($overlap) {
            return response()->json([
                'duplicate' => true,
                'nomor_ba' => $overlap->nomor_ba,
                'range_tersimpan' => $overlap->nomor_dus_awal . ' – ' . $overlap->nomor_dus_akhir,
                'tanggal' => \Carbon\Carbon::parse($overlap->tanggal_penyerahan)->format('d/m/Y'),
            ]);
        }

        return response()->json(['duplicate' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penyerahan' => 'required|date',
            'nomor_ba' => 'required|string|max:255',
            'pecahan' => 'required|string|max:10',
            'tahun_emisi' => 'required|digits:4',
            'tahun_anggaran' => 'required|string|max:10',
            'nomor_dus_awal' => 'required|integer|min:1',
            'nomor_dus_akhir' => 'required|integer|gte:nomor_dus_awal',
            'jumlah_bilyet' => 'required|integer|min:1',
        ]);

        $awal = (int)$request->nomor_dus_awal;
        $akhir = (int)$request->nomor_dus_akhir;

        // --- Validasi Hard: Cegah duplikasi range nomor dus ---
        $overlap = PenyerahanBi::where('pecahan', $request->pecahan)
            ->where('tahun_emisi', $request->tahun_emisi)
            ->where('tahun_anggaran', $request->tahun_anggaran)
            ->where('nomor_dus_awal', '<=', $akhir)
            ->where('nomor_dus_akhir', '>=', $awal)
            ->first();

        if ($overlap) {
            return back()->withInput()->withErrors([
                'nomor_dus_awal' => "Range dus {$awal}–{$akhir} sudah tercatat dalam penyerahan Nomor BA: {$overlap->nomor_ba} (Range: {$overlap->nomor_dus_awal}–{$overlap->nomor_dus_akhir}). Tidak dapat menyimpan data duplikat.",
            ]);
        }

        $jumlah = $akhir - $awal + 1;

        // --- Validasi informatif: cek coverage dus di tabel pengemasans ---
        $covering = Pengemasan::where('pecahan', $request->pecahan)
            ->where('tahun_emisi', $request->tahun_emisi)
            ->where('tahun_anggaran', $request->tahun_anggaran)
            ->get(['dus_awal', 'dus_akhir']);

        // Bangun set nomor dus yang sudah ada (dari pengemasans)
        $existing = collect();
        foreach ($covering as $p) {
            foreach (range($p->dus_awal, $p->dus_akhir) as $n) {
                $existing->push($n);
            }
        }
        $existing = $existing->unique();

        // Hitung nomor dalam range yang ADA dan BELUM ADA
        $rangeRequested = collect(range($awal, $akhir));
        $jumlahAda = $rangeRequested->filter(fn($n) => $existing->contains($n))->count();
        $jumlahBelumAda = $jumlah - $jumlahAda;
        $statusData = $jumlahBelumAda === 0 ? 'Lengkap' : 'Belum Lengkap';

        $penyerahan = PenyerahanBi::create([
            'tanggal_penyerahan' => $request->tanggal_penyerahan,
            'nomor_ba' => $request->nomor_ba,
            'pecahan' => $request->pecahan,
            'tahun_emisi' => $request->tahun_emisi,
            'tahun_anggaran' => $request->tahun_anggaran,
            'nomor_dus_awal' => $awal,
            'nomor_dus_akhir' => $akhir,
            'jumlah_dus' => $jumlah,
            'jumlah_bilyet' => $request->jumlah_bilyet,
            'status_data' => $statusData,
            'created_by' => auth()->id(),
        ]);

        if ($jumlahBelumAda > 0) {
            $estimasiBilyet = $jumlahBelumAda * 45000;
            session()->flash('warning_penyerahan', [
                'range_diminta' => "{$awal} – {$akhir}",
                'jumlah_diminta' => $jumlah,
                'jumlah_ada' => $jumlahAda,
                'jumlah_belum_ada' => $jumlahBelumAda,
                'estimasi_bilyet' => $estimasiBilyet,
            ]);
        }

        return redirect()->route('penyerahan-bi.index')
            ->with('success', 'Data penyerahan berhasil disimpan.');
    }

    /**
     * Export CSV.
     */
    public function export(Request $request)
    {
        $query = PenyerahanBi::with('user');
        $this->applyFilters($query, $request);
        $rows = $query->orderBy('tanggal_penyerahan', 'desc')->get();

        $filename = 'laporan_penyerahan_bi_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Nomor BA', 'Pecahan', 'TA', 'TE', 'No Dus Awal', 'No Dus Akhir', 'Jml Dus', 'Jml Bilyet', 'Status', 'Petugas']);
            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r->tanggal_penyerahan->format('d/m/Y'),
                    $r->nomor_ba,
                    $r->pecahan,
                    $r->tahun_anggaran,
                    $r->tahun_emisi,
                    $r->nomor_dus_awal,
                    $r->nomor_dus_akhir,
                    $r->jumlah_dus,
                    $r->jumlah_bilyet,
                    $r->status_data,
                    $r->user->name ?? '-',
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * View print PDF.
     */
    public function print(Request $request)
    {
        $query = PenyerahanBi::with('user');
        $this->applyFilters($query, $request);
        $penyerahans = $query->orderBy('tanggal_penyerahan', 'desc')->get();

        return view('penyerahan-bi.print', compact('penyerahans'));
    }

    /**
     * Helper: terapkan filter request ke query.
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('pecahan'))
            $query->where('pecahan', $request->pecahan);
        if ($request->filled('tahun_anggaran'))
            $query->where('tahun_anggaran', $request->tahun_anggaran);
        if ($request->filled('tahun_emisi'))
            $query->where('tahun_emisi', $request->tahun_emisi);
        if ($request->filled('nomor_ba'))
            $query->where('nomor_ba', 'like', '%' . $request->nomor_ba . '%');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nomor_ba', 'like', "%$s%")
            ->orWhere('pecahan', 'like', "%$s%")
            ->orWhere('tahun_anggaran', 'like', "%$s%"));
        }
        if ($request->filled('tanggal_awal'))
            $query->whereDate('tanggal_penyerahan', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir'))
            $query->whereDate('tanggal_penyerahan', '<=', $request->tanggal_akhir);
    }
}
