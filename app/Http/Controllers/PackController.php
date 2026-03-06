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
        ]);

        $packs = Pack::where('batch', $request->batch)
            ->where('seri', $request->seri)
            ->select('pack_number', 'supplier')
            ->get();

        return response()->json($packs);
    }
}
