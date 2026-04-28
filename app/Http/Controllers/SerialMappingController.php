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
            'x_pengganti_seri_id'     => 'required|exists:x_pengganti_seris,id',
            'pack_number'             => 'required|numeric|regex:/^\d+$/|min:1',
            'source_prefix'           => ['required', new SerialPrefixRule],
            'replacement_prefix'      => ['required', new SerialPrefixRule],
            'replacement_pack_number' => 'required|numeric|regex:/^\d+$/|min:1',
        ]);

        $seri = XPenggantiSeri::findOrFail($validated['x_pengganti_seri_id']);
        $parsed = SerialPrefixGenerator::parseSeriLabel($seri->seri);
        
        // Validasi nomor pack asal
        if ($validated['pack_number'] < $parsed['pack_start'] || $validated['pack_number'] > $parsed['pack_end']) {
            return back()->withInput()->withErrors(['error' => "Nomor pack asal ({$validated['pack_number']}) berada di luar batas seri ini ({$parsed['pack_start']} - {$parsed['pack_end']})."]);
        }

        try {
            // Kalkulasi Otomatis Sisi Asal (Penuh 1 Brood = 1000)
            $sourceRange = SerialPrefixGenerator::calculateSerialRange($validated['pack_number']);
            $category    = SerialPrefixGenerator::categorizePrefix(
                strtoupper($validated['source_prefix']), 
                $parsed['seri1_base'], 
                $parsed['seri2_base']
            );

            if ($category === 'unknown') {
                return back()->withInput()->withErrors(['error' => "Prefix '{$validated['source_prefix']}' tidak dikenali dalam Master Seri ini."]);
            }

            // Kalkulasi Otomatis Sisi Pengganti (Penuh 1 Brood = 1000)
            $repRange = SerialPrefixGenerator::calculateSerialRange($validated['replacement_pack_number']);

            $this->mappingService->registerBroodReplacement(
                $validated['x_pengganti_seri_id'],
                $validated['pack_number'],
                strtoupper($validated['source_prefix']),
                $sourceRange['start'],
                $sourceRange['end'],
                strtoupper($validated['replacement_prefix']),
                $repRange['start'],
                $repRange['end'],
                $category,
                auth()->id()
            );

            // Recalculate agregat Rikyet secara otomatis (Surgical)
            $this->aggregatorService->recalculateFromMappings($validated['x_pengganti_seri_id'], (int) $validated['pack_number']);

            return redirect()
                ->route('x-pengganti.mapping.create', ['seri_id' => $validated['x_pengganti_seri_id']])
                ->with('x_success', "Pack {$validated['pack_number']} berhasil dimapping (Brood Penuh).");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store: Partial brood replacement (Custom digit range).
     */
    public function storePartial(Request $request)
    {
        $validated = $request->validate([
            'x_pengganti_seri_id'      => 'required|exists:x_pengganti_seris,id',
            'pack_number'              => 'required|numeric|regex:/^\d+$/|min:1',
            'source_prefix'            => ['required', new SerialPrefixRule],
            'src_start_offset'         => 'required|numeric|min:0|max:1000',
            'src_end_offset'           => 'required|numeric|min:0|max:1000',
            'replacement_pack_number'  => 'required|numeric|regex:/^\d+$/|min:1',
            'replacement_prefix'       => ['required', new SerialPrefixRule],
            'rep_start_offset'         => 'required|numeric|min:0|max:1000',
            'rep_end_offset'           => 'required|numeric|min:0|max:1000',
        ]);

        $seri = XPenggantiSeri::findOrFail($validated['x_pengganti_seri_id']);
        $parsed = SerialPrefixGenerator::parseSeriLabel($seri->seri);

        try {
            // Helper function for offset (000 -> 1000)
            $getVal = fn($val) => ($val == 0 || $val == '000') ? 1000 : (int)$val;

            $srcS = $getVal($validated['src_start_offset']);
            $srcE = $getVal($validated['src_end_offset']);
            $repS = $getVal($validated['rep_start_offset']);
            $repE = $getVal($validated['rep_end_offset']);

            // 1. Validasi Range (Awal <= Akhir)
            if ($srcS > $srcE) {
                return back()->withInput()->withErrors(['error' => "Asal: Digit awal ({$srcS}) tidak boleh > digit akhir ({$srcE})."]);
            }
            if ($repS > $repE) {
                return back()->withInput()->withErrors(['error' => "Pengganti: Digit awal ({$repS}) tidak boleh > digit akhir ({$repE})."]);
            }

            // 2. Lempar Peringatan jika jumlah bilyet tidak sama
            $srcCount = $srcE - $srcS + 1;
            $repCount = $repE - $repS + 1;

            if ($srcCount !== $repCount) {
                return back()->withInput()->withErrors(['error' => "Jumlah bilyet tidak sama! Seri Asal: {$srcCount} lbr, Seri Pengganti: {$repCount} lbr. Pastikan rentang sama panjang."]);
            }

            // 3. Kalkulasi Nomor Seri Absolut
            $sourceStart = (($validated['pack_number'] - 1) * 1000) + $srcS;
            $sourceEnd   = (($validated['pack_number'] - 1) * 1000) + $srcE;
            $repStart    = (($validated['replacement_pack_number'] - 1) * 1000) + $repS;
            $repEnd      = (($validated['replacement_pack_number'] - 1) * 1000) + $repE;

            $category = SerialPrefixGenerator::categorizePrefix(strtoupper($validated['source_prefix']), $parsed['seri1_base'], $parsed['seri2_base']);
            if ($category === 'unknown') {
                return back()->withInput()->withErrors(['error' => "Prefix '{$validated['source_prefix']}' tidak dikenali."]);
            }

            $this->mappingService->registerBroodReplacement(
                $validated['x_pengganti_seri_id'],
                $validated['pack_number'],
                strtoupper($validated['source_prefix']),
                $sourceStart,
                $sourceEnd,
                strtoupper($validated['replacement_prefix']),
                $repStart,
                $repEnd,
                $category,
                auth()->id()
            );

            // Recalculate agregat
            $this->aggregatorService->recalculateFromMappings($validated['x_pengganti_seri_id'], (int) $validated['pack_number']);

            return redirect()
                ->route('x-pengganti.mapping.create', ['seri_id' => $validated['x_pengganti_seri_id']])
                ->with('x_success', "Inschiet Parsial berhasil tersimpan ({$srcCount} lembar).");

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

        $prefix  = $request->input('prefix');
        $serial  = $request->input('serial');
        $pecahan = $request->input('pecahan');
        $tahun   = $request->input('tahun_anggaran');
        $mode    = $request->input('mode', 'forward');

        if ($request->filled('prefix') && $request->filled('serial') && $request->filled('pecahan')) {
            $searched = true;
            $prefix   = strtoupper($prefix);
            $serial   = (int) $serial;

            if ($mode === 'reverse') {
                $reverseResult = $this->mappingService->reverseLookup($prefix, $serial, $pecahan, $tahun);
            } else {
                $result = $this->mappingService->lookupBySourceSerial($prefix, $serial, $pecahan, $tahun);
            }
        }

        return view('x-pengganti.mapping.lookup', [
            'result'        => $result,
            'reverseResult' => $reverseResult,
            'searched'      => $searched,
            'prefix'        => $prefix,
            'serial'        => $serial,
            'pecahan'       => $pecahan,
            'tahun'         => $tahun,
            'mode'          => $mode,
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

    /**
     * Hapus semua baris mapping untuk nomor pack tertentu sekaligus.
     */
    public function destroyByPack(int $seri_id, int $pack_number)
    {
        $deleted = SerialRangeMapping::where('x_pengganti_seri_id', $seri_id)
            ->where('nomor_pack', $pack_number)
            ->delete();

        // Recalculate agregat setelah hapus massal
        $this->aggregatorService->recalculateFromMappings($seri_id);

        return redirect()
            ->route('x-pengganti.mapping.create', ['seri_id' => $seri_id])
            ->with('x_success', "Semua {$deleted} baris inschiet untuk Pack {$pack_number} berhasil dihapus.");
    }

    /**
     * Hapus semua baris dalam satu "Sesi Input" yang sama (berdasarkan timestamp).
     */
    public function destroyBySession(int $seri_id, string $timestamp)
    {
        // Decode timestamp jika diperlukan, tapi Laravel Route akan menangani string standar
        $deleted = SerialRangeMapping::where('x_pengganti_seri_id', $seri_id)
            ->where('created_at', $timestamp)
            ->delete();

        // Recalculate agregat
        $this->aggregatorService->recalculateFromMappings($seri_id);

        return redirect()
            ->route('x-pengganti.mapping.create', ['seri_id' => $seri_id])
            ->with('x_success', "Sesi input ({$deleted} baris) berhasil dibatalkan.");
    }
}
