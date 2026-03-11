<?php

namespace App\Http\Controllers;

use App\Models\TargetTahunan;
use App\Models\TargetBulanan;
use App\Models\TargetBulananPengemasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    /**
     * Display a listing of targets.
     */
    public function index()
    {
        $targets = TargetTahunan::orderBy('tahun_anggaran', 'desc')
            ->orderBy('pecahan', 'asc')
            ->paginate(15);

        return view('targets.index', compact('targets'));
    }

    /**
     * Show the form for creating a new target.
     */
    public function create()
    {
        return view('targets.create');
    }

    /**
     * Store a newly created target in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pecahan' => 'required|string|max:10',
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'tahun_emisi' => 'required|integer|min:2000|max:2100',
            'target_tahunan' => 'required|numeric|min:0|max:50000000000',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            $request->validate([
                "bulan_{$i}" => 'required|numeric|min:0',
                "pengemasan_bulan_{$i}" => 'required|numeric|min:0',
            ]);
        }

        // Check uniqueness
        $exists = TargetTahunan::where([
            'pecahan' => $request->pecahan,
            'tahun_anggaran' => $request->tahun_anggaran,
            'tahun_emisi' => $request->tahun_emisi,
        ])->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['pecahan' => 'Kombinasi Pecahan, Tahun Anggaran, dan Tahun Emisi sudah terdaftar.']);
        }

        DB::transaction(function () use ($request) {
            TargetTahunan::create([
                'pecahan' => $request->pecahan,
                'tahun_anggaran' => $request->tahun_anggaran,
                'tahun_emisi' => $request->tahun_emisi,
                'target' => $request->target_tahunan,
            ]);

            $targetBulananData = [
                'pecahan' => $request->pecahan,
                'tahun_anggaran' => $request->tahun_anggaran,
                'tahun_emisi' => $request->tahun_emisi,
            ];

            $targetPengemasanData = $targetBulananData;

            for ($i = 1; $i <= 12; $i++) {
                $targetBulananData["bulan_{$i}"] = $request->input("bulan_{$i}");
                $targetPengemasanData["bulan_{$i}"] = $request->input("pengemasan_bulan_{$i}");
            }

            TargetBulanan::create($targetBulananData);
            TargetBulananPengemasan::create($targetPengemasanData);
        });

        return redirect()->route('targets.index')->with('success', 'Target berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified target.
     */
    public function edit($id)
    {
        $targetTahunan = TargetTahunan::findOrFail($id);
        $targetBulanan = TargetBulanan::where([
            'pecahan' => $targetTahunan->pecahan,
            'tahun_anggaran' => $targetTahunan->tahun_anggaran,
            'tahun_emisi' => $targetTahunan->tahun_emisi,
        ])->firstOrFail();

        $targetPengemasan = TargetBulananPengemasan::firstOrCreate([
            'pecahan' => $targetTahunan->pecahan,
            'tahun_anggaran' => $targetTahunan->tahun_anggaran,
            'tahun_emisi' => $targetTahunan->tahun_emisi,
        ]);

        return view('targets.edit', compact('targetTahunan', 'targetBulanan', 'targetPengemasan'));
    }

    /**
     * Update the specified target in storage.
     */
    public function update(Request $request, $id)
    {
        $targetTahunan = TargetTahunan::findOrFail($id);
        $targetBulanan = TargetBulanan::where([
            'pecahan' => $targetTahunan->pecahan,
            'tahun_anggaran' => $targetTahunan->tahun_anggaran,
            'tahun_emisi' => $targetTahunan->tahun_emisi,
        ])->firstOrFail();

        $targetPengemasan = TargetBulananPengemasan::firstOrCreate([
            'pecahan' => $targetTahunan->pecahan,
            'tahun_anggaran' => $targetTahunan->tahun_anggaran,
            'tahun_emisi' => $targetTahunan->tahun_emisi,
        ]);

        $request->validate([
            'pecahan' => 'required|string|max:10',
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'tahun_emisi' => 'required|integer|min:2000|max:2100',
            'target_tahunan' => 'required|numeric|min:0|max:50000000000',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            $request->validate([
                "bulan_{$i}" => 'required|numeric|min:0',
                "pengemasan_bulan_{$i}" => 'required|numeric|min:0',
            ]);
        }

        // Check uniqueness excluding current
        $exists = TargetTahunan::where('id', '!=', $id)
            ->where([
            'pecahan' => $request->pecahan,
            'tahun_anggaran' => $request->tahun_anggaran,
            'tahun_emisi' => $request->tahun_emisi,
        ])->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['pecahan' => 'Kombinasi Pecahan, Tahun Anggaran, dan Tahun Emisi sudah terdaftar pada data lain.']);
        }

        DB::transaction(function () use ($request, $targetTahunan, $targetBulanan, $targetPengemasan) {
            $targetTahunan->update([
                'pecahan' => $request->pecahan,
                'tahun_anggaran' => $request->tahun_anggaran,
                'tahun_emisi' => $request->tahun_emisi,
                'target' => $request->target_tahunan,
            ]);

            $targetBulananData = [
                'pecahan' => $request->pecahan,
                'tahun_anggaran' => $request->tahun_anggaran,
                'tahun_emisi' => $request->tahun_emisi,
            ];

            $targetPengemasanData = $targetBulananData;

            for ($i = 1; $i <= 12; $i++) {
                $targetBulananData["bulan_{$i}"] = $request->input("bulan_{$i}");
                $targetPengemasanData["bulan_{$i}"] = $request->input("pengemasan_bulan_{$i}");
            }

            $targetBulanan->update($targetBulananData);
            $targetPengemasan->update($targetPengemasanData);
        });

        return redirect()->route('targets.index')->with('success', 'Target berhasil diperbarui.');
    }

    /**
     * Remove the specified target from storage.
     */
    public function destroy($id)
    {
        $targetTahunan = TargetTahunan::findOrFail($id);

        DB::transaction(function () use ($targetTahunan) {
            $filter = [
                'pecahan' => $targetTahunan->pecahan,
                'tahun_anggaran' => $targetTahunan->tahun_anggaran,
                'tahun_emisi' => $targetTahunan->tahun_emisi,
            ];

            TargetBulanan::where($filter)->delete();
            TargetBulananPengemasan::where($filter)->delete();

            $targetTahunan->delete();
        });

        return redirect()->route('targets.index')->with('success', 'Target berhasil dihapus.');
    }
}
