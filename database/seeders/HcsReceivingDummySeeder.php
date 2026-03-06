<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\HcsReceivingService;
use Carbon\Carbon;

class HcsReceivingDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(HcsReceivingService $service): void
    {
        $user = User::where('role', 'sortir')->first() ?? User::first();
        if (!$user) {
            $this->command->warn("No users found to assign the records to.");
            return;
        }

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $gilirList = ['Gilir 1', 'Gilir 2', 'Gilir 3'];
        $supplierList = ['Cutpack', 'Rikyet'];

        $this->command->info('Creating 50 dummy HCS Receiving records...');

        for ($i = 0; $i < 50; $i++) {
            $packsNeeded = rand(1, 10); // 1 to 10 packs
            $jumlah = $packsNeeded * 45000;

            // Generate a random, unique-ish batch (migration says 6 chars)
            $batch = Str::upper(Str::random(3)) . sprintf("%03d", $i);
            $seri = 'AA-BB' . rand(1, 9);

            $data = [
                'nomor_bon' => 'DUMMY-' . rand(1000, 9999) . '-' . $i,
                'tanggal_penerimaan' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d'),
                'pecahan' => $pecahanList[array_rand($pecahanList)],
                'jumlah' => $jumlah,
                'gilir' => $gilirList[array_rand($gilirList)],
                'mesin' => 'Mesin ' . rand(1, 15),
                'supplier' => $supplierList[array_rand($supplierList)],
                'batch' => $batch,
                'seri' => $seri,
                'emisi' => 2024,
                'repass' => rand(0, 1) ? 'repass' : null,
                'packs' => range(1, $packsNeeded),
            ];

            try {
                $service->createReceiving($data, $user->id);
            }
            catch (\Exception $e) {
                $this->command->error("Failed on iteration {$i}: " . $e->getMessage());
            }
        }

        $this->command->info('Finished seeding 50 records!');
    }
}
