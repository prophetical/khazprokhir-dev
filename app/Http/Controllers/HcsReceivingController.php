<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHcsReceivingRequest;
use App\Http\Requests\UpdateHcsReceivingRequest;
use App\Models\HcsReceiving;
use App\Models\HcsReceivingHistory;
use App\Models\HcsKhazaiRegistration;
use App\Services\HcsReceivingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $query->where('tanggal_penerimaan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal_penerimaan', '<=', $request->end_date);
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
            $inputType = $request->input('input_type', 'direct'); // registration or direct

            if ($inputType === 'registration') {
                // Jalur 1: Registrasi Khazai (Pending Scan)
                $barcode = date('Ymd') . strtoupper(Str::random(4));
                $registration = HcsKhazaiRegistration::create([
                    'barcode_token' => $barcode,
                    'nomor_bon' => $validated['nomor_bon'],
                    'pecahan' => $validated['pecahan'],
                    'jumlah' => $validated['jumlah'],
                    'batch' => $validated['batch'],
                    'seri' => $validated['seri'],
                    'emisi' => $validated['emisi'],
                    'tahun_anggaran' => $validated['tahun_anggaran'],
                    'gilir' => $validated['gilir'],
                    'mesin' => $validated['mesin'],
                    'supplier' => $validated['supplier'],
                    'tanggal_pembuatan' => $validated['tanggal_penerimaan'],
                    'petugas_khazai_id' => auth()->id(),
                    'packs_data' => $validated['packs'],
                    'status' => 'pending'
                ]);

                return redirect()->route('hcs-khazai-registration.barcode', $registration->id)
                    ->with('success', 'Registrasi HCS berhasil disimpan. Silakan cetak barcode.');
            } else {
                // Jalur 2: Penerimaan Langsung (Seksi Penerimaan / Bypass Scan)
                $this->service->createReceiving($validated, auth()->id());
                return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS (Manual Langsung) berhasil disimpan.');
            }
        } catch (\Exception $e) {
            \Log::error('HCS Receiving Store Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
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
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(HcsReceiving $hcsReceiving)
    {
        try {
            $this->service->deleteReceiving($hcsReceiving, auth()->id());
            
            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Penerimaan HCS berhasil dibatalkan. Status registrasi kembali ke PENDING.']);
            }

            return redirect()->route('hcs-receiving.index')->with('success', 'Data Penerimaan HCS berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('HCS Receiving Delete Error: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function scan()
    {
        $registrations = HcsKhazaiRegistration::whereDate('tanggal_pembuatan', today())
            ->orderBy('status', 'asc')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Ambil data input manual juga untuk dashboard scan
        $manualReceivings = HcsReceiving::whereDate('created_at', today())
            ->get()
            ->filter(function($item) {
                return !HcsKhazaiRegistration::where('nomor_bon', $item->nomor_bon)
                    ->where('batch', $item->batch)
                    ->where('seri', $item->seri)
                    ->exists();
            });

        return view('hcs-receiving.scan', compact('registrations', 'manualReceivings'));
    }

    public function scanStatus()
    {
        $registrations = HcsKhazaiRegistration::whereDate('tanggal_pembuatan', today())
            ->orderBy('status', 'asc')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Ambil data input manual juga untuk tabel status
        $manualReceivings = HcsReceiving::whereDate('created_at', today())
            ->get()
            ->filter(function($item) {
                // Hanya ambil yang tidak punya registrasi (berarti manual direct entry)
                return !HcsKhazaiRegistration::where('nomor_bon', $item->nomor_bon)
                    ->where('batch', $item->batch)
                    ->where('seri', $item->seri)
                    ->exists();
            });

        return view('hcs-receiving.scan-status-table', compact('registrations', 'manualReceivings'));
    }

    public function manualConfirm(HcsKhazaiRegistration $registration)
    {
        try {
            $hcs = $this->service->createFromRegistration($registration->barcode_token, auth()->id());
            return response()->json([
                'success' => true,
                'message' => 'Konfirmasi manual barcode ' . $registration->barcode_token . ' berhasil.',
                'data' => $hcs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function storeScan(Request $request)
    {
        $request->validate(['barcode' => 'required|string']);

        try {
            $hcs = $this->service->createFromRegistration($request->barcode, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Data barcode ' . $request->barcode . ' berhasil diterima.',
                'data' => $hcs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function history($barcode)
    {
        $histories = HcsReceivingHistory::with('user')
            ->where('barcode_token', $barcode)
            ->latest()
            ->get();

        return view('hcs-receiving.history', compact('histories', 'barcode'));
    }
}
