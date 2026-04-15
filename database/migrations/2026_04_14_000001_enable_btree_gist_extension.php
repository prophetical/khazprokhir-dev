<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Enable btree_gist extension for PostgreSQL.
     * Required for EXCLUDE constraints that combine equality (=) with range overlap (&&).
     */
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS btree_gist');
    }

    public function down(): void
    {
        DB::statement('DROP EXTENSION IF EXISTS btree_gist');
    }
};
