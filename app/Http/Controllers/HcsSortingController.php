<?php

namespace App\Http\Controllers;

use App\Models\HcsSorting;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HcsSortingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Build Query for Available Groups with Filters
        $query = Pack::whereNull('hcs_sorting_id')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id');

        if ($request->filled('pecahan')) {
            $query->where('hcs_receivings.pecahan', $request->pecahan);
        }
        if ($request->filled('batch')) {
            $query->where('packs.batch', 'like', '%' . $request->batch . '%');
        }
        if ($request->filled('seri')) {
            $query->where('packs.seri', 'like', '%' . $request->seri . '%');
        }

        $AvailableGroups = $query->select('hcs_receivings.pecahan', 'packs.batch', 'packs.seri', DB::raw('count(*) as total_pack'))
            ->groupBy('hcs_receivings.pecahan', 'packs.batch', 'packs.seri')
            ->orderBy('total_pack', 'desc')
            ->paginate(20)->withQueryString();

        // 2. Build Summary per Pecahan
        $summaryData = Pack::whereNull('hcs_sorting_id')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->select('hcs_receivings.pecahan', DB::raw('count(*) as total_pack'))
            ->groupBy('hcs_receivings.pecahan')
            ->pluck('total_pack', 'hcs_receivings.pecahan')
            ->toArray();

        $expectedPecahan = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $summaries = [];
        $totalAllPacks = 0;
        foreach ($expectedPecahan as $p) {
            $count = $summaryData[$p] ?? 0;
            $summaries[$p] = $count;
            $totalAllPacks += $count;
        }

        return view('hcs-sorting.index', compact('AvailableGroups', 'summaries', 'totalAllPacks'));
    }

    public function create(Request $request)
    {
        $pecahan = $request->query('pecahan');
        $batch = $request->query('batch');
        $seri = $request->query('seri');

        if (!$pecahan || !$batch || !$seri) {
            return redirect()->route('hcs-sorting.index')->with('error', 'Silahkan pilih grup data terlebih dahulu.');
        }

        // Get all packs for this specific group (sorted and unsorted up to 100)
        // Note: The prompt says "pack 1 sampai 100", so we need to know the status of each pack 1-100.
        $packsData = Pack::join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.pecahan', $pecahan)
            ->where('packs.batch', $batch)
            ->where('packs.seri', $seri)
            ->where('packs.pack_number', '<=', 100)
            ->select('packs.*', 'packs.supplier as pack_supplier', 'hcs_receivings.pecahan')
            ->get()
            ->keyBy('pack_number');

        return view('hcs-sorting.create', compact('pecahan', 'batch', 'seri', 'packsData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pecahan' => 'required',
            'batch' => 'required',
            'seri' => 'required',
            'supplier' => 'required|in:Rikyet,Cutpack',
            'petugas_1' => 'required',
            'petugas_2' => 'nullable',
            'tanggal' => 'required|date',
            'gilir' => 'required',
            'selected_packs' => 'required|array|min:4',
        ]);

        $selectedPacks = $request->selected_packs;
        sort($selectedPacks);

        // Validation 1: Must be in groups of 4 and sequential
        // To be valid, the selected packs must be contiguous blocks, each block length must be a multiple of 4.
        $contiguousBlocks = [];
        $currentBlock = [];
        foreach ($selectedPacks as $packNum) {
            $packNum = (int)$packNum;
            if (empty($currentBlock)) {
                $currentBlock[] = $packNum;
            }
            else {
                $lastNum = end($currentBlock);
                if ($packNum == $lastNum + 1) {
                    $currentBlock[] = $packNum;
                }
                else {
                    $contiguousBlocks[] = $currentBlock;
                    $currentBlock = [$packNum];
                }
            }
        }
        if (!empty($currentBlock)) {
            $contiguousBlocks[] = $currentBlock;
        }

        foreach ($contiguousBlocks as $block) {
            if (count($block) % 4 !== 0) {
                throw ValidationException::withMessages([
                    'selected_packs' => 'Pack yang dipilih harus berurutan dan berkelipatan 4 (contoh: 1-4, 5-8, dll).',
                ]);
            }

            // Also ensure the block starts at a correct multiple of 4 boundary.
            // i.e., pack 1, 5, 9, 13... so (pack - 1) % 4 == 0
            if (($block[0] - 1) % 4 !== 0) {
                throw ValidationException::withMessages([
                    'selected_packs' => 'Posisi awal pack yang dipilih tidak valid. Harus dimulai dari kelipatan yang benar (misal: 1, 5, 9, dst).',
                ]);
            }
        }

        // Ensure none of these packs are already sorted
        $alreadySorted = Pack::where('batch', $request->batch)
            ->where('seri', $request->seri)
            ->whereIn('pack_number', $selectedPacks)
            ->whereNotNull('hcs_sorting_id')
            ->exists();

        if ($alreadySorted) {
            throw ValidationException::withMessages([
                'selected_packs' => 'Salah satu atau lebih pack yang dipilih sudah disortir sebelumnya.',
            ]);
        }

        $jumlahPack = count($selectedPacks);
        $jumlahBilyet = $jumlahPack * 45000;

        DB::beginTransaction();

        try {
            $sorting = HcsSorting::create([
                'pecahan' => $request->pecahan,
                'batch' => $request->batch,
                'seri' => $request->seri,
                'supplier' => $request->supplier,
                'packs_selected' => $selectedPacks,
                'jumlah_pack' => $jumlahPack,
                'jumlah_bilyet' => $jumlahBilyet,
                'petugas_1' => $request->petugas_1,
                'petugas_2' => $request->petugas_2,
                'tanggal_sortir' => $request->tanggal,
                'gilir' => $request->gilir,
                'created_by' => auth()->id(),
            ]);

            // Update Packs
            Pack::where('batch', $request->batch)
                ->where('seri', $request->seri)
                ->whereIn('pack_number', $selectedPacks)
                ->update(['hcs_sorting_id' => $sorting->id]);

            DB::commit();

            return redirect()->route('hcs-sorting.index')->with('success', 'Data penyortiran berhasil disimpan.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
