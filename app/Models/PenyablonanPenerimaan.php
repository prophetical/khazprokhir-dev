<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyablonanPenerimaan extends Model
{
    protected $fillable = ['tanggal', 'jumlah', 'keterangan', 'created_by'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
