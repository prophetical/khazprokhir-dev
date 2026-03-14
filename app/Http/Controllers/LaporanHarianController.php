<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Models\Pengemasan;
use App\Models\PenyerahanBi;
use App\Models\TargetBulananPengemasan;
use App\Models\TargetTahunan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanHarianController extends Controller
{
    public function index(Request $request)
    {
        $options = $this->getYearOptions();
        $tahunAnggaranOptions = $options['tahun_anggaran'];
        $tahunEmisiOptions = $options['tahun_emisi'];

        $filters = $this->getFilters($request, $tahunEmisiOptions);
        $data = $this->getReportData($filters);

        $reportData = $data['reportData'];
        $totals = $data['totals'];
        $secondaryData = $data['secondaryData'];
        $secondaryTotals = $data['secondaryTotals'];
        $sisaHariKerja = $data['sisaHariKerja'];

        $hctsInventoryData = $this->getHctsInventoryData($filters);

        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];

        return view('laporan-harian.index', compact(
            'reportData',
            'totals',
            'secondaryData',
            'secondaryTotals',
            'sisaHariKerja',
            'hctsInventoryData',
            'tanggalLaporan',
            'tahunAnggaran',
            'tahunEmisi',
            'tahunAnggaranOptions',
            'tahunEmisiOptions'
        ));
    }

    public function export(Request $request)
    {
        $options = $this->getYearOptions();
        $tahunEmisiOptions = $options['tahun_emisi'];
        $filters = $this->getFilters($request, $tahunEmisiOptions);
        $data = $this->getReportData($filters);
        $reportData = $data['reportData'];
        $totals = $data['totals'];

        $filename = 'laporan_harian_operasional_'.$filters['tanggal_laporan'].'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
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
                'Akumulasi Penerimaan HCS',
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
        $options = $this->getYearOptions();
        $tahunEmisiOptions = $options['tahun_emisi'];
        $filters = $this->getFilters($request, $tahunEmisiOptions);
        $data = $this->getReportData($filters);

        return view('laporan-harian.print-operasional', array_merge($filters, $data));
    }

    private function getFilters(Request $request, $tahunEmisiOptions = [])
    {
        $defaultEmisi = ! empty($tahunEmisiOptions) ? $tahunEmisiOptions[0] : '2022';

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
            $penyerahanHariIniDus = ceil($penyerahanHariIniBilyet / 20000);

            $akumulasiPenyerahan = (clone $penyerahanQuery)
                ->whereDate('tanggal_penyerahan', '<=', $tanggalLaporan)
                ->sum('jumlah_bilyet');

            $siapKirimBilyet = $totalPengemasan - $akumulasiPenyerahan;
            $siapKirimDus = ceil($siapKirimBilyet / 20000);

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

        $secondaryData = $this->getSecondaryReportData($filters);

        return ['reportData' => $reportData, 'totals' => $totals, 'secondaryData' => $secondaryData['data'], 'secondaryTotals' => $secondaryData['totals'], 'sisaHariKerja' => $secondaryData['sisaHariKerja']];
    }

    private function getSecondaryReportData(array $filters)
    {
        $tanggalLaporan = Carbon::parse($filters['tanggal_laporan']);
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $month = $tanggalLaporan->month;
        $targetColumn = 'bulan_'.$month;
        $startOfMonth = $tanggalLaporan->copy()->startOfMonth();
        $kemasDate = $tanggalLaporan->copy()->subDay()->toDateString();

        $sisaHariKerja = $this->calculateSisaHariKerja($tanggalLaporan);

        $data = [];
        $totals = [
            'target_penyerahan_bulan' => 0,
            'penyerahan_bulan' => 0,
            'sisa_target_bilyet' => 0,
            'sisa_target_doos' => 0,
            'target_produksi_harian' => 0,
            'kemas_g1' => 0,
            'kemas_g2' => 0,
            'kemas_g3' => 0,
            'total_kemas' => 0,
            'hcs_rikyet' => 0,
            'hcs_cutpack' => 0,
            'total_hcs' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
            // 1. Target Pengemasan Bulan
            $target = TargetBulananPengemasan::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tahun_emisi', $tahunEmisi)
                ->first();
            $targetBulan = $target ? ($target->{ $targetColumn} ?? 0) : 0;

            $pengemasanBulanBilyet = Pengemasan::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tahun_emisi', $tahunEmisi)
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth->toDateString())
                ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan->toDateString())
                ->sum(DB::raw('jumlah_dus * 20000'));

            // 3. Sisa Target
            $sisaTargetBilyet = $targetBulan - $pengemasanBulanBilyet;
            $sisaTargetDoos = ceil($sisaTargetBilyet / 20000);

            // 6. Target Produksi Harian
            $targetProduksiHarian = $sisaHariKerja > 0 ? floor($sisaTargetBilyet / $sisaHariKerja) : 0;

            // 7. Data Kemas (Accumulation in current month up to tanggalLaporan)
            $kemasG1Query = Pengemasan::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth->toDateString())
                ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan->toDateString())
                ->where('gilir', '1');
            if ($tahunEmisi) {
                $kemasG1Query->where('tahun_emisi', $tahunEmisi);
            }
            $kemasG1 = $kemasG1Query->sum(DB::raw('jumlah_dus * 20000'));

            $kemasG2Query = Pengemasan::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth->toDateString())
                ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan->toDateString())
                ->where('gilir', '2');
            if ($tahunEmisi) {
                $kemasG2Query->where('tahun_emisi', $tahunEmisi);
            }
            $kemasG2 = $kemasG2Query->sum(DB::raw('jumlah_dus * 20000'));

            $kemasG3Query = Pengemasan::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth->toDateString())
                ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan->toDateString())
                ->where('gilir', '3');
            if ($tahunEmisi) {
                $kemasG3Query->where('tahun_emisi', $tahunEmisi);
            }
            $kemasG3 = $kemasG3Query->sum(DB::raw('jumlah_dus * 20000'));

            $totalKemas = $kemasG1 + $kemasG2 + $kemasG3;

            // 9. Penerimaan HCS (Accumulation in current month up to tanggalLaporan)
            $hcsRikyetQuery = HcsReceiving::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->whereDate('tanggal_penerimaan', '>=', $startOfMonth->toDateString())
                ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan->toDateString())
                ->where('supplier', 'Rikyet');
            if ($tahunEmisi) {
                $hcsRikyetQuery->where('emisi', $tahunEmisi);
            }
            $hcsRikyet = $hcsRikyetQuery->sum('jumlah');

            $hcsCutpackQuery = HcsReceiving::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->whereDate('tanggal_penerimaan', '>=', $startOfMonth->toDateString())
                ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan->toDateString())
                ->where('supplier', 'Cutpack');
            if ($tahunEmisi) {
                $hcsCutpackQuery->where('emisi', $tahunEmisi);
            }
            $hcsCutpack = $hcsCutpackQuery->sum('jumlah');

            $totalHcs = $hcsRikyet + $hcsCutpack;

            $row = [
                'pecahan' => $pecahan,
                'target_penyerahan_bulan' => $targetBulan,
                'penyerahan_bulan' => $pengemasanBulanBilyet,
                'sisa_target_bilyet' => $sisaTargetBilyet,
                'sisa_target_doos' => $sisaTargetDoos,
                'target_produksi_harian' => $targetProduksiHarian,
                'kemas_g1' => $kemasG1,
                'kemas_g2' => $kemasG2,
                'kemas_g3' => $kemasG3,
                'total_kemas' => $totalKemas,
                'hcs_rikyet' => $hcsRikyet,
                'hcs_cutpack' => $hcsCutpack,
                'total_hcs' => $totalHcs,
            ];

            $data[] = $row;

            // Aggregate totals
            $totals['target_penyerahan_bulan'] += $targetBulan;
            $totals['penyerahan_bulan'] += $pengemasanBulanBilyet;
            $totals['sisa_target_bilyet'] += $sisaTargetBilyet;
            $totals['sisa_target_doos'] += $sisaTargetDoos;
            $totals['target_produksi_harian'] += $targetProduksiHarian;
            $totals['kemas_g1'] += $kemasG1;
            $totals['kemas_g2'] += $kemasG2;
            $totals['kemas_g3'] += $kemasG3;
            $totals['total_kemas'] += $totalKemas;
            $totals['hcs_rikyet'] += $hcsRikyet;
            $totals['hcs_cutpack'] += $hcsCutpack;
            $totals['total_hcs'] += $totalHcs;
        }

        return [
            'data' => $data,
            'totals' => $totals,
            'sisaHariKerja' => $sisaHariKerja,
        ];
    }

    private function calculateSisaHariKerja(Carbon $date)
    {
        $endOfMonth = $date->copy()->endOfMonth();
        $count = 0;

        $current = $date->copy();
        while ($current <= $endOfMonth) {
            if ($current->isWeekday()) {
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    private function getYearOptions()
    {
        // Fetch unique budget years from all major tables
        $ta = DB::table('hcs_receivings')->distinct()->pluck('tahun_anggaran')
            ->merge(DB::table('pengemasans')->distinct()->pluck('tahun_anggaran'))
            ->merge(DB::table('penyerahan_bi')->distinct()->pluck('tahun_anggaran'))
            ->merge(DB::table('target_tahunan')->distinct()->pluck('tahun_anggaran'))
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        // Fetch unique emission years from all major tables
        $te = DB::table('hcs_receivings')->distinct()->pluck('emisi')
            ->merge(DB::table('pengemasans')->distinct()->pluck('tahun_emisi'))
            ->merge(DB::table('penyerahan_bi')->distinct()->pluck('tahun_emisi'))
            ->merge(DB::table('target_tahunan')->distinct()->pluck('tahun_emisi'))
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        // Fallback to current year if empty
        if (empty($ta)) {
            $ta = [date('Y')];
        }
        if (empty($te)) {
            $te = ['2022', '2016'];
        }

        return [
            'tahun_anggaran' => $ta,
            'tahun_emisi' => $te,
        ];
    }

    private function getHctsInventoryData(array $filters)
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];
        $previousDay = Carbon::parse($tanggalLaporan)->subDay()->toDateString();
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        $hctsInventoryData = [];
        foreach ($pecahanList as $pec) {
            // Penerimaan H-1
            $penerimaanH1 = \App\Models\HctsReceiving::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
                ->whereDate('tanggal_penerimaan', $previousDay)
                ->sum('jumlah');

            // Penyerahan Hari Ini
            $penyerahanHariIni = \App\Models\HctsSubmission::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
                ->whereDate('tanggal_penyerahan', $tanggalLaporan)
                ->sum('jumlah_bilyet');

            // Akumulasi Penerimaan (Sampai Tanggal Laporan)
            $akumulasiTerima = \App\Models\HctsReceiving::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
                ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan)
                ->sum('jumlah');

            // Akumulasi Penyerahan (Sampai Tanggal Laporan)
            $akumulasiSerah = \App\Models\HctsSubmission::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
                ->whereDate('tanggal_penyerahan', '<=', $tanggalLaporan)
                ->sum('jumlah_bilyet');

            $persediaan = $akumulasiTerima - $akumulasiSerah;
            $ctSiapHitung = floor($persediaan / 3000000);

            $hctsInventoryData[$pec] = [
                'penerimaan_h1' => (int) $penerimaanH1,
                'penyerahan_hari_ini' => (int) $penyerahanHariIni,
                'akumulasi_penerimaan' => (int) $akumulasiTerima,
                'akumulasi_penyerahan' => (int) $akumulasiSerah,
                'persediaan' => (int) $persediaan,
                'ct_siap_hitung' => (int) $ctSiapHitung,
            ];
        }

        return $hctsInventoryData;
    }
}
