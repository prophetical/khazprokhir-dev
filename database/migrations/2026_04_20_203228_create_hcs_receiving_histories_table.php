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
        Schema::create('hcs_receiving_histories', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('hcs_receiving_id')->constrained('hcs_receivings')->onDelete('cascade');
            $blueprint->string('barcode_token');
            $blueprint->foreignId('user_id')->constrained('users');
            $blueprint->string('field_name');
            $blueprint->text('old_value')->nullable();
            $blueprint->text('new_value')->nullable();
            $blueprint->timestamps();

            $blueprint->index('barcode_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hcs_receiving_histories');
    }
};
