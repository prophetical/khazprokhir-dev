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
        Schema::table('pengemasans', function (Blueprint $table) {
            $table->bigInteger('total_bilyet')->default(0)->after('jumlah_dus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengemasans', function (Blueprint $table) {
            $table->dropColumn('total_bilyet');
        });
    }
};
