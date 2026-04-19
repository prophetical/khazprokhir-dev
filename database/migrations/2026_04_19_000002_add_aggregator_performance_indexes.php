<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Menambahkan index performa untuk MappingAggregatorService.
 *
 * Query utama aggregator:
 *   SELECT nomor_pack, COUNT(DISTINCT source_start), source_category
 *   FROM serial_range_mappings
 *   WHERE x_pengganti_seri_id = ? AND unit_type IN (...)
 *   GROUP BY nomor_pack, source_category
 *
 * Index srm_aggregator_idx menutupi seluruh akses pattern tersebut
 * dengan covering index (semua kolom yang diakses masuk ke index).
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Index utama untuk MappingAggregatorService ──
        // Covering index: filter by seri_id + unit_type, group by pack + category
        // Kolom source_start diikutsertakan untuk COUNT(DISTINCT source_start)
        DB::statement('
            CREATE INDEX srm_aggregator_idx
            ON serial_range_mappings(x_pengganti_seri_id, unit_type, nomor_pack, source_category, source_start)
        ');

        // ── Index untuk reset massal per seri (UPDATE ... WHERE seri_id = ?) ──
        // Khazai packs
        Schema::table('x_pengganti_packs', function (Blueprint $table) {
            $table->index('x_pengganti_seri_id', 'xpp_seri_id_idx');
        });

        // Cutpack packs
        Schema::table('x_pengganti_cutpack_packs', function (Blueprint $table) {
            $table->index('x_pengganti_seri_id', 'xpcp_seri_id_idx');
        });

        // Rikyet packs
        Schema::table('x_pengganti_rikyet_packs', function (Blueprint $table) {
            $table->index('x_pengganti_seri_id', 'xprp_seri_id_idx');
        });
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS srm_aggregator_idx');

        Schema::table('x_pengganti_packs', function (Blueprint $table) {
            $table->dropIndex('xpp_seri_id_idx');
        });
        Schema::table('x_pengganti_cutpack_packs', function (Blueprint $table) {
            $table->dropIndex('xpcp_seri_id_idx');
        });
        Schema::table('x_pengganti_rikyet_packs', function (Blueprint $table) {
            $table->dropIndex('xprp_seri_id_idx');
        });
    }
};
