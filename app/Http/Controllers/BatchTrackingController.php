<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pack;
use App\Models\HcsReceiving;

class BatchTrackingController extends Controller
{
    public function index(Request $request)
    {
        $batch = $request->input('batch');
        $seri = $request->input('seri');

        $packs = collect();
        $history = collect();
        $supplierDistribution = [];

        if ($batch && $seri) {
            $packs = Pack::with('hcsReceiving', 'user')
                ->where('batch', $batch)
                ->where('seri', $seri)
                ->orderBy('pack_number')
                ->get();

            $history = HcsReceiving::with('user')
                ->where('batch', $batch)
                ->where('seri', $seri)
                ->orderBy('created_at', 'desc')
                ->get();

            $sups = $packs->pluck('supplier')->countBy();
            $supplierDistribution = [
                'Cutpack' => $sups->get('Cutpack', 0),
                'Rikyet' => $sups->get('Rikyet', 0),
            ];
        }

        return view('batch-tracking.index', compact(
            'packs', 'history', 'supplierDistribution', 'batch', 'seri'
        ));
    }
}
