<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Models\Pengemasan;
use App\Models\PenyerahanBi;
use App\Models\TargetTahunan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanHarianController extends Controller
{
    public function index(Request $request)
    {
        $tahunEmisiOptions = HcsReceiving::select('emisi')->distinct()->orderBy('emisi', 'desc')->pluck('emisi')->toArray();
        if (empty($tahunEmisiOptions)) {
            $tahunEmisiOptions = ['2022', '2016']; // Fallback options
        }

        $filters = $this->getFilters($request, $tahunEmisiOptions);
        $data = $this->getReportData($filters);

        $reportData = $data['reportData'];
        $totals = $data['totals'];
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];

        $tahunAnggaranOptions = collect(range(date('Y') - 2, date('Y') + 2))->toArray();

        return view('laporan-harian.index', compact(
            'reportData',
            'totals',
            'tanggalLaporan',
            'tahunAnggaran',
            'tahunEmisi',
            'tahunAnggaranOptions',
            'tahunEmisiOptions'
        ));
    }

    public function export(Request $request)
    {
        $tahunEmisiOptions = HcsReceiving::select('emisi')->distinct()->pluck('emisi')->toArray();
        $filters = $this->getFilters($request, $tahunEmisiOptions);
        $data = $this->getReportData($filters);
        $reportData = $data['reportData'];
        $totals = $data['totals'];

        $filename = "laporan_harian_operasional_" . $filters['tanggal_laporan'] . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($reportData, $totals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Pecahan', 
                'Siap Kemas (Bilyet)', 
                'Siap Kirim (Bilyet)', 
                'Siap Kirim (Dus)', 
                'Total Persediaan (Bilyet)', 
                'Penyerahan Hari Ini (Bilyet)', 
                'Penyerahan Hari Ini (Dus)', 
                'Akumulasi Penyerahan (Bilyet)', 
                'Target', 
                'Sisa Target', 
                'Persentase (%)', 
                'Akumulasi Penerimaan HCS'
            ]);

            foreach ($reportData as $row) {
                fputcsv($file, [
                    $row['pecahan'],
                    $row['siap_kemas_bilyet'],
                    $row['siap_kirim_bilyet'],
                    $row['siap_kirim_dus'],
                    $row['total_persediaan_bilyet'],
                    $row['penyerahan_hari_ini_bilyet'],
                    $row['penyerahan_hari_ini_dus'],
                    $row['akumulasi_penyerahan'],
                    $row['target'],
                    $row['sisa_target'],
                    number_format($row['persentase_target'], 1, ',', '.'),
                    $row['akumulasi_penerimaan_hcs'],
                ]);
            }

            // Total Row
            $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0;
            fputcsv($file, [
                'TOTAL',
                $totals['siap_kemas_bilyet'],
                $totals['siap_kirim_bilyet'],
                $totals['siap_kirim_bilyet'] / 20000,
                $totals['total_persediaan_bilyet'],
                $totals['penyerahan_hari_ini_bilyet'],
                $totals['penyerahan_hari_ini_bilyet'] / 20000,
                $totals['akumulasi_penyerahan_bilyet'],
                $totals['target'],
                $totals['sisa_target'],
                number_format($totalPct, 1, ',', '.'),
                $totals['akumulasi_penerimaan_hcs'],
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $tahunEmisiOptions = HcsReceiving::select('emisi')->distinct()->pluck('emisi')->toArray();
        $filters = $this->getFilters($request, $tahunEmisiOptions);
        $data = $this->getReportData($filters);
        
        return view('laporan-harian.print-operasional', array_merge($filters, $data));
    }

    private function getFilters(Request $request, $tahunEmisiOptions = [])
    {
        $defaultEmisi = !empty($tahunEmisiOptions) ? $tahunEmisiOptions[0] : '2022';
        return [
            'tanggal_laporan' => $request->get('tanggal_laporan', Carbon::today()->toDateString()),
            'tahun_anggaran' => $request->get('tahun_anggaran', date('Y')),
            'tahun_emisi' => $request->get('tahun_emisi', $defaultEmisi),
        ];
    }

    private function getReportData(array $filters)
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $reportData = [];

        $totals = [
            'siap_kemas_bilyet' => 0,
            'siap_kirim_bilyet' => 0,
            'total_persediaan_bilyet' => 0,
            'penyerahan_hari_ini_bilyet' => 0,
            'akumulasi_penyerahan_bilyet' => 0,
            'target' => 0,
            'sisa_target' => 0,
            'akumulasi_penerimaan_hcs' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
            $penerimaanQuery = HcsReceiving::where('pecahan', $pecahan)
                ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan)
                ->where('tahun_anggaran', $tahunAnggaran);
            if ($tahunEmisi) {
                $penerimaanQuery->where('emisi', $tahunEmisi);
            }
            $totalPenerimaan = $penerimaanQuery->sum('jumlah');

            $pengemasanQuery = Pengemasan::where('pecahan', $pecahan)
                ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan)
                ->where('tahun_anggaran', $tahunAnggaran);
            if ($tahunEmisi) {
                $pengemasanQuery->where('tahun_emisi', $tahunEmisi);
            }
            $totalPengemasan = $pengemasanQuery->sum('jumlah_dus') * 20000;

            $penyerahanQuery = PenyerahanBi::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran);
            if ($tahunEmisi) {
                $penyerahanQuery->where('tahun_emisi', $tahunEmisi);
            }

            $penyerahanHariIniBilyet = (clone $penyerahanQuery)
                ->whereDate('tanggal_penyerahan', $tanggalLaporan)
                ->sum('jumlah_bilyet');
            $penyerahanHariIniDus = $penyerahanHariIniBilyet / 20000;

            $akumulasiPenyerahan = (clone $penyerahanQuery)
                ->whereDate('tanggal_penyerahan', '<=', $tanggalLaporan)
                ->sum('jumlah_bilyet');

            $siapKirimBilyet = $totalPengemasan - $akumulasiPenyerahan;
            $siapKirimDus = $siapKirimBilyet / 20000;

            $siapKemasBilyet = $totalPenerimaan - $totalPengemasan;

            $totalPersediaanBilyet = $siapKemasBilyet + $siapKirimBilyet;

            $targetQuery = TargetTahunan::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran);
            if ($tahunEmisi) {
                $targetQuery->where('tahun_emisi', $tahunEmisi);
            }
            $target = $targetQuery->sum('target');

            $sisaTarget = $target - $akumulasiPenyerahan;
            $persentaseTarget = $target > 0 ? ($akumulasiPenyerahan / $target) * 100 : 0;

            $reportData[] = [
                'pecahan' => $pecahan,
                'siap_kemas_bilyet' => $siapKemasBilyet,
                'siap_kirim_bilyet' => $siapKirimBilyet,
                'siap_kirim_dus' => $siapKirimDus,
                'total_persediaan_bilyet' => $totalPersediaanBilyet,
                'penyerahan_hari_ini_bilyet' => $penyerahanHariIniBilyet,
                'penyerahan_hari_ini_dus' => $penyerahanHariIniDus,
                'akumulasi_penyerahan' => $akumulasiPenyerahan,
                'target' => $target,
                'sisa_target' => $sisaTarget,
                'persentase_target' => $persentaseTarget,
                'akumulasi_penerimaan_hcs' => $totalPenerimaan,
            ];

            $totals['siap_kemas_bilyet'] += $siapKemasBilyet;
            $totals['siap_kirim_bilyet'] += $siapKirimBilyet;
            $totals['total_persediaan_bilyet'] += $totalPersediaanBilyet;
            $totals['penyerahan_hari_ini_bilyet'] += $penyerahanHariIniBilyet;
            $totals['akumulasi_penyerahan_bilyet'] += $akumulasiPenyerahan;
            $totals['target'] += $target;
            $totals['sisa_target'] += $sisaTarget;
            $totals['akumulasi_penerimaan_hcs'] += $totalPenerimaan;
        }

        return ['reportData' => $reportData, 'totals' => $totals];
    }

}
