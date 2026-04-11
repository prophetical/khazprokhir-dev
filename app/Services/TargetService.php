<?php

namespace App\Services;

use App\Models\TargetBulanan;
use App\Models\TargetBulananPengemasan;
use App\Models\TargetTahunan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TargetService
{
    public function listTargets(int $perPage = 20)
    {
        return TargetTahunan::orderBy('tahun_anggaran', 'desc')
            ->orderBy('pecahan', 'asc')
            ->simplePaginate(20);
    }

    public function storeTarget(array $data)
    {
        $this->validateUniqueness($data);

        return DB::transaction(function () use ($data) {
            $tt = TargetTahunan::create([
                'pecahan' => $data['pecahan'],
                'tahun_anggaran' => $data['tahun_anggaran'],
                'tahun_emisi' => $data['tahun_emisi'],
                'target' => $data['target_tahunan'],
            ]);

            $base = [
                'pecahan' => $data['pecahan'],
                'tahun_anggaran' => $data['tahun_anggaran'],
                'tahun_emisi' => $data['tahun_emisi'],
            ];

            $bulanan = $base;
            $pengemasan = $base;

            for ($i = 1; $i <= 12; $i++) {
                $bulanan["bulan_{$i}"] = $data["bulan_{$i}"];
                $pengemasan["bulan_{$i}"] = $data["pengemasan_bulan_{$i}"];
            }

            TargetBulanan::create($bulanan);
            TargetBulananPengemasan::create($pengemasan);

            return $tt;
        });
    }

    public function updateTarget(int $id, array $data)
    {
        $tt = TargetTahunan::findOrFail($id);
        $this->validateUniqueness($data, $id);

        $tb = TargetBulanan::where(['pecahan' => $tt->pecahan, 'tahun_anggaran' => $tt->tahun_anggaran, 'tahun_emisi' => $tt->tahun_emisi])->firstOrFail();
        $tp = TargetBulananPengemasan::firstOrCreate(['pecahan' => $tt->pecahan, 'tahun_anggaran' => $tt->tahun_anggaran, 'tahun_emisi' => $tt->tahun_emisi]);

        DB::transaction(function () use ($data, $tt, $tb, $tp) {
            $tt->update([
                'pecahan' => $data['pecahan'],
                'tahun_anggaran' => $data['tahun_anggaran'],
                'tahun_emisi' => $data['tahun_emisi'],
                'target' => $data['target_tahunan'],
            ]);

            $base = ['pecahan' => $data['pecahan'], 'tahun_anggaran' => $data['tahun_anggaran'], 'tahun_emisi' => $data['tahun_emisi']];
            $bulanan = $base; $pengemasan = $base;

            for ($i = 1; $i <= 12; $i++) {
                $bulanan["bulan_{$i}"] = $data["bulan_{$i}"];
                $pengemasan["bulan_{$i}"] = $data["pengemasan_bulan_{$i}"];
            }

            $tb->update($bulanan);
            $tp->update($pengemasan);
        });
    }

    public function deleteTarget(int $id)
    {
        $tt = TargetTahunan::findOrFail($id);

        DB::transaction(function () use ($tt) {
            $filter = ['pecahan' => $tt->pecahan, 'tahun_anggaran' => $tt->tahun_anggaran, 'tahun_emisi' => $tt->tahun_emisi];
            TargetBulanan::where($filter)->delete();
            TargetBulananPengemasan::where($filter)->delete();
            $tt->delete();
        });
    }

    protected function validateUniqueness(array $data, $excludeId = null)
    {
        $query = TargetTahunan::where([
            'pecahan' => $data['pecahan'],
            'tahun_anggaran' => $data['tahun_anggaran'],
            'tahun_emisi' => $data['tahun_emisi'],
        ]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'pecahan' => 'Kombinasi Pecahan, Tahun Anggaran, dan Tahun Emisi sudah terdaftar.'
            ]);
        }
    }
}
