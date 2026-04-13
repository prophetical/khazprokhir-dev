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
        // 1. Tabel packs - Fundamental joins & sorting
        Schema::table('packs', function (Blueprint $table) {
            $table->index('hcs_receiving_id');
            $table->index('id_pengemasan');
            $table->index('hcs_sorting_id');
            $table->index('created_at'); // Mendukung latest()
        });

        // 2. Monitoring & Auditing Indexes
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->index('created_by');
            // Covering index untuk sum(jumlah) di dashboard
            $table->index(['pecahan', 'tahun_anggaran', 'emisi', 'jumlah'], 'hcs_rec_covering_index');
        });

        // 3. X Pengganti - Joins & Rekap (Critical for Scaling)
        Schema::table('x_pengganti_packs', function (Blueprint $table) {
            $table->index('x_pengganti_seri_id');
            $table->index('created_at');
        });

        Schema::table('x_pengganti_details', function (Blueprint $table) {
            // Indeks pada slot & jumlah_rusak_vell untuk covering sum()
            $table->index(['x_pengganti_pack_id', 'slot', 'jumlah_rusak_vell'], 'xpgt_detail_covering_index');
        });

        Schema::table('x_pengganti_cutpack_packs', function (Blueprint $table) {
            $table->index('x_pengganti_seri_id', 'xpgt_cutpack_seri_index');
        });

        Schema::table('x_pengganti_rikyet_packs', function (Blueprint $table) {
            $table->index('x_pengganti_seri_id', 'xpgt_rikyet_seri_index');
        });

        // 4. Dashboards & Reports Covering Indexes
        Schema::table('pengemasans', function (Blueprint $table) {
            $table->index('created_by');
            $table->index(['pecahan', 'tahun_anggaran', 'tahun_emisi', 'total_bilyet'], 'pengemasan_covering_index');
        });

        Schema::table('penyerahan_bi', function (Blueprint $table) {
            $table->index('created_by');
            $table->index(['pecahan', 'tahun_anggaran', 'tahun_emisi', 'jumlah_bilyet'], 'penyerahan_covering_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packs', function (Blueprint $table) {
            $table->dropIndex(['hcs_receiving_id']);
            $table->dropIndex(['id_pengemasan']);
            $table->dropIndex(['hcs_sorting_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->dropIndex(['created_by']);
            $table->dropIndex('hcs_rec_covering_index');
        });

        Schema::table('x_pengganti_packs', function (Blueprint $table) {
            $table->dropIndex(['x_pengganti_seri_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('x_pengganti_details', function (Blueprint $table) {
            $table->dropIndex('xpgt_detail_covering_index');
        });

        Schema::table('x_pengganti_cutpack_packs', function (Blueprint $table) {
            $table->dropIndex('xpgt_cutpack_seri_index');
        });

        Schema::table('x_pengganti_rikyet_packs', function (Blueprint $table) {
            $table->dropIndex('xpgt_rikyet_seri_index');
        });

        Schema::table('pengemasans', function (Blueprint $table) {
            $table->dropIndex(['created_by']);
            $table->dropIndex('pengemasan_covering_index');
        });

        Schema::table('penyerahan_bi', function (Blueprint $table) {
            $table->dropIndex(['created_by']);
            $table->dropIndex('penyerahan_covering_index');
        });
    }
};
