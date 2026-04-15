<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class SerialRangeMapping extends Model
{
    protected $table = 'serial_range_mappings';

    protected $guarded = ['id'];

    protected $casts = [
        'source_start'      => 'integer',
        'source_end'        => 'integer',
        'replacement_start' => 'integer',
        'replacement_end'   => 'integer',
        'nomor_pack'        => 'integer',
    ];

    /* ───────────────────── Relasi ───────────────────── */

    public function seri(): BelongsTo
    {
        return $this->belongsTo(XPenggantiSeri::class, 'x_pengganti_seri_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ───────────────────── Atribut Komputasi ───────────────────── */

    /**
     * Jumlah bilyet yang tercakup dalam rentang ini.
     */
    public function getBilyetCountAttribute(): int
    {
        return $this->source_end - $this->source_start + 1;
    }

    /**
     * Tampilan nomor seri sumber lengkap: contoh: "ABA701001 – ABA702000"
     */
    public function getSourceDisplayAttribute(): string
    {
        $start = $this->source_prefix . str_pad($this->source_start, 6, '0', STR_PAD_LEFT);
        $end   = $this->source_prefix . str_pad($this->source_end, 6, '0', STR_PAD_LEFT);

        return $this->source_start === $this->source_end
            ? $start
            : "{$start} – {$end}";
    }

    /**
     * Tampilan nomor seri pengganti lengkap: contoh: "ZZA000001 – ZZA001000"
     */
    public function getReplacementDisplayAttribute(): string
    {
        $start = $this->replacement_prefix . str_pad($this->replacement_start, 6, '0', STR_PAD_LEFT);
        $end   = $this->replacement_prefix . str_pad($this->replacement_end, 6, '0', STR_PAD_LEFT);

        return $this->replacement_start === $this->replacement_end
            ? $start
            : "{$start} – {$end}";
    }

    /* ───────────────────── Scope Query ───────────────────── */

    /**
     * Mencari rentang yang berisi nomor seri sumber tertentu.
     * Menggunakan indeks GIST untuk performa O(log n).
     *
     * Penggunaan: SerialRangeMapping::containingSourceSerial('ABA', 701500)->first()
     */
    public function scopeContainingSourceSerial($query, string $prefix, int $serial)
    {
        return $query->where('source_prefix', $prefix)
                     ->where('source_start', '<=', $serial)
                     ->where('source_end', '>=', $serial);
    }

    /**
     * Mencari rentang yang berisi nomor seri pengganti tertentu.
     *
     * Penggunaan: SerialRangeMapping::containingReplacementSerial('ZZA', 500)->first()
     */
    public function scopeContainingReplacementSerial($query, string $prefix, int $serial)
    {
        return $query->where('replacement_prefix', $prefix)
                     ->where('replacement_start', '<=', $serial)
                     ->where('replacement_end', '>=', $serial);
    }

    /**
     * Memfilter pemetaan untuk master seri tertentu.
     */
    public function scopeForSeri($query, int $seriId)
    {
        return $query->where('x_pengganti_seri_id', $seriId);
    }

    /**
     * Memfilter pemetaan untuk nomor pack tertentu.
     */
    public function scopeForPack($query, int $seriId, int $packNumber)
    {
        return $query->where('x_pengganti_seri_id', $seriId)
                     ->where('nomor_pack', $packNumber);
    }

    /* ───────────────────── Metode Helper ───────────────────── */

    /**
     * Berdasarkan nomor seri sumber, menghitung nomor seri pengganti yang sesuai.
     */
    public function calculateReplacement(int $sourceSerial): ?array
    {
        if ($sourceSerial < $this->source_start || $sourceSerial > $this->source_end) {
            return null;
        }

        $offset = $sourceSerial - $this->source_start;

        return [
            'prefix' => $this->replacement_prefix,
            'serial' => $this->replacement_start + $offset,
            'full'   => $this->replacement_prefix . str_pad($this->replacement_start + $offset, 6, '0', STR_PAD_LEFT),
        ];
    }

    /**
     * Berdasarkan nomor seri pengganti, menghitung nomor seri sumber asli.
     */
    public function calculateSource(int $replacementSerial): ?array
    {
        if ($replacementSerial < $this->replacement_start || $replacementSerial > $this->replacement_end) {
            return null;
        }

        $offset = $replacementSerial - $this->replacement_start;

        return [
            'prefix' => $this->source_prefix,
            'serial' => $this->source_start + $offset,
            'full'   => $this->source_prefix . str_pad($this->source_start + $offset, 6, '0', STR_PAD_LEFT),
        ];
    }
}
