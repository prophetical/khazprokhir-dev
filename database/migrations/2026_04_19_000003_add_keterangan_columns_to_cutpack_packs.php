<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Memindahkan kolom keterangan dari tabel detail (slot) ke tabel pack.
 * Hal ini mendukung layout flat (1 baris per pack) pada halaman Cutpack input.
 *
 * Kolom detail (slot-level) tetap dipertahankan untuk audit trail historis,
 * tapi input UI Cutpack kini menggunakan level pack — bukan level slot.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('x_pengganti_cutpack_packs', function (Blueprint $table) {
            // Keterangan pengganti — dipindah dari level slot ke level pack
            $table->integer('nomor_pack_pengganti')->nullable()->after('total_rusak_campuran');
            $table->integer('nomor_bilyet_pengganti')->nullable()->after('nomor_pack_pengganti');
        });
    }

    public function down(): void
    {
        Schema::table('x_pengganti_cutpack_packs', function (Blueprint $table) {
            $table->dropColumn(['nomor_pack_pengganti', 'nomor_bilyet_pengganti']);
        });
    }
};
