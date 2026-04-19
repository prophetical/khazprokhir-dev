<?php

namespace App\Http\Controllers;

use App\Models\XPenggantiSeri;
use App\Models\XPenggantiCutpackPack;
use App\Models\XPenggantiCutpackDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XPenggantiCutpackController extends Controller
{
    /**
     * Halaman index: tabel pilih master seri
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
        if ($request->filled('pecahan'))        $query->where('pecahan',        $request->pecahan);
        if ($request->filled('tahun_anggaran')) $query->where('tahun_anggaran', $request->tahun_anggaran);
        if ($request->filled('tahun_emisi'))    $query->where('tahun_emisi',    $request->tahun_emisi);

        $masters           = $query->simplePaginate(20)->withQueryString();
        $tahunAnggaranList = XPenggantiSeri::distinct()->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran');
        $tahunEmisiList    = XPenggantiSeri::distinct()->orderByDesc('tahun_emisi')->pluck('tahun_emisi');
        $pecahanOptions    = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        return view('x-pengganti.cutpack.index', [
            'masters'           => $masters,
            'search'            => $request->input('search', ''),
            'filterPecahan'     => $request->input('pecahan', ''),
            'filterTA'          => $request->input('tahun_anggaran', ''),
            'filterTE'          => $request->input('tahun_emisi', ''),
            'pecahanOptions'    => $pecahanOptions,
            'tahunAnggaranList' => $tahunAnggaranList,
            'tahunEmisiList'    => $tahunEmisiList,
        ]);
    }

    /**
     * Halaman input form 100-pack — dibuka di tab baru.
     */
    public function inputForm(Request $request)
    {
        $seriId = $request->input('seri_id');
        $seri   = XPenggantiSeri::findOrFail($seriId);

        $packs = XPenggantiCutpackPack::where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        // Bangun grid flat (1 baris per pack, tanpa slot)
        $gridData = [];
        for ($p = 1; $p <= 100; $p++) {
            $pack         = $packs->get($p);
            $gridData[$p] = [
                'seri_pengganti'         => $pack?->seri_pengganti         ?? '',
                // Read-only: diisi otomatis MappingAggregatorService
                'total_rusak_seri_1'     => $pack?->total_rusak_seri_1     ?? 0,
                'total_rusak_seri_2'     => $pack?->total_rusak_seri_2     ?? 0,
                'total_rusak_campuran'   => $pack?->total_rusak_campuran   ?? 0,
                // Dapat diisi manual
                'nomor_pack_pengganti'   => $pack?->nomor_pack_pengganti   ?? '',
                'nomor_bilyet_pengganti' => $pack?->nomor_bilyet_pengganti ?? '',
            ];
        }

        return view('x-pengganti.cutpack.input', [
            'seri'     => $seri,
            'gridData' => $gridData,
        ]);
    }

    /**
     * Simpan seri_pengganti, nomor_pack_pengganti, nomor_bilyet_pengganti per pack.
     * Kolom total_rusak_* diisi otomatis oleh MappingAggregatorService.
     */
    public function store(Request $request)
    {
        // Handle JSON-encoded packs data to bypass PHP max_input_vars limit
        if ($request->filled('packs_json')) {
            $jsonData = json_decode($request->packs_json, true);
            if (is_array($jsonData)) {
                $request->merge(['packs' => $jsonData]);
            }
        }

        $validated = $request->validate([
            'x_pengganti_seri_id'                    => 'required|exists:x_pengganti_seris,id',
            'packs'                                  => 'nullable|array',
            'packs.*.nomor_pack'                     => 'required|integer|min:1|max:100',
            'packs.*.seri_pengganti'                 => ['nullable', 'string', 'regex:/^[A-Z]{2}-[A-Z]{2}[0-9]$/'],
            'packs.*.nomor_pack_pengganti'           => 'nullable|integer|min:0',
            'packs.*.nomor_bilyet_pengganti'         => 'nullable|integer|min:0',
        ], [
            'packs.*.seri_pengganti.regex' => 'Format Seri Pengganti harus XX-XX9 (contoh: AB-BB1).',
        ]);

        DB::transaction(function () use ($validated) {
            $seriId = $validated['x_pengganti_seri_id'];
            $now    = now();

            $packValues = [];
            foreach ($validated['packs'] ?? [] as $packData) {
                $packValues[] = [
                    'x_pengganti_seri_id'    => $seriId,
                    'nomor_pack'             => $packData['nomor_pack'],
                    'seri_pengganti'         => ($packData['seri_pengganti']         ?? '') ?: null,
                    'nomor_pack_pengganti'   => ($packData['nomor_pack_pengganti']   ?? '') !== '' ? $packData['nomor_pack_pengganti']   : null,
                    'nomor_bilyet_pengganti' => ($packData['nomor_bilyet_pengganti'] ?? '') !== '' ? $packData['nomor_bilyet_pengganti'] : null,
                    // total_rusak_* TIDAK disentuh — diurus MappingAggregatorService
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($packValues)) {
                XPenggantiCutpackPack::upsert(
                    $packValues,
                    ['x_pengganti_seri_id', 'nomor_pack'],
                    ['seri_pengganti', 'nomor_pack_pengganti', 'nomor_bilyet_pengganti', 'updated_at']
                    // total_rusak_* sengaja TIDAK ada di update columns
                );
            }
        });

        $seri = \App\Models\XPenggantiSeri::find($validated['x_pengganti_seri_id']);
        $msg  = "Data Cutpack Seri {$seri->seri} Batch {$seri->batch} berhasil disimpan.";

        return redirect()
            ->route('x-pengganti.cutpack.input', [
                'seri_id' => $validated['x_pengganti_seri_id'],
                'saved'   => 1
            ])
            ->with('x_success', $msg);
    }

    private function buildEmptyGrid(): array
    {
        $grid = [];
        for ($p = 1; $p <= 100; $p++) {
            $grid[$p] = [
                'seri_pengganti'         => '',
                'total_rusak_seri_1'     => 0,
                'total_rusak_seri_2'     => 0,
                'total_rusak_campuran'   => 0,
                'nomor_pack_pengganti'   => '',
                'nomor_bilyet_pengganti' => '',
            ];
        }
        return $grid;
    }

    /**
     * Ekspor PDF Cutpack Grid — flat (tanpa slot)
     */
    public function exportPdf(Request $request)
    {
        $seriId = $request->input('seri_id');
        $seri   = XPenggantiSeri::findOrFail($seriId);

        $packs = XPenggantiCutpackPack::where('x_pengganti_seri_id', $seriId)
            ->orderBy('nomor_pack')
            ->get();

        // Build gridData flat untuk view PDF
        $gridData = $this->buildEmptyGrid();
        foreach ($packs as $pack) {
            $p = $pack->nomor_pack;
            $gridData[$p] = [
                'seri_pengganti'         => $pack->seri_pengganti         ?? '',
                'total_rusak_seri_1'     => $pack->total_rusak_seri_1     ?? 0,
                'total_rusak_seri_2'     => $pack->total_rusak_seri_2     ?? 0,
                'total_rusak_campuran'   => $pack->total_rusak_campuran   ?? 0,
                'nomor_pack_pengganti'   => $pack->nomor_pack_pengganti   ?? '',
                'nomor_bilyet_pengganti' => $pack->nomor_bilyet_pengganti ?? '',
            ];
        }

        return view('x-pengganti.cutpack.pdfxpgtcutpack', [
            'seri'       => $seri,
            'gridData'   => $gridData,
            'exportDate' => \Carbon\Carbon::now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB',
        ]);
    }
}
