<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Models\HctsReceiving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HctsReceivingController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $pecahanFilter = $request->input('pecahan');
        $gilirFilter = $request->input('gilir');
        $taFilter = $request->input('tahun_anggaran');
        $teFilter = $request->input('tahun_emisi');

        // Ambil pilihan yang tersedia untuk filter
        $availableYears = HctsReceiving::distinct()->pluck('tahun_anggaran')->sortDesc();
        $availableEmissions = HctsReceiving::distinct()->pluck('emisi')->sortDesc();

        $query = HctsReceiving::with('user');

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
        }

        if ($pecahanFilter) {
            $query->where('pecahan', $pecahanFilter);
        }

        if ($gilirFilter) {
            $query->where('gilir', $gilirFilter);
        }

        if ($taFilter) {
            $query->where('tahun_anggaran', $taFilter);
        }

        if ($teFilter) {
            $query->where('emisi', $teFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bon', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%");
            });
        }

        // Hitung ringkasan (berdasarkan filter, tapi tidak kena batasan halaman)
        $summaryQuery = clone $query;
        $summaryData = $summaryQuery->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan')
            ->toArray();

        $grandTotal = array_sum($summaryData);

        $receivings = $query->latest()->paginate(10)->withQueryString();

        return view('hcts-receiving.index', compact(
            'receivings',
            'startDate',
            'endDate',
            'search',
            'pecahanFilter',
            'gilirFilter',
            'taFilter',
            'teFilter',
            'availableYears',
            'availableEmissions',
            'summaryData',
            'grandTotal'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $filename = 'laporan_penerimaan_hcts_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Nomor Bon', 'Pecahan', 'Gilir', 'Jumlah', 'Batch', 'Seri', 'Emisi', 'TA', 'Nomor Segel', 'Petugas']);

            /** @var \Illuminate\Database\Eloquent\Builder $query */
            $query = HctsReceiving::with('user');
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_penerimaan', [$request->start_date, $request->end_date]);
            }
            if ($request->filled('pecahan')) {
                $query->where('pecahan', $request->pecahan);
            }
            if ($request->filled('gilir')) {
                $query->where('gilir', $request->gilir);
            }
            if ($request->filled('tahun_anggaran')) {
                $query->where('tahun_anggaran', $request->tahun_anggaran);
            }
            if ($request->filled('tahun_emisi')) {
                $query->where('emisi', $request->tahun_emisi);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_bon', 'like', "%{$search}%")
                        ->orWhere('batch', 'like', "%{$search}%")
                        ->orWhere('seri', 'like', "%{$search}%");
                });
            }

            $query->chunk(100, function ($rows) use ($file) {
                foreach ($rows as $row) {
                    fputcsv($file, [
                        $row->tanggal_penerimaan,
                        $row->nomor_bon,
                        $row->pecahan,
                        $row->gilir,
                        $row->jumlah,
                        $row->batch,
                        $row->seri,
                        $row->emisi,
                        $row->tahun_anggaran,
                        $row->nomor_segel,
                        $row->user->name ?? '-',
                    ]);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $pecahanFilter = $request->input('pecahan');
        $gilirFilter = $request->input('gilir');
        $taFilter = $request->input('tahun_anggaran');
        $teFilter = $request->input('tahun_emisi');

        $query = HctsReceiving::with('user');
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
        }
        if ($pecahanFilter) {
            $query->where('pecahan', $pecahanFilter);
        }
        if ($gilirFilter) {
            $query->where('gilir', $gilirFilter);
        }
        if ($taFilter) {
            $query->where('tahun_anggaran', $taFilter);
        }
        if ($teFilter) {
            $query->where('emisi', $teFilter);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bon', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%");
            });
        }

        $receivings = $query->latest()->get();

        return view('hcts-receiving.print', compact('receivings', 'startDate', 'endDate', 'search', 'pecahanFilter', 'gilirFilter', 'taFilter', 'teFilter'));
    }

    public function getHcsTotal(Request $request)
    {
        $hcsTotal = HcsReceiving::where('pecahan', $request->pecahan)
            ->where('batch', $request->batch)
            ->where('seri', $request->seri)
            ->where('emisi', $request->emisi)
            ->where('tahun_anggaran', $request->tahun_anggaran)
            ->sum('jumlah');

        return response()->json(['total' => $hcsTotal]);
    }

    public function create()
    {
        return view('hcts-receiving.create');
    }

    public function edit(HctsReceiving $hcts_receiving)
    {
        return view('hcts-receiving.edit', compact('hcts_receiving'));
    }

    public function update(Request $request, HctsReceiving $hcts_receiving)
    {
        $validated = $request->validate([
            'nomor_bon' => 'required|string',
            'tanggal_penerimaan' => 'required|date',
            'pecahan' => 'required|in:S,T,U,V,W,X,Y',
            'gilir' => 'required|in:Gilir 1,Gilir 2,Gilir 3',
            'jumlah' => 'required|integer|min:0',
            'batch' => 'required|string|max:10',
            'seri' => 'required|string|regex:/^[A-Z]{2}-[A-Z]{2}[0-9]$/',
            'emisi' => 'required|integer',
            'tahun_anggaran' => 'required|integer',
            'nomor_segel' => 'required|string',
        ]);

        // Validasi: Gabungan HCS + HCTS tidak boleh lewat dari 4.500.000 bilyet
        $hcsTotal = HcsReceiving::where('pecahan', $validated['pecahan'])
            ->where('batch', $validated['batch'])
            ->where('seri', $validated['seri'])
            ->where('emisi', $validated['emisi'])
            ->where('tahun_anggaran', $validated['tahun_anggaran'])
            ->sum('jumlah');

        $hctsTotal = HctsReceiving::where('pecahan', $validated['pecahan'])
            ->where('batch', $validated['batch'])
            ->where('seri', $validated['seri'])
            ->where('emisi', $validated['emisi'])
            ->where('tahun_anggaran', $validated['tahun_anggaran'])
            ->where('id', '!=', $hcts_receiving->id)
            ->sum('jumlah');

        if (($hcsTotal + $hctsTotal + $validated['jumlah']) > 4500000) {
            $rem = 4500000 - ($hcsTotal + $hctsTotal);

            return back()->withInput()->withErrors([
                'jumlah' => "Total Penerimaan HCS ($hcsTotal) + HCTS ($hctsTotal) melebihi batas 4.500.000. Sisa kuota: ".number_format($rem, 0, ',', '.'),
            ]);
        }

        $hcts_receiving->update($validated);

        return redirect()->route('hcts-receiving.index')->with('success', 'Data Penerimaan HCTS berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_bon' => 'required|string',
            'tanggal_penerimaan' => 'required|date',
            'pecahan' => 'required|in:S,T,U,V,W,X,Y',
            'gilir' => 'required|in:Gilir 1,Gilir 2,Gilir 3',
            'jumlah' => 'required|integer|min:0',
            'batch' => 'required|string|max:10',
            'seri' => 'required|string|regex:/^[A-Z]{2}-[A-Z]{2}[0-9]$/',
            'emisi' => 'required|integer',
            'tahun_anggaran' => 'required|integer',
            'nomor_segel' => 'required|string',
        ]);

        // Validation: HCS + HCTS <= 4,500,000
        $hcsTotal = HcsReceiving::where('pecahan', $validated['pecahan'])
            ->where('batch', $validated['batch'])
            ->where('seri', $validated['seri'])
            ->where('emisi', $validated['emisi'])
            ->where('tahun_anggaran', $validated['tahun_anggaran'])
            ->sum('jumlah');

        $hctsTotal = HctsReceiving::where('pecahan', $validated['pecahan'])
            ->where('batch', $validated['batch'])
            ->where('seri', $validated['seri'])
            ->where('emisi', $validated['emisi'])
            ->where('tahun_anggaran', $validated['tahun_anggaran'])
            ->sum('jumlah');

        if (($hcsTotal + $hctsTotal + $validated['jumlah']) > 4500000) {
            $rem = 4500000 - ($hcsTotal + $hctsTotal);

            return back()->withInput()->withErrors([
                'jumlah' => "Total Penerimaan HCS ($hcsTotal) + HCTS ($hctsTotal) melebihi batas 4.500.000. Sisa kuota: ".number_format($rem, 0, ',', '.'),
            ]);
        }

        $validated['created_by'] = auth()->id();
        HctsReceiving::create($validated);

        return redirect()->route('hcts-receiving.index')->with('success', 'Data Penerimaan HCTS berhasil disimpan.');
    }

    public function summary(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = $this->getSummaryQuery($request);
        $groups = $query->paginate(15)->withQueryString();

        return view('hcts-receiving.summary', compact('groups', 'startDate', 'endDate', 'search'));
    }

    public function summaryExport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $filename = 'hcs_hcts_summary_'.($startDate ?: 'all').'_to_'.($endDate ?: 'all').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Batch', 'Seri', 'Pecahan', 'Emisi', 'TA', 'Total HCS', 'Total HCTS', 'Grand Total', '% HCTS/HCS']);

            $query = $this->getSummaryQuery($request);
            $query->chunk(100, function ($rows) use ($file) {
                foreach ($rows as $row) {
                    $grandTotal = $row->total_hcs + $row->total_hcts;
                    $percent = $row->total_hcs > 0 ? round(($row->total_hcts / $row->total_hcs) * 100, 2) : ($row->total_hcts > 0 ? 100 : 0);
                    fputcsv($file, [
                        $row->batch,
                        $row->seri,
                        $row->pecahan,
                        $row->emisi,
                        $row->tahun_anggaran,
                        $row->total_hcs,
                        $row->total_hcts,
                        $grandTotal,
                        $percent.'%',
                    ]);
                }
            });
            fclose($file);
        };

    }

    public function summaryPrint(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $taFilter = $request->input('tahun_anggaran');
        $teFilter = $request->input('tahun_emisi');

        $query = $this->getSummaryQuery($request);
        $groups = $query->get();

        return view('hcts-receiving.summary-print', compact('groups', 'startDate', 'endDate', 'search', 'taFilter', 'teFilter'));
    }

    private function getSummaryQuery(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $hcsSub = DB::table('hcs_receivings')
            ->select('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran', 'jumlah', 'tanggal_penerimaan', DB::raw("'HCS' as type"));

        $hctsSub = DB::table('hcts_receivings')
            ->select('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran', 'jumlah', 'tanggal_penerimaan', DB::raw("'HCTS' as type"));

        if ($startDate && $endDate) {
            $hcsSub->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
            $hctsSub->whereBetween('tanggal_penerimaan', [$startDate, $endDate]);
        }

        if ($search) {
            $hcsSub->where(function ($q) use ($search) {
                $q->where('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%");
            });
            $hctsSub->where(function ($q) use ($search) {
                $q->where('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%");
            });
        }

        return DB::table($hcsSub->unionAll($hctsSub), 'combined')
            ->select(
                'pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran',
                DB::raw("SUM(CASE WHEN type = 'HCS' THEN jumlah ELSE 0 END) as total_hcs"),
                DB::raw("SUM(CASE WHEN type = 'HCTS' THEN jumlah ELSE 0 END) as total_hcts")
            )
            ->groupBy('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran')
            ->orderBy('tahun_anggaran', 'desc')
            ->orderBy('pecahan', 'asc');
    }

    public function destroy(HctsReceiving $hcts_receiving)
    {
        if (! in_array(auth()->user()->role, ['sortir', 'admin'])) {
            abort(403);
        }
        $hcts_receiving->delete();

        return redirect()->route('hcts-receiving.index')->with('success', 'Data Penerimaan HCTS berhasil dihapus.');
    }
}
