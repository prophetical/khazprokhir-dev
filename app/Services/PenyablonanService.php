<?php

namespace App\Services;

use App\Models\PenyablonanStok;
use App\Models\PenyablonanDus;
use Illuminate\Support\Facades\DB;

class PenyablonanService
{
    /**
     * Mendapatkan jumlah stok global kotak blanko saat ini.
     */
    public function getCurrentStok()
    {
        return PenyablonanStok::first()->stok_blanko ?? 0;
    }

    /**
     * Memperbarui saldo stok global.
     * @param int $jumlah Jumlah yang akan diubah.
     * @param string $tipe Jenis operasi: 'tambah' atau 'kurangi'.
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
     * Memeriksa apakah rentang nomor dus beririsan dengan data penyablonan dus yang sudah ada.
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
     * Menghitung jumlah dus berdasarkan nomor awal dan nomor akhir (secara inklusif).
     */
    public function calculateJumlah(int $awal, int $akhir)
    {
        return max(0, $akhir - $awal + 1);
    }
}
