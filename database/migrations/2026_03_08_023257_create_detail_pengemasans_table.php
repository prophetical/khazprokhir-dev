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
        Schema::create('detail_pengemasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengemasan')->constrained('pengemasans')->cascadeOnDelete();
            $table->integer('no_dus');
            $table->integer('pack_awal')->nullable();
            $table->integer('pack_akhir')->nullable();
            $table->string('seri_awal');
            $table->string('seri_akhir');
            $table->string('batch');
            $table->integer('jumlah_bilyet')->default(20000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengemasans');
    }
};
