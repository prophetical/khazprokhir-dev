<?php

namespace Database\Seeders;

use App\Models\TargetTahunan;
use App\Models\TargetBulanan;
use App\Models\TargetBulananPengemasan;
use Illuminate\Database\Seeder;

class TargetBulananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yearlyTargets = TargetTahunan::all();

        foreach ($yearlyTargets as $yearly) {
            $monthlyData = $this->distributeTarget($yearly->target);
            
            // Seed Target Bulanan (Penyerahan)
            TargetBulanan::updateOrCreate(
                [
                    'pecahan' => $yearly->pecahan,
                    'tahun_anggaran' => $yearly->tahun_anggaran,
                    'tahun_emisi' => $yearly->tahun_emisi,
                ],
                $monthlyData
            );

            // Seed Target Bulanan Pengemasan
            TargetBulananPengemasan::updateOrCreate(
                [
                    'pecahan' => $yearly->pecahan,
                    'tahun_anggaran' => $yearly->tahun_anggaran,
                    'tahun_emisi' => $yearly->tahun_emisi,
                ],
                $monthlyData
            );
        }
    }

    /**
     * Distribute yearly target into 12 months randomly but sum must match.
     */
    private function distributeTarget($totalTarget): array
    {
        if ($totalTarget == 0) {
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $data["bulan_{$i}"] = 0;
            }
            return $data;
        }

        $weights = [];
        for ($i = 0; $i < 12; $i++) {
            $weights[] = rand(80, 120) / 100; // Realistic variance between 80% and 120%
        }

        $sumWeights = array_sum($weights);
        $distributedValues = [];
        $currentSum = 0;

        for ($i = 0; $i < 11; $i++) {
            $value = round(($weights[$i] / $sumWeights) * $totalTarget);
            $distributedValues["bulan_" . ($i + 1)] = $value;
            $currentSum += $value;
        }

        // Adjust last month to ensure total sum is exactly correct
        $distributedValues["bulan_12"] = $totalTarget - $currentSum;

        return $distributedValues;
    }
}
