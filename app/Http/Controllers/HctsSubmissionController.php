<?php

namespace App\Http\Controllers;

use App\Models\HctsSubmission;
use App\Models\HctsSubmissionBatch;
use App\Models\HctsReceiving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HctsSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = HctsSubmission::with(['user', 'batches']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_ba', 'like', "%{$search}%")
                  ->orWhere('pecahan', 'like', "%{$search}%")
                  ->orWhere('tahun_anggaran', 'like', "%{$search}%")
                  ->orWhere('tahun_emisi', 'like', "%{$search}%")
                  ->orWhere('pemasok1', 'like', "%{$search}%")
                  ->orWhere('pemasok2', 'like', "%{$search}%");
            });
        } elseif ($startDate && $endDate) {
            $query->whereBetween('tanggal_penyerahan', [$startDate, $endDate]);
        }

        $submissions = $query->latest()->paginate(10)->withQueryString();

        return view('hcts-submission.index', compact('submissions', 'startDate', 'endDate', 'search'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $filename = "laporan_penyerahan_hcts_bi_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal Penyerahan', 'Nomor BA', 'Pecahan', 'Tahun Anggaran', 'Tahun Emisi', 'Jumlah Bilyet', 'Pemasok 1', 'Pemasok 2', 'Batch Detail', 'Petugas']);

            $query = HctsSubmission::with(['user', 'batches']);
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_ba', 'like', "%{$search}%")
                      ->orWhere('pecahan', 'like', "%{$search}%")
                      ->orWhere('tahun_anggaran', 'like', "%{$search}%")
                      ->orWhere('tahun_emisi', 'like', "%{$search}%")
                      ->orWhere('pemasok1', 'like', "%{$search}%")
                      ->orWhere('pemasok2', 'like', "%{$search}%");
                });
            } elseif ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_penyerahan', [$request->start_date, $request->end_date]);
            }

            $query->latest()->chunk(100, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        $batchDetail = $row->batches->map(function ($b) {
                                    return $b->batch . " (" . number_format($b->jumlah) . ")";
                                }
                                )->implode('; ');

                                fputcsv($file, [
                                    $row->tanggal_penyerahan,
                                    $row->nomor_ba,
                                    $row->pecahan,
                                    $row->tahun_anggaran,
                                    $row->tahun_emisi,
                                    $row->jumlah_bilyet,
                                    $row->pemasok1,
                                    $row->pemasok2,
                                    $batchDetail,
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
        $search = $request->input('search');

        $query = HctsSubmission::with(['user', 'batches']);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_ba', 'like', "%{$search}%")
                  ->orWhere('pecahan', 'like', "%{$search}%")
                  ->orWhere('tahun_anggaran', 'like', "%{$search}%")
                  ->orWhere('tahun_emisi', 'like', "%{$search}%")
                  ->orWhere('pemasok1', 'like', "%{$search}%")
                  ->orWhere('pemasok2', 'like', "%{$search}%");
            });
        } elseif ($startDate && $endDate) {
            $query->whereBetween('tanggal_penyerahan', [$startDate, $endDate]);
        }

        $submissions = $query->latest()->get();
        return view('hcts-submission.print', compact('submissions', 'startDate', 'endDate', 'search'));
    }

    public function create()
    {
        $availableTA = DB::table('hcts_receivings')->distinct()->pluck('tahun_anggaran')->merge(
            DB::table('hcts_submissions')->distinct()->pluck('tahun_anggaran')
        )->unique()->sortDesc()->values();

        $availableTE = DB::table('hcts_receivings')->distinct()->pluck('emisi')->merge(
            DB::table('hcts_submissions')->distinct()->pluck('tahun_emisi')
        )->unique()->sortDesc()->values();

        return view('hcts-submission.create', compact('availableTA', 'availableTE'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_penyerahan' => 'required|date',
            'pecahan' => 'required|string',
            'tahun_anggaran' => 'required|integer',
            'tahun_emisi' => 'required|integer',
            'jumlah_bilyet' => 'required|integer|min:1',
            'pemasok1' => 'required|string',
            'pemasok2' => 'nullable|string',
            'nomor_ba' => 'required|string',
            'batches' => 'required|array|min:1',
            'batches.*.batch' => 'required|string',
            'batches.*.jumlah' => 'required|integer|min:1',
        ]);

        $totalBatchAmount = collect($request->batches)->sum('jumlah');

        if ($totalBatchAmount != $validated['jumlah_bilyet']) {
            return back()->withInput()->withErrors([
                'jumlah_bilyet' => 'Total Jumlah Per Batch (' . number_format($totalBatchAmount) . ') tidak sama dengan Jumlah Bilyet (' . number_format($validated['jumlah_bilyet']) . ').'
            ]);
        }

        try {
            DB::beginTransaction();

            $submission = HctsSubmission::create([
                'tanggal_penyerahan' => $validated['tanggal_penyerahan'],
                'pecahan' => $validated['pecahan'],
                'tahun_anggaran' => $validated['tahun_anggaran'],
                'tahun_emisi' => $validated['tahun_emisi'],
                'jumlah_bilyet' => $validated['jumlah_bilyet'],
                'pemasok1' => strtoupper($validated['pemasok1']),
                'pemasok2' => strtoupper($validated['pemasok2'] ?? ''),
                'nomor_ba' => $validated['nomor_ba'],
                'created_by' => auth()->id(),
            ]);

            foreach ($request->batches as $batchData) {
                HctsSubmissionBatch::create([
                    'hcts_submission_id' => $submission->id,
                    'batch' => $batchData['batch'],
                    'jumlah' => $batchData['jumlah'],
                ]);
            }

            DB::commit();

            return redirect()->route('hcts-submission.index')->with('success', 'Penyerahan HCTS berhasil disimpan.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit(HctsSubmission $hcts_submission)
    {
        $hcts_submission->load('batches');

        $availableTA = DB::table('hcts_receivings')->distinct()->pluck('tahun_anggaran')->merge(
            DB::table('hcts_submissions')->distinct()->pluck('tahun_anggaran')
        )->unique()->sortDesc()->values();

        $availableTE = DB::table('hcts_receivings')->distinct()->pluck('emisi')->merge(
            DB::table('hcts_submissions')->distinct()->pluck('tahun_emisi')
        )->unique()->sortDesc()->values();

        return view('hcts-submission.edit', compact('hcts_submission', 'availableTA', 'availableTE'));
    }

    public function update(Request $request, HctsSubmission $hcts_submission)
    {
        $validated = $request->validate([
            'tanggal_penyerahan' => 'required|date',
            'pecahan' => 'required|string',
            'tahun_anggaran' => 'required|integer',
            'tahun_emisi' => 'required|integer',
            'jumlah_bilyet' => 'required|integer|min:1',
            'pemasok1' => 'required|string',
            'pemasok2' => 'nullable|string',
            'nomor_ba' => 'required|string',
            'batches' => 'required|array|min:1',
            'batches.*.batch' => 'required|string',
            'batches.*.jumlah' => 'required|integer|min:1',
        ]);

        $totalBatchAmount = collect($request->batches)->sum('jumlah');

        if ($totalBatchAmount != $validated['jumlah_bilyet']) {
            return back()->withInput()->withErrors([
                'jumlah_bilyet' => 'Total Jumlah Per Batch (' . number_format($totalBatchAmount) . ') tidak sama dengan Jumlah Bilyet (' . number_format($validated['jumlah_bilyet']) . ').'
            ]);
        }

        try {
            DB::beginTransaction();

            $hcts_submission->update([
                'tanggal_penyerahan' => $validated['tanggal_penyerahan'],
                'pecahan' => $validated['pecahan'],
                'tahun_anggaran' => $validated['tahun_anggaran'],
                'tahun_emisi' => $validated['tahun_emisi'],
                'jumlah_bilyet' => $validated['jumlah_bilyet'],
                'pemasok1' => strtoupper($validated['pemasok1']),
                'pemasok2' => strtoupper($validated['pemasok2'] ?? ''),
                'nomor_ba' => $validated['nomor_ba'],
            ]);

            // Replace batches
            $hcts_submission->batches()->delete();
            foreach ($request->batches as $batchData) {
                HctsSubmissionBatch::create([
                    'hcts_submission_id' => $hcts_submission->id,
                    'batch' => $batchData['batch'],
                    'jumlah' => $batchData['jumlah'],
                ]);
            }

            DB::commit();

            return redirect()->route('hcts-submission.index')->with('success', 'Penyerahan HCTS berhasil diperbarui.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function destroy(HctsSubmission $hcts_submission)
    {
        try {
            DB::beginTransaction();
            $hcts_submission->batches()->delete();
            $hcts_submission->delete();
            DB::commit();
            return redirect()->route('hcts-submission.index')->with('success', 'Penyerahan HCTS ke BI berhasil dihapus.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    public function getAvailableBatches(Request $request)
    {
        $pecahan = $request->input('pecahan');
        $tahun_anggaran = $request->input('tahun_anggaran');
        $tahun_emisi = $request->input('tahun_emisi');
        $exclude_id = $request->input('exclude_id');

        if (!$pecahan || !$tahun_anggaran || !$tahun_emisi) {
            return response()->json([]);
        }

        // 1. Get total received per batch
        $receipts = HctsReceiving::where('pecahan', $pecahan)
            ->where('tahun_anggaran', $tahun_anggaran)
            ->where('emisi', $tahun_emisi)
            ->select('batch', DB::raw('SUM(jumlah) as total_received'))
            ->groupBy('batch')
            ->get();

        // 2. Get total submitted per batch
        $submissionsQuery = DB::table('hcts_submission_batches')
            ->join('hcts_submissions', 'hcts_submission_batches.hcts_submission_id', '=', 'hcts_submissions.id')
            ->where('hcts_submissions.pecahan', $pecahan)
            ->where('hcts_submissions.tahun_anggaran', $tahun_anggaran)
            ->where('hcts_submissions.tahun_emisi', $tahun_emisi);

        if ($exclude_id) {
            $submissionsQuery->where('hcts_submissions.id', '!=', $exclude_id);
        }

        $submissions = $submissionsQuery->select('hcts_submission_batches.batch', DB::raw('SUM(hcts_submission_batches.jumlah) as total_submitted'))
            ->groupBy('hcts_submission_batches.batch')
            ->get();

        // 3. Calculate stock and filter
        $availableBatches = $receipts->map(function ($receipt) use ($submissions) {
            $submission = $submissions->where('batch', $receipt->batch)->first();
            $totalSubmitted = $submission ? $submission->total_submitted : 0;
            $stock = $receipt->total_received - $totalSubmitted;

            return [
            'batch' => $receipt->batch,
            'total_received' => (int)$receipt->total_received,
            'total_submitted' => (int)$totalSubmitted,
            'stock' => (int)$stock
            ];
        })->filter(function ($item) {
            return $item['stock'] > 0;
        })->values();

        return response()->json($availableBatches);
    }
}
