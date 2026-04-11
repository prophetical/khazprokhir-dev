<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Stok Global
        Schema::create('penyablonan_stoks', function (Blueprint $table) {
            $table->id();
            $table->integer('stok_blanko')->default(0);
            $table->timestamps();
        });

        // Insert default row
        DB::table('penyablonan_stoks')->insert([
            'stok_blanko' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Tabel Penerimaan Blanko
        Schema::create('penyablonan_penerimaans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // 3. Tabel Aktivitas Penyablonan Dus
        Schema::create('penyablonan_dus', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('gilir'); // Gilir 1, 2, 3
            $table->string('pecahan'); // S, T, U, V, W, X, Y
            $table->integer('te');
            $table->integer('ta');
            $table->integer('no_awal');
            $table->integer('no_akhir');
            $table->integer('jumlah');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            // Index untuk validasi duplikasi range
            $table->index(['pecahan', 'ta', 'te']);
        });

        // 4. Tabel Kerusakan Blanko
        Schema::create('penyablonan_kerusakans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyablonan_kerusakans');
        Schema::dropIfExists('penyablonan_dus');
        Schema::dropIfExists('penyablonan_penerimaans');
        Schema::dropIfExists('penyablonan_stoks');
    }
};
