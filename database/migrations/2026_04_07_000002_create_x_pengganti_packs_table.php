<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('x_pengganti_packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('x_pengganti_seri_id')->constrained('x_pengganti_seris')->cascadeOnDelete();
            $table->unsignedTinyInteger('nomor_pack'); // 1–100
            $table->string('seri_pengganti', 6)->nullable(); // Format: XX-XX9
            $table->timestamps();

            $table->unique(['x_pengganti_seri_id', 'nomor_pack']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('x_pengganti_packs');
    }
};
