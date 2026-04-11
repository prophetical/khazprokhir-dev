<?php

namespace App\Services;

use App\Models\PenyablonanStok;
use App\Models\PenyablonanDus;
use Illuminate\Support\Facades\DB;

class PenyablonanService
{
    /**
     * Get the current global stock of blank boxes.
     */
    public function getCurrentStok()
    {
        return PenyablonanStok::first()->stok_blanko ?? 0;
    }

    /**
     * Update the global stock balance.
     * @param int $jumlah The amount to change
     * @param string $tipe 'tambah' or 'kurangi'
     * @return array ['stok' => int, 'warning' => bool]
     */
    public function updateStok(int $jumlah, string $tipe)
    {
        $stokRecord = PenyablonanStok::first();
        if (!$stokRecord) {
            $stokRecord = PenyablonanStok::create(['stok_blanko' => 0]);
        }

        if ($tipe === 'tambah') {
            $stokRecord->stok_blanko += $jumlah;
        } else {
            $stokRecord->stok_blanko -= $jumlah;
        }

        $stokRecord->save();

        return [
            'stok' => $stokRecord->stok_blanko,
            'warning' => $stokRecord->stok_blanko <= 1000
        ];
    }

    /**
     * Check if a box range overlaps with existing screen-printed boxes for the same ID.
     */
    public function checkDuplicateRange(string $pecahan, int $ta, int $te, int $awal, int $akhir)
    {
        return PenyablonanDus::where('pecahan', $pecahan)
            ->where('ta', $ta)
            ->where('te', $te)
            ->where('no_awal', '<=', $akhir)
            ->where('no_akhir', '>=', $awal)
            ->exists();
    }

    /**
     * Calculate the number of boxes based on start and end numbers (inclusive).
     */
    public function calculateJumlah(int $awal, int $akhir)
    {
        return max(0, $akhir - $awal + 1);
    }
}
