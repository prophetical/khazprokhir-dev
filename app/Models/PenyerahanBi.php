<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyerahanBi extends Model
{
    use HasFactory;

    protected $table = 'penyerahan_bi';

    protected $fillable = [
        'tanggal_penyerahan',
        'nomor_ba',
        'pecahan',
        'tahun_emisi',
        'tahun_anggaran',
        'nomor_dus_awal',
        'nomor_dus_akhir',
        'jumlah_dus',
        'jumlah_bilyet',
        'status_data',
        'created_by',
    ];

    protected $casts = [
        'tanggal_penyerahan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'created_by');
    }
}
