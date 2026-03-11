<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
{
    public function used(Request $request)
    {
        $request->validate([
            'batch' => 'required|string|size:7',
            'seri' => 'required|string',
            'exclude_hcs_id' => 'nullable|integer',
        ]);

        $query = Pack::where('batch', $request->batch)
            ->where('seri', $request->seri);

        if ($request->filled('pecahan')) {
            $query->whereHas('hcsReceiving', function($q) use ($request) {
                $q->where('pecahan', $request->pecahan);
            });
        }

        if ($request->filled('emisi')) {
            $query->whereHas('hcsReceiving', function($q) use ($request) {
                $q->where('emisi', $request->emisi);
            });
        }

        if ($request->filled('tahun_anggaran')) {
            $query->whereHas('hcsReceiving', function($q) use ($request) {
                $q->where('tahun_anggaran', $request->tahun_anggaran);
            });
        }

        if ($request->filled('exclude_hcs_id')) {
            $query->where('hcs_receiving_id', '!=', $request->exclude_hcs_id);
        }

        $packs = $query->with('hcsReceiving:id,nomor_bon')
            ->select('pack_number', 'supplier', 'hcs_sorting_id', 'hcs_receiving_id')
            ->get();

        return response()->json($packs);
    }
}
