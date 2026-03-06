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
        $type = $request->input('type', 'daily');
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $gilir = $request->input('gilir');

        $data = collect();

        // Calculate GLOBAL all-time totals for each denomination for the summary cards
        $globalTotalsPerPecahan = collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0]);
        $totals = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');
        $globalTotalsPerPecahan = $globalTotalsPerPecahan->merge($totals);

        // Calculate Global Grand Total
        $globalGrandTotal = $globalTotalsPerPecahan->sum();

        if ($type === 'daily') {
            $query = HcsReceiving::with('user')
                ->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);

            if ($gilir) {
                $query->where('gilir', $gilir);
            }

            $data = $query->orderBy('tanggal_penerimaan', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        elseif ($type === 'denomination') {
            $data = StockLedger::orderBy('pecahan')->get();
        }

        return view('reports.index', compact('type', 'startDate', 'endDate', 'gilir', 'data', 'globalTotalsPerPecahan', 'globalGrandTotal'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'daily');
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $gilir = $request->input('gilir');

        $filename = "report_{$type}_{$startDate}_to_{$endDate}.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($type, $startDate, $endDate, $gilir) {
            $file = fopen('php://output', 'w');

            if ($type === 'daily') {
                fputcsv($file, ['Tanggal', 'No Bon', 'Pecahan', 'Jumlah', 'Gilir', 'Mesin', 'Supplier', 'Batch', 'Seri', 'Operator']);

                $query = HcsReceiving::with('user')
                    ->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);

                if ($gilir) {
                    $query->where('gilir', $gilir);
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
                    }
                    elseif ($type === 'denomination') {
                        fputcsv($file, ['Pecahan', 'Batch', 'Seri', 'Total Diterima', 'Total Dipacking']);

                        StockLedger::chunk(100, function ($ledgers) use ($file) {
                            foreach ($ledgers as $row) {
                                fputcsv($file, [
                                    $row->pecahan,
                                    $row->batch,
                                    $row->seri,
                                    $row->total_received,
                                    $row->total_packed
                                ]);
                            }
                        }
                        );
                    }

                    fclose($file);
                };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $type = $request->input('type', 'daily');
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $gilir = $request->input('gilir');

        $data = collect();

        // Global totals for the print header
        $globalTotalsPerPecahan = collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0]);
        $totals = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');
        $globalTotalsPerPecahan = $globalTotalsPerPecahan->merge($totals);
        $globalGrandTotal = $globalTotalsPerPecahan->sum();

        if ($type === 'daily') {
            $query = HcsReceiving::with('user')
                ->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);

            if ($gilir) {
                $query->where('gilir', $gilir);
            }

            $data = $query->orderBy('tanggal_penerimaan', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        elseif ($type === 'denomination') {
            $data = StockLedger::orderBy('pecahan')->get();
        }

        return view('reports.print', compact('type', 'startDate', 'endDate', 'gilir', 'data', 'globalTotalsPerPecahan', 'globalGrandTotal'));
    }
}
