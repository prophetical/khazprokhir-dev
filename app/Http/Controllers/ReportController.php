<?php

namespace App\Http\Controllers;

use App\Services\ReceivingReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $service;

    public function __construct(ReceivingReportService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $reportData = $this->service->getReportData($params);

        return view('reports.index', array_merge($params, $reportData, [
            'startDate' => $params['start_date'] ?? null,
            'endDate' => $params['end_date'] ?? null,
            'tahunAnggaran' => $params['tahun_anggaran'] ?? null,
            'tahunEmisi' => $params['tahun_emisi'] ?? null,
            'gilir' => $params['gilir'] ?? null,
            'globalTotalsPerPecahan' => $reportData['globalTotals'],
            'globalGrandTotal' => $reportData['globalGrandTotal']
        ]));
    }

    public function export(Request $request)
    {
        $params = $request->all();
        $filename = 'report_receiving_'.($params['start_date'] ?? 'all').'_to_'.($params['end_date'] ?? 'all').'.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function () use ($params) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'No Bon', 'Pecahan', 'Emisi', 'TA', 'Jumlah', 'Gilir', 'Mesin', 'Supplier', 'Batch', 'Seri', 'Operator']);

            $this->service->getExportQuery($params)->chunk(100, function ($receivings) use ($file) {
                foreach ($receivings as $row) {
                    fputcsv($file, [
                        $row->tanggal_penerimaan, $row->nomor_bon, $row->pecahan, $row->emisi, $row->tahun_anggaran,
                        $row->jumlah, $row->gilir, $row->mesin, $row->supplier, $row->batch, $row->seri, $row->user->name ?? '-'
                    ]);
                }
            });
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $params = $request->all();
        $data = $this->service->getExportQuery($params)->orderBy('tanggal_penerimaan', 'desc')->orderBy('created_at', 'desc')->get();
        $globalTotalsPerPecahan = $this->service->getTotals($params, true);
        $filteredTotalsPerPecahan = $this->service->getTotals($params, false);

        return view('reports.print', array_merge($params, [
            'startDate' => $params['start_date'] ?? null,
            'endDate' => $params['end_date'] ?? null,
            'tahunAnggaran' => $params['tahun_anggaran'] ?? null,
            'tahunEmisi' => $params['tahun_emisi'] ?? null,
            'gilir' => $params['gilir'] ?? null,
            'data' => $data,
            'globalTotalsPerPecahan' => $globalTotalsPerPecahan,
            'globalGrandTotal' => $globalTotalsPerPecahan->sum(),
            'filteredTotalsPerPecahan' => $filteredTotalsPerPecahan,
            'filteredGrandTotal' => $filteredTotalsPerPecahan->sum()
        ]));
    }
}
