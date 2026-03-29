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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get all data required for the dashboard.
     * 
     * @param Request $request
     * @return array
     */
    public function getDashboardData(Request $request): array
    {
        $today = Carbon::today();
        
        // Ambil daftar tahun dari semua data yang ada (Bisa dioptimalkan dengan cache nanti)
        $yearsPengemasan = Pengemasan::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsPenyerahan = PenyerahanBi::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsTarget = TargetTahunan::distinct()->pluck('tahun_anggaran')->toArray();

        $availableYears = array_unique(array_merge($yearsPengemasan, $yearsPenyerahan, $yearsTarget));
        sort($availableYears);

        if (empty($availableYears)) {
            $availableYears = [$today->year];
        }

        $currentYear = $request->get('tahun_anggaran', $today->year);

        // Ambil daftar tahun emisi yang tersedia
        $emissionsTargetTahun = TargetTahunan::distinct()->pluck('tahun_emisi')->toArray();
        $emissionsTargetBulan = TargetBulanan::distinct()->pluck('tahun_emisi')->toArray();
        $availableEmissions = array_unique(array_merge($emissionsTargetTahun, $emissionsTargetBulan));
        sort($availableEmissions);

        $currentTE = $request->get('tahun_emisi', (!empty($availableEmissions) ? max($availableEmissions) : ''));

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // 1. Data Hari Ini
        $totalHcsToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->count();
        $totalBilyetToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->sum('jumlah');
        $totalPacksToday = Pack::whereDate('created_at', $today)->count();

        // 2. Sebaran Supplier Hari Ini
        $supplierDistribution = Pack::whereDate('created_at', $today)
            ->selectRaw('supplier, count(*) as count')
            ->groupBy('supplier')
            ->pluck('count', 'supplier')
            ->toArray();

        $cutpackCount = $supplierDistribution['Cutpack'] ?? 0;
        $rikyetCount = $supplierDistribution['Rikyet'] ?? 0;

        // 3. Sebaran Supplier Tahunan
        $supplierDistributionYear = Pack::join('pengemasans', 'packs.id_pengemasan', '=', 'pengemasans.id')
            ->where('pengemasans.tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('pengemasans.tahun_emisi', $currentTE))
            ->selectRaw('packs.supplier, SUM(packs.jumlah) as total')
            ->groupBy('packs.supplier')
            ->pluck('total', 'packs.supplier')
            ->toArray();

        // 4. Data Bulanan untuk Grafik (Eager grouping di PHP)
        $months = range(1, 12);

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

        $monthlyTargetsRaw = TargetBulanan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, 
                SUM(bulan_1) as bulan_1, SUM(bulan_2) as bulan_2, SUM(bulan_3) as bulan_3, 
                SUM(bulan_4) as bulan_4, SUM(bulan_5) as bulan_5, SUM(bulan_6) as bulan_6, 
                SUM(bulan_7) as bulan_7, SUM(bulan_8) as bulan_8, SUM(bulan_9) as bulan_9, 
                SUM(bulan_10) as bulan_10, SUM(bulan_11) as bulan_11, SUM(bulan_12) as bulan_12')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        // 5. Kesimpulan Tahunan
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

        // 6. Mapping Dataset Grafik
        $chartData = [];
        $totalMonthlyKemas = array_fill(0, 12, 0);
        $totalMonthlySerah = array_fill(0, 12, 0);
        $totalMonthlyTargetArr = array_fill(0, 12, 0);

        foreach ($pecahanList as $pec) {
            $dataPecahan = ['pengemasan' => [], 'penyerahan' => [], 'target' => []];
            $pecTarget = $monthlyTargetsRaw->get($pec);

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
                $totalMonthlyTargetArr[$month - 1] += $target;
            }
            $chartData[$pec] = $dataPecahan;
        }

        $chartData['TOTAL'] = [
            'pengemasan' => $totalMonthlyKemas,
            'penyerahan' => $totalMonthlySerah,
            'target' => $totalMonthlyTargetArr,
        ];

        // 7. Sebaran Pecahan & Heatmap
        $pecahanDistribution = Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, SUM(total_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan')
            ->toArray();

        $heatmapData = Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('DATE(tanggal_pengemasan) as date, SUM(total_bilyet) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $latestHeatmapDate = !empty($heatmapData) ? max(array_keys($heatmapData)) : null;
        $heatmapYear = $latestHeatmapDate ? Carbon::parse($latestHeatmapDate)->year : $currentYear;

        return [
            'totalHcsToday' => $totalHcsToday,
            'totalBilyetToday' => $totalBilyetToday,
            'totalPacksToday' => $totalPacksToday,
            'cutpackCount' => $cutpackCount,
            'rikyetCount' => $rikyetCount,
            'chartData' => $chartData,
            'heatmapData' => $heatmapData,
            'heatmapYear' => $heatmapYear,
            'months' => $months,
            'currentYear' => $currentYear,
            'availableYears' => $availableYears,
            'currentTE' => $currentTE,
            'availableEmissions' => $availableEmissions,
            'totalKemasYear' => $totalKemasYear,
            'totalSerahYear' => $totalSerahYear,
            'totalTargetYear' => $totalTargetYear,
            'totalTerimaYear' => $totalTerimaYear,
            'totalHctsYear' => $totalHctsYear,
            'inschietProduksi' => $inschietProduksi,
            'inschietFinal' => $inschietFinal,
            'totalHctsSerahYear' => $totalHctsSerahYear,
            'pecahanDistribution' => $pecahanDistribution,
            'supplierDistributionYear' => $supplierDistributionYear
        ];
    }
}
