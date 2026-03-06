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
        $query = HcsReceiving::with('user');

        // Text Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bon', 'like', "%{$search}%")
                    ->orWhere('batch', 'like', "%{$search}%")
                    ->orWhere('seri', 'like', "%{$search}%");
            });
        }

        // Date Range Filter
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_penerimaan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_penerimaan', '<=', $request->end_date);
        }

        // Dropdown Filters
        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }
        if ($request->filled('supplier')) {
            $query->where('supplier', $request->supplier);
        }

        // Sorting
        $sortableColumns = ['tanggal_penerimaan', 'nomor_bon', 'pecahan', 'jumlah', 'supplier', 'batch', 'seri'];
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if (in_array($sortBy, $sortableColumns)) {
            $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        else {
            $query->latest();
        }

        // Append query strings to pagination links so filters and sorts persist across pages
        $receivings = $query->paginate(10)->withQueryString();

        return view('hcs-receiving.index', compact('receivings'));
    }

    public function create()
    {
        return view('hcs-receiving.create');
    }

    public function store(StoreHcsReceivingRequest $request)
    {
        $validated = $request->validated();

        $jumlah = $validated['jumlah'];
        $packsNeeded = $jumlah / 45000;

        $selectedPacksCount = count($validated['packs']);

        if ($selectedPacksCount !== (int)$packsNeeded) {
            return back()->withInput()->withErrors(['packs' => "Jumlah packs yang dipilih ($selectedPacksCount) tidak sesuai dengan jumlah bilyet ($jumlah). Dibutuhkan $packsNeeded packs."]);
        }

        try {
            $this->service->createReceiving($validated, auth()->id());
            return redirect()->route('hcs-receiving.index')->with('success', 'Data HCS Receiving berhasil disimpan.');
        }
        catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit(HcsReceiving $hcsReceiving)
    {
        // Require Sortir access validation via middleware/policies, here we just show the view
        if (auth()->user()->role !== 'sortir') {
            abort(403, 'Unauthorized action.');
        }

        $hcsReceiving->load('packs');
        return view('hcs-receiving.edit', compact('hcsReceiving'));
    }

    public function update(UpdateHcsReceivingRequest $request, HcsReceiving $hcsReceiving)
    {
        $validated = $request->validated();
        $jumlah = $validated['jumlah'];
        $packsNeeded = $jumlah / 45000;
        $selectedPacksCount = count($validated['packs']);

        if ($selectedPacksCount !== (int)$packsNeeded) {
            return back()->withInput()->withErrors(['packs' => "Jumlah packs yang dipilih ($selectedPacksCount) tidak sesuai dengan jumlah bilyet ($jumlah). Dibutuhkan $packsNeeded packs."]);
        }

        try {
            $this->service->updateReceiving($hcsReceiving, $validated, auth()->id());
            return redirect()->route('hcs-receiving.index')->with('success', 'Data HCS Receiving berhasil diperbarui.');
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

        try {
            $this->service->deleteReceiving($hcsReceiving, auth()->id());
            return redirect()->route('hcs-receiving.index')->with('success', 'Data HCS Receiving berhasil dihapus secara permanen.');
        }
        catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
