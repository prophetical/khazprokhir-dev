<?php

namespace App\Services;

use App\Models\HcsReceiving;
use App\Models\Pack;
use App\Models\Pengemasan;
use App\Models\PenyerahanBi;
use App\Models\TargetBulanan;
use App\Models\TargetTahunan;
use App\Models\HctsReceiving;
use App\Models\HctsSubmission;
use App\Models\BahanPenolong;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Ambil semua data yang dibutuhkan untuk dashboard.
     * 
     * @param Request $request
     * @return array
     */
    public function getDashboardData(Request $request): array
    {
        $today = Carbon::today();

        // 1. Ambil Filter (Tahun & Emisi)
        $filters = $this->getAvailableFilters($request, $today);
        $currentYear = $filters['currentYear'];
        $currentTE = $filters['currentTE'];

        // 2. Data Hari Ini
        $todayMetrics = $this->getTodayMetrics($today);
        $todayHcsByPecahan = $this->getTodayHcsByPecahan($today, $currentYear, $currentTE);

        // 3. Kesimpulan Tahunan & Inschiet
        $annualSummary = $this->getAnnualSummary($currentYear, $currentTE);

        // 4. Data Bulanan untuk Grafik
        $chartData = $this->getMonthlyChartData($currentYear, $currentTE);

        // 5. Sebaran Pecahan & Heatmap
        $distributionData = $this->getDistributionAndHeatmapData($currentYear, $currentTE);

        return array_merge(
            $filters,
            $todayMetrics,
            ['todayHcsByPecahan' => $todayHcsByPecahan],
            ['todayFormatted' => $today->locale('id')->translatedFormat('l, j F Y')],
            $annualSummary,
            ['chartData' => $chartData],
            $distributionData,
            ['months' => range(1, 12)],
            ['bahanPenolong' => BahanPenolong::all()]
        );
    }

    /**
     * Ambil tahun emisi dan tahun anggaran.
     */
    private function getAvailableFilters(Request $request, Carbon $today): array
    {
        $yearsPengemasan = Pengemasan::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsPenyerahan = PenyerahanBi::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsTarget = TargetTahunan::distinct()->pluck('tahun_anggaran')->toArray();

        $availableYears = array_unique(array_merge($yearsPengemasan, $yearsPenyerahan, $yearsTarget));
        sort($availableYears);

        if (empty($availableYears)) {
            $availableYears = [$today->year];
        }

        $currentYear = $request->input('tahun_anggaran', $today->year);

        $emissionsTargetTahun = TargetTahunan::distinct()->pluck('tahun_emisi')->toArray();
        $emissionsTargetBulan = TargetBulanan::distinct()->pluck('tahun_emisi')->toArray();
        $availableEmissions = array_unique(array_merge($emissionsTargetTahun, $emissionsTargetBulan));
        sort($availableEmissions);

        $currentTE = $request->input('tahun_emisi', (!empty($availableEmissions) ? max($availableEmissions) : ''));

        return [
            'availableYears' => $availableYears,
            'currentYear' => $currentYear,
            'availableEmissions' => $availableEmissions,
            'currentTE' => $currentTE
        ];
    }

    /**
     * Ambil metrik untuk aktivitas hari ini.
     */
    private function getTodayMetrics(Carbon $today): array
    {
        $totalHcsToday = HcsReceiving::whereBetween('tanggal_penerimaan', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])->count();
        $totalBilyetToday = HcsReceiving::whereBetween('tanggal_penerimaan', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])->sum('jumlah');
        $totalPacksToday = Pack::whereBetween('created_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])->count();

        $supplierDistribution = Pack::whereBetween('created_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->selectRaw('supplier, count(*) as count')
            ->groupBy('supplier')
            ->pluck('count', 'supplier')
            ->toArray();

        return [
            'totalHcsToday' => $totalHcsToday,
            'totalBilyetToday' => $totalBilyetToday,
            'totalPacksToday' => $totalPacksToday,
            'cutpackCount' => $supplierDistribution['Cutpack'] ?? 0,
            'rikyetCount' => $supplierDistribution['Rikyet'] ?? 0,
        ];
    }

    /**
     * Ambil total ringkasan tahunan dan perhitungan inschiet.
     */
    private function getAnnualSummary(int $currentYear, $currentTE): array
    {
        $totalKemasYear = Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('total_bilyet');
        $totalSerahYear = PenyerahanBi::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('jumlah_bilyet');
        $totalTargetYear = TargetTahunan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('target');
        $totalTerimaYear = HcsReceiving::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('emisi', $currentTE))
            ->sum('jumlah');
        $totalHctsYear = HctsReceiving::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('emisi', $currentTE))
            ->sum('jumlah');
        $totalHctsSerahYear = HctsSubmission::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('jumlah_bilyet');

        $inschietProduksi = $totalTerimaYear > 0 ? ($totalHctsYear / $totalTerimaYear) * 100 : 0;
        $inschietFinal = $totalSerahYear > 0 ? ($totalHctsSerahYear / $totalSerahYear) * 100 : 0;

        $supplierDistributionYear = Pack::join('pengemasans', 'packs.id_pengemasan', '=', 'pengemasans.id')
            ->where('pengemasans.tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('pengemasans.tahun_emisi', $currentTE))
            ->selectRaw('packs.supplier, SUM(packs.jumlah) as total')
            ->groupBy('packs.supplier')
            ->pluck('total', 'packs.supplier')
            ->toArray();

        return [
            'totalKemasYear' => $totalKemasYear,
            'totalSerahYear' => $totalSerahYear,
            'totalTargetYear' => $totalTargetYear,
            'totalTerimaYear' => $totalTerimaYear,
            'totalHctsYear' => $totalHctsYear,
            'totalHctsSerahYear' => $totalHctsSerahYear,
            'inschietProduksi' => $inschietProduksi,
            'inschietFinal' => $inschietFinal,
            'supplierDistributionYear' => $supplierDistributionYear,
        ];
    }

    /**
     * Ambil data grafik bulanan untuk semua pecahan.
     */
    private function getMonthlyChartData(int $currentYear, $currentTE): array
    {
        $months = range(1, 12);
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        $pengemasanMonthly = Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("pecahan, strftime('%m', tanggal_pengemasan) as month, SUM(total_bilyet) as total")
            ->groupBy('pecahan', 'month')
            ->get()
            ->groupBy('pecahan');

        $penyerahanMonthly = PenyerahanBi::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("pecahan, strftime('%m', tanggal_penyerahan) as month, SUM(jumlah_bilyet) as total")
            ->groupBy('pecahan', 'month')
            ->get()
            ->groupBy('pecahan');

        $monthlyTargets = TargetBulanan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, 
                SUM(bulan_1) as bulan_1, SUM(bulan_2) as bulan_2, SUM(bulan_3) as bulan_3, 
                SUM(bulan_4) as bulan_4, SUM(bulan_5) as bulan_5, SUM(bulan_6) as bulan_6, 
                SUM(bulan_7) as bulan_7, SUM(bulan_8) as bulan_8, SUM(bulan_9) as bulan_9, 
                SUM(bulan_10) as bulan_10, SUM(bulan_11) as bulan_11, SUM(bulan_12) as bulan_12')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        $chartData = [];
        $totalMonthlyKemas = array_fill(0, 12, 0);
        $totalMonthlySerah = array_fill(0, 12, 0);
        $totalMonthlyTarget = array_fill(0, 12, 0);

        foreach ($pecahanList as $pec) {
            $dataPecahan = ['pengemasan' => [], 'penyerahan' => [], 'target' => []];
            $pecTarget = $monthlyTargets->get($pec);

            foreach ($months as $month) {
                $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
                $kemas = (int) ($pengemasanMonthly->get($pec)?->where('month', $monthStr)->first()?->total ?? 0);
                $serah = (int) ($penyerahanMonthly->get($pec)?->where('month', $monthStr)->first()?->total ?? 0);
                $target = (int) ($pecTarget ? $pecTarget->{"bulan_{$month}"} : 0);

                $dataPecahan['pengemasan'][] = $kemas;
                $dataPecahan['penyerahan'][] = $serah;
                $dataPecahan['target'][] = $target;

                $totalMonthlyKemas[$month - 1] += $kemas;
                $totalMonthlySerah[$month - 1] += $serah;
                $totalMonthlyTarget[$month - 1] += $target;
            }
            $chartData[$pec] = $dataPecahan;
        }

        $chartData['TOTAL'] = [
            'pengemasan' => $totalMonthlyKemas,
            'penyerahan' => $totalMonthlySerah,
            'target' => $totalMonthlyTarget,
        ];

        return $chartData;
    }

    /**
     * Ambil data sebaran pecahan dan heatmap aktivitas.
     */
    private function getDistributionAndHeatmapData(int $currentYear, $currentTE): array
    {
        $pecahanDistribution = Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, SUM(total_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan')
            ->toArray();

        $heatmapData = DB::table('pengemasans')
            ->where('tahun_anggaran', (string)$currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('DATE(tanggal_pengemasan) as date, SUM(total_bilyet) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $latestHeatmapDate = !empty($heatmapData) ? max(array_keys($heatmapData)) : null;
        $heatmapYear = $latestHeatmapDate ? Carbon::parse($latestHeatmapDate)->year : $currentYear;

        return [
            'pecahanDistribution' => $pecahanDistribution,
            'heatmapData' => $heatmapData,
            'heatmapYear' => $heatmapYear
        ];
    }

    /**
     * Ambil data penerimaan HCS berdasarkan pecahan untuk hari ini.
     */
    private function getTodayHcsByPecahan(Carbon $today, int $currentYear, $currentTE): array
    {
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $data = HcsReceiving::whereBetween('tanggal_penerimaan', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('emisi', $currentTE))
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan')
            ->toArray();

        $result = [];
        foreach ($pecahanList as $pec) {
            $result[$pec] = (int) ($data[$pec] ?? 0);
        }
        return $result;
    }
}
