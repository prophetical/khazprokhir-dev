<?php

namespace App\Http\Controllers;

use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class RekomendasiPenyortiranController extends Controller
{
    protected $service;

    public function __construct(RekomendasiService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $availableYears = DB::table('hcs_receivings')->distinct()->whereNotNull('tahun_anggaran')->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');
        $availableEmissions = DB::table('hcs_receivings')->distinct()->whereNotNull('emisi')->orderBy('emisi', 'desc')->pluck('emisi');

        $recommendations = $this->service->getPenyortiranRecommendations($request->all());

        // Summaries
        $summaries = collect(['S', 'T', 'U', 'V', 'W', 'X', 'Y'])->mapWithKeys(fn($p) => [$p => ['total_pack' => 0, 'total_bilyet' => 0]])->toArray();
        $totalAllPacks = 0; $totalAllBilyet = 0;

        foreach ($recommendations as $r) {
            if (isset($summaries[$r['pecahan']])) {
                $summaries[$r['pecahan']]['total_pack'] += $r['total_pack'];
                $summaries[$r['pecahan']]['total_bilyet'] += $r['total_bilyet'];
            }
            $totalAllPacks += $r['total_pack'];
            $totalAllBilyet += $r['total_bilyet'];
        }

        $page = $request->get('page', 1);
        $perPage = 20;
        $paginator = new Paginator(
            array_slice($recommendations, ($page - 1) * $perPage, $perPage),
            $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('rekomendasi-penyortiran.index', compact('paginator', 'summaries', 'totalAllPacks', 'totalAllBilyet', 'availableYears', 'availableEmissions'));
    }
}
