<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HcsPengemasanLanjutanSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('docs/migrasi_pengemasan_lanjutan.csv');

        $csvLines = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (count($csvLines) <= 1) {
            $this->command->warn('CSV lanjutan tidak ditemukan atau kosong.');
            return;
        }
        array_shift($csvLines);

        // Collect pack keys from CSV
        $csvPackKeys = [];
        foreach ($csvLines as $line) {
            $r = str_getcsv($line, ';');
            if (count($r) < 12) continue;
            [$ka, $ki, $batch, $tgl, $pec, $ta, $te, $pa, $pk, $seri, $jum, $supplier] = $r;
            $pa = (int) $pa; $pk = (int) $pk;
            for ($pn = $pa; $pn <= $pk; $pn++) {
                $csvPackKeys["{$batch}|{$seri}|{$pn}"] = true;
            }
        }

        // Guard: check if first CSV pack already has id_pengemasan
        $firstLine = $csvLines[0];
        $r = str_getcsv($firstLine, ';');
        if (count($r) >= 12) {
            [, , $batch, , , , , $pa, , $seri] = $r;
            $pa = (int) $pa;
            $existing = DB::table('packs')
                ->where('batch', $batch)->where('seri', $seri)->where('pack_number', $pa)
                ->whereNotNull('id_pengemasan')
                ->count();
            if ($existing > 0) {
                $this->command->warn("Pack pertama CSV lanjutan sudah memiliki pengemasan. Seeder dibatalkan.");
                return;
            }
        }

        DB::transaction(function () use ($csvLines, $csvPackKeys) {
            // Load peta pack (batch|seri|pack_number => {id, jumlah, sorting_id, receiving_gilir})
            $packMap = [];
            $keys = array_keys($csvPackKeys);
            $chunkSize = 2000; // ~500 pack keys per chunk (4 OR groups each)
            foreach (array_chunk($keys, 500) as $chunk) {
                $whereClause = [];
                $bindings = [];
                foreach ($chunk as $key) {
                    [$b, $s, $pn] = explode('|', $key);
                    $whereClause[] = "(batch = ? AND seri = ? AND pack_number = ?)";
                    $bindings[] = $b; $bindings[] = $s; $bindings[] = $pn;
                }
                $packRows = DB::table('packs')
                    ->whereRaw(implode(' OR ', $whereClause), $bindings)
                    ->select('id', 'batch', 'seri', 'pack_number', 'jumlah', 'hcs_sorting_id', 'hcs_receiving_id')
                    ->get();
                foreach ($packRows as $p) {
                    $packMap["{$p->batch}|{$p->seri}|{$p->pack_number}"] = [
                        'id' => $p->id,
                        'jumlah' => $p->jumlah,
                        'sorting_id' => $p->hcs_sorting_id,
                        'receiving_id' => $p->hcs_receiving_id,
                    ];
                }
            }
            $this->command->info('Pack map ter-load: '.count($packMap));

            // Load sortings for gilir lookup
            $sortingIdSet = [];
            $receivingIdSet = [];
            foreach ($packMap as $info) {
                if ($info['sorting_id']) $sortingIdSet[$info['sorting_id']] = true;
                if ($info['receiving_id']) $receivingIdSet[$info['receiving_id']] = true;
            }
            $sortGilirMap = [];
            foreach (array_chunk(array_keys($sortingIdSet), 500) as $chunk) {
                $rows = DB::table('hcs_sortings')->whereIn('id', $chunk)
                    ->selectRaw('id, gilir, status_kunci_pengemasan as locked')->get();
                foreach ($rows as $s) $sortGilirMap[$s->id] = ['gilir' => $s->gilir, 'locked' => $s->locked];
            }

            $recvGilirMap = [];
            foreach (array_chunk(array_keys($receivingIdSet), 500) as $chunk) {
                $rows = DB::table('hcs_receivings')->whereIn('id', $chunk)
                    ->select('id', 'gilir')->get();
                foreach ($rows as $r) $recvGilirMap[$r->id] = $r->gilir;
            }

            $detailRows = [];
            $created = 0; $skipped = 0;
            $now = now();

            foreach ($csvLines as $i => $line) {
                $r = str_getcsv($line, ';');
                if (count($r) < 12) { $skipped++; continue; }
                [$ka, $ki, $batch, $tgl, $pec, $ta, $te, $pa, $pk, $seri, $jum, $supplier] = $r;
                $ka = (int) $ka; $ki = (int) $ki; $pa = (int) $pa; $pk = (int) $pk;
                $expected = $pk - $pa + 1;

                $ids = []; $sumJumlah = 0; $ok = true; $sortingId = null; $gilirRaw = null; $receivingId = null;
                for ($pn = $pa; $pn <= $pk; $pn++) {
                    $key = "{$batch}|{$seri}|{$pn}";
                    if (!isset($packMap[$key])) { $ok = false; break; }
                    $info = $packMap[$key];
                    $ids[] = $info['id'];
                    $sumJumlah += $info['jumlah'];
                    if (!$sortingId && $info['sorting_id']) $sortingId = $info['sorting_id'];
                    if (!$receivingId) $receivingId = $info['receiving_id'];
                }
                if (!$ok || count($ids) !== $expected) { $skipped++; continue; }

                // Determine gilir
                if ($sortingId && isset($sortGilirMap[$sortingId])) {
                    $gilirRaw = $sortGilirMap[$sortingId]['gilir'];
                } elseif ($receivingId && isset($recvGilirMap[$receivingId])) {
                    $gilirRaw = $recvGilirMap[$receivingId];
                }
                if (!$gilirRaw) $gilirRaw = 'Gilir 1';

                $gilir = match ($gilirRaw) {
                    'Gilir 1' => '1',
                    'Gilir 2' => '2',
                    'Gilir 3' => '3',
                    default => preg_replace('/\D/', '', $gilirRaw) ?: '1',
                };

                $pengId = DB::table('pengemasans')->insertGetId([
                    'tanggal_pengemasan' => \Carbon\Carbon::createFromFormat('Y-m-d', $tgl)->format('Y-m-d'),
                    'gilir'              => $gilir,
                    'tahun_anggaran'     => (string) $ta,
                    'tahun_emisi'        => (int) $te,
                    'pecahan'            => $pec,
                    'batch'              => $batch,
                    'seri'               => $seri,
                    'pack_awal'          => $pa,
                    'pack_akhir'         => $pk,
                    'jumlah_pack'        => $expected,
                    'jumlah_dus'         => ($ki - $ka + 1),
                    'total_bilyet'       => $sumJumlah,
                    'dus_awal'           => $ka,
                    'dus_akhir'          => $ki,
                    'created_by'         => 1,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]);
                $created++;

                DB::table('packs')->whereIn('id', $ids)->update(['id_pengemasan' => $pengId]);
                if ($sortingId) {
                    DB::statement('UPDATE hcs_sortings SET status_kunci_pengemasan = ? WHERE id = ?', [1, $sortingId]);
                }

                // detail: 9 dus
                $parts = explode('-', $seri);
                $p1A = ($parts[0] ?? '') . 'A'; $p1K = ($parts[0] ?? '') . 'U';
                preg_match('/^[A-Za-z]+/', ($parts[1] ?? ''), $m);
                $sp = $m[0] ?? ($parts[0] ?? '');
                $p2A = $sp . 'A'; $p2K = $sp . 'U';
                $p3A = ($parts[0] ?? '') . 'V'; $p3K = $sp . 'Z';

                $chunkBilyet = $sumJumlah;
                $bilyetPerDus = intdiv($chunkBilyet, 9);
                $sisa = $chunkBilyet % 9;
                [$p1n, $p2n, $p3n, $p4n] = [$pa, $pa + 1, $pa + 2, $pa + 3];
                $rows = [
                    [$p1n, $p4n, $p3A, $p3K],
                    [$p1n, $p1n, $p2A, $p2K],
                    [$p1n, $p1n, $p1A, $p1K],
                    [$p2n, $p2n, $p2A, $p2K],
                    [$p2n, $p2n, $p1A, $p1K],
                    [$p3n, $p3n, $p2A, $p2K],
                    [$p3n, $p3n, $p1A, $p1K],
                    [$p4n, $p4n, $p2A, $p2K],
                    [$p4n, $p4n, $p1A, $p1K],
                ];
                $no = $ka;
                foreach ($rows as [$paD, $pkD, $sa, $sk]) {
                    $add = $sisa > 0 ? 1 : 0; if ($sisa > 0) $sisa--;
                    $detailRows[] = [
                        'id_pengemasan' => $pengId, 'no_dus' => $no++, 'pack_awal' => $paD, 'pack_akhir' => $pkD,
                        'seri_awal' => $sa, 'seri_akhir' => $sk, 'batch' => $batch,
                        'jumlah_bilyet' => $bilyetPerDus + $add, 'created_at' => $now, 'updated_at' => $now,
                    ];
                }

                if (count($detailRows) >= 2000) {
                    DB::table('detail_pengemasans')->insert($detailRows);
                    $detailRows = [];
                }
                if (($i + 1) % 500 === 0) $this->command->info('  -> ' . ($i + 1) . ' baris CSV');
            }

            if (!empty($detailRows)) DB::table('detail_pengemasans')->insert($detailRows);

            $this->command->info("SELESAI: $created pengemasans dibuat dari CSV lanjutan, $skipped baris di-lewati.");
        });
    }
}
