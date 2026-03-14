<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hcs_receivings', function (Blueprint $table) {
            $table->id();
            $table->text('nomor_bon');
            $table->date('tanggal_penerimaan');
            $table->enum('pecahan', ['S', 'T', 'U', 'V', 'W', 'X', 'Y']);
            $table->integer('jumlah');
            $table->enum('gilir', ['Gilir 1', 'Gilir 2', 'Gilir 3']);
            $table->string('mesin');
            $table->enum('supplier', ['Rikyet', 'Cutpack']);
            $table->string('batch', 6);
            $table->string('seri');
            $table->year('emisi');
            $table->enum('repass', ['repass'])->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hcs_receivings');
    }
};
