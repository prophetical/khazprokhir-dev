<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHcsReceivingRequest;
use App\Http\Requests\UpdateHcsReceivingRequest;
use App\Models\HcsReceiving;
use App\Services\HcsReceivingService;
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
        $query = HcsReceiving::with('user')->withCount('packs');

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
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        if (in_array($sortBy, $sortableColumns)) {
            $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        // Tambahin query string ke link pagination biar filter dan urutan datanya gak ilang pas pindah halaman
        $receivings = $query->simplePaginate(20)->withQueryString();

        return view('hcs-receiving.index', compact('receivings'));
    }

    public function create()
    {
        $lastReceiving = HcsReceiving::latest()->first();

        return view('hcs-receiving.create', compact('lastReceiving'));
    }

    public function store(StoreHcsReceivingRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['is_manual'] = $request->has('is_manual');
            $this->service->createReceiving($validated, auth()->id());

            return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('HCS Receiving Store Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function edit(HcsReceiving $hcsReceiving)
    {
        $hcsReceiving->load('packs');
        $sortedPacks = $hcsReceiving->packs->whereNotNull('hcs_sorting_id')->pluck('pack_number')->toArray();
        return view('hcs-receiving.edit', [
            'hcsReceiving' => $hcsReceiving,
            'sortedPacks' => $sortedPacks,
            'hasSortedPacks' => !empty($sortedPacks),
            'sortedPacksCount' => count($sortedPacks)
        ]);
    }

    public function update(UpdateHcsReceivingRequest $request, HcsReceiving $hcsReceiving)
    {
        try {
            $validated = $request->validated();
            $validated['is_manual'] = $request->has('is_manual');
            $this->service->updateReceiving($hcsReceiving, $validated, auth()->id());

            return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('HCS Receiving Update Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.']);
        }
    }

    public function destroy(HcsReceiving $hcsReceiving)
    {
        try {
            $this->service->deleteReceiving($hcsReceiving, auth()->id());
            return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('HCS Receiving Delete Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.']);
        }
    }
}
