<?php

namespace App\Http\Controllers;

use App\Models\HcsSorting;
use App\Services\HcsSortingService;
use App\Traits\SanitizesCsv;
use Illuminate\Http\Request;

class HcsSortingReportController extends Controller
{
    use SanitizesCsv;

    protected $service;

    public function __construct(HcsSortingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = HcsSorting::query();
        $this->applyFilters($query, $request);

        return view('hcs-sorting-report.index', [
            'reports' => $query->orderBy('tanggal_sortir', 'desc')->orderBy('created_at', 'desc')->simplePaginate(20)->withQueryString()
        ]);
    }

    public function edit(HcsSorting $hcs_sorting_report)
    {
        if ($hcs_sorting_report->status_kunci_pengemasan) {
            return redirect()->route('hcs-sorting-reports.index')->with('error', 'Data penyortiran tidak dapat diubah karena sudah digunakan dalam proses pengemasan.');
        }

        $packsData = $this->service->getPacksData($hcs_sorting_report->pecahan, $hcs_sorting_report->batch, $hcs_sorting_report->seri);
        return view('hcs-sorting-report.edit', compact('hcs_sorting_report', 'packsData'));
    }

    public function update(Request $request, HcsSorting $hcs_sorting_report)
    {
        $validated = $request->validate([
            'supplier' => 'required|in:Rikyet,Cutpack',
            'emisi' => 'required',
            'petugas_1' => 'required',
            'petugas_2' => 'nullable',
            'tanggal_sortir' => 'required|date',
            'gilir' => 'required',
            'selected_packs' => 'required|array|min:' . ($request->has('is_manual') ? '1' : '4'),
        ]);

        if ($request->has('is_manual'))
            $validated['is_manual'] = true;

        try {
            $this->service->processUpdate($hcs_sorting_report, $validated);
            return redirect()->route('hcs-sorting-reports.index')->with('success', 'Data laporan penyortiran berhasil diubah.');
        } catch (\Exception $e) {
            \Log::error('HCS Sorting Report Update Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat mengubah data. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy(HcsSorting $hcs_sorting_report)
    {
        try {
            $this->service->processDelete($hcs_sorting_report);
            return redirect()->route('hcs-sorting-reports.index')->with('success', 'Data laporan penyortiran berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('HCS Sorting Report Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus data. Silakan coba lagi.');
        }
    }

    public function export(Request $request)
    {
        $filename = 'report_penyortiran_' . date('Ymd_His') . '.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Gilir', 'Batch', 'Seri', 'Emisi', 'TA', 'Pecahan', 'Supplier', 'Pack Terpilih', 'Jumlah Pack', 'Total Bilyet', 'Petugas 1', 'Petugas 2']);

            $query = HcsSorting::query();
            $this->applyFilters($query, $request);

            $query->orderBy('tanggal_sortir', 'desc')->orderBy('created_at', 'desc')->chunk(100, function ($reports) use ($file) {
                foreach ($reports as $row) {
                    $displayStr = $this->service->formatPacksToRanges($row->packs_selected ?: []);
                    fputcsv($file, array_map([$this, 'sanitizeCsvField'], [
                        $row->tanggal_sortir->format('Y-m-d'),
                        $row->gilir,
                        $row->batch,
                        $row->seri,
                        $row->emisi,
                        $row->tahun_anggaran,
                        $row->pecahan,
                        $row->supplier,
                        $displayStr,
                        $row->jumlah_pack,
                        $row->jumlah_bilyet,
                        $row->petugas_1,
                        $row->petugas_2 ?? '-',
                    ]));
                }
            });
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = HcsSorting::query();
        $this->applyFilters($query, $request);
        $reports = $query->orderBy('tanggal_sortir', 'desc')->orderBy('created_at', 'desc')->limit(1000)->get();

        return view('hcs-sorting-report.print', compact('reports'));
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('tanggal_dari'))
            $query->whereDate('tanggal_sortir', '>=', $request->tanggal_dari);
        if ($request->filled('tanggal_sampai'))
            $query->whereDate('tanggal_sortir', '<=', $request->tanggal_sampai);
        if ($request->filled('batch'))
            $query->where('batch', 'ilike', '%' . $request->batch . '%');
        if ($request->filled('seri'))
            $query->where('seri', 'ilike', '%' . $request->seri . '%');
        if ($request->filled('gilir'))
            $query->where('gilir', $request->gilir);
        if ($request->filled('pecahan'))
            $query->where('pecahan', $request->pecahan);
    }
}
