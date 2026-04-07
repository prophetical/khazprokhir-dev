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
        Schema::create('x_pengganti_cutpack_packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('x_pengganti_seri_id')->constrained()->cascadeOnDelete();
            $table->integer('nomor_pack');
            $table->string('seri_pengganti', 10)->nullable();
            $table->integer('total_rusak_seri_1')->nullable();
            $table->integer('total_rusak_seri_2')->nullable();
            $table->integer('total_rusak_campuran')->nullable();
            $table->timestamps();
            
            $table->unique(['x_pengganti_seri_id', 'nomor_pack'], 'cutpack_pack_seri_nomor_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('x_pengganti_cutpack_packs');
    }
};
