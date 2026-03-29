<?php

namespace App\Http\Controllers;

use App\Models\TargetBulanan;
use App\Models\TargetBulananPengemasan;
use App\Models\TargetTahunan;
use App\Services\TargetService;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    protected $service;

    public function __construct(TargetService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('targets.index', ['targets' => $this->service->listTargets()]);
    }

    public function create()
    {
        return view('targets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pecahan' => 'required|string|max:10',
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'tahun_emisi' => 'required|integer|min:2000|max:2100',
            'target_tahunan' => 'required|numeric|min:0|max:50000000000',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            $data["bulan_{$i}"] = $request->validate(["bulan_{$i}" => 'required|numeric|min:0'])["bulan_{$i}"];
            $data["pengemasan_bulan_{$i}"] = $request->validate(["pengemasan_bulan_{$i}" => 'required|numeric|min:0'])["pengemasan_bulan_{$i}"];
        }

        $this->service->storeTarget($data);

        return redirect()->route('targets.index')->with('success', 'Target berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $targetTahunan = TargetTahunan::findOrFail($id);
        $targetBulanan = TargetBulanan::where(['pecahan' => $targetTahunan->pecahan, 'tahun_anggaran' => $targetTahunan->tahun_anggaran, 'tahun_emisi' => $targetTahunan->tahun_emisi])->firstOrFail();
        $targetPengemasan = TargetBulananPengemasan::firstOrCreate(['pecahan' => $targetTahunan->pecahan, 'tahun_anggaran' => $targetTahunan->tahun_anggaran, 'tahun_emisi' => $targetTahunan->tahun_emisi]);

        return view('targets.edit', compact('targetTahunan', 'targetBulanan', 'targetPengemasan'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'pecahan' => 'required|string|max:10',
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'tahun_emisi' => 'required|integer|min:2000|max:2100',
            'target_tahunan' => 'required|numeric|min:0|max:50000000000',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            $data["bulan_{$i}"] = $request->validate(["bulan_{$i}" => 'required|numeric|min:0'])["bulan_{$i}"];
            $data["pengemasan_bulan_{$i}"] = $request->validate(["pengemasan_bulan_{$i}" => 'required|numeric|min:0'])["pengemasan_bulan_{$i}"];
        }

        $this->service->updateTarget($id, $data);

        return redirect()->route('targets.index')->with('success', 'Target berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->service->deleteTarget($id);
        return redirect()->route('targets.index')->with('success', 'Target berhasil dihapus.');
    }
}
