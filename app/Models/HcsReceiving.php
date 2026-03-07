<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcsReceiving extends Model
{
    protected $guarded = ['id', 'tahun_anggaran'];

    public function packs()
    {
        return $this->hasMany(Pack::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'created_by');
    }
}
