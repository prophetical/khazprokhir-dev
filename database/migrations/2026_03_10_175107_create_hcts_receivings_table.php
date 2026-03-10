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
        Schema::create('hcts_receivings', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bon');
            $table->date('tanggal_penerimaan');
            $table->enum('pecahan', ['S', 'T', 'U', 'V', 'W', 'X', 'Y']);
            $table->integer('jumlah');
            $table->string('batch', 10);
            $table->string('seri');
            $table->year('emisi');
            $table->year('tahun_anggaran');
            $table->string('nomor_segel');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hcts_receivings');
    }
};
