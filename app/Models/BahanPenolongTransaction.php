<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanPenolongTransaction extends Model
{
    protected $fillable = [
        'bahan_penolong_id',
        'tipe',
        'kategori',
        'jumlah',
        'stok_akhir',
        'keterangan',
        'created_by',
    ];

    public function bahanPenolong()
    {
        return $this->belongsTo(BahanPenolong::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
