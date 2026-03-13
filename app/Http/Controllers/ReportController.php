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
        $tahunAnggaran = $request->input('tahun_anggaran');
        $tahunEmisi = $request->input('tahun_emisi');

        // Data unik untuk dropdown filter
        $availableYears = HcsReceiving::distinct()->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');
        $availableEmissions = HcsReceiving::distinct()->orderBy('emisi', 'desc')->pluck('emisi');

        // Hitung total KESELURUHAN (sesuai filter TA/TE jika dipilih) buat tiap pecahan untuk ditampilin di kartu ringkasan
        $globalTotalsPerPecahan = collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0]);
        
        $totalsQuery = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total');
        
        if ($tahunAnggaran) {
            $totalsQuery->where('tahun_anggaran', $tahunAnggaran);
        }
        if ($tahunEmisi) {
            $totalsQuery->where('emisi', $tahunEmisi);
        }

        $totals = $totalsQuery->groupBy('pecahan')->pluck('total', 'pecahan');
        $globalTotalsPerPecahan = $globalTotalsPerPecahan->merge($totals);

        // Hitung Total Keseluruhan (Semua Pecahan)
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

        if ($tahunAnggaran) {
            $query->where('tahun_anggaran', $tahunAnggaran);
        }

        if ($tahunEmisi) {
            $query->where('emisi', $tahunEmisi);
        }

        $data = $query->orderBy('tanggal_penerimaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('reports.index', compact(
            'startDate', 'endDate', 'gilir', 'pecahan', 'tahunAnggaran', 'tahunEmisi',
            'data', 'globalTotalsPerPecahan', 'globalGrandTotal', 
            'availableYears', 'availableEmissions'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $gilir = $request->input('gilir');
        $pecahan = $request->input('pecahan');
        $tahunAnggaran = $request->input('tahun_anggaran');
        $tahunEmisi = $request->input('tahun_emisi');

        $filename = "report_receiving_" . ($startDate ?: 'all') . "_to_" . ($endDate ?: 'all') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($startDate, $endDate, $gilir, $pecahan, $tahunAnggaran, $tahunEmisi) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Tanggal', 'No Bon', 'Pecahan', 'Emisi', 'TA', 'Jumlah', 'Gilir', 'Mesin', 'Supplier', 'Batch', 'Seri', 'Operator']);

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

            if ($tahunAnggaran) {
                $query->where('tahun_anggaran', $tahunAnggaran);
            }

            if ($tahunEmisi) {
                $query->where('emisi', $tahunEmisi);
            }

            $query->chunk(100, function ($receivings) use ($file) {
                    foreach ($receivings as $row) {
                        fputcsv($file, [
                            $row->tanggal_penerimaan,
                            $row->nomor_bon,
                            $row->pecahan,
                            $row->emisi,
                            $row->tahun_anggaran,
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
        $tahunAnggaran = $request->input('tahun_anggaran');
        $tahunEmisi = $request->input('tahun_emisi');

        // Total Keseluruhan (Sesuai filter TA/TE jika ada)
        $globalTotalsPerPecahan = collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0]);
        
        $globalTotalsQuery = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total');
        if ($tahunAnggaran) {
            $globalTotalsQuery->where('tahun_anggaran', $tahunAnggaran);
        }
        if ($tahunEmisi) {
            $globalTotalsQuery->where('emisi', $tahunEmisi);
        }
        $totals = $globalTotalsQuery->groupBy('pecahan')->pluck('total', 'pecahan');
        $globalTotalsPerPecahan = $globalTotalsPerPecahan->merge($totals);
        $globalGrandTotal = $globalTotalsPerPecahan->sum();

        // Total Hasil Filter (Sesuai yang tampil sekarang - include date/gilir)
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

        if ($tahunAnggaran) {
            $query->where('tahun_anggaran', $tahunAnggaran);
            $totalsQuery->where('tahun_anggaran', $tahunAnggaran);
        }

        if ($tahunEmisi) {
            $query->where('emisi', $tahunEmisi);
            $totalsQuery->where('emisi', $tahunEmisi);
        }

        $currentTotals = $totalsQuery->groupBy('pecahan')->pluck('total', 'pecahan');
        $filteredTotalsPerPecahan = $filteredTotalsPerPecahan->merge($currentTotals);

        $data = $query->orderBy('tanggal_penerimaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $filteredGrandTotal = $filteredTotalsPerPecahan->sum();

        return view('reports.print', compact(
            'startDate', 'endDate', 'gilir', 'pecahan', 'tahunAnggaran', 'tahunEmisi',
            'data', 'globalTotalsPerPecahan', 'globalGrandTotal', 
            'filteredTotalsPerPecahan', 'filteredGrandTotal'
        ));
    }

}
