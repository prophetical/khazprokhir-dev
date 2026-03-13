<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HcsReceiving;
use App\Models\Pack;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        // Get available years from all relevant models
        $yearsPengemasan = \App\Models\Pengemasan::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsPenyerahan = \App\Models\PenyerahanBi::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsTarget = \App\Models\TargetTahunan::distinct()->pluck('tahun_anggaran')->toArray();
        
        $availableYears = array_unique(array_merge($yearsPengemasan, $yearsPenyerahan, $yearsTarget));
        sort($availableYears);
        
        // If no years found, at least show current year
        if (empty($availableYears)) {
            $availableYears = [$today->year];
        }

        $currentYear = $request->get('tahun_anggaran', $today->year);
        
        // Get available emission years
        $emissionsTargetTahun = \App\Models\TargetTahunan::distinct()->pluck('tahun_emisi')->toArray();
        $emissionsTargetBulan = \App\Models\TargetBulanan::distinct()->pluck('tahun_emisi')->toArray();
        $availableEmissions = array_unique(array_merge($emissionsTargetTahun, $emissionsTargetBulan));
        sort($availableEmissions);
        
        // Default TE: use the latest if not specified
        $currentTE = $request->get('tahun_emisi', (!empty($availableEmissions) ? max($availableEmissions) : ''));

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // 1. Total HCS today
        $totalHcsToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->count();
        $totalBilyetToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->sum('jumlah');
        $totalPacksToday = Pack::whereDate('created_at', $today)->count();

        // 4. Supplier distribution
        $supplierDistribution = Pack::whereDate('created_at', $today)
            ->selectRaw('supplier, count(*) as count')
            ->groupBy('supplier')
            ->pluck('count', 'supplier')
            ->toArray();

        $cutpackCount = $supplierDistribution['Cutpack'] ?? 0;
        $rikyetCount = $supplierDistribution['Rikyet'] ?? 0;

        // Data for Charts
        $chartData = [];
        $months = range(1, 12);

        // Fetch monthly Pengemasan (Filtered by TA and TE)
        $pengemasanMonthly = \App\Models\Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("pecahan, strftime('%m', tanggal_pengemasan) as month, SUM(jumlah_dus * 20000) as total")
            ->groupBy('pecahan', 'month')
            ->get()
            ->groupBy('pecahan');

        // Fetch monthly Penyerahan (Filtered by TA and TE)
        $penyerahanMonthly = \App\Models\PenyerahanBi::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("pecahan, strftime('%m', tanggal_penyerahan) as month, SUM(jumlah_bilyet) as total")
            ->groupBy('pecahan', 'month')
            ->get()
            ->groupBy('pecahan');

        // Fetch Monthly Targets (Filtered by TA and TE)
        $monthlyTargetsRaw = \App\Models\TargetBulanan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("pecahan, 
                SUM(bulan_1) as bulan_1, SUM(bulan_2) as bulan_2, SUM(bulan_3) as bulan_3, 
                SUM(bulan_4) as bulan_4, SUM(bulan_5) as bulan_5, SUM(bulan_6) as bulan_6, 
                SUM(bulan_7) as bulan_7, SUM(bulan_8) as bulan_8, SUM(bulan_9) as bulan_9, 
                SUM(bulan_10) as bulan_10, SUM(bulan_11) as bulan_11, SUM(bulan_12) as bulan_12")
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');
        
        // Fetch Annual targets for fallback (Filtered by TA and TE)
        $annualTargets = \App\Models\TargetTahunan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, SUM(target) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan')
            ->toArray();

        // Prepare TOTAL data arrays
        $totalMonthlyKemas = array_fill(0, 12, 0);
        $totalMonthlySerah = array_fill(0, 12, 0);
        $totalMonthlyTargetArr = array_fill(0, 12, 0);

        // Fetch totals for cards (Avoid rounding errors from monthly arrays)
        $totalKemasYear = \App\Models\Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum(DB::raw('jumlah_dus * 20000'));
        $totalSerahYear = \App\Models\PenyerahanBi::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('jumlah_bilyet');
        $totalTargetYear = \App\Models\TargetTahunan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('target');

        // Prepare chart categories
        foreach ($pecahanList as $pec) {
            $dataPecahan = ['pengemasan' => [], 'penyerahan' => [], 'target' => []];
            $pecTarget = $monthlyTargetsRaw->get($pec);

            foreach ($months as $month) {
                $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
                $kemas = (int)($pengemasanMonthly->get($pec)?->where('month', $monthStr)->first()?->total ?? 0);
                $serah = (int)($penyerahanMonthly->get($pec)?->where('month', $monthStr)->first()?->total ?? 0);
                
                // Use actual month specific target directly from DB
                $target = (int)($pecTarget ? $pecTarget->{"bulan_{$month}"} : 0);

                $dataPecahan['pengemasan'][] = $kemas;
                $dataPecahan['penyerahan'][] = $serah;
                $dataPecahan['target'][] = $target;

                // Accumulate totals for the TOTAL chart series
                $totalMonthlyKemas[$month - 1] += $kemas;
                $totalMonthlySerah[$month - 1] += $serah;
                $totalMonthlyTargetArr[$month - 1] += $target;
            }
            $chartData[$pec] = $dataPecahan;
        }

        // Add TOTAL
        $chartData['TOTAL'] = [
            'pengemasan' => $totalMonthlyKemas,
            'penyerahan' => $totalMonthlySerah,
            'target' => $totalMonthlyTargetArr
        ];

        // 5. Heatmap Data (Daily production based on Pengemasan)
        $heatmapData = \App\Models\Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("DATE(tanggal_pengemasan) as date, SUM(jumlah_dus * 20000) as total")
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Determine which year to show for the heatmap calendar
        $latestHeatmapDate = !empty($heatmapData) ? max(array_keys($heatmapData)) : null;
        $heatmapYear = $latestHeatmapDate ? Carbon::parse($latestHeatmapDate)->year : $currentYear;

        return view('dashboard', compact(
            'totalHcsToday',
            'totalBilyetToday',
            'totalPacksToday',
            'cutpackCount',
            'rikyetCount',
            'chartData',
            'heatmapData',
            'heatmapYear',
            'months',
            'currentYear',
            'availableYears',
            'currentTE',
            'availableEmissions',
            'totalKemasYear',
            'totalSerahYear',
            'totalTargetYear'
        ));
    }
}
