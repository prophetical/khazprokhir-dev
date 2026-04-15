<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TargetTahunan;
use App\Models\TargetBulanan;
use App\Models\TargetBulananPengemasan;
use Illuminate\Support\Facades\DB;

class TargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            2025 => 6000000000, // 6 Milyar
            2026 => 5400000000, // 5.4 Milyar
        ];

        $pecahans = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $te = 2022;

        foreach ($years as $year => $totalTarget) {
            // Hapus data lama untuk tahun anggaran ini agar tidak duplikat
            $filter = ['tahun_anggaran' => $year, 'tahun_emisi' => $te];
            TargetTahunan::where($filter)->delete();
            TargetBulanan::where($filter)->delete();
            TargetBulananPengemasan::where($filter)->delete();

            // 1. Distribusi per Pecahan (Tidak Rata)
            // Bobot per pecahan (total 100)
            $pchWeights = [20, 15, 15, 12, 13, 12, 13]; 
            
            $distributedTotal = 0;
            foreach ($pecahans as $index => $pch) {
                $weight = $pchWeights[$index];
                
                // Jika pecahan terakhir, ambil sisanya agar total pas
                if ($index === count($pecahans) - 1) {
                    $pchAnnualTarget = $totalTarget - $distributedTotal;
                } else {
                    $pchAnnualTarget = (int) ($totalTarget * ($weight / 100));
                    $distributedTotal += $pchAnnualTarget;
                }

                // 2. Simpan Target Tahunan
                TargetTahunan::create([
                    'pecahan' => $pch,
                    'tahun_anggaran' => $year,
                    'tahun_emisi' => $te,
                    'target' => $pchAnnualTarget,
                ]);

                // 3. Distribusi Bulanan (Tidak Rata)
                $monthWeights = [8, 7, 9, 10, 8, 7, 11, 9, 8, 7, 10, 6]; // Total 100

                // Penyerahan
                $this->createMonthlyTargets(
                    TargetBulanan::class,
                    $pch, $year, $te, $pchAnnualTarget, $monthWeights
                );

                // Pengemasan (Gunakan bobot yang sedikit berbeda agar tidak sama persis)
                $packMonthWeights = [7, 9, 8, 8, 10, 9, 7, 10, 8, 8, 7, 9]; // Total 100
                $this->createMonthlyTargets(
                    TargetBulananPengemasan::class,
                    $pch, $year, $te, $pchAnnualTarget, $packMonthWeights
                );
            }
        }
    }

    /**
     * Helper untuk membuat data target bulanan dengan distribusi bobot.
     */
    private function createMonthlyTargets($modelClass, $pch, $year, $te, $annualTarget, $weights)
    {
        $data = [
            'pecahan' => $pch,
            'tahun_anggaran' => $year,
            'tahun_emisi' => $te,
        ];

        $distributed = 0;
        for ($m = 1; $m <= 12; $m++) {
            if ($m === 12) {
                $monthValue = $annualTarget - $distributed;
            } else {
                $monthValue = (int) ($annualTarget * ($weights[$m - 1] / 100));
                $distributed += $monthValue;
            }
            $data["bulan_{$m}"] = $monthValue;
        }

        $modelClass::create($data);
    }
}
