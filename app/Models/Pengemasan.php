<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengemasan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pengemasan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function detailPengemasans()
    {
        return $this->hasMany(DetailPengemasan::class, 'id_pengemasan');
    }

    public function packs()
    {
        return $this->hasMany(Pack::class, 'id_pengemasan');
    }
}
