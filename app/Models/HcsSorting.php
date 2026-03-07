<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcsSorting extends Model
{
    protected $fillable = [
        'pecahan',
        'batch',
        'seri',
        'supplier',
        'packs_selected',
        'jumlah_pack',
        'jumlah_bilyet',
        'petugas_1',
        'petugas_2',
        'tanggal_sortir',
        'gilir',
        'created_by',
    ];

    protected $casts = [
        'tanggal_sortir' => 'date',
        'packs_selected' => 'array',
    ];

    public function packs()
    {
        return $this->hasMany(Pack::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'created_by');
    }
}
