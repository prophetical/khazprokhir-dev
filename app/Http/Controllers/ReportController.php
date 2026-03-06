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
        $type = $request->input('type', 'daily'); // daily, batch, supplier, denomination
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $data = collect();

        if ($type === 'daily') {
            $data = HcsReceiving::with('user')
                ->whereDate('tanggal_penerimaan', $date)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        elseif ($type === 'denomination') {
            $data = StockLedger::orderBy('pecahan')->get();
        }

        return view('reports.index', compact('type', 'date', 'data'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'daily');
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $filename = "report_{$type}_{$date}.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($type, $date) {
            $file = fopen('php://output', 'w');

            if ($type === 'daily') {
                fputcsv($file, ['Tanggal', 'No Bon', 'Pecahan', 'Jumlah', 'Gilir', 'Mesin', 'Supplier', 'Batch', 'Seri', 'Operator']);

                HcsReceiving::with('user')->whereDate('tanggal_penerimaan', $date)->chunk(100, function ($receivings) use ($file) {
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
}
