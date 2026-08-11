<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HcsSortingLanjutanSeeder extends Seeder
{
    public function run(): void
    {
        $migratedPackSql = function ($q) {
            $q->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
              ->where('hcs_receivings.created_by', 1)->where('hcs_receivings.mesin', 'migrasi');
        };

        // Guard: jangan jalan 2x
        $already = DB::table('packs')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.created_by', 1)->where('hcs_receivings.mesin', 'migrasi')
            ->whereNotNull('packs.hcs_sorting_id')->count();
        $totalMigratedPacks = DB::table('packs')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.created_by', 1)->where('hcs_receivings.mesin', 'migrasi')->count();
        if ($already > 0 && $already === $totalMigratedPacks) {
            $this->command->warn("Semua pack migrasi sudah disortir ($already/$totalMigratedPacks). Dibatalkan.");
            return;
        }

        $this->command->info("Memulai penyortiran LANJUTAN untuk pack yang belum disortir...");

        DB::transaction(function () use ($migratedPackSql) {
            $packs = DB::table('packs')
                ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
                ->where('hcs_receivings.created_by', 1)->where('hcs_receivings.mesin', 'migrasi')
                ->whereNull('packs.hcs_sorting_id')
                ->select('packs.id', 'packs.batch', 'packs.seri', 'packs.pack_number', 'packs.jumlah', 'packs.supplier',
                         'hcs_receivings.pecahan', 'hcs_receivings.gilir', 'hcs_receivings.emisi',
                         'hcs_receivings.tahun_anggaran', 'hcs_receivings.tanggal_penerimaan')
                ->orderBy('packs.batch')->orderBy('packs.seri')->orderBy('packs.pack_number')
                ->get();

            if ($packs->isEmpty()) {
                $this->command->warn('Tidak ada pack baru yang perlu disortir.');
                return;
            }

            $groups = [];
            foreach ($packs as $p) {
                $key = "{$p->pecahan}|{$p->batch}|{$p->seri}";
                if (!isset($groups[$key])) {
                    $groups[$key] = [
                        'ids' => [], 'packs' => [], 'suppliers' => [], 'gilirs' => [],
                        'emis' => [], 'ta' => [], 'dates' => [], 'bilyet' => 0,
                        'pecahan' => $p->pecahan, 'batch' => $p->batch, 'seri' => $p->seri,
                    ];
                }
                $g = &$groups[$key];
                $g['ids'][] = $p->id;
                $g['packs'][] = $p->pack_number;
                $g['suppliers'][$p->supplier] = ($g['suppliers'][$p->supplier] ?? 0) + 1;
                $g['gilirs'][$p->gilir] = ($g['gilirs'][$p->gilir] ?? 0) + 1;
                $g['emis'][$p->emisi] = ($g['emis'][$p->emisi] ?? 0) + 1;
                $g['ta'][$p->tahun_anggaran] = ($g['ta'][$p->tahun_anggaran] ?? 0) + 1;
                $g['dates'][] = $p->tanggal_penerimaan;
                $g['bilyet'] += $p->jumlah;
            }

            $mode = function (array $arr) {
                arsort($arr);
                return array_key_first($arr);
            };

            $total = count($groups);
            $this->command->info("Bundle penyortiran baru: $total");
            $auditRows = [];
            $ledgerDelta = [];
            $i = 0;

            foreach ($groups as $g) {
                $i++;
                $sortingId = DB::table('hcs_sortings')->insertGetId([
                    'pecahan'          => $g['pecahan'],
                    'batch'            => $g['batch'],
                    'seri'             => $g['seri'],
                    'emisi'            => (int) array_key_first($g['emis']),
                    'tahun_anggaran'   => (string) array_key_first($g['ta']),
                    'supplier'         => $mode($g['suppliers']),
                    'packs_selected'   => json_encode($g['packs']),
                    'jumlah_pack'      => count($g['packs']),
                    'jumlah_bilyet'    => $g['bilyet'],
                    'petugas_1'        => 'admin',
                    'petugas_2'        => null,
                    'tanggal_sortir'   => min($g['dates']),
                    'gilir'            => $mode($g['gilirs']),
                    'created_by'       => 1,
                    'status_kunci_pengemasan' => false,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                DB::table('packs')->whereIn('id', $g['ids'])->update(['hcs_sorting_id' => $sortingId]);

                $auditRows[] = [
                    'user_id'    => 1,
                    'action'     => 'receiving_sorted',
                    'module'     => 'HCS Sorting',
                    'record_id'  => $sortingId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $lk = "{$g['pecahan']}|{$g['batch']}|{$g['seri']}";
                if (!isset($ledgerDelta[$lk])) $ledgerDelta[$lk] = ['rcv' => 0, 'pk' => 0];
                $ledgerDelta[$lk]['rcv'] += $g['bilyet'];
                $ledgerDelta[$lk]['pk']  += count($g['packs']);

                if ($i % 50 === 0) $this->command->info('  -> ' . $i . '/' . $total . ' bundle');
            }

            // Stock ledger (replikasi service: firstOrCreate + increment, hindari zero-kan data lama)
            foreach ($ledgerDelta as $lk => $d) {
                [$pec, $b, $s] = explode('|', $lk, 3);
                DB::table('stock_ledgers')->updateOrInsert(
                    ['pecahan' => $pec, 'batch' => $b, 'seri' => $s],
                    ['pecahan' => $pec, 'batch' => $b, 'seri' => $s, 'created_at' => now(), 'updated_at' => now()]
                );
                DB::table('stock_ledgers')
                    ->where('pecahan', $pec)->where('batch', $b)->where('seri', $s)
                    ->increment('total_received', $d['rcv']);
                DB::table('stock_ledgers')
                    ->where('pecahan', $pec)->where('batch', $b)->where('seri', $s)
                    ->increment('total_packed', $d['pk']);
            }

            foreach (array_chunk($auditRows, 2000) as $chunk) {
                DB::table('audit_logs')->insert($chunk);
            }
        });

        $this->command->info("SELESAI: penyortiran lanjutan selesai.");
    }
}
