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
        Schema::table('x_pengganti_seris', function (Blueprint $table) {
            $table->index('created_at'); // Mendukung latest() pada halaman index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('x_pengganti_seris', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
