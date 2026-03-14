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
        Schema::create('target_tahunan', function (Blueprint $table) {
            $table->id();
            $table->string('pecahan');
            $table->integer('tahun_anggaran');
            $table->integer('tahun_emisi');
            $table->decimal('target', 20, 0); // Up to 50 billion (11 digits + buffer)
            $table->timestamps();

            $table->unique(['pecahan', 'tahun_anggaran', 'tahun_emisi'], 'target_tahunan_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_tahunan');
    }
};
