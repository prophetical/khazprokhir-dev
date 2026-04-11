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
use Illuminate\Support\Facades\Cache;

class ReportService
{
    public function getYearOptions(): array
    {
        return Cache::remember('report_year_options', 3600, function () {
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
        });
    }

    public function getReportData(array $filters): array
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        
        // --- High Performance Aggregated Queries ---
        
        $receivings = HcsReceiving::whereIn('pecahan', $pecahanList)
            ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn($q) => $q->where('emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $pengemasans = Pengemasan::whereIn('pecahan', $pecahanList)
            ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(jumlah_dus) as total_dus')
            ->groupBy('pecahan')
            ->pluck('total_dus', 'pecahan');

        $penyerahanHariIni = PenyerahanBi::whereIn('pecahan', $pecahanList)
            ->whereDate('tanggal_penyerahan', $tanggalLaporan)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $penyerahanAkumulasi = PenyerahanBi::whereIn('pecahan', $pecahanList)
            ->whereDate('tanggal_penyerahan', '<=', $tanggalLaporan)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $targets = TargetTahunan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(target) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $reportData = [];
        $totals = [
            'siap_kemas_bilyet' => 0, 'siap_kirim_bilyet' => 0, 'total_persediaan_bilyet' => 0,
            'penyerahan_hari_ini_bilyet' => 0, 'akumulasi_penyerahan_bilyet' => 0,
            'target' => 0, 'sisa_target' => 0, 'akumulasi_penerimaan_hcs' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
            $totalPenerimaan = $receivings->get($pecahan, 0);
            $totalPengemasan = $pengemasans->get($pecahan, 0) * 20000;
            $penyerahanHariIniBilyet = $penyerahanHariIni->get($pecahan, 0);
            $akumulasiPenyerahan = $penyerahanAkumulasi->get($pecahan, 0);
            $target = $targets->get($pecahan, 0);

            $penyerahanHariIniDus = ceil($penyerahanHariIniBilyet / 20000);
            $siapKirimBilyet = $totalPengemasan - $akumulasiPenyerahan;
            $siapKirimDus = ceil($siapKirimBilyet / 20000);
            $siapKemasBilyet = $totalPenerimaan - $totalPengemasan;
            $totalPersediaanBilyet = $siapKemasBilyet + $siapKirimBilyet;
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
        $month = $tanggalLaporan->month;
        $targetColumn = 'bulan_' . $month;
        $sisaHariKerja = $this->calculateSisaHariKerja($tanggalLaporan);

        $rawData = $this->fetchSecondaryRawData($filters);
        
        return $this->calculateSecondaryMetrics($rawData, $sisaHariKerja, $targetColumn);
    }

    private function fetchSecondaryRawData(array $filters): array
    {
        $tanggalLaporan = Carbon::parse($filters['tanggal_laporan']);
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $startOfMonth = $tanggalLaporan->copy()->startOfMonth()->toDateString();
        $endDate = $tanggalLaporan->toDateString();

        return [
            'targets' => TargetBulananPengemasan::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tahun_emisi', $tahunEmisi)
                ->get()
                ->keyBy('pecahan'),

            'pengemasans' => Pengemasan::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tahun_emisi', $tahunEmisi)
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth)
                ->whereDate('tanggal_pengemasan', '<=', $endDate)
                ->selectRaw('pecahan, SUM(jumlah_dus * 20000) as total')
                ->groupBy('pecahan')
                ->pluck('total', 'pecahan'),

            'kemasGilir' => Pengemasan::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn($q) => $q->where('tahun_emisi', $tahunEmisi))
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth)
                ->whereDate('tanggal_pengemasan', '<=', $endDate)
                ->selectRaw('pecahan, gilir, SUM(jumlah_dus * 20000) as total')
                ->groupBy('pecahan', 'gilir')
                ->get()
                ->groupBy('pecahan'),

