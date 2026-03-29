<?php

namespace App\Services;

use App\Models\HctsReceiving;
use App\Models\HctsSubmission;
use App\Models\HctsSubmissionBatch;
use Illuminate\Support\Facades\DB;
use Exception;

class HctsSubmissionService
{
    public function getAvailableOptions(): array
    {
        $ta = DB::table('hcts_receivings')->distinct()->pluck('tahun_anggaran')
            ->merge(DB::table('hcts_submissions')->distinct()->pluck('tahun_anggaran'))
            ->unique()->sortDesc()->values();

        $te = DB::table('hcts_receivings')->distinct()->pluck('emisi')
            ->merge(DB::table('hcts_submissions')->distinct()->pluck('tahun_emisi'))
            ->unique()->sortDesc()->values();

        return ['ta' => $ta, 'te' => $te];
    }

    public function getAvailableBatches(string $pecahan, int $ta, int $te, ?int $excludeId = null)
    {
        $receipts = HctsReceiving::where('pecahan', $pecahan)->where('tahun_anggaran', $ta)->where('emisi', $te)
            ->select('batch', DB::raw('SUM(jumlah) as total_received'))->groupBy('batch')->get();

        $submissionsQuery = DB::table('hcts_submission_batches')
            ->join('hcts_submissions', 'hcts_submission_batches.hcts_submission_id', '=', 'hcts_submissions.id')
            ->where('hcts_submissions.pecahan', $pecahan)->where('hcts_submissions.tahun_anggaran', $ta)
            ->where('hcts_submissions.tahun_emisi', $te);

        if ($excludeId) $submissionsQuery->where('hcts_submissions.id', '!=', $excludeId);

        $submissions = $submissionsQuery->select('hcts_submission_batches.batch', DB::raw('SUM(hcts_submission_batches.jumlah) as total_submitted'))
            ->groupBy('hcts_submission_batches.batch')->get();

        return $receipts->map(function ($r) use ($submissions) {
            $sub = $submissions->where('batch', $r->batch)->first();
            $totalSub = $sub ? $sub->total_submitted : 0;
            $stock = $r->total_received - $totalSub;
            return ['batch' => $r->batch, 'total_received' => (int)$r->total_received, 'total_submitted' => (int)$totalSub, 'stock' => (int)$stock];
        })->filter(fn($i) => $i['stock'] > 0)->values();
    }

    public function processStore(array $data, int $userId)
    {
        $this->validateBatchTotal($data);

        return DB::transaction(function() use ($data, $userId) {
            $submission = HctsSubmission::create([
                'tanggal_penyerahan' => $data['tanggal_penyerahan'], 'pecahan' => $data['pecahan'],
                'tahun_anggaran' => $data['tahun_anggaran'], 'tahun_emisi' => $data['tahun_emisi'],
                'jumlah_bilyet' => $data['jumlah_bilyet'], 'pemasok1' => strtoupper($data['pemasok1']),
                'pemasok2' => strtoupper($data['pemasok2'] ?? ''), 'nomor_ba' => $data['nomor_ba'],
                'created_by' => $userId,
            ]);

            foreach ($data['batches'] as $b) {
                HctsSubmissionBatch::create(['hcts_submission_id' => $submission->id, 'batch' => $b['batch'], 'jumlah' => $b['jumlah']]);
            }
            return $submission;
        });
    }

    public function processUpdate(HctsSubmission $model, array $data)
    {
        $this->validateBatchTotal($data);

        return DB::transaction(function() use ($model, $data) {
            $model->update([
                'tanggal_penyerahan' => $data['tanggal_penyerahan'], 'pecahan' => $data['pecahan'],
                'tahun_anggaran' => $data['tahun_anggaran'], 'tahun_emisi' => $data['tahun_emisi'],
                'jumlah_bilyet' => $data['jumlah_bilyet'], 'pemasok1' => strtoupper($data['pemasok1']),
                'pemasok2' => strtoupper($data['pemasok2'] ?? ''), 'nomor_ba' => $data['nomor_ba'],
            ]);

            $model->batches()->delete();
            foreach ($data['batches'] as $b) {
                HctsSubmissionBatch::create(['hcts_submission_id' => $model->id, 'batch' => $b['batch'], 'jumlah' => $b['jumlah']]);
            }
            return $model;
        });
    }

    protected function validateBatchTotal(array $data)
    {
        $total = collect($data['batches'])->sum('jumlah');
        if ($total != $data['jumlah_bilyet']) {
            throw new Exception('Total Jumlah Per Batch ('.number_format($total).') tidak sama dengan Jumlah Bilyet ('.number_format($data['jumlah_bilyet']).').');
        }
    }
}
