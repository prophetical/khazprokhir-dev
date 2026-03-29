<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Models\Pack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        // Ambil daftar tahun dari semua data yang ada
        $yearsPengemasan = \App\Models\Pengemasan::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsPenyerahan = \App\Models\PenyerahanBi::distinct()->pluck('tahun_anggaran')->toArray();
        $yearsTarget = \App\Models\TargetTahunan::distinct()->pluck('tahun_anggaran')->toArray();

        $availableYears = array_unique(array_merge($yearsPengemasan, $yearsPenyerahan, $yearsTarget));
        sort($availableYears);

        // Kalau belum ada data tahun, tampilkan minimal tahun sekarang
        if (empty($availableYears)) {
            $availableYears = [$today->year];
        }

        $currentYear = $request->get('tahun_anggaran', $today->year);

        // Ambil daftar tahun emisi yang tersedia
        $emissionsTargetTahun = \App\Models\TargetTahunan::distinct()->pluck('tahun_emisi')->toArray();
        $emissionsTargetBulan = \App\Models\TargetBulanan::distinct()->pluck('tahun_emisi')->toArray();
        $availableEmissions = array_unique(array_merge($emissionsTargetTahun, $emissionsTargetBulan));
        sort($availableEmissions);

        // Pakai tahun emisi terbaru kalau user tidak pilih
        $currentTE = $request->get('tahun_emisi', (!empty($availableEmissions) ? max($availableEmissions) : ''));

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // 1. Hitung total penerimaan hari ini
        $totalHcsToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->count();
        $totalBilyetToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->sum('jumlah');
        $totalPacksToday = Pack::whereDate('created_at', $today)->count();

        // 4. Cek sebaran supplier hari ini
        $supplierDistribution = Pack::whereDate('created_at', $today)
            ->selectRaw('supplier, count(*) as count')
            ->groupBy('supplier')
            ->pluck('count', 'supplier')
            ->toArray();

        $cutpackCount = $supplierDistribution['Cutpack'] ?? 0;
        $rikyetCount = $supplierDistribution['Rikyet'] ?? 0;

        // 4b. Cek sebaran supplier selama setahun
        $supplierDistributionYear = Pack::join('pengemasans', 'packs.id_pengemasan', '=', 'pengemasans.id')
            ->where('pengemasans.tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('pengemasans.tahun_emisi', $currentTE))
            ->selectRaw('packs.supplier, SUM(packs.jumlah) as total')
            ->groupBy('packs.supplier')
            ->pluck('total', 'packs.supplier')
            ->toArray();

        // Siapkan data untuk grafik
        $chartData = [];
        $months = range(1, 12);

        // Ambil data pengemasan bulanan (pakai filter TA dan TE)
        $pengemasanMonthly = \App\Models\Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("pecahan, strftime('%m', tanggal_pengemasan) as month, SUM(total_bilyet) as total")
            ->groupBy('pecahan', 'month')
            ->get()
            ->groupBy('pecahan');

        // Ambil data penyerahan bulanan (pakai filter TA dan TE)
        $penyerahanMonthly = \App\Models\PenyerahanBi::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw("pecahan, strftime('%m', tanggal_penyerahan) as month, SUM(jumlah_bilyet) as total")
            ->groupBy('pecahan', 'month')
            ->get()
            ->groupBy('pecahan');

        // Ambil target bulanan (pakai filter TA dan TE)
        $monthlyTargetsRaw = \App\Models\TargetBulanan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, 
                SUM(bulan_1) as bulan_1, SUM(bulan_2) as bulan_2, SUM(bulan_3) as bulan_3, 
                SUM(bulan_4) as bulan_4, SUM(bulan_5) as bulan_5, SUM(bulan_6) as bulan_6, 
                SUM(bulan_7) as bulan_7, SUM(bulan_8) as bulan_8, SUM(bulan_9) as bulan_9, 
                SUM(bulan_10) as bulan_10, SUM(bulan_11) as bulan_11, SUM(bulan_12) as bulan_12')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        // Ambil target tahunan (pakai filter TA dan TE)
        $annualTargets = \App\Models\TargetTahunan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, SUM(target) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan')
            ->toArray();

        // Siapkan wadah untuk data total
        $totalMonthlyKemas = array_fill(0, 12, 0);
        $totalMonthlySerah = array_fill(0, 12, 0);
        $totalMonthlyTargetArr = array_fill(0, 12, 0);

        // Ambil total untuk kartu ringkasan (biar lebih akurat)
        $totalKemasYear = \App\Models\Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('total_bilyet');
        $totalSerahYear = \App\Models\PenyerahanBi::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('jumlah_bilyet');
        $totalTargetYear = \App\Models\TargetTahunan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('target');

        $totalTerimaYear = \App\Models\HcsReceiving::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('emisi', $currentTE))
            ->sum('jumlah');

        $totalHctsYear = \App\Models\HctsReceiving::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('emisi', $currentTE))
            ->sum('jumlah');

        $totalHctsSerahYear = \App\Models\HctsSubmission::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->sum('jumlah_bilyet');

        $inschietProduksi = $totalTerimaYear > 0 ? ($totalHctsYear / $totalTerimaYear) * 100 : 0;
        $inschietFinal = $totalSerahYear > 0 ? ($totalHctsSerahYear / $totalSerahYear) * 100 : 0;

        // Kelompokkan data untuk tiap pecahan
        foreach ($pecahanList as $pec) {
            $dataPecahan = ['pengemasan' => [], 'penyerahan' => [], 'target' => []];
            $pecTarget = $monthlyTargetsRaw->get($pec);

            foreach ($months as $month) {
                $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
                $kemas = (int) ($pengemasanMonthly->get($pec)?->where('month', $monthStr)->first()?->total ?? 0);
                $serah = (int) ($penyerahanMonthly->get($pec)?->where('month', $monthStr)->first()?->total ?? 0);

                // Pakai target bulanan langsung dari database
                $target = (int) ($pecTarget ? $pecTarget->{"bulan_{$month}"} : 0);

                $dataPecahan['pengemasan'][] = $kemas;
                $dataPecahan['penyerahan'][] = $serah;
                $dataPecahan['target'][] = $target;

                // Jumlahkan semuanya untuk data grafik gabungan
                $totalMonthlyKemas[$month - 1] += $kemas;
                $totalMonthlySerah[$month - 1] += $serah;
                $totalMonthlyTargetArr[$month - 1] += $target;
            }
            $chartData[$pec] = $dataPecahan;
        }

        // Tambahkan data total ke dataset
        $chartData['TOTAL'] = [
            'pengemasan' => $totalMonthlyKemas,
            'penyerahan' => $totalMonthlySerah,
            'target' => $totalMonthlyTargetArr,
        ];

        // 4c. Hitung sebaran per pecahan selama setahun
        $pecahanDistribution = \App\Models\Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('pecahan, SUM(total_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan')
            ->toArray();

        // 5. Ambil data harian untuk heatmap (dari pengemasan)
        $heatmapData = \App\Models\Pengemasan::where('tahun_anggaran', $currentYear)
            ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
            ->selectRaw('DATE(tanggal_pengemasan) as date, SUM(total_bilyet) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Tentukan tahun mana yang tampil di kalender heatmap
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
            'totalTargetYear',
            'totalTerimaYear',
            'totalHctsYear',
            'inschietProduksi',
            'inschietFinal',
            'totalHctsSerahYear',
            'pecahanDistribution',
            'supplierDistributionYear'
        ));
    }
}
