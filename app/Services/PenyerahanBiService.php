<?php

namespace App\Services;

use App\Models\Pengemasan;
use App\Models\PenyerahanBi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class PenyerahanBiService
{
    public function getIncompletePenyerahanWarnings(): Collection
    {
        $candidates = PenyerahanBi::all();
        $warningsArray = [];

        foreach ($candidates as $p) {
            $rangeRequested = range($p->nomor_dus_awal, $p->nomor_dus_akhir);
            $pengemasans = Pengemasan::where('pecahan', $p->pecahan)
                ->where('tahun_emisi', $p->tahun_emisi)
                ->where('tahun_anggaran', $p->tahun_anggaran)
                ->where('dus_awal', '<=', $p->nomor_dus_akhir)
                ->where('dus_akhir', '>=', $p->nomor_dus_awal)
                ->get(['dus_awal', 'dus_akhir']);

            $existingNums = collect();
            foreach ($pengemasans as $pkg) {
                foreach (range($pkg->dus_awal, $pkg->dus_akhir) as $n) {
                    $existingNums->push($n);
                }
            }
            $existingNums = $existingNums->unique()->values();
            $missing = collect($rangeRequested)->filter(fn($n) => ! $existingNums->contains($n))->values();

            if ($missing->isEmpty()) {
                if ($p->status_data === 'Belum Lengkap') $p->update(['status_data' => 'Lengkap']);
                continue;
            }

            if ($p->status_data === 'Lengkap') $p->update(['status_data' => 'Belum Lengkap']);

            $warningsArray[] = [
                'id' => $p->id,
                'nomor_ba' => $p->nomor_ba,
                'pecahan' => $p->pecahan,
                'tahun_anggaran' => $p->tahun_anggaran,
                'tahun_emisi' => $p->tahun_emisi,
                'nomor_range' => $p->nomor_dus_awal.'–'.$p->nomor_dus_akhir,
                'missing_count' => $missing->count(),
                'missing_ranges' => $this->formatNomorDusToRanges($missing->toArray()),
            ];
        }

        return collect($warningsArray);
    }

    public function formatNomorDusToRanges(array $nums): string
    {
        if (empty($nums)) return '';
        sort($nums);
        $ranges = []; $start = $nums[0]; $prev = $nums[0];
        for ($i = 1; $i < count($nums); $i++) {
            if ($nums[$i] === $prev + 1) {
                $prev = $nums[$i];
            } else {
                $ranges[] = $start === $prev ? $start : "{$start}–{$prev}";
                $start = $nums[$i]; $prev = $nums[$i];
            }
        }
        $ranges[] = $start === $prev ? $start : "{$start}–{$prev}";
        return implode(', ', $ranges);
    }

    public function checkOverlap(array $data, ?int $excludeId = null)
    {
        $query = PenyerahanBi::where('pecahan', $data['pecahan'])
            ->where('tahun_emisi', $data['tahun_emisi'])
            ->where('tahun_anggaran', $data['tahun_anggaran'])
            ->where('nomor_dus_awal', '<=', (int) $data['nomor_dus_akhir'])
            ->where('nomor_dus_akhir', '>=', (int) $data['nomor_dus_awal']);

        if ($excludeId) $query->where('id', '!=', $excludeId);

        return $query->first();
    }

    public function processUpsert(array $data, ?int $id = null, int $userId)
    {
        $awal = (int) $data['nomor_dus_awal'];
        $akhir = (int) $data['nomor_dus_akhir'];
        $jumlah = $akhir - $awal + 1;

        $covering = Pengemasan::where('pecahan', $data['pecahan'])
            ->where('tahun_emisi', $data['tahun_emisi'])
            ->where('tahun_anggaran', $data['tahun_anggaran'])
            ->get(['dus_awal', 'dus_akhir']);

        $existing = collect();
        foreach ($covering as $p) {
            foreach (range($p->dus_awal, $p->dus_akhir) as $n) $existing->push($n);
        }
        $existing = $existing->unique();
        $jumlahAda = collect(range($awal, $akhir))->filter(fn($n) => $existing->contains($n))->count();
        $statusData = ($jumlah - $jumlahAda) === 0 ? 'Lengkap' : 'Belum Lengkap';

        $payload = [
            'tanggal_penyerahan' => $data['tanggal_penyerahan'],
            'nomor_ba' => $data['nomor_ba'],
            'pecahan' => $data['pecahan'],
            'tahun_emisi' => $data['tahun_emisi'],
            'tahun_anggaran' => $data['tahun_anggaran'],
            'nomor_dus_awal' => $awal,
            'nomor_dus_akhir' => $akhir,
            'jumlah_dus' => $jumlah,
            'jumlah_bilyet' => $data['jumlah_bilyet'],
            'status_data' => $statusData,
        ];

        if ($id) {
            $penyerahan = PenyerahanBi::findOrFail($id);
            $penyerahan->update($payload);
        } else {
            $payload['created_by'] = $userId;
            $penyerahan = PenyerahanBi::create($payload);
        }

        $jumlahBelumAda = $jumlah - $jumlahAda;
        if ($jumlahBelumAda > 0) {
            session()->flash('warning_penyerahan', [
                'range_diminta' => "{$awal} – {$akhir}",
                'jumlah_diminta' => $jumlah,
                'jumlah_ada' => $jumlahAda,
                'jumlah_belum_ada' => $jumlahBelumAda,
                'estimasi_bilyet' => $jumlahBelumAda * 45000,
            ]);
        }

        return $penyerahan;
    }

    public function getAvailableFilterOptions(): array
    {
        return [
            'years' => DB::table('hcs_receivings')->distinct()->whereNotNull('tahun_anggaran')->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran'),
            'emissions' => DB::table('hcs_receivings')->distinct()->whereNotNull('emisi')->orderBy('emisi', 'desc')->pluck('emisi'),
        ];
    }
}
