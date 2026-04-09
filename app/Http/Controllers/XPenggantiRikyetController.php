<?php

namespace App\Http\Controllers;

use App\Models\XPenggantiSeri;
use App\Models\XPenggantiRikyetPack;
use App\Models\XPenggantiRikyetDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class XPenggantiRikyetController extends Controller
{
    /**
     * Halaman index: tabel pilih master seri (max 10 baris, paginasi).
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

        $masters           = $query->paginate(10)->withQueryString();
        $tahunAnggaranList = XPenggantiSeri::distinct()->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran');
        $tahunEmisiList    = XPenggantiSeri::distinct()->orderByDesc('tahun_emisi')->pluck('tahun_emisi');
        $pecahanOptions    = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        return view('x-pengganti.rikyet.index', [
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
     * Halaman input form 100-pack — 1 halaman, 4 kolom.
     */
    public function inputForm(Request $request)
    {
        $seriId = $request->input('seri_id');
        $seri   = XPenggantiSeri::findOrFail($seriId);

        $gridData = $this->buildEmptyGrid();

        $packs = XPenggantiRikyetPack::with('details')
            ->where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        for ($p = 1; $p <= 100; $p++) {
            $pack = $packs->get($p);

            $gridData[$p]['seri_pengganti']       = $pack?->seri_pengganti       ?? '';
            $gridData[$p]['total_rusak_seri_1']   = $pack?->total_rusak_seri_1   ?? '';
            $gridData[$p]['total_rusak_seri_2']   = $pack?->total_rusak_seri_2   ?? '';
            $gridData[$p]['total_rusak_campuran'] = $pack?->total_rusak_campuran ?? '';

            for ($s = 1; $s <= 4; $s++) {
                $detail = $pack?->details->firstWhere('slot', $s);
                $gridData[$p]['slots'][$s] = [
                    'rusak_seri_1'   => $detail?->rusak_seri_1   ?? '',
                    'rusak_seri_2'   => $detail?->rusak_seri_2   ?? '',
                    'rusak_campuran' => $detail?->rusak_campuran ?? '',
                    'seri_pengganti' => $detail?->seri_pengganti ?? '',
                ];
            }
        }

        return view('x-pengganti.rikyet.input', [
            'seri'     => $seri,
            'gridData' => $gridData,
        ]);
    }

    /**
     * Simpan / update data Rikyet (partial upsert — data boleh diisi sebagian).
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
            'x_pengganti_seri_id'                => 'required|exists:x_pengganti_seris,id',
            'packs'                              => 'nullable|array',
            'packs.*.nomor_pack'                 => 'required|integer|min:1|max:100',
            'packs.*.seri_pengganti'             => ['nullable', 'string', 'regex:/^[A-Z]{2}-[A-Z]{2}[0-9]$/'],
            'packs.*.total_rusak_seri_1'         => 'nullable|integer|min:0',
            'packs.*.total_rusak_seri_2'         => 'nullable|integer|min:0',
            'packs.*.total_rusak_campuran'       => 'nullable|integer|min:0',
            'packs.*.slots'                      => 'nullable|array',
            'packs.*.slots.*.slot'               => 'required|integer|min:1|max:4',
            'packs.*.slots.*.rusak_seri_1'       => 'nullable|integer|min:0',
            'packs.*.slots.*.rusak_seri_2'       => 'nullable|integer|min:0',
            'packs.*.slots.*.rusak_campuran'     => 'nullable|integer|min:0',
            'packs.*.slots.*.seri_pengganti'     => ['nullable', 'string', 'regex:/^[A-Z]{2}-[A-Z]{2}[0-9]$/'],
        ], [
            'packs.*.seri_pengganti.regex'       => 'Format Seri Pengganti harus XX-XX9 (contoh: AB-BB1). Maksimal 6 karakter.',
            'packs.*.slots.*.seri_pengganti.regex' => 'Format Seri Pengganti harus XX-XX9 (contoh: AB-BB1). Maksimal 6 karakter.',
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
                XPenggantiRikyetPack::upsert($packValues, ['x_pengganti_seri_id', 'nomor_pack'], [
                    'seri_pengganti',
                    'total_rusak_seri_1',
                    'total_rusak_seri_2',
                    'total_rusak_campuran',
                    'updated_at'
                ]);
            }

            // Get mapping nomor_pack -> id
            $packsMap = XPenggantiRikyetPack::where('x_pengganti_seri_id', $seriId)->pluck('id', 'nomor_pack');

            $detailValues = [];
            foreach ($validated['packs'] ?? [] as $packData) {
                $packId = $packsMap[$packData['nomor_pack']] ?? null;
                if (!$packId) continue;

                foreach ($packData['slots'] ?? [] as $slotData) {
                    $detailValues[] = [
                        'x_pengganti_rikyet_pack_id' => $packId,
                        'slot'                       => $slotData['slot'],
                        'rusak_seri_1'               => ($slotData['rusak_seri_1']   ?? '') !== '' ? $slotData['rusak_seri_1']   : null,
                        'rusak_seri_2'               => ($slotData['rusak_seri_2']   ?? '') !== '' ? $slotData['rusak_seri_2']   : null,
                        'rusak_campuran'             => ($slotData['rusak_campuran'] ?? '') !== '' ? $slotData['rusak_campuran'] : null,
                        'seri_pengganti'             => ($slotData['seri_pengganti'] ?? '') ?: null,
                        'created_at'                 => $now,
                        'updated_at'                 => $now,
                    ];
                }
            }

            if (!empty($detailValues)) {
                XPenggantiRikyetDetail::upsert($detailValues, ['x_pengganti_rikyet_pack_id', 'slot'], [
                    'rusak_seri_1',
                    'rusak_seri_2',
                    'rusak_campuran',
                    'seri_pengganti',
                    'updated_at'
                ]);
            }
        });

        $seri = \App\Models\XPenggantiSeri::find($validated['x_pengganti_seri_id']);
        $msg  = "Data Rikyet Seri {$seri->seri} Batch {$seri->batch} berhasil disimpan.";

        return redirect()
            ->route('x-pengganti.rikyet.input', ['seri_id' => $validated['x_pengganti_seri_id']])
            ->with('x_success', $msg);
    }

    /**
     * Export data ke tampilan PDF (print-friendly).
     */
    public function exportPdf(Request $request)
    {
        $seriId = $request->input('seri_id');
        $seri   = XPenggantiSeri::findOrFail($seriId);

        $gridData = $this->buildEmptyGrid();

        $packs = XPenggantiRikyetPack::with('details')
            ->where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        for ($p = 1; $p <= 100; $p++) {
            $pack = $packs->get($p);
            $gridData[$p]['seri_pengganti']       = $pack?->seri_pengganti       ?? '';
            $gridData[$p]['total_rusak_seri_1']   = $pack?->total_rusak_seri_1   ?? '';
            $gridData[$p]['total_rusak_seri_2']   = $pack?->total_rusak_seri_2   ?? '';
            $gridData[$p]['total_rusak_campuran'] = $pack?->total_rusak_campuran ?? '';

            for ($s = 1; $s <= 4; $s++) {
                $detail = $pack?->details->firstWhere('slot', $s);
                $gridData[$p]['slots'][$s] = [
                    'rusak_seri_1'   => $detail?->rusak_seri_1   ?? '',
                    'rusak_seri_2'   => $detail?->rusak_seri_2   ?? '',
                    'rusak_campuran' => $detail?->rusak_campuran ?? '',
                    'seri_pengganti' => $detail?->seri_pengganti ?? '',
                ];
            }
        }

        return view('x-pengganti.rikyet.pdfxpgtrikyet', [
            'seri'       => $seri,
            'gridData'   => $gridData,
            'exportedAt' => Carbon::now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB',
        ]);
    }

    /**
     * Build empty grid: array[1..100]
     */
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
                    1 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
                    2 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
                    3 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
                    4 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
                ],
            ];
        }
        return $grid;
    }
}
