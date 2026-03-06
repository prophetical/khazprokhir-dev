<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HcsReceiving;
use App\Models\Pack;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Total HCS received today (count of transactions)
        $totalHcsToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->count();

        // 2. Total bilyet processed today
        $totalBilyetToday = HcsReceiving::whereDate('tanggal_penerimaan', $today)->sum('jumlah');

        // 3. Total packs processed today
        $totalPacksToday = Pack::whereDate('created_at', $today)->count();

        // 4. Supplier distribution today
        $supplierDistribution = Pack::whereDate('created_at', $today)
            ->selectRaw('supplier, count(*) as count')
            ->groupBy('supplier')
            ->pluck('count', 'supplier')
            ->toArray();

        $cutpackCount = $supplierDistribution['Cutpack'] ?? 0;
        $rikyetCount = $supplierDistribution['Rikyet'] ?? 0;

        // Ensure we handle division by zero
        $packUsagePercentage = $totalPacksToday > 0
            ? round(($totalPacksToday / 1000) * 100, 2) // Assuming 1000 is a mock capacity for demo
             : 0;

        return view('dashboard', compact(
            'totalHcsToday',
            'totalBilyetToday',
            'totalPacksToday',
            'cutpackCount',
            'rikyetCount',
            'packUsagePercentage'
        ));
    }
}
