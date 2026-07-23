<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiLaporan extends Model
{
    protected $fillable = [
        'jenis_laporan',
        'tanggal_mulai',
        'tanggal_akhir',
        'tahun_anggaran',
        'tahun_emisi',
        'verified_by',
        'verified_at',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_akhir' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
