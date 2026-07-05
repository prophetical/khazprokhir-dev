<?php

namespace App\Http\Controllers;

use App\Models\HctsReceiving;
use App\Services\HctsReceivingService;
use App\Traits\SanitizesCsv;
use Illuminate\Http\Request;

class HctsReceivingController extends Controller
{
    use SanitizesCsv;

    protected $service;

    public function __construct(HctsReceivingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $options = $this->service->getFilterOptions();
        $query = HctsReceiving::with('user');
        $this->applyFilters($query, $request);

        $summaryQuery = clone $query;
        $summaryData = $summaryQuery->selectRaw('pecahan, SUM(jumlah) as total')->groupBy('pecahan')->pluck('total', 'pecahan')->toArray();

        return view('hcts-receiving.index', array_merge($request->all(), [
            'receivings' => $query->latest()->simplePaginate(20)->withQueryString(),
            'availableYears' => $options['years'],
            'availableEmissions' => $options['emissions'],
            'summaryData' => $summaryData,
            'grandTotal' => array_sum($summaryData),
            'startDate' => $request->start_date, 'endDate' => $request->end_date, 'search' => $request->search,
            'pecahanFilter' => $request->pecahan, 'gilirFilter' => $request->gilir,
            'taFilter' => $request->tahun_anggaran, 'teFilter' => $request->tahun_emisi,
        ]));
    }

    public function export(Request $request)
    {
        $query = HctsReceiving::with('user');
        $this->applyFilters($query, $request);

        $filename = 'laporan_penerimaan_hcts_'.date('Ymd_His').'.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Nomor Bon', 'Pecahan', 'Gilir', 'Jumlah', 'Batch', 'Seri', 'Emisi', 'TA', 'Nomor Segel', 'Petugas']);
            $query->chunk(100, function ($rows) use ($file) {
                foreach ($rows as $row) {
                    fputcsv($file, array_map([$this, 'sanitizeCsvField'], [
                        $row->tanggal_penerimaan, $row->nomor_bon, $row->pecahan, $row->gilir, $row->jumlah, $row->batch, $row->seri, $row->emisi, $row->tahun_anggaran, $row->nomor_segel, $row->user->name ?? '-'
                    ]));
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = HctsReceiving::with('user');
        $this->applyFilters($query, $request);
        $receivings = $query->latest()->get();

        return view('hcts-receiving.print', array_merge($request->all(), [
            'receivings' => $receivings,
            'startDate' => $request->start_date, 'endDate' => $request->end_date, 'search' => $request->search,
            'pecahanFilter' => $request->pecahan, 'gilirFilter' => $request->gilir,
            'taFilter' => $request->tahun_anggaran, 'teFilter' => $request->tahun_emisi,
        ]));
    }

    public function getHcsTotal(Request $request)
    {
        return response()->json(['total' => $this->service->calculateHcsTotal($request->all())]);
    }

    public function create() { return view('hcts-receiving.create'); }

    public function edit(HctsReceiving $hcts_receiving) { return view('hcts-receiving.edit', compact('hcts_receiving')); }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        try {
            $this->service->processStore($validated, auth()->id());
            return redirect()->route('hcts-receiving.index')->with('success', 'Data Penerimaan HCTS berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('HCTS Receiving Store Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['jumlah' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function update(Request $request, HctsReceiving $hcts_receiving)
    {
        $validated = $this->validateRequest($request);
        try {
            $this->service->processUpdate($hcts_receiving, $validated);
            return redirect()->route('hcts-receiving.index')->with('success', 'Data Penerimaan HCTS berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('HCTS Receiving Update Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['jumlah' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.']);
        }
    }

    public function summary(Request $request)
    {
        $groups = $this->service->getSummaryQuery($request->all())->simplePaginate(20)->withQueryString();
        return view('hcts-receiving.summary', [
            'groups' => $groups,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
            'search' => $request->search,
            'taFilter' => $request->tahun_anggaran,
            'teFilter' => $request->tahun_emisi,
        ]);
    }

    public function summaryExport(Request $request)
    {
        $filename = 'hcs_hcts_summary_'.date('Ymd_His').'.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $params = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'search' => $request->search,
            'tahun_anggaran' => $request->tahun_anggaran,
            'tahun_emisi' => $request->tahun_emisi,
        ];

        $callback = function () use ($params) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Batch', 'Seri', 'Pecahan', 'Emisi', 'TA', 'Total HCS', 'Total HCTS', 'Grand Total', '% HCTS/HCS']);
            $this->service->getSummaryQuery($params)->chunk(100, function ($rows) use ($file) {
                foreach ($rows as $row) {
                    $grandTotal = $row->total_hcs + $row->total_hcts;
                    $percent = $row->total_hcs > 0 ? round(($row->total_hcts / $row->total_hcs) * 100, 2) : ($row->total_hcts > 0 ? 100 : 0);
                    fputcsv($file, array_map([$this, 'sanitizeCsvField'], [
                        $row->batch, $row->seri, $row->pecahan, $row->emisi, $row->tahun_anggaran, $row->total_hcs, $row->total_hcts, $grandTotal, $percent.'%'
                    ]));
                }
            });
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function summaryPrint(Request $request)
    {
        $groups = $this->service->getSummaryQuery($request->all())->get();
        return view('hcts-receiving.summary-print', [
            'groups' => $groups,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
            'search' => $request->search,
            'taFilter' => $request->tahun_anggaran,
            'teFilter' => $request->tahun_emisi,
        ]);
    }

    public function destroy(HctsReceiving $hcts_receiving)
    {
        $hcts_receiving->delete();
        return redirect()->route('hcts-receiving.index')->with('success', 'Data Penerimaan HCTS berhasil dihapus.');
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) $query->whereBetween('tanggal_penerimaan', [$request->start_date, $request->end_date]);
        if ($request->filled('pecahan')) $query->where('pecahan', $request->pecahan);
        if ($request->filled('gilir')) $query->where('gilir', $request->gilir);
        if ($request->filled('tahun_anggaran')) $query->where('tahun_anggaran', $request->tahun_anggaran);
        if ($request->filled('tahun_emisi')) $query->where('emisi', $request->tahun_emisi);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) { $q->where('nomor_bon', 'like', "%{$s}%")->orWhere('batch', 'like', "%{$s}%")->orWhere('seri', 'like', "%{$s}%"); });
        }
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'nomor_bon' => 'required|string', 'tanggal_penerimaan' => 'required|date', 'pecahan' => 'required|in:S,T,U,V,W,X,Y',
            'gilir' => 'required|in:Gilir 1,Gilir 2,Gilir 3', 'jumlah' => 'required|integer|min:0', 'batch' => 'required|string|max:10',
            'seri' => 'required|string|regex:/^[A-Z]{2}-[A-Z]{2}[0-9]$/', 'emisi' => 'required|integer', 'tahun_anggaran' => 'required|integer', 'nomor_segel' => 'required|string',
        ]);
    }
}
