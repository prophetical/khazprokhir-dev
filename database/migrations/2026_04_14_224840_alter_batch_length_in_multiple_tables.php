<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter length limit for 'batch' column to varchar(7)
        DB::statement('ALTER TABLE hcs_receivings ALTER COLUMN batch TYPE varchar(7)');
        DB::statement('ALTER TABLE stock_ledgers ALTER COLUMN batch TYPE varchar(7)');
        DB::statement('ALTER TABLE x_pengganti_seris ALTER COLUMN batch TYPE varchar(7)');
        DB::statement('ALTER TABLE packs ALTER COLUMN batch TYPE varchar(7)');
        
        // Let's also alter 'seri_pengganti' just in case they have longer values like 'XXX-XX10'
        DB::statement('ALTER TABLE x_pengganti_packs ALTER COLUMN seri_pengganti TYPE varchar(255)');
        DB::statement('ALTER TABLE x_pengganti_rikyet_packs ALTER COLUMN seri_pengganti TYPE varchar(255)');
        DB::statement('ALTER TABLE x_pengganti_rikyet_details ALTER COLUMN seri_pengganti TYPE varchar(255)');
    }

    public function down(): void
    {
        // Cannot cleanly revert if data is legitimately longer than 6
    }
};
