<?php

namespace App\Services;

use App\Models\HctsReceiving;
use Illuminate\Support\Facades\DB;

class HctsInventoryService
{
    public function getInventoryData(array $filters): array
    {
        $ta = $filters['tahun_anggaran'] ?? null;
        $te = $filters['tahun_emisi'] ?? null;

        $receiptsRaw = HctsReceiving::select('pecahan', 'tahun_anggaran', 'emisi as tahun_emisi', DB::raw('SUM(jumlah) as total_received'));
        if ($ta) $receiptsRaw->where('tahun_anggaran', $ta);
        if ($te) $receiptsRaw->where('emisi', $te);
        $receipts = $receiptsRaw->groupBy('pecahan', 'tahun_anggaran', 'emisi')->get();

        $submissionsRaw = DB::table('hcts_submission_batches')
            ->join('hcts_submissions', 'hcts_submission_batches.hcts_submission_id', '=', 'hcts_submissions.id')
            ->select('hcts_submissions.pecahan', 'hcts_submissions.tahun_anggaran', 'hcts_submissions.tahun_emisi', DB::raw('SUM(hcts_submission_batches.jumlah) as total_submitted'));
        if ($ta) $submissionsRaw->where('hcts_submissions.tahun_anggaran', $ta);
        if ($te) $submissionsRaw->where('hcts_submissions.tahun_emisi', $te);
        $submissions = $submissionsRaw->groupBy('hcts_submissions.pecahan', 'hcts_submissions.tahun_anggaran', 'hcts_submissions.tahun_emisi')->get();

        $inventory = $receipts->map(function ($r) use ($submissions) {
            $s = $submissions->where('pecahan', $r->pecahan)->where('tahun_anggaran', $r->tahun_anggaran)->where('tahun_emisi', $r->tahun_emisi)->first();
            $totalSub = $s ? (int)$s->total_submitted : 0;
            return (object) [
                'pecahan' => $r->pecahan, 'tahun_anggaran' => $r->tahun_anggaran, 'tahun_emisi' => $r->tahun_emisi,
                'total_received' => (int)$r->total_received, 'total_submitted' => $totalSub, 'stock' => (int)$r->total_received - $totalSub,
            ];
        })->values();

        $cardStats = []; $totalInv = 0;
        foreach (['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $denom) {
            $stock = $inventory->where('pecahan', $denom)->sum('stock');
            $cardStats[$denom] = $stock;
            $totalInv += $stock;
        }

        $availableTA = DB::table('hcts_receivings')->distinct()->pluck('tahun_anggaran')
            ->merge(DB::table('hcts_submissions')->distinct()->pluck('tahun_anggaran'))
            ->unique()->sortDesc()->values();
        $availableTE = DB::table('hcts_receivings')->distinct()->pluck('emisi')
            ->merge(DB::table('hcts_submissions')->distinct()->pluck('tahun_emisi'))
            ->unique()->sortDesc()->values();

        return compact('inventory', 'cardStats', 'totalInventory', 'availableTA', 'availableTE', 'ta', 'te');
    }

    public function getBatchDetail(string $pecahan, int $ta, int $te): array
    {
        $receipts = HctsReceiving::where('pecahan', $pecahan)->where('tahun_anggaran', $ta)->where('emisi', $te)
            ->select('batch', DB::raw('SUM(jumlah) as total_received'))->groupBy('batch')->get();

        $submissions = DB::table('hcts_submission_batches')
            ->join('hcts_submissions', 'hcts_submission_batches.hcts_submission_id', '=', 'hcts_submissions.id')
            ->where('hcts_submissions.pecahan', $pecahan)->where('hcts_submissions.tahun_anggaran', $ta)->where('hcts_submissions.tahun_emisi', $te)
            ->select('hcts_submission_batches.batch', DB::raw('SUM(hcts_submission_batches.jumlah) as total_submitted'))->groupBy('hcts_submission_batches.batch')->get();

        return $receipts->map(function ($r) use ($submissions) {
            $s = $submissions->where('batch', $r->batch)->first();
            $totalSub = $s ? $s->total_submitted : 0;
            return ['batch' => $r->batch, 'total_received' => (int)$r->total_received, 'total_submitted' => (int)$totalSub, 'stock' => (int)$r->total_received - $totalSub];
        })->values()->toArray();
    }
}
