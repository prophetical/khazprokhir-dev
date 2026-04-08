<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('x_pengganti_rikyet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('x_pengganti_rikyet_pack_id')->constrained('x_pengganti_rikyet_packs')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot'); // 1–4
            $table->integer('rusak_seri_1')->nullable();     // SERI 1 (A1-U1), satuan brood
            $table->integer('rusak_seri_2')->nullable();     // SERI 2 (A2-U2), satuan brood
            $table->integer('rusak_campuran')->nullable();   // CAMPURAN (V1,W1,Y1,Z1 & V2), satuan brood
            $table->string('seri_pengganti', 6)->nullable(); // Keterangan per-slot, format XX-XX9
            $table->timestamps();

            $table->unique(['x_pengganti_rikyet_pack_id', 'slot'], 'rikyet_detail_pack_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('x_pengganti_rikyet_details');
    }
};
