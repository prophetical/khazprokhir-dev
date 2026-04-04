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
        Schema::create('bahan_penolongs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->string('kode_material')->nullable();
            $table->string('satuan'); // pcs, roll, buah, dll
            $table->integer('stok')->default(0);
            $table->integer('min_stok')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_penolongs');
    }
};
