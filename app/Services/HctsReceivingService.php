<?php

namespace App\Services;

use App\Models\HcsReceiving;
use App\Models\HctsReceiving;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HctsReceivingService
{
    public function getSummaryQuery(array $filters)
    {
        $hcsSub = DB::table('hcs_receivings')->select('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran', 'jumlah', 'tanggal_penerimaan', DB::raw("'HCS' as type"));
        $hctsSub = DB::table('hcts_receivings')->select('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran', 'jumlah', 'tanggal_penerimaan', DB::raw("'HCTS' as type"));

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $hcsSub->whereBetween('tanggal_penerimaan', [$filters['start_date'], $filters['end_date']]);
            $hctsSub->whereBetween('tanggal_penerimaan', [$filters['start_date'], $filters['end_date']]);
        }

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $hcsSub->where(function ($q) use ($s) { $q->where('batch', 'like', "%{$s}%")->orWhere('seri', 'like', "%{$s}%"); });
            $hctsSub->where(function ($q) use ($s) { $q->where('batch', 'like', "%{$s}%")->orWhere('seri', 'like', "%{$s}%"); });
        }

        return DB::table($hcsSub->unionAll($hctsSub), 'combined')
            ->select('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran',
                DB::raw("SUM(CASE WHEN type = 'HCS' THEN jumlah ELSE 0 END) as total_hcs"),
                DB::raw("SUM(CASE WHEN type = 'HCTS' THEN jumlah ELSE 0 END) as total_hcts")
            )->groupBy('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran')
            ->orderBy('tahun_anggaran', 'desc')->orderBy('pecahan', 'asc');
    }

    public function calculateHcsTotal(array $p)
    {
        return HcsReceiving::where('pecahan', $p['pecahan'])->where('batch', $p['batch'])->where('seri', $p['seri'])->where('emisi', $p['emisi'])->where('tahun_anggaran', $p['tahun_anggaran'])->sum('jumlah');
    }

    public function validateHctsLimit(array $data, ?int $excludeId = null)
    {
        $hcsTotal = $this->calculateHcsTotal($data);
        $q = HctsReceiving::where('pecahan', $data['pecahan'])->where('batch', $data['batch'])->where('seri', $data['seri'])->where('emisi', $data['emisi'])->where('tahun_anggaran', $data['tahun_anggaran']);
        if ($excludeId) $q->where('id', '!=', $excludeId);
        $hctsTotal = $q->sum('jumlah');

        if (($hcsTotal + $hctsTotal + $data['jumlah']) > 4500000) {
            $rem = 4500000 - ($hcsTotal + $hctsTotal);
            throw ValidationException::withMessages(['jumlah' => "Total HCS ($hcsTotal) + HCTS ($hctsTotal) melebihi 4.500.000. Sisa: ".number_format($rem, 0, ',', '.')]);
        }
    }

    public function processStore(array $data, int $userId)
    {
        $this->validateHctsLimit($data);
        $data['created_by'] = $userId;
        return HctsReceiving::create($data);
    }

    public function processUpdate(HctsReceiving $model, array $data)
    {
        $this->validateHctsLimit($data, $model->id);
        $model->update($data);
        return $model;
    }

    public function getFilterOptions(): array
    {
        return [
            'years' => HctsReceiving::distinct()->pluck('tahun_anggaran')->sortDesc(),
            'emissions' => HctsReceiving::distinct()->pluck('emisi')->sortDesc(),
        ];
    }
}
