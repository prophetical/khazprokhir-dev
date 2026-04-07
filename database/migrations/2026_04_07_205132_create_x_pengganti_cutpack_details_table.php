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
        Schema::create('x_pengganti_cutpack_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('x_pengganti_cutpack_pack_id')->constrained()->cascadeOnDelete();
            $table->integer('slot');
            $table->integer('rusak_seri_1')->nullable();
            $table->integer('rusak_seri_2')->nullable();
            $table->integer('rusak_campuran')->nullable();
            $table->integer('nomor_pack_pengganti')->nullable();
            $table->integer('nomor_bilyet_pengganti')->nullable();
            $table->timestamps();
            
            $table->unique(['x_pengganti_cutpack_pack_id', 'slot'], 'cutpack_detail_pack_slot_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('x_pengganti_cutpack_details');
    }
};
