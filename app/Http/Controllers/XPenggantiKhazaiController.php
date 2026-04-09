<?php

namespace App\Http\Controllers;

use App\Models\XPenggantiSeri;
use App\Models\XPenggantiPack;
use App\Models\XPenggantiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XPenggantiKhazaiController extends Controller
{
    /**
     * Halaman index: hanya tabel pilih master seri (max 10 baris, paginasi).
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

        return view('x-pengganti.khazai.index', [
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

        $packs = XPenggantiPack::with('details')
            ->where('x_pengganti_seri_id', $seriId)
            ->get()
            ->keyBy('nomor_pack');

        for ($p = 1; $p <= 100; $p++) {
            $pack = $packs->get($p);
            $gridData[$p]['seri_pengganti'] = $pack?->seri_pengganti ?? '';
            for ($s = 1; $s <= 4; $s++) {
                $detail = $pack?->details->firstWhere('slot', $s);
                $gridData[$p]['slots'][$s] = [
                    'jumlah_rusak_vell'    => $detail?->jumlah_rusak_vell    ?? '',
                    'nomor_pack_pengganti' => $detail?->nomor_pack_pengganti ?? '',
                    'nomor_vell_pengganti' => $detail?->nomor_vell_pengganti ?? '',
                ];
            }
        }

        return view('x-pengganti.khazai.input', [
            'seri'     => $seri,
            'gridData' => $gridData,
        ]);
    }

    /**
     * Simpan / update data Khazai (partial upsert — data boleh diisi sebagian).
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
            'x_pengganti_seri_id'                  => 'required|exists:x_pengganti_seris,id',
            'packs'                                => 'nullable|array',
            'packs.*.nomor_pack'                   => 'required|integer|min:1|max:100',
            'packs.*.seri_pengganti'               => ['nullable', 'string', 'regex:/^[A-Z]{2}-[A-Z]{2}[0-9]$/'],
            'packs.*.slots'                        => 'nullable|array',
            'packs.*.slots.*.slot'                 => 'required|integer|min:1|max:4',
            'packs.*.slots.*.jumlah_rusak_vell'    => 'nullable|integer|min:0',
            'packs.*.slots.*.nomor_pack_pengganti' => 'nullable|integer|min:0',
            'packs.*.slots.*.nomor_vell_pengganti' => 'nullable|integer|min:0',
        ], [
            'packs.*.seri_pengganti.regex' => 'Format Seri Pengganti harus XX-XX9 (contoh: AB-BB1). Maksimal 6 karakter.',
        ]);

        DB::transaction(function () use ($validated) {
            $seriId     = $validated['x_pengganti_seri_id'];
            $packValues = [];
            $now        = now();

            foreach ($validated['packs'] ?? [] as $packData) {
                $packValues[] = [
                    'x_pengganti_seri_id' => $seriId,
                    'nomor_pack'          => $packData['nomor_pack'],
                    'seri_pengganti'      => ($packData['seri_pengganti'] ?? '') ?: null,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }

            if (!empty($packValues)) {
                XPenggantiPack::upsert($packValues, ['x_pengganti_seri_id', 'nomor_pack'], ['seri_pengganti', 'updated_at']);
            }

            // Get mapping nomor_pack -> id
            $packsMap = XPenggantiPack::where('x_pengganti_seri_id', $seriId)
                ->pluck('id', 'nomor_pack');

            $detailValues = [];
            foreach ($validated['packs'] ?? [] as $packData) {
                $packId = $packsMap[$packData['nomor_pack']] ?? null;
                if (!$packId) continue;

                foreach ($packData['slots'] ?? [] as $slotData) {
                    $detailValues[] = [
                        'x_pengganti_pack_id'  => $packId,
                        'slot'                 => $slotData['slot'],
                        'jumlah_rusak_vell'    => ($slotData['jumlah_rusak_vell']    ?? '') !== '' ? $slotData['jumlah_rusak_vell']    : null,
                        'nomor_pack_pengganti' => ($slotData['nomor_pack_pengganti'] ?? '') !== '' ? $slotData['nomor_pack_pengganti'] : null,
                        'nomor_vell_pengganti' => ($slotData['nomor_vell_pengganti'] ?? '') !== '' ? $slotData['nomor_vell_pengganti'] : null,
                        'created_at'           => $now,
                        'updated_at'           => $now,
                    ];
                }
            }

            if (!empty($detailValues)) {
                XPenggantiDetail::upsert($detailValues, ['x_pengganti_pack_id', 'slot'], [
                    'jumlah_rusak_vell',
                    'nomor_pack_pengganti',
                    'nomor_vell_pengganti',
                    'updated_at'
                ]);
            }
        });

        $seri = \App\Models\XPenggantiSeri::find($validated['x_pengganti_seri_id']);
        $msg  = "Data Khazai Seri {$seri->seri} Batch {$seri->batch} berhasil disimpan.";

        return redirect()
            ->route('x-pengganti.khazai.input', [
                'seri_id' => $validated['x_pengganti_seri_id'],
                'saved'   => 1
            ])
            ->with('x_success', $msg);
    }

    /**
     * Export data ke tampilan PDF (print-friendly).
     */
    public function exportPdf(Request $request)
    {
        $seriId = $request->input('seri_id');
        $seri   = XPenggantiSeri::findOrFail($seriId);

        $packs = XPenggantiPack::with('details')
            ->where('x_pengganti_seri_id', $seriId)
            ->orderBy('nomor_pack')
            ->get();

        return view('x-pengganti.khazai.pdfxpgtkhazai', [
            'seri'       => $seri,
            'packs'      => $packs,
            'exportedAt' => now()->timezone('Asia/Jakarta')->format('d F Y, H:i') . ' WIB',
        ]);
    }

    /**
     * Build empty grid: array[1..100]['seri_pengganti'] + ['slots'][1..4]
     */
    private function buildEmptyGrid(): array
    {
        $grid = [];
        for ($p = 1; $p <= 100; $p++) {
            $grid[$p] = [
                'seri_pengganti' => '',
                'slots' => [
                    1 => ['jumlah_rusak_vell' => '', 'nomor_pack_pengganti' => '', 'nomor_vell_pengganti' => ''],
                    2 => ['jumlah_rusak_vell' => '', 'nomor_pack_pengganti' => '', 'nomor_vell_pengganti' => ''],
                    3 => ['jumlah_rusak_vell' => '', 'nomor_pack_pengganti' => '', 'nomor_vell_pengganti' => ''],
                    4 => ['jumlah_rusak_vell' => '', 'nomor_pack_pengganti' => '', 'nomor_vell_pengganti' => ''],
                ],
            ];
        }
        return $grid;
    }
}
