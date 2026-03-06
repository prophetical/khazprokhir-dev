<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pack;
use App\Models\HcsReceiving;
use Illuminate\Pagination\LengthAwarePaginator;

class BatchTrackingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Distinct (batch, seri) combos with optional filtering
        $query = HcsReceiving::select('batch', 'seri')->distinct();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%");
            });
        }
        if ($startDate) {
            $query->whereDate('tanggal_penerimaan', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal_penerimaan', '<=', $endDate);
        }

        // Paginate 10 combos per page
        $perPage = 10;
        $page = $request->input('page', 1);
        $allCombos = $query->orderBy('batch')->orderBy('seri')->get();
        $total = $allCombos->count();
        $combos = $allCombos->forPage($page, $perPage);

        // Build paginator (preserves query string for filter + page links)
        $paginator = new LengthAwarePaginator(
            $combos, $total, $perPage, $page,
        ['path' => $request->url(), 'query' => $request->query()]
            );

        // Build detailed data only for this page
        $trackingData = [];

        foreach ($combos as $combo) {
            $batch = $combo->batch;
            $seri = $combo->seri;

            $packQuery = Pack::with('hcsReceiving')
                ->where('batch', $batch)
                ->where('seri', $seri);

            if ($startDate || $endDate) {
                $packQuery->whereHas('hcsReceiving', function ($q) use ($startDate, $endDate) {
                    if ($startDate)
                        $q->whereDate('tanggal_penerimaan', '>=', $startDate);
                    if ($endDate)
                        $q->whereDate('tanggal_penerimaan', '<=', $endDate);
                });
            }

            $packs = $packQuery->orderBy('pack_number')
                ->get(['pack_number', 'supplier', 'created_at', 'hcs_receiving_id']);

            $totalQuery = HcsReceiving::where('batch', $batch)->where('seri', $seri);
            if ($startDate)
                $totalQuery->whereDate('tanggal_penerimaan', '>=', $startDate);
            if ($endDate)
                $totalQuery->whereDate('tanggal_penerimaan', '<=', $endDate);
            $totalJumlah = $totalQuery->sum('jumlah');

            $cutpackCount = $packs->where('supplier', 'Cutpack')->count();
            $riktyetCount = $packs->where('supplier', 'Rikyet')->count();

            $pecahanGroups = [];
            foreach ($packs as $pack) {
                $pecahan = $pack->hcsReceiving->pecahan ?? '?';
                $pecahanGroups[$pecahan][$pack->pack_number] = [
                    'supplier' => $pack->supplier,
                    'date' => $pack->created_at,
                ];
            }
            ksort($pecahanGroups);

            $trackingData[] = [
                'batch' => $batch,
                'seri' => $seri,
                'packs' => $packs,
                'total_packs' => $packs->count(),
                'total_jumlah' => $totalJumlah,
                'cutpack' => $cutpackCount,
                'rikyet' => $riktyetCount,
                'pecahan_groups' => $pecahanGroups,
            ];
        }

        return view('batch-tracking.index', compact(
            'trackingData', 'paginator', 'search', 'startDate', 'endDate'
        ));
    }
}
