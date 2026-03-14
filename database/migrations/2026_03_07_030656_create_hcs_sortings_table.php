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
        Schema::create('hcs_sortings', function (Blueprint $table) {
            $table->id();
            $table->string('pecahan');
            $table->string('batch');
            $table->string('seri');
            $table->string('supplier');
            $table->text('packs_selected');
            $table->integer('jumlah_pack');
            $table->integer('jumlah_bilyet');
            $table->string('petugas_1');
            $table->string('petugas_2')->nullable();
            $table->date('tanggal_sortir');
            $table->string('gilir');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hcs_sortings');
    }
};
