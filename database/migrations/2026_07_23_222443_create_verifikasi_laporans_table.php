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
        Schema::create('verifikasi_laporans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_laporan'); // 'harian' atau 'rekonsiliasi'
            $table->date('tanggal_mulai');   // Tanggal laporan (harian) atau tanggal awal (rekonsiliasi)
            $table->date('tanggal_akhir');   // Sama dengan tanggal_mulai untuk harian, tanggal akhir untuk rekonsiliasi
            $table->string('tahun_anggaran')->nullable();
            $table->string('tahun_emisi')->nullable();
            $table->foreignId('verified_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('verified_at');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Satu tanggal/rentang hanya bisa diverifikasi satu kali per jenis laporan
            $table->unique(['jenis_laporan', 'tanggal_mulai', 'tanggal_akhir', 'tahun_anggaran'], 'verifikasi_unik');
            $table->index('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_laporans');
    }
};
