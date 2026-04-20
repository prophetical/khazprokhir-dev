<?php

namespace App\Http\Controllers;

use App\Models\HcsKhazaiRegistration;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HcsKhazaiRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = HcsKhazaiRegistration::with('petugasKhazai');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bon', 'like', "%{$search}%")
                  ->orWhere('barcode_token', 'like', "%{$search}%")
                  ->orWhere('batch', 'like', "%{$search}%");
            });
        }

        $registrations = $query->latest()->simplePaginate(20);
        return view('hcs-khazai.index', compact('registrations'));
    }

    public function create()
    {
        return view('hcs-khazai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_bon' => 'required|string',
            'pecahan' => 'required|string',
            'jumlah' => 'required|integer',
            'batch' => 'required|string',
            'seri' => 'required|string',
            'emisi' => 'required|string',
            'tahun_anggaran' => 'required|string',
            'gilir' => 'required|string',
            'mesin' => 'required|string',
            'supplier' => 'required|string',
            'tanggal_pembuatan' => 'required|date',
            'packs' => 'required|array',
        ]);

        $barcode = date('Ymd', strtotime($request->tanggal_pembuatan)) . strtoupper(Str::random(4));

        $registration = HcsKhazaiRegistration::create(array_merge($validated, [
            'barcode_token' => $barcode,
            'petugas_khazai_id' => auth()->id(),
            'packs_data' => $request->packs,
            'status' => 'pending'
        ]));

        return redirect()->route('hcs-khazai-registration.index')->with('success', 'Registrasi HCS berhasil disimpan. Barcode: ' . $barcode);
    }

    public function edit(HcsKhazaiRegistration $hcsKhazaiRegistration)
    {
        if ($hcsKhazaiRegistration->status === 'diterima') {
            return back()->with('error', 'Data yang sudah diterima tidak dapat diubah.');
        }
        return view('hcs-khazai.edit', compact('hcsKhazaiRegistration'));
    }

    public function update(Request $request, HcsKhazaiRegistration $hcsKhazaiRegistration)
    {
        if ($hcsKhazaiRegistration->status === 'diterima') {
            return back()->with('error', 'Data yang sudah diterima tidak dapat diubah.');
        }

        $validated = $request->validate([
            'nomor_bon' => 'required|string',
            'pecahan' => 'required|string',
            'jumlah' => 'required|integer',
            'batch' => 'required|string',
            'seri' => 'required|string',
            'emisi' => 'required|string',
            'tahun_anggaran' => 'required|string',
            'gilir' => 'required|string',
            'mesin' => 'required|string',
            'supplier' => 'required|string',
            'tanggal_pembuatan' => 'required|date',
            'packs' => 'required|array',
        ]);

        $hcsKhazaiRegistration->update(array_merge($validated, [
            'packs_data' => $request->packs
        ]));

        return redirect()->route('hcs-khazai-registration.index')->with('success', 'Registrasi HCS berhasil diperbarui.');
    }

    public function destroy(HcsKhazaiRegistration $hcsKhazaiRegistration)
    {
        if ($hcsKhazaiRegistration->status === 'diterima') {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Data yang sudah diterima tidak dapat dihapus.'], 422);
            }
            return back()->with('error', 'Data yang sudah diterima tidak dapat dihapus.');
        }

        $hcsKhazaiRegistration->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Registrasi HCS berhasil dihapus.']);
        }

        return redirect()->route('hcs-khazai-registration.index')->with('success', 'Registrasi HCS berhasil dihapus.');
    }

    public function barcode($id)
    {
        $registration = HcsKhazaiRegistration::findOrFail($id);
        return view('hcs-khazai.barcode', compact('registration'));
    }

    public function getPackStatus(Request $request)
    {
        $batch = $request->batch;
        $seri = $request->seri;
        $pecahan = $request->pecahan;
        $emisi = $request->emisi;
        $tahun_anggaran = $request->tahun_anggaran;
        $exclude_id = $request->exclude_id;

        if (!$batch || !$seri) {
            return response()->json([]);
        }

        // 1. Ambil Registrasi Pending dari sisi Khazai
        $pendingRegs = HcsKhazaiRegistration::where('batch', $batch)
            ->where('seri', $seri)
            ->where('pecahan', $pecahan)
            ->where('emisi', $emisi)
            ->where('tahun_anggaran', $tahun_anggaran)
            ->where('status', 'pending')
            ->when($exclude_id, function($q) use ($exclude_id) {
                return $q->where('id', '!=', $exclude_id);
            })
            ->get();

        // 2. Ambil Pack yang sudah diterima atau disortir dari sisi Khazpro
        $receivedPacks = Pack::join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.batch', $batch)
            ->where('hcs_receivings.seri', $seri)
            ->where('hcs_receivings.pecahan', $pecahan)
            ->where('hcs_receivings.emisi', $emisi)
            ->where('hcs_receivings.tahun_anggaran', $tahun_anggaran)
            ->select('packs.*')
            ->get();

        $statusMap = [];

        // Prioritas 1: Sudah Diterima / Disortir di Khazpro
        foreach ($receivedPacks as $pack) {
            $status = 'received';
            if ($pack->hcs_sorting_id) {
                $status = 'sorted';
            }
            $statusMap[$pack->pack_number] = [
                'status' => $status,
                'supplier' => $pack->supplier,
                'source' => 'khazpro'
            ];
        }

        // Prioritas 2: Sudah Terdaftar di Khazai (Pending)
        foreach ($pendingRegs as $reg) {
            foreach ($reg->packs_data as $packNum) {
                if (!isset($statusMap[$packNum])) {
                    $statusMap[$packNum] = [
                        'status' => 'pending',
                        'supplier' => $reg->supplier,
                        'source' => 'khazai'
                    ];
                }
            }
        }

        return response()->json($statusMap);
    }
}
