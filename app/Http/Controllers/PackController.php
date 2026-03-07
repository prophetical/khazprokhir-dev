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

        if ($request->filled('exclude_hcs_id')) {
            $query->where('hcs_receiving_id', '!=', $request->exclude_hcs_id);
        }

        $packs = $query->select('pack_number', 'supplier', 'hcs_sorting_id')->get();

        return response()->json($packs);
    }
}
