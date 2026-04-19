<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom agregat jumlah_rusak_vell ke x_pengganti_packs.
 * Kolom ini diisi otomatis oleh MappingAggregatorService berdasarkan
 * data di serial_range_mappings (unit_type = 'vell').
 *
 * Kolom nomor_pack_pengganti dan nomor_vell_pengganti DIHAPUS dari
 * tabel ini (user hanya butuh jumlah, bukan detail pack/vell pengganti).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('x_pengganti_packs', function (Blueprint $table) {
            // Kolom agregat vell — diisi otomatis oleh aggregator service
            $table->smallInteger('jumlah_rusak_vell')->default(0)->after('seri_pengganti');
        });
    }

    public function down(): void
    {
        Schema::table('x_pengganti_packs', function (Blueprint $table) {
            $table->dropColumn('jumlah_rusak_vell');
        });
    }
};
