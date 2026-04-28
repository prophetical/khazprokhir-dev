<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menggunakan B-Tree Composite Index untuk performa O(log n) pada filter multi-kolom.
     */
    public function up(): void
    {
        // 1. Optimasi Penerimaan HCS (Lot Lookup)
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->index(['batch', 'seri', 'tahun_anggaran'], 'idx_hcs_rec_batch_seri_ta');
        });

        // 2. Optimasi Penyortiran HCS (History & Dashboard)
        Schema::table('hcs_sortings', function (Blueprint $table) {
            $table->index(['batch', 'seri', 'tahun_anggaran'], 'idx_hcs_sort_batch_seri_ta');
        });

        // 3. Optimasi Detail Pack (Production Level)
        Schema::table('packs', function (Blueprint $table) {
            // Mempercepat lookup pack yang BELUM disortir dalam satu lot
            $table->index(['batch', 'seri', 'hcs_sorting_id'], 'idx_packs_sorting_lookup');
            
            // Mempercepat join/pengambilan pack per data penerimaan
            $table->index(['hcs_receiving_id', 'pack_number'], 'idx_packs_receiving_lookup');
            
            // Mempercepat reverse lookup dan operasi bulk delete/update
            $table->index('hcs_sorting_id', 'idx_packs_sorting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->dropIndex('idx_hcs_rec_batch_seri_ta');
        });

        Schema::table('hcs_sortings', function (Blueprint $table) {
            $table->dropIndex('idx_hcs_sort_batch_seri_ta');
        });

        Schema::table('packs', function (Blueprint $table) {
            $table->dropIndex('idx_packs_sorting_lookup');
            $table->dropIndex('idx_packs_receiving_lookup');
            $table->dropIndex('idx_packs_sorting_id');
        });
    }
};
