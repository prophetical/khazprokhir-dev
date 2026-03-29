<?php

namespace App\Services;

use App\Models\DetailPengemasan;
use App\Models\Pack;
use App\Models\Pengemasan;
use App\Models\HcsSorting;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengemasanService
{
    public function getReadyToPackageGroups(array $filters = [])
    {
        $query = Pack::whereNotNull('hcs_sorting_id')
            ->whereNull('id_pengemasan')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->select(
                'packs.pack_number',
                'packs.batch',
                'packs.seri',
                'hcs_receivings.pecahan',
                'hcs_receivings.emisi',
                'hcs_receivings.tahun_anggaran'
            );

        if (! empty($filters['pecahan'])) {
            $query->where('hcs_receivings.pecahan', $filters['pecahan']);
        }
        if (! empty($filters['tahun_anggaran'])) {
            $query->where('hcs_receivings.tahun_anggaran', $filters['tahun_anggaran']);
        }
        if (! empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('packs.batch', 'like', "%{$s}%")
                    ->orWhere('packs.seri', 'like', "%{$s}%")
                    ->orWhere('hcs_receivings.pecahan', 'like', "%{$s}%");
            });
        }

        $packs = $query
            ->orderBy('hcs_receivings.tahun_anggaran')
            ->orderBy('hcs_receivings.emisi')
            ->orderBy('hcs_receivings.pecahan')
            ->orderBy('packs.batch')
            ->orderBy('packs.seri')
            ->orderBy('packs.pack_number')
            ->get();

        $grouped = [];
        foreach ($packs as $pack) {
            $key = "{$pack->tahun_anggaran}|{$pack->emisi}|{$pack->pecahan}|{$pack->batch}|{$pack->seri}";
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'tahun_anggaran' => $pack->tahun_anggaran,
                    'emisi' => $pack->emisi,
                    'pecahan' => $pack->pecahan,
                    'batch' => $pack->batch,
                    'seri' => $pack->seri,
                    'numbers' => [],
                ];
            }
            $grouped[$key]['numbers'][] = $pack->pack_number;
        }

        $readyGroups = [];
        foreach ($grouped as $group) {
            $numbers = $group['numbers'];
            if (empty($numbers)) continue;

            $contiguousBlocks = [];
            $currentBlock = [];
            foreach ($numbers as $num) {
                if (empty($currentBlock)) {
                    $currentBlock[] = $num;
                } else {
                    $last = end($currentBlock);
                    if ($num == $last + 1) {
                        $currentBlock[] = $num;
                    } else {
                        $contiguousBlocks[] = $currentBlock;
                        $currentBlock = [$num];
                    }
                }
            }
            if (! empty($currentBlock)) $contiguousBlocks[] = $currentBlock;

            foreach ($contiguousBlocks as $block) {
                $totalInBlock = count($block);
                if ($totalInBlock < 1) continue;

                $packIds = Pack::where('batch', $group['batch'])
                    ->where('seri', $group['seri'])
                    ->whereNotNull('hcs_sorting_id')
                    ->whereNull('id_pengemasan')
                    ->whereIn('pack_number', $block)
                    ->pluck('id');

                $hasBuntut = ($totalInBlock % 4 !== 0) || Pack::whereIn('id', $packIds)->where('jumlah', '<', 45000)->exists();

                $readyGroups[] = [
                    'tahun_anggaran' => $group['tahun_anggaran'],
                    'emisi' => $group['emisi'],
                    'pecahan' => $group['pecahan'],
                    'batch' => $group['batch'],
                    'seri' => $group['seri'],
                    'pack_awal' => $block[0],
                    'pack_akhir' => end($block),
                    'jumlah_pack' => $totalInBlock,
                    'has_buntut' => $hasBuntut,
                ];
            }
        }

        return collect($readyGroups);
    }

    public function processStore(array $data, int $userId)
    {
        $packNumbers = [];
        $parsedChunks = [];
        $selectedChunksInput = $data['selected_chunks'] ?? [];
        $selectedPacksInput = $data['selected_packs'] ?? [];

        usort($selectedChunksInput, function ($a, $b) {
            return (int) explode('-', $a)[0] <=> (int) explode('-', $b)[0];
        });

        foreach ($selectedChunksInput as $chunkStr) {
            $parts = explode('-', $chunkStr);
            if (count($parts) == 2) {
                $cAwal = (int) $parts[0]; $cAkhir = (int) $parts[1];
                $parsedChunks[] = ['awal' => $cAwal, 'akhir' => $cAkhir];
                for ($i = $cAwal; $i <= $cAkhir; $i++) $packNumbers[] = $i;
            }
        }
        foreach ($selectedPacksInput as $pNum) {
            $pNum = (int) $pNum;
            $packNumbers[] = $pNum;
            $parsedChunks[] = ['awal' => $pNum, 'akhir' => $pNum];
        }

        $packNumbers = array_unique($packNumbers);
        $jumlahPack = count($packNumbers);

        if (! ($data['is_manual_sisa'] ?? false)) {
            if ($jumlahPack <= 0 || $jumlahPack % 4 !== 0) {
                $field = isset($data['selected_chunks']) ? 'selected_chunks' : 'selected_packs';
                throw ValidationException::withMessages([$field => "Jumlah pack ($jumlahPack) tidak valid. Harus kelipatan 4."]);
            }
        }

        $packs = Pack::where('packs.batch', $data['batch'])
            ->where('packs.seri', $data['seri'])
            ->whereIn('packs.pack_number', $packNumbers)
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.pecahan', $data['pecahan'])
            ->where('hcs_receivings.emisi', $data['tahun_emisi'])
            ->where('hcs_receivings.tahun_anggaran', $data['tahun_anggaran'])
            ->select('packs.*')
            ->get();

        if ($packs->count() !== $jumlahPack) {
            throw ValidationException::withMessages(['selected_packs' => 'Ketidaksesuaian jumlah pack.']);
        }

        foreach ($packs as $pack) {
            if (is_null($pack->hcs_sorting_id)) throw ValidationException::withMessages(['selected_packs' => "Pack {$pack->pack_number} belum disortir."]);
            if (! is_null($pack->id_pengemasan)) throw ValidationException::withMessages(['selected_packs' => "Pack {$pack->pack_number} sudah dikemas."]);
        }

        $jumlahDus = ($data['is_manual_sisa'] ?? false) ? count($data['manual_details'] ?? []) : ($jumlahPack / 4) * 9;
        
        if ($data['is_manual'] ?? false) {
            $nomorDusAwal = (int) $data['dus_awal'];
            $nomorDusAkhir = (int) $data['dus_akhir'];
            if (($nomorDusAkhir - $nomorDusAwal + 1) !== $jumlahDus) throw ValidationException::withMessages(['dus_awal' => "Range dus tidak sesuai. Harus $jumlahDus dus."]);
        } else {
            if ($data['is_manual_sisa'] ?? false) {
                $sudahAdaNoDus = array_column($data['manual_details'], 'no_dus');
                $nomorDusAwal = min($sudahAdaNoDus); $nomorDusAkhir = max($sudahAdaNoDus);
            } else {
                $lastDus = DetailPengemasan::whereHas('pengemasan', function ($q) use ($data) {
                    $q->where('pecahan', $data['pecahan'])->where('tahun_anggaran', $data['tahun_anggaran'])->where('tahun_emisi', $data['tahun_emisi']);
                })->orderBy('no_dus', 'desc')->first();
                $nomorDusAwal = $lastDus ? $lastDus->no_dus + 1 : 1;
                $nomorDusAkhir = $nomorDusAwal + $jumlahDus - 1;
            }
        }

        $usedDusExists = DetailPengemasan::whereHas('pengemasan', function ($q) use ($data) {
            $q->where('pecahan', $data['pecahan'])->where('tahun_anggaran', $data['tahun_anggaran'])->where('tahun_emisi', $data['tahun_emisi']);
        })->where(function($q) use ($data, $nomorDusAwal, $nomorDusAkhir) {
            if ($data['is_manual_sisa'] ?? false) {
                $q->whereIn('no_dus', array_column($data['manual_details'], 'no_dus'));
            } else {
                $q->whereBetween('no_dus', [$nomorDusAwal, $nomorDusAkhir]);
            }
        })->exists();

        if ($usedDusExists) throw ValidationException::withMessages(['dus_awal' => 'Satu atau lebih nomor dus sudah digunakan.']);

        DB::beginTransaction();
        try {
            $pengemasan = Pengemasan::create([
                'tanggal_pengemasan' => $data['tanggal_pengemasan'],
                'gilir' => $data['gilir'],
                'tahun_anggaran' => $data['tahun_anggaran'],
                'tahun_emisi' => $data['tahun_emisi'],
                'pecahan' => $data['pecahan'],
                'batch' => $data['batch'],
                'seri' => $data['seri'],
                'pack_awal' => min($packNumbers),
                'pack_akhir' => max($packNumbers),
                'jumlah_pack' => $jumlahPack,
                'jumlah_dus' => $jumlahDus,
                'total_bilyet' => $packs->sum('jumlah'),
                'dus_awal' => $nomorDusAwal,
                'dus_akhir' => $nomorDusAkhir,
                'created_by' => $userId,
            ]);

            Pack::whereIn('id', $packs->pluck('id'))->update(['id_pengemasan' => $pengemasan->id]);
            $sortingIds = $packs->pluck('hcs_sorting_id')->filter()->unique();
            if ($sortingIds->isNotEmpty()) HcsSorting::whereIn('id', $sortingIds)->update(['status_kunci_pengemasan' => 1]);

            if ($data['is_manual_sisa'] ?? false) {
                foreach ($data['manual_details'] as $detail) {
                    DetailPengemasan::create([
                        'id_pengemasan' => $pengemasan->id,
                        'no_dus' => $detail['no_dus'],
                        'pack_awal' => $detail['pack_awal'] ?? $pengemasan->pack_awal,
                        'pack_akhir' => $detail['pack_akhir'] ?? $pengemasan->pack_akhir,
                        'seri_awal' => $detail['seri_awal'] ?? $data['seri'],
                        'seri_akhir' => $detail['seri_akhir'] ?? $data['seri'],
                        'batch' => $data['batch'],
                        'jumlah_bilyet' => $detail['jumlah_bilyet'] ?? 0,
                    ]);
                }
            } else {
                $this->generateDetailPengemasan($pengemasan, $data['seri'], $nomorDusAwal, $data['batch'], $parsedChunks, $packs);
            }
            DB::commit();
            return $pengemasan;
        } catch (\Exception $e) {
            DB::rollBack(); throw $e;
        }
    }

    public function processDestroy(Pengemasan $pengemasan)
    {
        DB::beginTransaction();
        try {
            $sortingIds = Pack::where('id_pengemasan', $pengemasan->id)->pluck('hcs_sorting_id')->filter()->unique();
            Pack::where('id_pengemasan', $pengemasan->id)->update(['id_pengemasan' => null]);

            foreach ($sortingIds as $sId) {
                if (! Pack::where('hcs_sorting_id', $sId)->whereNotNull('id_pengemasan')->exists()) {
                    HcsSorting::where('id', $sId)->update(['status_kunci_pengemasan' => 0]);
                }
            }
            DetailPengemasan::where('id_pengemasan', $pengemasan->id)->delete();
            $pengemasan->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack(); throw $e;
        }
    }

    public function detectMissingDusGaps(): array
    {
        $detailList = DB::table('detail_pengemasans')
            ->join('pengemasans', 'detail_pengemasans.id_pengemasan', '=', 'pengemasans.id')
            ->select('pengemasans.pecahan', 'pengemasans.tahun_anggaran', 'pengemasans.tahun_emisi', 'detail_pengemasans.no_dus')
            ->orderBy('pengemasans.pecahan')->orderBy('pengemasans.tahun_anggaran')->orderBy('pengemasans.tahun_emisi')->orderBy('detail_pengemasans.no_dus')
            ->get();

        $groupedDus = [];
        foreach ($detailList as $d) {
            $key = $d->pecahan.'|'.$d->tahun_anggaran.'|'.$d->tahun_emisi;
            $groupedDus[$key][] = $d->no_dus;
        }

        $missingGaps = [];
        foreach ($groupedDus as $key => $numbers) {
            [$pecahan, $ta, $te] = explode('|', $key);
            $expected = 1; $missingRanges = [];
            foreach ($numbers as $num) {
                if ($num > $expected) {
                    $startGap = $expected; $endGap = $num - 1;
                    $missingRanges[] = ($startGap == $endGap) ? $startGap : $startGap.'-'.$endGap;
                }
                if ($num >= $expected) $expected = $num + 1;
            }
            if (! empty($missingRanges)) $missingGaps[] = ['pecahan' => $pecahan, 'tahun_anggaran' => $ta, 'tahun_emisi' => $te, 'gaps' => implode(', ', $missingRanges)];
        }
        return $missingGaps;
    }

    private function generateDetailPengemasan($pengemasan, $seriRaw, $startNumber, $batch, $parsedChunks, $packs)
    {
        $parts = explode('-', $seriRaw);
        $seriAwalPrefix = $parts[0] ?? ''; $seriAkhirFull = $parts[1] ?? '';
        preg_match('/^[A-Za-z]+/', $seriAkhirFull, $matches);
        $seriAkhirPrefix = $matches[0] ?? $seriAwalPrefix;

        $p1Awal = $seriAwalPrefix.'A'; $p1Akhir = $seriAwalPrefix.'U';
        $p2Awal = $seriAkhirPrefix.'A'; $p2Akhir = $seriAkhirPrefix.'U';
        $p3Awal = $seriAwalPrefix.'V'; $p3Akhir = $seriAkhirPrefix.'Z';

        $currentNoDus = $startNumber;
        foreach ($parsedChunks as $chunk) {
            $p1 = $chunk['awal']; $p2 = $p1 + 1; $p3 = $p1 + 2; $p4 = $p1 + 3;
            $chunkBilyet = $packs->whereIn('pack_number', [$p1, $p2, $p3, $p4])->sum('jumlah');
            $bilyetPerDus = floor($chunkBilyet / 9); $sisaBilyet = $chunkBilyet % 9;

            $this->createDusRow($pengemasan->id, $currentNoDus++, $p1, $p4, $p3Awal, $p3Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p1, $p1, $p2Awal, $p2Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p1, $p1, $p1Awal, $p1Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p2, $p2, $p2Awal, $p2Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p2, $p2, $p1Awal, $p1Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p3, $p3, $p2Awal, $p2Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p3, $p3, $p1Awal, $p1Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p4, $p4, $p2Awal, $p2Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
            $this->createDusRow($pengemasan->id, $currentNoDus++, $p4, $p4, $p1Awal, $p1Akhir, $batch, $bilyetPerDus + ($sisaBilyet-- > 0 ? 1 : 0));
        }
    }

    private function createDusRow($id, $no, $pA, $pK, $sA, $sK, $b, $jml)
    {
        DetailPengemasan::create(['id_pengemasan' => $id, 'no_dus' => $no, 'pack_awal' => $pA, 'pack_akhir' => $pK, 'seri_awal' => $sA, 'seri_akhir' => $sK, 'batch' => $b, 'jumlah_bilyet' => $jml]);
    }
}
