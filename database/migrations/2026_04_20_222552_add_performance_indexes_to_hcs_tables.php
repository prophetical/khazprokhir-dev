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
        Schema::table('registrasi_penerimaan_hcs', function (Blueprint $table) {
            // Composite index for fast batch/seri filtering
            $table->index(['batch', 'seri', 'pecahan', 'emisi', 'tahun_anggaran'], 'idx_hcs_reg_batch_seri_lookup');
            $table->index('supplier');
        });

        Schema::table('packs', function (Blueprint $table) {
            $table->index('batch');
            $table->index('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrasi_penerimaan_hcs', function (Blueprint $table) {
            $table->dropIndex('idx_hcs_reg_batch_seri_lookup');
            $table->dropIndex(['supplier']);
        });

        Schema::table('packs', function (Blueprint $table) {
            $table->dropIndex(['batch']);
            $table->dropIndex(['supplier']);
        });
    }
};
