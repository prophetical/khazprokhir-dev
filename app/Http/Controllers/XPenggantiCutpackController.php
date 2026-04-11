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

        $gridData = $this->buildEmptyGrid();

        $packs = XPenggantiCutpackPack::with('details')
            ->where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        for ($p = 1; $p <= 100; $p++) {
            $pack = $packs->get($p);
            
            $gridData[$p]['seri_pengganti'] = $pack?->seri_pengganti ?? '';
            $gridData[$p]['total_rusak_seri_1'] = $pack?->total_rusak_seri_1 ?? '';
            $gridData[$p]['total_rusak_seri_2'] = $pack?->total_rusak_seri_2 ?? '';
            $gridData[$p]['total_rusak_campuran'] = $pack?->total_rusak_campuran ?? '';

            for ($s = 1; $s <= 4; $s++) {
                $detail = $pack?->details->firstWhere('slot', $s);
                $gridData[$p]['slots'][$s] = [
                    'rusak_seri_1'           => $detail?->rusak_seri_1           ?? '',
                    'rusak_seri_2'           => $detail?->rusak_seri_2           ?? '',
                    'rusak_campuran'         => $detail?->rusak_campuran         ?? '',
                    'nomor_pack_pengganti'   => $detail?->nomor_pack_pengganti   ?? '',
                    'nomor_bilyet_pengganti' => $detail?->nomor_bilyet_pengganti ?? '',
                ];
            }
        }

        return view('x-pengganti.cutpack.input', [
            'seri'     => $seri,
            'gridData' => $gridData,
        ]);
    }

    /**
     * Simpan / update data Cutpack (partial upsert).
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
            'packs.*.total_rusak_seri_1'             => 'nullable|integer|min:0',
            'packs.*.total_rusak_seri_2'             => 'nullable|integer|min:0',
            'packs.*.total_rusak_campuran'           => 'nullable|integer|min:0',
            'packs.*.slots'                          => 'nullable|array',
            'packs.*.slots.*.slot'                   => 'required|integer|min:1|max:4',
            'packs.*.slots.*.rusak_seri_1'           => 'nullable|integer|min:0',
            'packs.*.slots.*.rusak_seri_2'           => 'nullable|integer|min:0',
            'packs.*.slots.*.rusak_campuran'         => 'nullable|integer|min:0',
            'packs.*.slots.*.nomor_pack_pengganti'   => 'nullable|integer|min:0',
            'packs.*.slots.*.nomor_bilyet_pengganti' => 'nullable|integer|min:0',
        ], [
            'packs.*.seri_pengganti.regex' => 'Format Seri Pengganti harus XX-XX9 (contoh: AB-BB1). Maksimal 6 karakter.',
        ]);

        DB::transaction(function () use ($validated) {
            $seriId     = $validated['x_pengganti_seri_id'];
            $packValues = [];
            $now        = now();

            foreach ($validated['packs'] ?? [] as $packData) {
                $packValues[] = [
                    'x_pengganti_seri_id'  => $seriId,
                    'nomor_pack'           => $packData['nomor_pack'],
                    'seri_pengganti'       => ($packData['seri_pengganti'] ?? '') ?: null,
                    'total_rusak_seri_1'   => ($packData['total_rusak_seri_1']   ?? '') !== '' ? $packData['total_rusak_seri_1']   : null,
                    'total_rusak_seri_2'   => ($packData['total_rusak_seri_2']   ?? '') !== '' ? $packData['total_rusak_seri_2']   : null,
                    'total_rusak_campuran' => ($packData['total_rusak_campuran'] ?? '') !== '' ? $packData['total_rusak_campuran'] : null,
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ];
            }

            if (!empty($packValues)) {
                XPenggantiCutpackPack::upsert($packValues, ['x_pengganti_seri_id', 'nomor_pack'], [
                    'seri_pengganti',
                    'total_rusak_seri_1',
                    'total_rusak_seri_2',
                    'total_rusak_campuran',
                    'updated_at'
                ]);
            }

            // Get mapping nomor_pack -> id
            $packsMap = XPenggantiCutpackPack::where('x_pengganti_seri_id', $seriId)->pluck('id', 'nomor_pack');

            $detailValues = [];
            foreach ($validated['packs'] ?? [] as $packData) {
                $packId = $packsMap[$packData['nomor_pack']] ?? null;
                if (!$packId) continue;

                foreach ($packData['slots'] ?? [] as $slotData) {
                    $detailValues[] = [
                        'x_pengganti_cutpack_pack_id' => $packId,
                        'slot'                        => $slotData['slot'],
                        'rusak_seri_1'                => ($slotData['rusak_seri_1']           ?? '') !== '' ? $slotData['rusak_seri_1']           : null,
                        'rusak_seri_2'                => ($slotData['rusak_seri_2']           ?? '') !== '' ? $slotData['rusak_seri_2']           : null,
                        'rusak_campuran'              => ($slotData['rusak_campuran']         ?? '') !== '' ? $slotData['rusak_campuran']         : null,
                        'nomor_pack_pengganti'        => ($slotData['nomor_pack_pengganti']   ?? '') !== '' ? $slotData['nomor_pack_pengganti']   : null,
                        'nomor_bilyet_pengganti'      => ($slotData['nomor_bilyet_pengganti'] ?? '') !== '' ? $slotData['nomor_bilyet_pengganti'] : null,
                        'created_at'                  => $now,
                        'updated_at'                  => $now,
                    ];
                }
            }

            if (!empty($detailValues)) {
                XPenggantiCutpackDetail::upsert($detailValues, ['x_pengganti_cutpack_pack_id', 'slot'], [
                    'rusak_seri_1',
                    'rusak_seri_2',
                    'rusak_campuran',
                    'nomor_pack_pengganti',
                    'nomor_bilyet_pengganti',
                    'updated_at'
                ]);
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
                'seri_pengganti'       => '',
                'total_rusak_seri_1'   => '',
                'total_rusak_seri_2'   => '',
                'total_rusak_campuran' => '',
                'slots' => [
                    1 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
                    2 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
                    3 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
                    4 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
                ],
            ];
        }
        return $grid;
    }

    /**
     * Ekspor PDF Cutpack Grid (Poin E)
     */
    public function exportPdf(Request $request)
    {
        $seriId = $request->input('seri_id');
        $seri   = XPenggantiSeri::findOrFail($seriId);

        $gridData = $this->buildEmptyGrid();

        $packs = XPenggantiCutpackPack::with('details')
            ->where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        for ($p = 1; $p <= 100; $p++) {
            $pack = $packs->get($p);
            
            $gridData[$p]['seri_pengganti'] = $pack?->seri_pengganti ?? '';
            $gridData[$p]['total_rusak_seri_1'] = $pack?->total_rusak_seri_1 ?? '';
            $gridData[$p]['total_rusak_seri_2'] = $pack?->total_rusak_seri_2 ?? '';
            $gridData[$p]['total_rusak_campuran'] = $pack?->total_rusak_campuran ?? '';

            for ($s = 1; $s <= 4; $s++) {
                $detail = $pack?->details->firstWhere('slot', $s);
                $gridData[$p]['slots'][$s] = [
                    'rusak_seri_1'           => $detail?->rusak_seri_1           ?? '',
                    'rusak_seri_2'           => $detail?->rusak_seri_2           ?? '',
                    'rusak_campuran'         => $detail?->rusak_campuran         ?? '',
                    'nomor_pack_pengganti'   => $detail?->nomor_pack_pengganti   ?? '',
                    'nomor_bilyet_pengganti' => $detail?->nomor_bilyet_pengganti ?? '',
                ];
            }
        }

        return view('x-pengganti.cutpack.pdfxpgtcutpack', [
            'seri'     => $seri,
            'gridData' => $gridData,
            'exportDate' => \Carbon\Carbon::now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB',
        ]);
    }
}
