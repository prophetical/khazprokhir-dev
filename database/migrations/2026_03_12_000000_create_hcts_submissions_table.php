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
        Schema::create('hcts_submissions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_penyerahan');
            $table->string('pecahan');
            $table->year('tahun_anggaran');
            $table->year('tahun_emisi');
            $table->bigInteger('jumlah_bilyet');
            $table->string('pemasok1');
            $table->string('pemasok2')->nullable();
            $table->string('nomor_ba');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('hcts_submission_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hcts_submission_id')->constrained('hcts_submissions')->onDelete('cascade');
            $table->string('batch', 10);
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hcts_submission_batches');
        Schema::dropIfExists('hcts_submissions');
    }
};
