<?php

namespace App\Http\Controllers;

use App\Models\HctsReceiving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HctsInventoryController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getInventoryData($request);

        return view('hcts-inventory.index', $data);
    }

    public function export(Request $request)
    {
        $data = $this->getInventoryData($request);
        $inventory = $data['inventory'];

        $filename = 'laporan_persediaan_hcts_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($inventory) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pecahan', 'Tahun Anggaran', 'Tahun Emisi', 'Total Received', 'Total Submitted', 'Stock']);

            foreach ($inventory as $row) {
                fputcsv($file, [
                    $row->pecahan,
                    $row->tahun_anggaran,
                    $row->tahun_emisi,
                    $row->total_received,
                    $row->total_submitted,
                    $row->stock,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $data = $this->getInventoryData($request);

        return view('hcts-inventory.print', $data);
    }

    private function getInventoryData(Request $request)
    {
        $ta = $request->input('tahun_anggaran');
        $te = $request->input('tahun_emisi');

        // 1. Calculate Total Receipts grouped by specs
        $receiptsQuery = HctsReceiving::select(
            'pecahan',
            'tahun_anggaran',
            'emisi as tahun_emisi',
            DB::raw('SUM(jumlah) as total_received')
        );

        if ($ta) {
            $receiptsQuery->where('tahun_anggaran', $ta);
        }
        if ($te) {
            $receiptsQuery->where('emisi', $te);
        }

        $receipts = $receiptsQuery->groupBy('pecahan', 'tahun_anggaran', 'emisi')->get();

        // 2. Calculate Total Submissions grouped by specs
        $submissionsQuery = DB::table('hcts_submission_batches')
            ->join('hcts_submissions', 'hcts_submission_batches.hcts_submission_id', '=', 'hcts_submissions.id')
            ->select(
                'hcts_submissions.pecahan',
                'hcts_submissions.tahun_anggaran',
                'hcts_submissions.tahun_emisi',
                DB::raw('SUM(hcts_submission_batches.jumlah) as total_submitted')
            );

        if ($ta) {
            $submissionsQuery->where('hcts_submissions.tahun_anggaran', $ta);
        }
        if ($te) {
            $submissionsQuery->where('hcts_submissions.tahun_emisi', $te);
        }

        $submissions = $submissionsQuery->groupBy('hcts_submissions.pecahan', 'hcts_submissions.tahun_anggaran', 'hcts_submissions.tahun_emisi')->get();

        // 3. Map submissions to receipts and calculate inventory
        $inventory = $receipts->map(function ($receipt) use ($submissions) {
            $submission = $submissions->where('pecahan', $receipt->pecahan)
                ->where('tahun_anggaran', $receipt->tahun_anggaran)
                ->where('tahun_emisi', $receipt->tahun_emisi)
                ->first();

            $totalSubmitted = $submission ? (int) $submission->total_submitted : 0;
            $stock = (int) $receipt->total_received - $totalSubmitted;

            return (object) [
                'pecahan' => $receipt->pecahan,
                'tahun_anggaran' => $receipt->tahun_anggaran,
                'tahun_emisi' => $receipt->tahun_emisi,
                'total_received' => (int) $receipt->total_received,
                'total_submitted' => $totalSubmitted,
                'stock' => $stock,
            ];
        })->values();

        // 4. Calculate Aggregate Stock for Cards (7 Denominations)
        $denominations = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $cardStats = [];
        $totalInventory = 0;

        foreach ($denominations as $denom) {
            $denomStock = $inventory->where('pecahan', $denom)->sum('stock');
            $cardStats[$denom] = $denomStock;
            $totalInventory += $denomStock;
        }

        // 5. Get available filters
        $availableTA = DB::table('hcts_receivings')->distinct()->pluck('tahun_anggaran')->merge(
            DB::table('hcts_submissions')->distinct()->pluck('tahun_anggaran')
        )->unique()->sortDesc()->values();

        $availableTE = DB::table('hcts_receivings')->distinct()->pluck('emisi')->merge(
            DB::table('hcts_submissions')->distinct()->pluck('tahun_emisi')
        )->unique()->sortDesc()->values();

        return compact('inventory', 'cardStats', 'totalInventory', 'availableTA', 'availableTE', 'ta', 'te');
    }

    public function getBatchDetail(Request $request)
    {
        $pecahan = $request->input('pecahan');
        $ta = $request->input('tahun_anggaran');
        $te = $request->input('tahun_emisi');

        if (! $pecahan || ! $ta || ! $te) {
            return response()->json([]);
        }

        // 1. Get receipts per batch
        $receipts = HctsReceiving::where('pecahan', $pecahan)
            ->where('tahun_anggaran', $ta)
            ->where('emisi', $te)
            ->select('batch', DB::raw('SUM(jumlah) as total_received'))
            ->groupBy('batch')
            ->get();

        // 2. Get submissions per batch
        $submissions = DB::table('hcts_submission_batches')
            ->join('hcts_submissions', 'hcts_submission_batches.hcts_submission_id', '=', 'hcts_submissions.id')
            ->where('hcts_submissions.pecahan', $pecahan)
            ->where('hcts_submissions.tahun_anggaran', $ta)
            ->where('hcts_submissions.tahun_emisi', $te)
            ->select('hcts_submission_batches.batch', DB::raw('SUM(hcts_submission_batches.jumlah) as total_submitted'))
            ->groupBy('hcts_submission_batches.batch')
            ->get();

        // 3. Calculate batch stock
        $batchInventory = $receipts->map(function ($receipt) use ($submissions) {
            $submission = $submissions->where('batch', $receipt->batch)->first();
            $totalSubmitted = $submission ? $submission->total_submitted : 0;
            $stock = $receipt->total_received - $totalSubmitted;

            return [
                'batch' => $receipt->batch,
                'total_received' => (int) $receipt->total_received,
                'total_submitted' => (int) $totalSubmitted,
                'stock' => (int) $stock,
            ];
        })->values();

        return response()->json($batchInventory);
    }
}
