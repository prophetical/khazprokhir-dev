<?php

namespace App\Services;

use App\Models\DetailPengemasan;
use App\Models\Pack;
use App\Models\Pengemasan;
use App\Models\HcsSorting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class PengemasanService
{
    public function getReadyToPackageGroupsQuery(array $filters = [])
    {
        // Algoritma Islands and Gaps menggunakan SQL untuk menemukan nomor pack yang berurutan.
        $rawQuery = Pack::query()
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->whereNotNull('packs.hcs_sorting_id')
            ->whereNull('packs.id_pengemasan')
            ->select([
                'packs.pack_number',
                'packs.batch',
                'packs.seri',
                'packs.jumlah',
                'hcs_receivings.pecahan',
                'hcs_receivings.emisi',
                'hcs_receivings.tahun_anggaran',
                DB::raw('packs.pack_number - ROW_NUMBER() OVER (
                    PARTITION BY packs.batch, packs.seri, hcs_receivings.pecahan, hcs_receivings.tahun_anggaran, hcs_receivings.emisi 
                    ORDER BY packs.pack_number
                ) as island_id')
            ]);

        if (!empty($filters['pecahan'])) {
            $rawQuery->where('hcs_receivings.pecahan', $filters['pecahan']);
        }
        if (!empty($filters['tahun_anggaran'])) {
            $rawQuery->where('hcs_receivings.tahun_anggaran', $filters['tahun_anggaran']);
        }
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $rawQuery->where(function ($q) use ($s) {
                $q->where('packs.batch', 'like', "%{$s}%")
                    ->orWhere('packs.seri', 'like', "%{$s}%");
            });
        }

        // Membungkus perhitungan dasar island ke dalam query ringkasan.
        $summaryQuery = DB::table(DB::raw("({$rawQuery->toSql()}) as clusters"))
            ->mergeBindings($rawQuery->getQuery())
            ->select([
                'pecahan',
                'emisi',
                'tahun_anggaran',
                'batch',
                'seri',
                DB::raw('MIN(pack_number) as pack_awal'),
                DB::raw('MAX(pack_number) as pack_akhir'),
                DB::raw('COUNT(*) as jumlah_pack'),
                DB::raw('(COUNT(*) % 4 != 0 OR MIN(jumlah) < 45000) as has_buntut')
            ])
            ->groupBy('pecahan', 'emisi', 'tahun_anggaran', 'batch', 'seri', 'island_id')
            ->orderBy('batch')
            ->orderBy('seri')
            ->orderBy('pack_awal');

        return $summaryQuery;
    }

    public function getReadyToPackageGroups(array $filters = [])
    {
        // Dukungan untuk metode lama yang mengharapkan hasil dalam bentuk koleksi.
        return $this->getReadyToPackageGroupsQuery($filters)->get()->map(fn($item) => (array) $item);
    }

    public function processStore(array $data, int $userId)
    {
        $input = $this->parseSelectedInput($data);
        $packNumbers = $input['packNumbers'];
        $parsedChunks = $input['parsedChunks'];

        $packs = $this->getAndValidatePacks($data, $packNumbers);
        $jumlahPack = count($packNumbers);

        [$nomorDusAwal, $nomorDusAkhir, $jumlahDus] = $this->getDusRange($data, $jumlahPack);

        $this->checkDusAvailability($data, $nomorDusAwal, $nomorDusAkhir);

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
            if ($sortingIds->isNotEmpty())
                HcsSorting::whereIn('id', $sortingIds)->update(['status_kunci_pengemasan' => 1]);

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
            Cache::forget('hcs_ready_notifications');
            return $pengemasan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processDestroy(Pengemasan $pengemasan)
    {
        DB::beginTransaction();
        try {
            $sortingIds = Pack::where('id_pengemasan', $pengemasan->id)->pluck('hcs_sorting_id')->filter()->unique();
            Pack::where('id_pengemasan', $pengemasan->id)->update(['id_pengemasan' => null]);

            foreach ($sortingIds as $sId) {
                if (!Pack::where('hcs_sorting_id', $sId)->whereNotNull('id_pengemasan')->exists()) {
                    HcsSorting::where('id', $sId)->update(['status_kunci_pengemasan' => 0]);
                }
            }
            DetailPengemasan::where('id_pengemasan', $pengemasan->id)->delete();
            $pengemasan->delete();
            DB::commit();
            Cache::forget('hcs_ready_notifications');
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function detectMissingDusGaps(): array
    {
        // Menggunakan SQL Window Functions untuk algoritma Islands and Gaps.
        // 1. Mengambil celah (gaps) di antara nomor-nomor yang sudah ada.
        $gaps = DB::table(function ($query) {
            $query->from('detail_pengemasans as dp_inner')
                ->join('pengemasans as p_inner', 'dp_inner.id_pengemasan', '=', 'p_inner.id')
                ->select([
                    'p_inner.pecahan',
                    'p_inner.tahun_anggaran',
                    'p_inner.tahun_emisi',
                    'dp_inner.no_dus',
                    DB::raw('LEAD(dp_inner.no_dus) OVER (PARTITION BY p_inner.pecahan, p_inner.tahun_anggaran, p_inner.tahun_emisi ORDER BY dp_inner.no_dus) as next_no')
                ]);
        }, 'tmp')
            ->select([
                'pecahan',
                'tahun_anggaran',
                'tahun_emisi',
                DB::raw('no_dus + 1 as gap_start'),
                DB::raw('next_no - 1 as gap_end')
            ])
            ->whereRaw('next_no > no_dus + 1')
            ->get();

        // 2. Memeriksa apakah dus pertama hilang (dimulai dari nomor 1).
        $starts = DB::table('detail_pengemasans as dp')
            ->join('pengemasans as p', 'dp.id_pengemasan', '=', 'p.id')
            ->select('p.pecahan', 'p.tahun_anggaran', 'p.tahun_emisi', DB::raw('MIN(dp.no_dus) as first_no'))
            ->groupBy('p.pecahan', 'p.tahun_anggaran', 'p.tahun_emisi')
            ->havingRaw('MIN(dp.no_dus) > 1')
            ->get();

        $rawGaps = [];
        foreach ($starts as $s) {
            $key = "{$s->pecahan}|{$s->tahun_anggaran}|{$s->tahun_emisi}";
            $range = ($s->first_no == 2) ? "1" : "1-" . ($s->first_no - 1);
            $rawGaps[$key][] = $range;
        }

        foreach ($gaps as $g) {
            $key = "{$g->pecahan}|{$g->tahun_anggaran}|{$g->tahun_emisi}";
            $range = ($g->gap_start == $g->gap_end) ? $g->gap_start : "{$g->gap_start}-{$g->gap_end}";
            $rawGaps[$key][] = $range;
        }

        $formatted = [];
        foreach ($rawGaps as $key => $ranges) {
            [$pecahan, $ta, $te] = explode('|', $key);
            $formatted[] = [
                'pecahan' => $pecahan,
                'tahun_anggaran' => $ta,
                'tahun_emisi' => $te,
                'ranges' => implode(', ', $ranges)
            ];
        }

        return $formatted;
    }

    private function generateDetailPengemasan($pengemasan, $seriRaw, $startNumber, $batch, $parsedChunks, $packs)
    {
        $parts = explode('-', $seriRaw);
        $seriAwalPrefix = $parts[0] ?? '';
        $seriAkhirFull = $parts[1] ?? '';
        preg_match('/^[A-Za-z]+/', $seriAkhirFull, $matches);
        $seriAkhirPrefix = $matches[0] ?? $seriAwalPrefix;

        $p1Awal = $seriAwalPrefix . 'A';
        $p1Akhir = $seriAwalPrefix . 'U';
        $p2Awal = $seriAkhirPrefix . 'A';
        $p2Akhir = $seriAkhirPrefix . 'U';
        $p3Awal = $seriAwalPrefix . 'V';
        $p3Akhir = $seriAkhirPrefix . 'Z';

        $currentNoDus = $startNumber;
        foreach ($parsedChunks as $chunk) {
            $p1 = $chunk['awal'];
            $p2 = $p1 + 1;
            $p3 = $p1 + 2;
            $p4 = $p1 + 3;
            $chunkBilyet = $packs->whereIn('pack_number', [$p1, $p2, $p3, $p4])->sum('jumlah');
            $bilyetPerDus = floor($chunkBilyet / 9);
            $sisaBilyet = $chunkBilyet % 9;

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

    private function parseSelectedInput(array $data): array
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
                $cAwal = (int) $parts[0];
                $cAkhir = (int) $parts[1];
                $parsedChunks[] = ['awal' => $cAwal, 'akhir' => $cAkhir];
                for ($i = $cAwal; $i <= $cAkhir; $i++)
                    $packNumbers[] = $i;
            }
        }
        foreach ($selectedPacksInput as $pNum) {
            $pNum = (int) $pNum;
            $packNumbers[] = $pNum;
            $parsedChunks[] = ['awal' => $pNum, 'akhir' => $pNum];
        }

        return [
            'packNumbers' => array_unique($packNumbers),
            'parsedChunks' => $parsedChunks,
        ];
    }

    private function getAndValidatePacks(array $data, array $packNumbers): \Illuminate\Database\Eloquent\Collection
    {
        $jumlahPack = count($packNumbers);

        if (!($data['is_manual_sisa'] ?? false)) {
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
            if (is_null($pack->hcs_sorting_id))
                throw ValidationException::withMessages(['selected_packs' => "Pack {$pack->pack_number} belum disortir."]);
            if (!is_null($pack->id_pengemasan))
                throw ValidationException::withMessages(['selected_packs' => "Pack {$pack->pack_number} sudah dikemas."]);
        }

        return $packs;
    }

    private function getDusRange(array $data, int $jumlahPack): array
    {
        $jumlahDus = ($data['is_manual_sisa'] ?? false) ? count($data['manual_details'] ?? []) : ($jumlahPack / 4) * 9;

        if ($data['is_manual'] ?? false) {
            $nomorDusAwal = (int) $data['dus_awal'];
            $nomorDusAkhir = (int) $data['dus_akhir'];
            if (($nomorDusAkhir - $nomorDusAwal + 1) !== $jumlahDus)
                throw ValidationException::withMessages(['dus_awal' => "Range dus tidak sesuai. Harus $jumlahDus dus."]);
        } else {
            if ($data['is_manual_sisa'] ?? false) {
                $sudahAdaNoDus = array_column($data['manual_details'], 'no_dus');
                $nomorDusAwal = min($sudahAdaNoDus);
                $nomorDusAkhir = max($sudahAdaNoDus);
            } else {
                $lastDus = DetailPengemasan::whereHas('pengemasan', function ($q) use ($data) {
                    $q->where('pecahan', $data['pecahan'])->where('tahun_anggaran', $data['tahun_anggaran'])->where('tahun_emisi', $data['tahun_emisi']);
                })->orderBy('no_dus', 'desc')->first();
                $nomorDusAwal = $lastDus ? $lastDus->no_dus + 1 : 1;
                $nomorDusAkhir = $nomorDusAwal + $jumlahDus - 1;
            }
        }

        return [$nomorDusAwal, $nomorDusAkhir, $jumlahDus];
    }

    private function checkDusAvailability(array $data, int $nomorDusAwal, int $nomorDusAkhir): void
    {
        $usedDusExists = DetailPengemasan::whereHas('pengemasan', function ($q) use ($data) {
            $q->where('pecahan', $data['pecahan'])->where('tahun_anggaran', $data['tahun_anggaran'])->where('tahun_emisi', $data['tahun_emisi']);
        })->where(function ($q) use ($data, $nomorDusAwal, $nomorDusAkhir) {
            if ($data['is_manual_sisa'] ?? false) {
                $q->whereIn('no_dus', array_column($data['manual_details'], 'no_dus'));
            } else {
                $q->whereBetween('no_dus', [$nomorDusAwal, $nomorDusAkhir]);
            }
        })->exists();

        if ($usedDusExists)
            throw ValidationException::withMessages(['dus_awal' => 'Satu atau lebih nomor dus sudah digunakan.']);
    }
}
