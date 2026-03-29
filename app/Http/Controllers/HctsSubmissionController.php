<?php

namespace App\Http\Controllers;

use App\Models\HctsSubmission;
use App\Services\HctsSubmissionService;
use Illuminate\Http\Request;

class HctsSubmissionController extends Controller
{
    protected $service;

    public function __construct(HctsSubmissionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = HctsSubmission::with(['user', 'batches']);
        $this->applyFilters($query, $request);

        return view('hcts-submission.index', [
            'submissions' => $query->latest()->paginate(10)->withQueryString(),
            'startDate' => $request->start_date, 'endDate' => $request->end_date, 'search' => $request->search,
        ]);
    }

    public function export(Request $request)
    {
        $filename = 'laporan_penyerahan_hcts_bi_'.date('Ymd_His').'.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal Penyerahan', 'Nomor BA', 'Pecahan', 'Tahun Anggaran', 'Tahun Emisi', 'Jumlah Bilyet', 'Pemasok 1', 'Pemasok 2', 'Batch Detail', 'Petugas']);

            $query = HctsSubmission::with(['user', 'batches']);
            $this->applyFilters($query, $request);

            $query->latest()->chunk(100, function ($rows) use ($file) {
                foreach ($rows as $row) {
                    $batchDetail = $row->batches->map(fn($b) => $b->batch.' ('.number_format($b->jumlah).')')->implode('; ');
                    fputcsv($file, [$row->tanggal_penyerahan, $row->nomor_ba, $row->pecahan, $row->tahun_anggaran, $row->tahun_emisi, $row->jumlah_bilyet, $row->pemasok1, $row->pemasok2, $batchDetail, $row->user->name ?? '-']);
                }
            });
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = HctsSubmission::with(['user', 'batches']);
        $this->applyFilters($query, $request);
        $submissions = $query->latest()->get();

        return view('hcts-submission.print', [
            'submissions' => $submissions,
            'startDate' => $request->start_date, 'endDate' => $request->end_date, 'search' => $request->search,
        ]);
    }

    public function create()
    {
        $options = $this->service->getAvailableOptions();
        return view('hcts-submission.create', ['availableTA' => $options['ta'], 'availableTE' => $options['te']]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        try {
            $this->service->processStore($validated, auth()->id());
            return redirect()->route('hcts-submission.index')->with('success', 'Penyerahan HCTS berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['jumlah_bilyet' => $e->getMessage()]);
        }
    }

    public function edit(HctsSubmission $hcts_submission)
    {
        $hcts_submission->load('batches');
        $options = $this->service->getAvailableOptions();
        return view('hcts-submission.edit', [
            'hcts_submission' => $hcts_submission,
            'availableTA' => $options['ta'],
            'availableTE' => $options['te']
        ]);
    }

    public function update(Request $request, HctsSubmission $hcts_submission)
    {
        $validated = $this->validateRequest($request);
        try {
            $this->service->processUpdate($hcts_submission, $validated);
            return redirect()->route('hcts-submission.index')->with('success', 'Penyerahan HCTS berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['jumlah_bilyet' => $e->getMessage()]);
        }
    }

    public function destroy(HctsSubmission $hcts_submission)
    {
        try {
            $hcts_submission->batches()->delete();
            $hcts_submission->delete();
            return redirect()->route('hcts-submission.index')->with('success', 'Penyerahan HCTS ke BI berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus data: '.$e->getMessage()]);
        }
    }

    public function getAvailableBatches(Request $request)
    {
        $pecahan = $request->input('pecahan');
        $ta = $request->input('tahun_anggaran');
        $te = $request->input('tahun_emisi');
        if (!$pecahan || !$ta || !$te) return response()->json([]);

        return response()->json($this->service->getAvailableBatches($pecahan, $ta, $te, $request->input('exclude_id')));
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nomor_ba', 'like', "%{$s}%")->orWhere('pecahan', 'like', "%{$s}%")->orWhere('pemasok1', 'like', "%{$s}%")->orWhere('pemasok2', 'like', "%{$s}%");
            });
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penyerahan', [$request->start_date, $request->end_date]);
        }
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'tanggal_penyerahan' => 'required|date', 'pecahan' => 'required|string', 'tahun_anggaran' => 'required|integer', 'tahun_emisi' => 'required|integer',
            'jumlah_bilyet' => 'required|integer|min:1', 'pemasok1' => 'required|string', 'pemasok2' => 'nullable|string', 'nomor_ba' => 'required|string',
            'batches' => 'required|array|min:1', 'batches.*.batch' => 'required|string', 'batches.*.jumlah' => 'required|integer|min:1',
        ]);
    }
}
