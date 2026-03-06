<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Services\HcsReceivingService;
use App\Http\Requests\StoreHcsReceivingRequest;
use Illuminate\Http\Request;

class HcsReceivingController extends Controller
{
    protected $service;

    public function __construct(HcsReceivingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $receivings = HcsReceiving::with('user')->latest()->paginate(10);
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
}
