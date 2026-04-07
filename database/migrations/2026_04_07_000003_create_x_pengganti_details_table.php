<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('x_pengganti_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('x_pengganti_pack_id')->constrained('x_pengganti_packs')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot'); // 1–4
            $table->smallInteger('jumlah_rusak_vell')->nullable();
            $table->integer('nomor_pack_pengganti')->nullable();
            $table->integer('nomor_vell_pengganti')->nullable();
            $table->timestamps();

            $table->unique(['x_pengganti_pack_id', 'slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('x_pengganti_details');
    }
};
