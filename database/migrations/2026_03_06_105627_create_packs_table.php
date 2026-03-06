<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hcs_receiving_id')->constrained('hcs_receivings')->cascadeOnDelete();
            $table->string('batch', 6);
            $table->string('seri');
            $table->integer('pack_number');
            $table->enum('supplier', ['Rikyet', 'Cutpack']);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->unique(['batch', 'seri', 'pack_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packs');
    }
};
