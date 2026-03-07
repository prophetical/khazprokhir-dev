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
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->string('tahun_anggaran')->default('2025')->after('emisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hcs_receivings', function (Blueprint $table) {
            $table->dropColumn('tahun_anggaran');
        });
    }
};
