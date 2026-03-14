<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyerahan_bi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_penyerahan');
            $table->string('nomor_ba');
            $table->string('pecahan');
            $table->year('tahun_emisi');
            $table->string('tahun_anggaran');
            $table->integer('nomor_dus_awal');
            $table->integer('nomor_dus_akhir');
            $table->integer('jumlah_dus');
            $table->bigInteger('jumlah_bilyet');
            $table->string('status_data')->default('Belum Lengkap'); // 'Lengkap' or 'Belum Lengkap'
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyerahan_bi');
    }
};
