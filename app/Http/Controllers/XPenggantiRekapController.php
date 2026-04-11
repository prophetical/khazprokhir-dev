<?php

namespace App\Http\Controllers;

use App\Models\XPenggantiSeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XPenggantiRekapController extends Controller
{
    /**
     * Halaman index: tabel pilih master seri untuk direkap.
     */
    public function index(Request $request)
    {
        $query = XPenggantiSeri::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('batch', 'like', "%{$s}%")
                  ->orWhere('seri',  'like', "%{$s}%");
            });
        }
        if ($request->filled('pecahan'))        $query->where('pecahan', $request->pecahan);
        if ($request->filled('tahun_anggaran')) $query->where('tahun_anggaran', $request->tahun_anggaran);
        if ($request->filled('tahun_emisi'))    $query->where('tahun_emisi', $request->tahun_emisi);

        $masters           = $query->simplePaginate(20)->withQueryString();
        $tahunAnggaranList = XPenggantiSeri::distinct()->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran');
        $tahunEmisiList    = XPenggantiSeri::distinct()->orderByDesc('tahun_emisi')->pluck('tahun_emisi');
        $pecahanOptions    = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        return view('x-pengganti.rekap.index', [
            'masters'           => $masters,
            'search'            => $request->input('search', ''),
            'filterPecahan'     => $request->input('pecahan', ''),
            'filterTA'          => $request->input('tahun_anggaran', ''),
            'filterTE'          => $request->input('tahun_emisi', ''),
            'pecahanOptions'    => $pecahanOptions,
            'tahunAnggaranList' => $tahunAnggaranList,
            'tahunEmisiList'    => $tahunEmisiList,
            'title'             => 'Hasil Rekap Khazprokhir',
            'fullScreen'        => false
        ]);
    }

    /**
     * Dashboard rekapan 100 pack
     */
    public function show(Request $request)
    {
        $seriId = $request->query('seri_id');
        $page   = (int) $request->query('page', 1);
        
        if (!$seriId) {
            return redirect()->route('x-pengganti.rekap.index')->with('error', 'Silakan pilih data seri terlebih dahulu.');
        }

        $data = $this->getRekapData($seriId);

        return view('x-pengganti.rekap.show', array_merge($data, [
            'currentPage' => $page,
            'title'       => 'Hasil Rekap Khazprokhir',
            'fullScreen'  => false
        ]));
    }

    /**
     * Halaman Print untuk Hasil Rekap (Mirip reports/print)
     */
    public function print(Request $request)
    {
        $seriId = $request->query('seri_id');
        if (!$seriId) abort(404);

        $data = $this->getRekapData($seriId);

        return view('x-pengganti.rekap.print', $data);
    }

    /**
     * Logika Agregasi Data Gabungan (Khazai, Cutpack, Rikyet)
     */
    private function getRekapData($seriId)
    {
        $seri = XPenggantiSeri::findOrFail($seriId);

        // 1. Data Khazai: Agregasi per Pack (KV = Jumlah Rusak Vell)
        $khazaiData = DB::table('x_pengganti_packs')
            ->leftJoin('x_pengganti_details', 'x_pengganti_packs.id', '=', 'x_pengganti_details.x_pengganti_pack_id')
            ->select('nomor_pack', DB::raw('SUM(jumlah_rusak_vell) as vell_total'))
            ->where('x_pengganti_seri_id', $seriId)
            ->groupBy('nomor_pack')
            ->get()
            ->keyBy('nomor_pack');

        // 2. Data Cutpack
        $cutpackData = DB::table('x_pengganti_cutpack_packs')
            ->where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        // 3. Data Rikyet
        $rikyetData = DB::table('x_pengganti_rikyet_packs')
            ->where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        $grid = [];
        $groupTotals = [];

        // Loop melintasi 100 Pack
        for ($p = 1; $p <= 100; $p++) {
            $kv = $khazaiData->get($p)->vell_total ?? 0;
            
            $c1 = $cutpackData->get($p)->total_rusak_seri_1 ?? 0;
            $c2 = $cutpackData->get($p)->total_rusak_seri_2 ?? 0;
            $cc = $cutpackData->get($p)->total_rusak_campuran ?? 0;
            
            $r1 = $rikyetData->get($p)->total_rusak_seri_1 ?? 0;
            $r2 = $rikyetData->get($p)->total_rusak_seri_2 ?? 0;
            $rc = $rikyetData->get($p)->total_rusak_campuran ?? 0;
            
            $resS1   = ($kv * 20) + $c1 + ($r1 * 1000);
            $resS2   = ($kv * 20) + $c2 + ($r2 * 1000);
            $resCamp = ($kv * 5)  + $cc + ($rc * 1000);

            $grid[$p] = [
                'nomor_pack' => $p,
                's1'         => $resS1,
                's2'         => $resS2,
                'camp'       => $resCamp,
            ];
        }

        // Kalkulasi Group Totals (Jumlah Campuran) per kelompok 4 Pack
        for ($g = 0; $g < 25; $g++) {
            $sum = 0;
            for ($i = 0; $i < 4; $i++) {
                $pIndex = ($g * 4) + 1 + $i;
                $sum += $grid[$pIndex]['camp'];
            }
            $groupTotals[$g] = $sum;
        }

        return [
            'seri'        => $seri,
            'grid'        => $grid,
            'groupTotals' => $groupTotals,
        ];
    }
}
