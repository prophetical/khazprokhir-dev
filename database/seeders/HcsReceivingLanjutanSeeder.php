<?php

namespace Database\Seeders;

use App\Services\HcsReceivingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HcsReceivingLanjutanSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('docs/migrasi_penerimaan_lanjutan.csv');
        if (!file_exists($path)) {
            $this->command->error("File tidak ditemukan: $path");
            return;
        }

        $svc = app(HcsReceivingService::class);
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        array_shift($lines);
        $total = count($lines);

        $this->command->info("Memulai migrasi penerimaan LANJUTAN: $total baris...");

        $gilirList = ['Gilir 1', 'Gilir 2', 'Gilir 3'];
        $usedBon = [];

        foreach ($lines as $i => $line) {
            $r = str_getcsv($line, ';');
            if (count($r) < 10) continue;

            [$batch, $tgl, $pecahan, $ta, $te, $pa, $pk, $seri, $jum, $sup] = $r;
            $pa = (int) $pa;
            $pk = (int) $pk;
            if ($pk === 0) $pk = 100;

            $packs = range($pa, $pk);
            $jumlah = (int) $jum;

            do {
                $bon = (string) rand(1000000, 9999999);
            } while (isset($usedBon[$bon]) || DB::table('hcs_receivings')->where('nomor_bon', $bon)->exists());

            $svc->createReceiving([
                'nomor_bon'          => $bon,
                'tanggal_penerimaan' => \Carbon\Carbon::createFromFormat('Y-m-d', $tgl)->format('Y-m-d'),
                'pecahan'            => $pecahan,
                'jumlah'             => $jumlah,
                'gilir'              => $gilirList[array_rand($gilirList)],
                'mesin'              => 'migrasi',
                'supplier'           => $sup,
                'batch'              => $batch,
                'seri'               => $seri,
                'emisi'              => (int) $te,
                'tahun_anggaran'     => (string) $ta,
                'repass'             => null,
                'packs'              => $packs,
                'is_manual'          => false,
            ], 1);

            if (($i + 1) % 500 === 0) {
                $this->command->info('  -> ' . ($i + 1) . '/' . $total);
            }
        }

        $this->command->info("SELESAI: $total penerimaan lanjutan dimigrasikan (append).");
    }
}
