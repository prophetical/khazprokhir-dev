<?php

namespace App\Services;

use App\Models\HcsReceiving;
use App\Models\Pack;
use Illuminate\Support\Facades\DB;

class RekomendasiService
{
    public function getPenerimaanRecommendations(array $filters): \Illuminate\Support\Collection
    {
        $query = HcsReceiving::select('pecahan', 'batch', 'seri', 'tahun_anggaran', 'emisi', DB::raw('count(*) as total_receiving'))
            ->groupBy('pecahan', 'batch', 'seri', 'tahun_anggaran', 'emisi');

        if (!empty($filters['pecahan'])) $query->where('pecahan', $filters['pecahan']);
        if (!empty($filters['batch'])) $query->where('batch', 'like', '%'.$filters['batch'].'%');
        if (!empty($filters['seri'])) $query->where('seri', 'like', '%'.$filters['seri'].'%');

        return $query->get()->map(function ($item) {
            $unsorted = Pack::with('hcsReceiving')->where(['batch' => $item->batch, 'seri' => $item->seri])->whereNull('hcs_sorting_id')->get();
            $groups = $unsorted->groupBy(fn($p) => ceil($p->pack_number / 4));

            $existing = []; $recommended = [];
            foreach ($groups as $groupNum => $packs) {
                $count = $packs->count();
                if ($count >= 1 && $count < 4) {
                    $supplier = $packs->first()->supplier;
                    foreach ($packs as $p) {
                        $existing[] = ['number' => $p->pack_number, 'supplier' => $supplier, 'received_at' => $p->hcsReceiving ? $p->hcsReceiving->created_at->format('d M Y') : '-'];
                    }
                    $start = ($groupNum - 1) * 4 + 1; $end = $groupNum * 4;
                    $nums = $packs->pluck('pack_number')->toArray();
                    for ($i = $start; $i <= $end; $i++) {
                        if (!in_array($i, $nums)) $recommended[] = ['number' => $i, 'supplier' => $supplier, 'group' => (int)$groupNum];
                    }
                }
            }
            $item->existing_unsorted_single = $existing; $item->recommended_packs = $recommended;
            return $item;
        })->filter(fn($i) => count($i->recommended_packs) > 0);
    }

    public function getPenyortiranRecommendations(array $filters): array
    {
        $query = Pack::whereNull('hcs_sorting_id')->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id');
        if (!empty($filters['tahun_anggaran'])) $query->where('hcs_receivings.tahun_anggaran', $filters['tahun_anggaran']);
        if (!empty($filters['emisi'])) $query->where('hcs_receivings.emisi', $filters['emisi']);

        $raw = $query->select('hcs_receivings.pecahan', 'packs.batch', 'packs.seri', 'packs.pack_number', 'packs.supplier')
            ->orderBy('hcs_receivings.pecahan')->orderBy('packs.batch')->orderBy('packs.seri')->orderBy('packs.pack_number')->get();

        $grouped = [];
        foreach ($raw as $p) {
            $key = $p->pecahan.'|'.$p->batch.'|'.$p->seri.'|'.$p->supplier;
            if (!isset($grouped[$key])) {
                $grouped[$key] = ['pecahan' => $p->pecahan, 'batch' => $p->batch, 'seri' => $p->seri, 'packs' => [], 'supplier' => $p->supplier];
            }
            $grouped[$key]['packs'][] = $p->pack_number;
        }

        $recs = [];
        foreach ($grouped as $data) {
            $consecutive = $this->extractConsecutiveRanges($data['packs']);
            $validRanges = [];
            foreach ($consecutive as $range) {
                $sub = $this->extractValidSubRanges($range);
                if (!empty($sub)) $validRanges = array_merge($validRanges, $sub);
            }

            if (!empty($validRanges)) {
                $total = 0; $rangeStrings = []; $flat = [];
                foreach ($validRanges as $vr) {
                    $c = count($vr); $total += $c;
                    $start = $vr[0]; $end = $vr[$c - 1];
                    $rangeStrings[] = ($start === $end) ? $start : $start.'-'.$end;
                    $flat = array_merge($flat, $vr);
                }
                $recs[] = [
                    'pecahan' => $data['pecahan'], 'batch' => $data['batch'], 'seri' => $data['seri'], 'supplier' => $data['supplier'],
                    'ranges_raw' => $validRanges, 'ranges_str' => $rangeStrings, 'flat_packs' => $flat,
                    'total_pack' => $total, 'total_bilyet' => $total * 45000,
                ];
            }
        }

        if (!empty($filters['pecahan'])) $recs = array_filter($recs, fn($r) => $r['pecahan'] == $filters['pecahan']);
        if (!empty($filters['batch'])) $recs = array_filter($recs, fn($r) => str_contains($r['batch'], $filters['batch']));
        if (!empty($filters['seri'])) $recs = array_filter($recs, fn($r) => str_contains($r['seri'], $filters['seri']));

        return array_values($recs);
    }

    private function extractConsecutiveRanges(array $numbers): array
    {
        if (empty($numbers)) return [];
        $ranges = []; $current = [$numbers[0]];
        for ($i = 1; $i < count($numbers); $i++) {
            if ($numbers[$i] == $numbers[$i - 1] + 1) $current[] = $numbers[$i];
            else { $ranges[] = $current; $current = [$numbers[$i]]; }
        }
        if (!empty($current)) $ranges[] = $current;
        return $ranges;
    }

    private function extractValidSubRanges(array $consecutive): array
    {
        $start = -1;
        for ($i = 0; $i < count($consecutive); $i++) {
            if (($consecutive[$i] - 1) % 4 === 0) { $start = $i; break; }
        }
        if ($start === -1) return [];
        $sliced = array_slice($consecutive, $start);
        $len = floor(count($sliced) / 4) * 4;
        return ($len < 4) ? [] : [array_slice($sliced, 0, $len)];
    }
}
