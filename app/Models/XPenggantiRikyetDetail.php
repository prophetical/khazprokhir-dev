<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XPenggantiRikyetDetail extends Model
{
    protected $guarded = ['id'];

    public function pack()
    {
        return $this->belongsTo(XPenggantiRikyetPack::class, 'x_pengganti_rikyet_pack_id');
    }
}
