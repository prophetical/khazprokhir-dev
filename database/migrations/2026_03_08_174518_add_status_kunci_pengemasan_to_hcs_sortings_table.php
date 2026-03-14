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
        Schema::table('hcs_sortings', function (Blueprint $table) {
            $table->boolean('status_kunci_pengemasan')->default(0)->after('jumlah_bilyet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hcs_sortings', function (Blueprint $table) {
            $table->dropColumn('status_kunci_pengemasan');
        });
    }
};
