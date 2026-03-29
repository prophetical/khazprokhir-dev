<?php

namespace App\Services;

use App\Models\HcsReceiving;
use App\Models\Pengemasan;
use App\Models\PenyerahanBi;
use App\Models\TargetBulananPengemasan;
use App\Models\TargetTahunan;
use App\Models\HctsReceiving;
use App\Models\HctsSubmission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getYearOptions(): array
    {
        $ta = DB::table('hcs_receivings')->distinct()->pluck('tahun_anggaran')
            ->merge(DB::table('pengemasans')->distinct()->pluck('tahun_anggaran'))
            ->merge(DB::table('penyerahan_bi')->distinct()->pluck('tahun_anggaran'))
            ->merge(DB::table('target_tahunan')->distinct()->pluck('tahun_anggaran'))
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        $te = DB::table('hcs_receivings')->distinct()->pluck('emisi')
            ->merge(DB::table('pengemasans')->distinct()->pluck('tahun_emisi'))
            ->merge(DB::table('penyerahan_bi')->distinct()->pluck('tahun_emisi'))
            ->merge(DB::table('target_tahunan')->distinct()->pluck('tahun_emisi'))
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        if (empty($ta)) $ta = [date('Y')];
        if (empty($te)) $te = ['2022', '2016'];

        return [
            'tahun_anggaran' => $ta,
            'tahun_emisi' => $te,
        ];
    }

    public function getReportData(array $filters): array
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
            if ($tahunEmisi) $penerimaanQuery->where('emisi', $tahunEmisi);
            $totalPenerimaan = $penerimaanQuery->sum('jumlah');

            $pengemasanQuery = Pengemasan::where('pecahan', $pecahan)
                ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan)
                ->where('tahun_anggaran', $tahunAnggaran);
            if ($tahunEmisi) $pengemasanQuery->where('tahun_emisi', $tahunEmisi);
            $totalPengemasan = $pengemasanQuery->sum('jumlah_dus') * 20000;

            $penyerahanQuery = PenyerahanBi::where('pecahan', $pecahan)
                ->where('tahun_anggaran', $tahunAnggaran);
            if ($tahunEmisi) $penyerahanQuery->where('tahun_emisi', $tahunEmisi);

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
            if ($tahunEmisi) $targetQuery->where('tahun_emisi', $tahunEmisi);
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

        return [
            'reportData' => $reportData, 
            'totals' => $totals, 
            'secondaryData' => $secondaryData['data'], 
            'secondaryTotals' => $secondaryData['totals'], 
            'sisaHariKerja' => $secondaryData['sisaHariKerja']
        ];
    }

    public function getSecondaryReportData(array $filters): array
    {
        $tanggalLaporan = Carbon::parse($filters['tanggal_laporan']);
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $month = $tanggalLaporan->month;
        $targetColumn = 'bulan_'.$month;
        $startOfMonth = $tanggalLaporan->copy()->startOfMonth();

        $sisaHariKerja = $this->calculateSisaHariKerja($tanggalLaporan);

        $data = [];
        $totals = [
            'target_penyerahan_bulan' => 0, 'penyerahan_bulan' => 0, 'sisa_target_bilyet' => 0,
            'sisa_target_doos' => 0, 'target_produksi_harian' => 0, 'kemas_g1' => 0,
            'kemas_g2' => 0, 'kemas_g3' => 0, 'total_kemas' => 0, 'hcs_rikyet' => 0,
            'hcs_cutpack' => 0, 'total_hcs' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
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

            $sisaTargetBilyet = $targetBulan - $pengemasanBulanBilyet;
            $sisaTargetDoos = ceil($sisaTargetBilyet / 20000);
            $targetProduksiHarian = $sisaHariKerja > 0 ? floor($sisaTargetBilyet / $sisaHariKerja) : 0;

            $kemasG1 = $this->getKemasJumlah($pecahan, $tahunAnggaran, $tahunEmisi, $startOfMonth->toDateString(), $tanggalLaporan->toDateString(), '1');
            $kemasG2 = $this->getKemasJumlah($pecahan, $tahunAnggaran, $tahunEmisi, $startOfMonth->toDateString(), $tanggalLaporan->toDateString(), '2');
            $kemasG3 = $this->getKemasJumlah($pecahan, $tahunAnggaran, $tahunEmisi, $startOfMonth->toDateString(), $tanggalLaporan->toDateString(), '3');
            $totalKemas = $kemasG1 + $kemasG2 + $kemasG3;

            $hcsRikyet = $this->getHcsJumlah($pecahan, $tahunAnggaran, $tahunEmisi, $startOfMonth->toDateString(), $tanggalLaporan->toDateString(), 'Rikyet');
            $hcsCutpack = $this->getHcsJumlah($pecahan, $tahunAnggaran, $tahunEmisi, $startOfMonth->toDateString(), $tanggalLaporan->toDateString(), 'Cutpack');
            $totalHcs = $hcsRikyet + $hcsCutpack;

            $data[] = [
                'pecahan' => $pecahan, 'target_penyerahan_bulan' => $targetBulan,
                'penyerahan_bulan' => $pengemasanBulanBilyet, 'sisa_target_bilyet' => $sisaTargetBilyet,
                'sisa_target_doos' => $sisaTargetDoos, 'target_produksi_harian' => $targetProduksiHarian,
                'kemas_g1' => $kemasG1, 'kemas_g2' => $kemasG2, 'kemas_g3' => $kemasG3,
                'total_kemas' => $totalKemas, 'hcs_rikyet' => $hcsRikyet,
                'hcs_cutpack' => $hcsCutpack, 'total_hcs' => $totalHcs,
            ];

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

        return ['data' => $data, 'totals' => $totals, 'sisaHariKerja' => $sisaHariKerja];
    }

    public function getHctsInventoryData(array $filters): array
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];
        $previousDay = Carbon::parse($tanggalLaporan)->subDay()->toDateString();
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        $hctsInventoryData = [];
        foreach ($pecahanList as $pec) {
            $penerimaanH1 = HctsReceiving::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
                ->whereDate('tanggal_penerimaan', $previousDay)
                ->sum('jumlah');

            $penyerahanHariIni = HctsSubmission::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
                ->whereDate('tanggal_penyerahan', $tanggalLaporan)
                ->sum('jumlah_bilyet');

            $akumulasiTerima = HctsReceiving::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
                ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan)
                ->sum('jumlah');

            $akumulasiSerah = HctsSubmission::where('pecahan', $pec)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
                ->whereDate('tanggal_penyerahan', '<=', $tanggalLaporan)
                ->sum('jumlah_bilyet');

            $persediaan = $akumulasiTerima - $akumulasiSerah;
            $hctsInventoryData[$pec] = [
                'penerimaan_h1' => (int) $penerimaanH1,
                'penyerahan_hari_ini' => (int) $penyerahanHariIni,
                'akumulasi_penerimaan' => (int) $akumulasiTerima,
                'akumulasi_penyerahan' => (int) $akumulasiSerah,
                'persediaan' => (int) $persediaan,
                'ct_siap_hitung' => (int) floor($persediaan / 3000000),
            ];
        }

        return $hctsInventoryData;
    }

    public function getTargetAchievementData(array $filters): array
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        $data = [];
        $totals = ['target_bilyet' => 0, 'target_dus' => 0, 'akumulasi_bilyet' => 0, 'akumulasi_dus' => 0, 'sisa_bilyet' => 0, 'sisa_dus' => 0];

        foreach ($pecahanList as $pecahan) {
            $target = TargetTahunan::where('pecahan', $pecahan)->where('tahun_anggaran', $tahunAnggaran)->where('tahun_emisi', $tahunEmisi)->sum('target');
            $akumulasiBilyet = Pengemasan::where('pecahan', $pecahan)->where('tahun_anggaran', $tahunAnggaran)->where('tahun_emisi', $tahunEmisi)->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan)->sum('total_bilyet');
            $akumulasiDus = (int) Pengemasan::where('pecahan', $pecahan)->where('tahun_anggaran', $tahunAnggaran)->where('tahun_emisi', $tahunEmisi)->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan)->sum('jumlah_dus');
            
            $sisaBilyet = $target - $akumulasiBilyet;
            $data[] = [
                'pecahan' => $pecahan, 'target_bilyet' => $target, 'target_dus' => ceil($target / 20000),
                'akumulasi_bilyet' => $akumulasiBilyet, 'akumulasi_dus' => $akumulasiDus,
                'sisa_bilyet' => $sisaBilyet, 'sisa_dus' => $sisaBilyet / 20000,
                'persen' => $target > 0 ? ($akumulasiBilyet / $target) * 100 : 0,
            ];

            $totals['target_bilyet'] += $target; $totals['target_dus'] += ceil($target / 20000);
            $totals['akumulasi_bilyet'] += $akumulasiBilyet; $totals['akumulasi_dus'] += $akumulasiDus;
            $totals['sisa_bilyet'] += $sisaBilyet; $totals['sisa_dus'] += $sisaBilyet / 20000;
        }

        return ['data' => $data, 'totals' => $totals];
    }

    public function getMonthlyTargetAchievementData(array $filters): array
    {
        $tanggalLaporan = Carbon::parse($filters['tanggal_laporan']);
        $startOfMonth = $tanggalLaporan->copy()->startOfMonth()->toDateString();
        $currentDate = $tanggalLaporan->toDateString();
        $targetColumn = 'bulan_'.$tanggalLaporan->month;
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        $data = [];
        $totals = ['target_bilyet' => 0, 'target_dus' => 0, 'akumulasi_bilyet' => 0, 'akumulasi_dus' => 0, 'sisa_bilyet' => 0, 'sisa_dus' => 0];

        foreach ($pecahanList as $pecahan) {
            $targetRow = TargetBulananPengemasan::where('pecahan', $pecahan)->where('tahun_anggaran', $filters['tahun_anggaran'])->where('tahun_emisi', $filters['tahun_emisi'])->first();
            $targetBilyet = $targetRow ? ($targetRow->{$targetColumn} ?? 0) : 0;
            $akumulasiBilyet = Pengemasan::where('pecahan', $pecahan)->where('tahun_anggaran', $filters['tahun_anggaran'])->where('tahun_emisi', $filters['tahun_emisi'])->whereDate('tanggal_pengemasan', '>=', $startOfMonth)->whereDate('tanggal_pengemasan', '<=', $currentDate)->sum('total_bilyet');
            $akumulasiDus = (int) Pengemasan::where('pecahan', $pecahan)->where('tahun_anggaran', $filters['tahun_anggaran'])->where('tahun_emisi', $filters['tahun_emisi'])->whereDate('tanggal_pengemasan', '>=', $startOfMonth)->whereDate('tanggal_pengemasan', '<=', $currentDate)->sum('jumlah_dus');
            
            $sisaBilyet = $targetBilyet - $akumulasiBilyet;
            $data[] = [
                'pecahan' => $pecahan, 'target_bilyet' => $targetBilyet, 'target_dus' => ceil($targetBilyet / 20000),
                'akumulasi_bilyet' => $akumulasiBilyet, 'akumulasi_dus' => $akumulasiDus,
                'sisa_bilyet' => $sisaBilyet, 'sisa_dus' => $sisaBilyet / 20000,
                'persen' => $targetBilyet > 0 ? ($akumulasiBilyet / $targetBilyet) * 100 : 0,
            ];

            $totals['target_bilyet'] += $targetBilyet; $totals['target_dus'] += ceil($targetBilyet / 20000);
            $totals['akumulasi_bilyet'] += $akumulasiBilyet; $totals['akumulasi_dus'] += $akumulasiDus;
            $totals['sisa_bilyet'] += $sisaBilyet; $totals['sisa_dus'] += $sisaBilyet / 20000;
        }

        return ['data' => $data, 'totals' => $totals];
    }

    private function calculateSisaHariKerja(Carbon $date): int
    {
        $endOfMonth = $date->copy()->endOfMonth();
        $count = 0; $current = $date->copy();
        while ($current <= $endOfMonth) {
            if ($current->isWeekday()) $count++;
            $current->addDay();
        }
        return $count;
    }

    private function getKemasJumlah($pecahan, $ta, $te, $start, $end, $gilir)
    {
        $query = Pengemasan::where('pecahan', $pecahan)->where('tahun_anggaran', $ta)->whereDate('tanggal_pengemasan', '>=', $start)->whereDate('tanggal_pengemasan', '<=', $end)->where('gilir', $gilir);
        if ($te) $query->where('tahun_emisi', $te);
        return $query->sum(DB::raw('jumlah_dus * 20000'));
    }

    private function getHcsJumlah($pecahan, $ta, $te, $start, $end, $supplier)
    {
        $query = HcsReceiving::where('pecahan', $pecahan)->where('tahun_anggaran', $ta)->whereDate('tanggal_penerimaan', '>=', $start)->whereDate('tanggal_penerimaan', '<=', $end)->where('supplier', $supplier);
        if ($te) $query->where('emisi', $te);
        return $query->sum('jumlah');
    }
}
