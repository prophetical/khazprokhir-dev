<?php

namespace App\Http\Controllers;

use App\Services\RekomendasiService;
use Illuminate\Http\Request;

class RekomendasiPenerimaanController extends Controller
{
    protected $service;

    public function __construct(RekomendasiService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $batches = $this->service->getPenerimaanRecommendations($request->all());
        return view('hcs-receiving.rekomendasi.index', compact('batches'));
    }

    public function show(Request $request)
    {
        $params = $request->only(['pecahan', 'batch', 'seri', 'tahun_anggaran', 'emisi']);
        $recommendations = $this->service->getPenerimaanRecommendations($params)->first()?->recommended_packs ?? [];
        return view('hcs-receiving.rekomendasi.show', compact('params', 'recommendations'));
    }

    public function print(Request $request)
    {
        $batches = $this->service->getPenerimaanRecommendations($request->all());
        return view('hcs-receiving.rekomendasi.print', compact('batches'));
    }

    public function export(Request $request)
    {
        $filename = 'rekomendasi_penerimaan_'.date('Y-m-d').'.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pecahan', 'Batch', 'Seri', 'TA', 'Emisi', 'Pack Existing', 'Pack Rekomendasi']);
            $batches = $this->service->getPenerimaanRecommendations($request->all());
            foreach ($batches as $item) {
                fputcsv($file, [
                    $item->pecahan, $item->batch, $item->seri, $item->tahun_anggaran, $item->emisi,
                    collect($item->existing_unsorted_single)->pluck('number')->implode(', '),
                    collect($item->recommended_packs)->pluck('number')->implode(', '),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
