<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HcsReceiving;
use App\Models\Pack;
use App\Models\StockLedger;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $gilir = $request->input('gilir');
        $pecahan = $request->input('pecahan');

        // Calculate GLOBAL all-time totals for each denomination for the summary cards
        $globalTotalsPerPecahan = collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0]);
        $totals = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');
        $globalTotalsPerPecahan = $globalTotalsPerPecahan->merge($totals);

        // Calculate Global Grand Total
        $globalGrandTotal = $globalTotalsPerPecahan->sum();

        $query = HcsReceiving::with('user');

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
        }

        if ($gilir) {
            $query->where('gilir', $gilir);
        }

        if ($pecahan) {
            $query->where('pecahan', $pecahan);
        }

        $data = $query->orderBy('tanggal_penerimaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('reports.index', compact('startDate', 'endDate', 'gilir', 'pecahan', 'data', 'globalTotalsPerPecahan', 'globalGrandTotal'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $gilir = $request->input('gilir');
        $pecahan = $request->input('pecahan');

        $filename = "report_receiving_" . ($startDate ?: 'all') . "_to_" . ($endDate ?: 'all') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($startDate, $endDate, $gilir, $pecahan) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Tanggal', 'No Bon', 'Pecahan', 'Jumlah', 'Gilir', 'Mesin', 'Supplier', 'Batch', 'Seri', 'Operator']);

            $query = HcsReceiving::with('user');

            if ($startDate && $endDate) {
                $query->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
            }

            if ($gilir) {
                $query->where('gilir', $gilir);
            }

            if ($pecahan) {
                $query->where('pecahan', $pecahan);
            }

            $query->chunk(100, function ($receivings) use ($file) {
                    foreach ($receivings as $row) {
                        fputcsv($file, [
                            $row->tanggal_penerimaan,
                            $row->nomor_bon,
                            $row->pecahan,
                            $row->jumlah,
                            $row->gilir,
                            $row->mesin,
                            $row->supplier,
                            $row->batch,
                            $row->seri,
                            $row->user->name ?? '-'
                        ]);
                    }
                }
                );

                fclose($file);
            };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $gilir = $request->input('gilir');
        $pecahan = $request->input('pecahan');

        // Global totals (All-time)
        $globalTotalsPerPecahan = collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0]);
        $totals = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');
        $globalTotalsPerPecahan = $globalTotalsPerPecahan->merge($totals);
        $globalGrandTotal = $globalTotalsPerPecahan->sum();

        // Filtered totals (Current View)
        $filteredTotalsPerPecahan = collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0]);

        $query = HcsReceiving::with('user');
        $totalsQuery = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total');

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
            $totalsQuery->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
        }

        if ($gilir) {
            $query->where('gilir', $gilir);
            $totalsQuery->where('gilir', $gilir);
        }

        if ($pecahan) {
            $query->where('pecahan', $pecahan);
            $totalsQuery->where('pecahan', $pecahan);
        }

        $currentTotals = $totalsQuery->groupBy('pecahan')->pluck('total', 'pecahan');
        $filteredTotalsPerPecahan = $filteredTotalsPerPecahan->merge($currentTotals);

        $data = $query->orderBy('tanggal_penerimaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $filteredGrandTotal = $filteredTotalsPerPecahan->sum();

        return view('reports.print', compact('startDate', 'endDate', 'gilir', 'pecahan', 'data', 'globalTotalsPerPecahan', 'globalGrandTotal', 'filteredTotalsPerPecahan', 'filteredGrandTotal'));
    }
}
