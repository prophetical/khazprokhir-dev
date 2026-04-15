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
        Schema::table('detail_pengemasans', function (Blueprint $table) {
            $table->index('id_pengemasan');
            $table->index('no_dus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengemasans', function (Blueprint $table) {
            $table->dropIndex(['id_pengemasan']);
            $table->dropIndex(['no_dus']);
        });
    }
};
