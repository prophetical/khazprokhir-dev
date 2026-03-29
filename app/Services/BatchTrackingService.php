<?php

namespace App\Services;

use App\Models\HcsReceiving;
use App\Models\Pack;
use Illuminate\Pagination\LengthAwarePaginator;

class BatchTrackingService
{
    public function getTrackingData(array $filters): array
    {
        $search = $filters['search'] ?? null;
        $start = $filters['start_date'] ?? null;
        $end = $filters['end_date'] ?? null;
        $pecahan = $filters['pecahan'] ?? null;
        $page = $filters['page'] ?? 1;

        $query = HcsReceiving::select('batch', 'seri', 'pecahan')->distinct();
        if ($search) $query->where(fn($q) => $q->where('batch', 'like', "%$search%")->orWhere('seri', 'like', "%$search%"));
        if ($start) $query->whereDate('tanggal_penerimaan', '>=', $start);
        if ($end) $query->whereDate('tanggal_penerimaan', '<=', $end);
        if ($pecahan) $query->where('pecahan', $pecahan);

        $perPage = 10;
        $all = $query->orderBy('batch')->orderBy('seri')->get();
        $combos = $all->forPage($page, $perPage);

        $trackingData = [];
        foreach ($combos as $combo) {
            $packQuery = Pack::with('hcsReceiving')->where('batch', $combo->batch)->where('seri', $combo->seri);
            if ($start || $end || $pecahan) {
                $packQuery->whereHas('hcsReceiving', function($q) use ($start, $end, $pecahan) {
                    if ($start) $q->whereDate('tanggal_penerimaan', '>=', $start);
                    if ($end) $q->whereDate('tanggal_penerimaan', '<=', $end);
                    if ($pecahan) $q->where('pecahan', $pecahan);
                });
            }
            $packs = $packQuery->orderBy('pack_number')->get();

            $totalQuery = HcsReceiving::where('batch', $combo->batch)->where('seri', $combo->seri);
            if ($start) $totalQuery->whereDate('tanggal_penerimaan', '>=', $start);
            if ($end) $totalQuery->whereDate('tanggal_penerimaan', '<=', $end);
            $totalJumlah = $totalQuery->sum('jumlah');

            $groups = [];
            foreach ($packs as $p) {
                $pcc = $p->hcsReceiving->pecahan ?? 'S';
                $groups[$pcc][$p->pack_number] = [
                    'supplier' => $p->supplier, 'date' => $p->created_at->format('Y-m-d H:i:s'), 'sorted' => (bool)$p->hcs_sorting_id
                ];
            }
            ksort($groups);

            $trackingData[] = [
                'batch' => $combo->batch, 'seri' => $combo->seri, 'packs' => $packs, 'total_packs' => $packs->count(),
                'total_jumlah' => $totalJumlah, 'cutpack' => $packs->where('supplier', 'Cutpack')->count(),
                'rikyet' => $packs->where('supplier', 'Rikyet')->count(), 'pecahan_groups' => $groups,
            ];
        }

        $paginator = new LengthAwarePaginator($combos, $all->count(), $perPage, $page, ['path' => request()->url(), 'query' => $filters]);

        return compact('trackingData', 'paginator');
    }
}
