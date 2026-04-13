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
        // 1. Indeks Global untuk Dashboard (Tanpa memandang pecahan)
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->index(['tahun_anggaran', 'emisi', 'jumlah'], 'hcs_rec_annual_covering_index');
        });

        Schema::table('pengemasans', function (Blueprint $table) {
            $table->index(['tahun_anggaran', 'tahun_emisi', 'total_bilyet'], 'pengemasan_annual_covering_index');
        });

        Schema::table('penyerahan_bi', function (Blueprint $table) {
            $table->index(['tahun_anggaran', 'tahun_emisi', 'jumlah_bilyet'], 'penyerahan_annual_covering_index');
        });

        // 2. Covering Index untuk Packs - Distribusi Supplier Tahunan
        // Sangat krusial jika packs berisi puluhan juta baris.
        Schema::table('packs', function (Blueprint $table) {
            $table->index(['id_pengemasan', 'supplier', 'jumlah'], 'packs_supplier_annual_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->dropIndex('hcs_rec_annual_covering_index');
        });

        Schema::table('pengemasans', function (Blueprint $table) {
            $table->dropIndex('pengemasan_annual_covering_index');
        });

        Schema::table('penyerahan_bi', function (Blueprint $table) {
            $table->dropIndex('penyerahan_annual_covering_index');
        });

        Schema::table('packs', function (Blueprint $table) {
            $table->dropIndex('packs_supplier_annual_index');
        });
    }
};
