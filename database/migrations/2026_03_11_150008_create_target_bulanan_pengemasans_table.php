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
        Schema::create('target_bulanan_pengemasans', function (Blueprint $table) {
            $table->id();
            $table->string('pecahan');
            $table->integer('tahun_anggaran');
            $table->integer('tahun_emisi');
            for ($i = 1; $i <= 12; $i++) {
                $table->decimal("bulan_{$i}", 20, 0)->default(0);
            }
            $table->timestamps();

            $table->unique(['pecahan', 'tahun_anggaran', 'tahun_emisi'], 'target_pengemasan_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_bulanan_pengemasans');
    }
};
