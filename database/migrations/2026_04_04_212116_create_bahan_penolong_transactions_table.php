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
        Schema::create('bahan_penolong_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_penolong_id')->constrained('bahan_penolongs')->onDelete('cascade');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->enum('kategori', ['penerimaan', 'pemakaian', 'mutasi', 'rusak']);
            $table->integer('jumlah');
            $table->integer('stok_akhir');
            $table->string('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_penolong_transactions');
    }
};
