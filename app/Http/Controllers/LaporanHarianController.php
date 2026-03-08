<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Models\HcsSorting;
use App\Models\Pengemasan;
use App\Models\PenyerahanBi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LaporanHarianController extends Controller
{
    private array $pecahans = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

    public function index(Request $request)
    {
        $data = $this->aggregateData($request);
        return view('laporan-harian.index', $data);
    }

    public function print(Request $request)
    {
        $data = $this->aggregateData($request);
        return view('laporan-harian.print', $data);
    }

    public function export(Request $request)
    {
        $data = $this->aggregateData($request);
        $filename = "laporan_harian_" . \Carbon\Carbon::parse($data['tanggal'])->format('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Header Info
            fputcsv($file, ['LAPORAN HARIAN TERINTEGRASI']);
            fputcsv($file, ['Tanggal', \Carbon\Carbon::parse($data['tanggal'])->format('d/m/Y')]);
            if ($data['pecahan'])
                fputcsv($file, ['Pecahan', $data['pecahan']]);
            if ($data['tahunAnggaran'])
                fputcsv($file, ['TA', $data['tahunAnggaran']]);
            if ($data['tahunEmisi'])
                fputcsv($file, ['TE', $data['tahunEmisi']]);
            fputcsv($file, []);

            // Summary per Pecahan
            fputcsv($file, ['RINGKASAN PER PECAHAN']);
            fputcsv($file, ['Pecahan', 'Penerimaan (Bilyet)', 'Sortir (Pack)', 'Sortir (Bilyet)', 'Kemas (Pack)', 'Kemas (Dus)', 'Serah BI (Bilyet)', 'Serah BI (Dus)']);
            foreach ($this->pecahans as $p) {
                fputcsv($file, [
                    $p,
                    $data['penerimaanPerPecahan'][$p]['jumlah'],
                    $data['sortirPerPecahan'][$p]['jumlah_pack'],
                    $data['sortirPerPecahan'][$p]['jumlah_bilyet'],
                    $data['pengemasanPerPecahan'][$p]['jumlah_pack'],
                    $data['pengemasanPerPecahan'][$p]['jumlah_dus'],
                    $data['penyerahanPerPecahan'][$p]['jumlah_bilyet'],
                    $data['penyerahanPerPecahan'][$p]['jumlah_dus'],
                ]);
            }
            fputcsv($file, []);

            // Detail Penerimaan
            fputcsv($file, ['DETAIL PENERIMAAN']);
            fputcsv($file, ['Nomor Bon', 'Pecahan', 'Emisi', 'TA', 'Jumlah', 'Gilir']);
            foreach ($data['penerimaans'] as $row) {
                fputcsv($file, [$row->nomor_bon, $row->pecahan, $row->emisi, $row->tahun_anggaran, $row->jumlah, $row->gilir]);
            }
            fputcsv($file, []);

            // Detail Penyortiran
            fputcsv($file, ['DETAIL PENYORTIRAN']);
            fputcsv($file, ['Pecahan', 'Batch', 'Seri', 'Emisi', 'TA', 'Pack', 'Bilyet', 'Gilir']);
            foreach ($data['sortirs'] as $row) {
                fputcsv($file, [$row->pecahan, $row->batch, $row->seri, $row->emisi, $row->tahun_anggaran, $row->jumlah_pack, $row->jumlah_bilyet, $row->gilir]);
            }
            fputcsv($file, []);

            // Detail Pengemasan
            fputcsv($file, ['DETAIL PENGEMASAN']);
            fputcsv($file, ['Pecahan', 'Batch', 'Seri', 'TE', 'TA', 'Pack', 'Dus', 'Gilir']);
            foreach ($data['pengemasans'] as $row) {
                fputcsv($file, [$row->pecahan, $row->batch, $row->seri, $row->tahun_emisi, $row->tahun_anggaran, $row->jumlah_pack, $row->jumlah_dus, $row->gilir]);
            }
            fputcsv($file, []);

            // Detail Penyerahan BI
            fputcsv($file, ['DETAIL PENYERAHAN BI']);
            fputcsv($file, ['Nomor BA', 'Pecahan', 'TE', 'TA', 'Bilyet', 'Dus', 'Status']);
            foreach ($data['penyerahans'] as $row) {
                fputcsv($file, [$row->nomor_ba, $row->pecahan, $row->tahun_emisi, $row->tahun_anggaran, $row->jumlah_bilyet, $row->jumlah_dus, $row->status_data]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function aggregateData(Request $request): array
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $pecahan = $request->get('pecahan', '');
        $tahunAnggaran = $request->get('tahun_anggaran', '');
        $tahunEmisi = $request->get('tahun_emisi', '');

        // --- 1. PENERIMAAN ---
        $penerimaanQuery = HcsReceiving::whereDate('tanggal_penerimaan', $tanggal);
        if ($pecahan)
            $penerimaanQuery->where('pecahan', $pecahan);
        if ($tahunAnggaran)
            $penerimaanQuery->where('tahun_anggaran', $tahunAnggaran);
        if ($tahunEmisi)
            $penerimaanQuery->where('emisi', $tahunEmisi);
        $penerimaans = $penerimaanQuery->get();

        $penerimaanPerPecahan = [];
        foreach ($this->pecahans as $p) {
            $filtered = $penerimaans->where('pecahan', $p);
            $penerimaanPerPecahan[$p] = [
                'jumlah' => $filtered->sum('jumlah'),
                'records' => $filtered->count(),
            ];
        }

        // --- 2. PENYORTIRAN ---
        $sortirQuery = HcsSorting::whereDate('tanggal_sortir', $tanggal);
        if ($pecahan)
            $sortirQuery->where('pecahan', $pecahan);
        if ($tahunAnggaran)
            $sortirQuery->where('tahun_anggaran', $tahunAnggaran);
        if ($tahunEmisi)
            $sortirQuery->where('emisi', $tahunEmisi);
        $sortirs = $sortirQuery->get();

        $sortirPerPecahan = [];
        foreach ($this->pecahans as $p) {
            $filtered = $sortirs->where('pecahan', $p);
            $sortirPerPecahan[$p] = [
                'jumlah_bilyet' => $filtered->sum('jumlah_bilyet'),
                'jumlah_pack' => $filtered->sum('jumlah_pack'),
                'records' => $filtered->count(),
            ];
        }

        // --- 3. PENGEMASAN ---
        $pengemasanQuery = Pengemasan::whereDate('tanggal_pengemasan', $tanggal);
        if ($pecahan)
            $pengemasanQuery->where('pecahan', $pecahan);
        if ($tahunAnggaran)
            $pengemasanQuery->where('tahun_anggaran', $tahunAnggaran);
        if ($tahunEmisi)
            $pengemasanQuery->where('tahun_emisi', $tahunEmisi);
        $pengemasans = $pengemasanQuery->get();

        $pengemasanPerPecahan = [];
        foreach ($this->pecahans as $p) {
            $filtered = $pengemasans->where('pecahan', $p);
            $pengemasanPerPecahan[$p] = [
                'jumlah_pack' => $filtered->sum('jumlah_pack'),
                'jumlah_dus' => $filtered->sum('jumlah_dus'),
                'records' => $filtered->count(),
            ];
        }

        // --- 4. PENYERAHAN KE BI ---
        $penyerahanQuery = PenyerahanBi::whereDate('tanggal_penyerahan', $tanggal);
        if ($pecahan)
            $penyerahanQuery->where('pecahan', $pecahan);
        if ($tahunAnggaran)
            $penyerahanQuery->where('tahun_anggaran', $tahunAnggaran);
        if ($tahunEmisi)
            $penyerahanQuery->where('tahun_emisi', $tahunEmisi);
        $penyerahans = $penyerahanQuery->get();

        $penyerahanPerPecahan = [];
        foreach ($this->pecahans as $p) {
            $filtered = $penyerahans->where('pecahan', $p);
            $penyerahanPerPecahan[$p] = [
                'jumlah_bilyet' => $filtered->sum('jumlah_bilyet'),
                'jumlah_dus' => $filtered->sum('jumlah_dus'),
                'records' => $filtered->count(),
            ];
        }

        // --- Dropdown Options ---
        $allYearsAnggaran = collect(
            HcsReceiving::distinct()->pluck('tahun_anggaran')
            ->merge(HcsSorting::distinct()->pluck('tahun_anggaran'))
            ->merge(Pengemasan::distinct()->pluck('tahun_anggaran'))
            ->merge(PenyerahanBi::distinct()->pluck('tahun_anggaran'))
        )->unique()->filter()->sort()->values();

        $allYearsEmisi = collect(
            HcsReceiving::distinct()->pluck('emisi')
            ->merge(HcsSorting::distinct()->pluck('emisi'))
            ->merge(Pengemasan::distinct()->pluck('tahun_emisi'))
            ->merge(PenyerahanBi::distinct()->pluck('tahun_emisi'))
        )->unique()->filter()->sort()->values();

        return [
            'tanggal' => $tanggal,
            'pecahan' => $pecahan,
            'tahunAnggaran' => $tahunAnggaran,
            'tahunEmisi' => $tahunEmisi,
            'penerimaanPerPecahan' => $penerimaanPerPecahan,
            'sortirPerPecahan' => $sortirPerPecahan,
            'pengemasanPerPecahan' => $pengemasanPerPecahan,
            'penyerahanPerPecahan' => $penyerahanPerPecahan,
            'penerimaans' => $penerimaans,
            'sortirs' => $sortirs,
            'pengemasans' => $pengemasans,
            'penyerahans' => $penyerahans,
            'allYearsAnggaran' => $allYearsAnggaran,
            'allYearsEmisi' => $allYearsEmisi,
        ];
    }
}
