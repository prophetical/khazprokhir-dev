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
        Schema::create('pengemasans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengemasan');
            $table->string('gilir');
            $table->string('tahun_anggaran');
            $table->year('tahun_emisi');
            $table->string('pecahan');
            $table->string('batch');
            $table->string('seri');
            $table->integer('pack_awal');
            $table->integer('pack_akhir');
            $table->integer('jumlah_pack');
            $table->integer('jumlah_dus');
            $table->integer('dus_awal');
            $table->integer('dus_akhir');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengemasans');
    }
};
