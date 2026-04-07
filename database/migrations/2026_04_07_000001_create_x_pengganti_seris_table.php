<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('x_pengganti_seris', function (Blueprint $table) {
            $table->id();
            $table->enum('pecahan', ['S', 'T', 'U', 'V', 'W', 'X', 'Y']);
            $table->string('seri');
            $table->string('batch', 7);
            $table->year('tahun_anggaran');
            $table->year('tahun_emisi');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('x_pengganti_seris');
    }
};