            'hcsSupplier' => HcsReceiving::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn($q) => $q->where('emisi', $tahunEmisi))
                ->whereDate('tanggal_penerimaan', '>=', $startOfMonth)
                ->whereDate('tanggal_penerimaan', '<=', $endDate)
                ->selectRaw('pecahan, supplier, SUM(jumlah) as total')
                ->groupBy('pecahan', 'supplier')
                ->get()
                ->groupBy('pecahan'),
        ];
    }

    private function calculateSecondaryMetrics(array $rawData, int $sisaHariKerja, string $targetColumn): array
    {
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $data = [];
        $totals = [
            'target_penyerahan_bulan' => 0, 'penyerahan_bulan' => 0, 'sisa_target_bilyet' => 0,
            'sisa_target_doos' => 0, 'target_produksi_harian' => 0, 'kemas_g1' => 0,
            'kemas_g2' => 0, 'kemas_g3' => 0, 'total_kemas' => 0, 'hcs_rikyet' => 0,
            'hcs_cutpack' => 0, 'total_hcs' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
            $targetRow = $rawData['targets']->get($pecahan);
            $targetBulan = $targetRow ? ($targetRow->{$targetColumn} ?? 0) : 0;
            $pengemasanBulanBilyet = $rawData['pengemasans']->get($pecahan, 0);

            $sisaTargetBilyet = $targetBulan - $pengemasanBulanBilyet;
            $sisaTargetDoos = ceil($sisaTargetBilyet / 20000);
            $targetProduksiHarian = $sisaHariKerja > 0 ? floor($sisaTargetBilyet / $sisaHariKerja) : 0;

            $pecKemas = $rawData['kemasGilir']->get($pecahan);
            $kemasG1 = $pecKemas?->where('gilir', '1')->first()?->total ?? 0;
            $kemasG2 = $pecKemas?->where('gilir', '2')->first()?->total ?? 0;
            $kemasG3 = $pecKemas?->where('gilir', '3')->first()?->total ?? 0;
            $totalKemas = $kemasG1 + $kemasG2 + $kemasG3;

            $pecHcs = $rawData['hcsSupplier']->get($pecahan);
            $hcsRikyet = $pecHcs?->where('supplier', 'Rikyet')->first()?->total ?? 0;
            $hcsCutpack = $pecHcs?->where('supplier', 'Cutpack')->first()?->total ?? 0;
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

        // --- Aggregated Queries ---
        
        $penerimaanH1Map = HctsReceiving::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
            ->whereDate('tanggal_penerimaan', $previousDay)
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $penyerahanHariIniMap = HctsSubmission::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->whereDate('tanggal_penyerahan', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $akumulasiTerimaMap = HctsReceiving::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
            ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $akumulasiSerahMap = HctsSubmission::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->whereDate('tanggal_penyerahan', '<=', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $hctsInventoryData = [];
        foreach ($pecahanList as $pec) {
            $penerimaanH1 = $penerimaanH1Map->get($pec, 0);
            $penyerahanHariIni = $penyerahanHariIniMap->get($pec, 0);
            $akumulasiTerima = $akumulasiTerimaMap->get($pec, 0);
            $akumulasiSerah = $akumulasiSerahMap->get($pec, 0);

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

        // --- Aggregated Queries ---
        
        $targetMap = TargetTahunan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('tahun_emisi', $tahunEmisi)
            ->selectRaw('pecahan, SUM(target) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $pengemasanMap = Pengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('tahun_emisi', $tahunEmisi)
            ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(total_bilyet) as total_bilyet, SUM(jumlah_dus) as total_dus')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        $data = [];
        $totals = ['target_bilyet' => 0, 'target_dus' => 0, 'akumulasi_bilyet' => 0, 'akumulasi_dus' => 0, 'sisa_bilyet' => 0, 'sisa_dus' => 0];

        foreach ($pecahanList as $pecahan) {
            $target = $targetMap->get($pecahan, 0);
            $pengRow = $pengemasanMap->get($pecahan);
            $akumulasiBilyet = $pengRow ? $pengRow->total_bilyet : 0;
            $akumulasiDus = $pengRow ? (int) $pengRow->total_dus : 0;
            
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

        // --- Aggregated Queries ---
        
        $targets = TargetBulananPengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->where('tahun_emisi', $filters['tahun_emisi'])
            ->get()
            ->keyBy('pecahan');

        $pengemasanMap = Pengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->where('tahun_emisi', $filters['tahun_emisi'])
            ->whereDate('tanggal_pengemasan', '>=', $startOfMonth)
            ->whereDate('tanggal_pengemasan', '<=', $currentDate)
            ->selectRaw('pecahan, SUM(total_bilyet) as total_bilyet, SUM(jumlah_dus) as total_dus')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        $data = [];
        $totals = ['target_bilyet' => 0, 'target_dus' => 0, 'akumulasi_bilyet' => 0, 'akumulasi_dus' => 0, 'sisa_bilyet' => 0, 'sisa_dus' => 0];

        foreach ($pecahanList as $pecahan) {
            $targetRow = $targets->get($pecahan);
            $targetBilyet = $targetRow ? ($targetRow->{$targetColumn} ?? 0) : 0;
            
            $pengRow = $pengemasanMap->get($pecahan);
            $akumulasiBilyet = $pengRow ? $pengRow->total_bilyet : 0;
            $akumulasiDus = $pengRow ? (int) $pengRow->total_dus : 0;
            
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
