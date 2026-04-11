<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel packs - mempercepat pencarian lintas batch
        Schema::table('packs', function (Blueprint $table) {
            $table->index('seri');
            $table->index('pack_number'); // Berguna untuk range query (misal: 1-4)
        });

        // 2. Tabel x_pengganti_seris - jantung modul X Pengganti
        Schema::table('x_pengganti_seris', function (Blueprint $table) {
            $table->index(['pecahan', 'batch', 'seri'], 'x_pengganti_filter_index');
            $table->index('tahun_anggaran');
        });

        // 3. Tabel hcs_receivings - mempercepat filter dashboard & sorting
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->index('pecahan');
            $table->index('emisi');
            $table->index('tahun_anggaran');
        });

        // 4. Tabel hcs_sortings - mempercepat laporan
        Schema::table('hcs_sortings', function (Blueprint $table) {
            $table->index(['pecahan', 'batch', 'seri'], 'hcs_sorting_filter_index');
            $table->index('tanggal_sortir');
            $table->index('gilir');
        });

        // 5. Tabel Pengemasan - mempercepat pencarian data akhir
        Schema::table('pengemasans', function (Blueprint $table) {
            $table->index(['pecahan', 'batch', 'seri'], 'pengemasan_filter_index');
            $table->index('tanggal_pengemasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packs', function (Blueprint $table) {
            $table->dropIndex(['seri']);
            $table->dropIndex(['pack_number']);
        });

        Schema::table('x_pengganti_seris', function (Blueprint $table) {
            $table->dropIndex('x_pengganti_filter_index');
            $table->dropIndex(['tahun_anggaran']);
        });

        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->dropIndex(['pecahan']);
            $table->dropIndex(['emisi']);
            $table->dropIndex(['tahun_anggaran']);
        });

        Schema::table('hcs_sortings', function (Blueprint $table) {
            $table->dropIndex('hcs_sorting_filter_index');
            $table->dropIndex(['tanggal_sortir']);
            $table->dropIndex(['gilir']);
        });

        Schema::table('pengemasans', function (Blueprint $table) {
            $table->dropIndex('pengemasan_filter_index');
            $table->dropIndex(['tanggal_pengemasan']);
        });
    }
};
