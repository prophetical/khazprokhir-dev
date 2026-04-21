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
        Schema::create('registrasi_penerimaan_hcs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('barcode_token')->unique();
            $blueprint->string('nomor_bon');
            $blueprint->string('pecahan');
            $blueprint->integer('jumlah');
            $blueprint->string('batch');
            $blueprint->string('seri');
            $blueprint->string('tahun_emisi_anggaran')->nullable();
            $blueprint->string('gilir');
            $blueprint->string('mesin');
            $blueprint->string('supplier');
            $blueprint->date('tanggal_pembuatan');
            $blueprint->foreignId('petugas_khazai_id')->constrained('users');
            $blueprint->json('packs_data');
            $blueprint->string('status')->default('pending');
            $blueprint->timestamps();

            // Indexing for performance
            $blueprint->index('status');
            $blueprint->index('tanggal_pembuatan');
            $blueprint->index('petugas_khazai_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi_penerimaan_hcs');
    }
};
