<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Core table for Range-to-Range Serial Mapping.
     *
     * Design rationale:
     * - Uses two integer columns (start/end) instead of int4range for Eloquent compatibility
     * - GIST index on source columns for O(log n) range containment queries
     * - EXCLUDE constraint prevents overlapping source ranges within same prefix+seri
     * - CHECK constraints enforce data integrity (count match, valid ranges)
     *
     * Storage efficiency:
     * - 1 pack replacement = 45 rows (instead of 45,000 per-bilyet rows)
     * - Compression ratio ~1000:1 for bulk replacements
     */
    public function up(): void
    {
        Schema::create('serial_range_mappings', function (Blueprint $table) {
            $table->id();

            // Context: linked to master seri (batch/pecahan/tahun)
            $table->foreignId('x_pengganti_seri_id')
                  ->constrained('x_pengganti_seris')
                  ->cascadeOnDelete();

            $table->integer('nomor_pack'); // Pack number (e.g., 701)

            // ── SOURCE (Seri Asal / Rusak) ──
            $table->char('source_prefix', 3);     // e.g., ABA, BBV
            $table->integer('source_start');        // e.g., 701001
            $table->integer('source_end');           // e.g., 702000

            // ── REPLACEMENT (Seri Pengganti / X) ──
            $table->char('replacement_prefix', 3); // e.g., ZZA
            $table->integer('replacement_start');    // e.g., 000001
            $table->integer('replacement_end');       // e.g., 001000

            // ── METADATA ──
            // Category: seri_1, seri_2, campuran_1, campuran_2
            $table->string('source_category', 20);
            // Unit scale: bilyet, brood, pack
            $table->string('unit_type', 10)->default('brood');
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            // ── B-TREE INDEXES ──
            // For reverse lookup by replacement serial
            $table->index(['replacement_prefix', 'replacement_start', 'replacement_end'], 'srm_replacement_lookup_idx');
            // For filtering by batch+pack
            $table->index(['x_pengganti_seri_id', 'nomor_pack'], 'srm_seri_pack_idx');
            // For temporal queries
            $table->index('created_at', 'srm_created_at_idx');
        });

        // ── PostgreSQL-specific: GIST INDEX for range containment queries ──
        // This enables O(log n) queries like: WHERE source_prefix = 'ABA' AND 701500 BETWEEN source_start AND source_end
        DB::statement('
            CREATE INDEX srm_source_range_gist_idx
            ON serial_range_mappings
            USING GIST (
                source_prefix,
                int4range(source_start, source_end, \'[]\')
            )
        ');

        // ── PostgreSQL-specific: EXCLUDE constraint for anti-overlap ──
        // Prevents inserting a range that overlaps with any existing range for the same prefix within the same seri
        DB::statement('
            ALTER TABLE serial_range_mappings
            ADD CONSTRAINT srm_no_source_overlap
            EXCLUDE USING GIST (
                x_pengganti_seri_id WITH =,
                source_prefix WITH =,
                int4range(source_start, source_end, \'[]\') WITH &&
            )
        ');

        // ── CHECK constraints ──
        DB::statement('ALTER TABLE serial_range_mappings ADD CONSTRAINT srm_source_range_valid CHECK (source_end >= source_start)');
        DB::statement('ALTER TABLE serial_range_mappings ADD CONSTRAINT srm_replacement_range_valid CHECK (replacement_end >= replacement_start)');
        DB::statement('ALTER TABLE serial_range_mappings ADD CONSTRAINT srm_count_match CHECK (source_end - source_start = replacement_end - replacement_start)');
    }

    public function down(): void
    {
        Schema::dropIfExists('serial_range_mappings');
    }
};
