<?php

namespace App\Http\Controllers;

use App\Models\SerialRangeMapping;
use App\Models\XPenggantiSeri;
use App\Rules\SerialPrefixRule;
use App\Services\MappingAggregatorService;
use App\Services\ReplacementMappingService;
use App\Services\SerialPrefixGenerator;
use Illuminate\Http\Request;

class SerialMappingController extends Controller
{
    public function __construct(
        private ReplacementMappingService $mappingService,
        private MappingAggregatorService  $aggregatorService
    ) {}

    /**
     * Daftar master seri untuk dipilih.
     */
    public function index(Request $request)
    {
        $query = XPenggantiSeri::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('batch', 'like', "%{$s}%")
                  ->orWhere('seri', 'like', "%{$s}%");
            });
        }
        if ($request->filled('pecahan'))        $query->where('pecahan', $request->pecahan);
        if ($request->filled('tahun_anggaran')) $query->where('tahun_anggaran', $request->tahun_anggaran);
        if ($request->filled('tahun_emisi'))    $query->where('tahun_emisi', $request->tahun_emisi);

        $masters           = $query->simplePaginate(20)->withQueryString();
        $tahunAnggaranList = XPenggantiSeri::distinct()->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran');
        $tahunEmisiList    = XPenggantiSeri::distinct()->orderByDesc('tahun_emisi')->pluck('tahun_emisi');
        $pecahanOptions    = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        return view('x-pengganti.mapping.index', [
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
     * Form input mapping (3 mode: Pack / Brood / Bilyet).
     */
    public function create(Request $request)
    {
        $seriId = $request->input('seri_id');
        $seri   = XPenggantiSeri::findOrFail($seriId);

        // Existing mappings for this seri
        $mappings = $this->mappingService->getMappingsForSeri($seriId, 50)->withQueryString();

        // Stats - Optimized with single query
        $stats = SerialRangeMapping::forSeri($seriId)
            ->selectRaw('COUNT(*) as total_mappings, SUM(source_end - source_start + 1) as total_bilyet')
            ->first();

        $totalMappings = $stats->total_mappings ?? 0;
        $totalBilyet   = $stats->total_bilyet   ?? 0;

        return view('x-pengganti.mapping.create', [
            'seri'          => $seri,
            'mappings'      => $mappings,
            'totalMappings' => (int) $totalMappings,
            'totalBilyet'   => (int) $totalBilyet,
        ]);
    }

    /**
     * Store: Pack-level replacement (45 rows auto-generated).
     */
    public function storePack(Request $request)
    {
        $validated = $request->validate([
            'x_pengganti_seri_id' => 'required|exists:x_pengganti_seris,id',
            'pack_number'         => 'required|numeric|regex:/^\d+$/|min:1',
            'rep_seri1_base'      => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'rep_seri2_base'      => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'rep_pack_number'     => 'required|numeric|regex:/^\d+$/|min:1',
        ]);

        $seri = XPenggantiSeri::findOrFail($validated['x_pengganti_seri_id']);
        $parsed = SerialPrefixGenerator::parseSeriLabel($seri->seri);
        if ($validated['pack_number'] < $parsed['pack_start'] || $validated['pack_number'] > $parsed['pack_end']) {
            return back()->withInput()->withErrors(['error' => "Nomor pack asal ({$validated['pack_number']}) berada di luar batas seri ini ({$parsed['pack_start']} - {$parsed['pack_end']})."]);
        }

        try {
            $rowsCreated = $this->mappingService->registerPackReplacement(
                $validated['x_pengganti_seri_id'],
                $validated['pack_number'],
                strtoupper($validated['rep_seri1_base']),
                strtoupper($validated['rep_seri2_base']),
                $validated['rep_pack_number'],
                auth()->id()
            );

            // Recalculate agregat Rikyet secara otomatis (Surgical)
            $this->aggregatorService->recalculateFromMappings($validated['x_pengganti_seri_id'], (int) $validated['pack_number']);

            return redirect()
                ->route('x-pengganti.mapping.create', ['seri_id' => $validated['x_pengganti_seri_id']])
                ->with('x_success', "Pack {$validated['pack_number']} berhasil dimapping. {$rowsCreated} baris dibuat (45 brood).");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store: Brood-level replacement (1 row).
     */
    public function storeBrood(Request $request)
    {
        $validated = $request->validate([
            'x_pengganti_seri_id'  => 'required|exists:x_pengganti_seris,id',
            'pack_number'          => 'required|numeric|regex:/^\d+$/|min:1',
            'source_prefix'        => ['required', new SerialPrefixRule],
            'source_start'         => 'required|numeric|regex:/^\d+$/|min:1',
            'source_end'           => 'required|numeric|regex:/^\d+$/|min:1|gte:source_start',
            'replacement_prefix'   => ['required', new SerialPrefixRule],
            'replacement_start'    => 'required|numeric|regex:/^\d+$/|min:1',
            'replacement_end'      => 'required|numeric|regex:/^\d+$/|min:1|gte:replacement_start',
            'source_category'      => 'required|in:seri_1,seri_2,campuran_1,campuran_2',
        ]);

        $seri = XPenggantiSeri::findOrFail($validated['x_pengganti_seri_id']);
        $parsed = SerialPrefixGenerator::parseSeriLabel($seri->seri);
        if ($validated['pack_number'] < $parsed['pack_start'] || $validated['pack_number'] > $parsed['pack_end']) {
            return back()->withInput()->withErrors(['error' => "Nomor pack asal ({$validated['pack_number']}) berada di luar batas seri ini ({$parsed['pack_start']} - {$parsed['pack_end']})."]);
        }

        try {
            $this->mappingService->registerBroodReplacement(
                $validated['x_pengganti_seri_id'],
                $validated['pack_number'],
                strtoupper($validated['source_prefix']),
                $validated['source_start'],
                $validated['source_end'],
                strtoupper($validated['replacement_prefix']),
                $validated['replacement_start'],
                $validated['replacement_end'],
                $validated['source_category'],
                auth()->id()
            );

            $count = $validated['source_end'] - $validated['source_start'] + 1;

            // Recalculate agregat Rikyet secara otomatis
            $this->aggregatorService->recalculateFromMappings($validated['x_pengganti_seri_id']);

            return redirect()
                ->route('x-pengganti.mapping.create', ['seri_id' => $validated['x_pengganti_seri_id']])
                ->with('x_success', "Brood {$validated['source_prefix']} ({$count} bilyet) berhasil dimapping.");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store: Vell-level replacement (1 sheet = 45 Bilyet across 45 prefixes).
     */
    public function storeVell(Request $request)
    {
        $validated = $request->validate([
            'x_pengganti_seri_id' => 'required|exists:x_pengganti_seris,id',
            'source_serial'       => 'required|numeric|regex:/^\d+$/|min:1',
            'rep_seri1_base'      => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'rep_seri2_base'      => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'replacement_serial'  => 'required|numeric|regex:/^\d+$/|min:1',
        ]);

        $seri = XPenggantiSeri::findOrFail($validated['x_pengganti_seri_id']);
        $parsed = SerialPrefixGenerator::parseSeriLabel($seri->seri);
        $minSerial = SerialPrefixGenerator::calculateSerialRange($parsed['pack_start'])['start'];
        $maxSerial = SerialPrefixGenerator::calculateSerialRange($parsed['pack_end'])['end'];
        
        if ($validated['source_serial'] < $minSerial || $validated['source_serial'] > $maxSerial) {
            return back()->withInput()->withErrors(['error' => "Nomor serial asal ({$validated['source_serial']}) berada di luar batas seri ini ({$minSerial} - {$maxSerial})."]);
        }

        try {
            $rowsCreated = $this->mappingService->registerVellReplacement(
                $validated['x_pengganti_seri_id'],
                $validated['source_serial'],
                strtoupper($validated['rep_seri1_base']),
                strtoupper($validated['rep_seri2_base']),
                $validated['replacement_serial'],
                auth()->id()
            );

            // Recalculate agregat Khazai secara otomatis (Surgical)
            $packNumber = intdiv($validated['source_serial'] - 1, 1000) + 1;
            $this->aggregatorService->recalculateFromMappings($validated['x_pengganti_seri_id'], $packNumber);

            return redirect()
                ->route('x-pengganti.mapping.create', ['seri_id' => $validated['x_pengganti_seri_id']])
                ->with('x_success', "Vell pada serial {$validated['source_serial']} berhasil dimapping. {$rowsCreated} pemecahan bilyet dieksekusi.");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store: Single bilyet replacement (may trigger range split).
     */
    public function storeSingle(Request $request)
    {
        $validated = $request->validate([
            'x_pengganti_seri_id' => 'required|exists:x_pengganti_seris,id',
            'source_prefix'       => ['required', new SerialPrefixRule],
            'source_serial'       => 'required|numeric|regex:/^\d+$/|min:1',
            'replacement_prefix'  => ['required', new SerialPrefixRule],
            'replacement_serial'  => 'required|numeric|regex:/^\d+$/|min:1',
        ]);

        $seri = XPenggantiSeri::findOrFail($validated['x_pengganti_seri_id']);
        $parsed = SerialPrefixGenerator::parseSeriLabel($seri->seri);
        $minSerial = SerialPrefixGenerator::calculateSerialRange($parsed['pack_start'])['start'];
        $maxSerial = SerialPrefixGenerator::calculateSerialRange($parsed['pack_end'])['end'];
        
        if ($validated['source_serial'] < $minSerial || $validated['source_serial'] > $maxSerial) {
            return back()->withInput()->withErrors(['error' => "Nomor serial asal ({$validated['source_serial']}) berada di luar batas seri ini ({$minSerial} - {$maxSerial})."]);
        }

        try {
            $result = $this->mappingService->registerSingleBilyet(
                $validated['x_pengganti_seri_id'],
                strtoupper($validated['source_prefix']),
                $validated['source_serial'],
                strtoupper($validated['replacement_prefix']),
                $validated['replacement_serial'],
                auth()->id()
            );

            $actionMsg = $result['action'] === 'split'
                ? "Range dipecah menjadi {$result['rows_affected']} bagian."
                : "Mapping bilyet tunggal dibuat.";

            // Recalculate agregat Cutpack secara otomatis (Surgical)
            $packNumber = intdiv($validated['source_serial'] - 1, 1000) + 1;
            $this->aggregatorService->recalculateFromMappings($validated['x_pengganti_seri_id'], $packNumber);

            return redirect()
                ->route('x-pengganti.mapping.create', ['seri_id' => $validated['x_pengganti_seri_id']])
                ->with('x_success', "Bilyet {$validated['source_prefix']}{$validated['source_serial']} berhasil dimapping. {$actionMsg}");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Lookup: Search for replacement by source serial.
     */
    public function lookup(Request $request)
    {
        $result        = null;
        $reverseResult = null;
        $searched      = false;

        if ($request->filled('prefix') && $request->filled('serial')) {
            $searched = true;
            $prefix   = strtoupper($request->input('prefix'));
            $serial   = (int) $request->input('serial');
            $mode     = $request->input('mode', 'forward');

            if ($mode === 'reverse') {
                $reverseResult = $this->mappingService->reverseLookup($prefix, $serial);
            } else {
                $result = $this->mappingService->lookupBySourceSerial($prefix, $serial);
            }
        }

        return view('x-pengganti.mapping.lookup', [
            'result'        => $result,
            'reverseResult' => $reverseResult,
            'searched'      => $searched,
            'prefix'        => $request->input('prefix', ''),
            'serial'        => $request->input('serial', ''),
            'mode'          => $request->input('mode', 'forward'),
        ]);
    }

    /**
     * Delete a mapping entry.
     */
    public function destroy(int $id)
    {
        $mapping = SerialRangeMapping::findOrFail($id);
        $seriId  = $mapping->x_pengganti_seri_id;

        $mapping->delete();

        // Recalculate agregat setelah hapus mapping
        $this->aggregatorService->recalculateFromMappings($seriId);

        return redirect()
            ->route('x-pengganti.mapping.create', ['seri_id' => $seriId])
            ->with('x_success', 'Mapping berhasil dihapus.');
    }
}
