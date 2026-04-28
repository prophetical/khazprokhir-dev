<?php

namespace Database\Seeders;

use App\Models\TargetTahunan;
use Illuminate\Database\Seeder;

class TargetTahunanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Target TA. 2025
            ['pecahan' => 'S', 'tahun_anggaran' => 2025, 'tahun_emisi' => 2022, 'target' => 737492000],
            ['pecahan' => 'T', 'tahun_anggaran' => 2025, 'tahun_emisi' => 2022, 'target' => 1140566000],
            ['pecahan' => 'U', 'tahun_anggaran' => 2025, 'tahun_emisi' => 2022, 'target' => 1080506000],
            ['pecahan' => 'V', 'tahun_anggaran' => 2025, 'tahun_emisi' => 2022, 'target' => 715988000],
            ['pecahan' => 'W', 'tahun_anggaran' => 2025, 'tahun_emisi' => 2022, 'target' => 401646000],
            ['pecahan' => 'X', 'tahun_anggaran' => 2025, 'tahun_emisi' => 2022, 'target' => 1271376000],
            ['pecahan' => 'Y', 'tahun_anggaran' => 2025, 'tahun_emisi' => 2022, 'target' => 1320368000],

            // Target TA. 2026
            ['pecahan' => 'T', 'tahun_anggaran' => 2026, 'tahun_emisi' => 2022, 'target' => 1655980000],
            ['pecahan' => 'U', 'tahun_anggaran' => 2026, 'tahun_emisi' => 2022, 'target' => 1020640000],
            ['pecahan' => 'V', 'tahun_anggaran' => 2026, 'tahun_emisi' => 2022, 'target' => 443200000],
            ['pecahan' => 'X', 'tahun_anggaran' => 2026, 'tahun_emisi' => 2022, 'target' => 1527580000],
            ['pecahan' => 'Y', 'tahun_anggaran' => 2026, 'tahun_emisi' => 2022, 'target' => 2131360000],
        ];

        foreach ($data as $item) {
            TargetTahunan::updateOrCreate(
                [
                    'pecahan' => $item['pecahan'],
                    'tahun_anggaran' => $item['tahun_anggaran'],
                    'tahun_emisi' => $item['tahun_emisi'],
                ],
                ['target' => $item['target']]
            );
        }
    }
}
