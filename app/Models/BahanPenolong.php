<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanPenolong extends Model
{
    protected $fillable = [
        'nama_bahan',
        'kode_material',
        'satuan',
        'stok',
        'min_stok',
        'keterangan',
    ];

    public function transactions()
    {
        return $this->hasMany(BahanPenolongTransaction::class);
    }
}
