<?php

namespace App\Http\Controllers;

use App\Models\HcsSorting;
use Illuminate\Http\Request;

class HcsSortingReportController extends Controller
{
    public function index(Request $request)
    {
        $query = HcsSorting::query();

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_sortir', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_sortir', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%' . $request->batch . '%');
        }
        if ($request->filled('seri')) {
            $query->where('seri', 'like', '%' . $request->seri . '%');
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('hcs-sorting-report.index', compact('reports'));
    }
}
