<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('x_pengganti_rikyet_packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('x_pengganti_seri_id')->constrained('x_pengganti_seris')->cascadeOnDelete();
            $table->unsignedTinyInteger('nomor_pack'); // 1–100
            $table->string('seri_pengganti', 6)->nullable(); // Format: XX-XX9
            $table->integer('total_rusak_seri_1')->nullable();     // SUM slot seri 1
            $table->integer('total_rusak_seri_2')->nullable();     // SUM slot seri 2
            $table->integer('total_rusak_campuran')->nullable();   // SUM slot campuran
            $table->timestamps();

            $table->unique(['x_pengganti_seri_id', 'nomor_pack'], 'rikyet_pack_seri_nomor_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('x_pengganti_rikyet_packs');
    }
};
