<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Services\HcsReceivingService;
use App\Http\Requests\StoreHcsReceivingRequest;
use App\Http\Requests\UpdateHcsReceivingRequest;
use Illuminate\Http\Request;

class HcsReceivingController extends Controller
{
    protected $service;

    public function __construct(HcsReceivingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = HcsReceiving::with(['user', 'packs']);

        // Pencarian Teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bon', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%");
            });
        }

        // Filter Rentang Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_penerimaan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_penerimaan', '<=', $request->end_date);
        }

        // Filter Dropdown
        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }
        if ($request->filled('supplier')) {
            $query->where('supplier', $request->supplier);
        }

        // Pengurutan Data (Sorting)
        $sortableColumns = ['tanggal_penerimaan', 'nomor_bon', 'pecahan', 'jumlah', 'supplier', 'batch', 'seri'];
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if (in_array($sortBy, $sortableColumns)) {
            $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        else {
            $query->latest();
        }

        // Tambahin query string ke link pagination biar filter dan urutan datanya gak ilang pas pindah halaman
        $receivings = $query->paginate(10)->withQueryString();

        return view('hcs-receiving.index', compact('receivings'));
    }

    public function create()
    {
        $lastReceiving = HcsReceiving::latest()->first();
        return view('hcs-receiving.create', compact('lastReceiving'));
    }

    public function store(StoreHcsReceivingRequest $request)
    {
        $validated = $request->validated();
        $isManual = $request->has('is_manual');

        $jumlah = $validated['jumlah'];
        
        if ($isManual) {
            if ($jumlah > 45000) {
                return back()->withInput()->withErrors(['jumlah' => 'Jumlah bilyet tidak boleh melebihi 45.000 untuk pack tidak full.']);
            }
            $packsNeeded = 1;
        } else {
            if ($jumlah % 45000 !== 0) {
                return back()->withInput()->withErrors(['jumlah' => 'Jumlah bilyet harus kelipatan 45.000.']);
            }
            $packsNeeded = $jumlah / 45000;
        }

        $selectedPacksCount = count($validated['packs']);

        if ($selectedPacksCount !== (int)$packsNeeded) {
            return back()->withInput()->withErrors(['packs' => "Jumlah packs yang dipilih ($selectedPacksCount) tidak sesuai kebutuhan ($packsNeeded)."]);
        }

        try {
            $validated['is_manual'] = $isManual;
            $this->service->createReceiving($validated, auth()->id());
            return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS berhasil disimpan.');
        }
        catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit(HcsReceiving $hcsReceiving)
    {
        // Butuh validasi akses Sortir (biasanya lewat middleware/policy), di sini kita cuma nampilin halamannya aja
        if (auth()->user()->role !== 'sortir') {
            abort(403, 'Unauthorized action.');
        }

        $hcsReceiving->load('packs');

        $sortedPacks = $hcsReceiving->packs->whereNotNull('hcs_sorting_id')->pluck('pack_number')->toArray();
        $hasSortedPacks = !empty($sortedPacks);
        $sortedPacksCount = count($sortedPacks);

        return view('hcs-receiving.edit', compact('hcsReceiving', 'sortedPacks', 'hasSortedPacks', 'sortedPacksCount'));
    }

    public function update(UpdateHcsReceivingRequest $request, HcsReceiving $hcsReceiving)
    {
        $validated = $request->validated();
        $isManual = $request->has('is_manual');
        $jumlah = $validated['jumlah'];

        if ($isManual) {
            if ($jumlah > 45000) {
                return back()->withInput()->withErrors(['jumlah' => 'Jumlah bilyet tidak boleh melebihi 45.000 untuk pack tidak full.']);
            }
            $packsNeeded = 1;
        } else {
            if ($jumlah % 45000 !== 0) {
                return back()->withInput()->withErrors(['jumlah' => 'Jumlah bilyet harus kelipatan 45.000.']);
            }
            $packsNeeded = $jumlah / 45000;
        }

        $selectedPacksCount = count($validated['packs']);

        if ($selectedPacksCount !== (int)$packsNeeded) {
            return back()->withInput()->withErrors(['packs' => "Jumlah packs yang dipilih ($selectedPacksCount) tidak sesuai kebutuhan ($packsNeeded)."]);
        }

        // Cek field yang gak boleh diedit (read-only) permanen
        if ($validated['pecahan'] !== $hcsReceiving->pecahan ||
            $validated['batch'] !== $hcsReceiving->batch ||
            $validated['seri'] !== $hcsReceiving->seri ||
            $validated['emisi'] != $hcsReceiving->emisi ||
            $validated['tahun_anggaran'] != $hcsReceiving->tahun_anggaran) {
            return back()->withInput()->withErrors(['error' => 'Tahun Anggaran, Emisi, Pecahan, Batch, dan Seri tidak boleh diubah untuk menjaga integritas satu batch.']);
        }

        $sortedPacks = $hcsReceiving->packs()->whereNotNull('hcs_sorting_id')->pluck('pack_number')->toArray();

        if (!empty($sortedPacks)) {
            // Validasi minimum pack
            if ($selectedPacksCount < count($sortedPacks)) {
                return back()->withInput()->withErrors(['packs' => 'Jumlah pack tidak boleh kurang dari pack yang sudah disortir (' . count($sortedPacks) . ' pack).']);
            }

            // Validasi pack yang sudah disortir tidak boleh di-unselect
            $missingSortedPacks = array_diff($sortedPacks, $validated['packs']);
            if (!empty($missingSortedPacks)) {
                return back()->withInput()->withErrors(['packs' => 'Pack yang sudah disortir tidak boleh dibuang.']);
            }
        }

        try {
            $validated['is_manual'] = $isManual;
            $this->service->updateReceiving($hcsReceiving, $validated, auth()->id());
            return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function destroy(HcsReceiving $hcsReceiving)
    {
        if (auth()->user()->role !== 'sortir') {
            abort(403, 'Unauthorized action.');
        }

        if ($hcsReceiving->packs()->whereNotNull('hcs_sorting_id')->exists()) {
            return back()->withErrors(['error' => 'Data tidak dapat dihapus karena beberapa pack sudah disortir.']);
        }

        try {
            $this->service->deleteReceiving($hcsReceiving, auth()->id());
            return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS berhasil dihapus secara permanen.');
        }
        catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
