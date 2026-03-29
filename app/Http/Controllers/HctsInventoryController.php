<?php

namespace App\Http\Controllers;

use App\Services\HctsInventoryService;
use Illuminate\Http\Request;

class HctsInventoryController extends Controller
{
    protected $service;

    public function __construct(HctsInventoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return view('hcts-inventory.index', $this->service->getInventoryData($request->all()));
    }

    public function export(Request $request)
    {
        $data = $this->service->getInventoryData($request->all());
        $filename = 'laporan_persediaan_hcts_'.date('Ymd_His').'.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pecahan', 'Tahun Anggaran', 'Tahun Emisi', 'Total Received', 'Total Submitted', 'Stock']);
            foreach ($data['inventory'] as $row) {
                fputcsv($file, [$row->pecahan, $row->tahun_anggaran, $row->tahun_emisi, $row->total_received, $row->total_submitted, $row->stock]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        return view('hcts-inventory.print', $this->service->getInventoryData($request->all()));
    }

    public function getBatchDetail(Request $request)
    {
        $pecahan = $request->input('pecahan');
        $ta = $request->input('tahun_anggaran');
        $te = $request->input('tahun_emisi');
        if (!$pecahan || !$ta || !$te) return response()->json([]);

        return response()->json($this->service->getBatchDetail($pecahan, $ta, $te));
    }
}
