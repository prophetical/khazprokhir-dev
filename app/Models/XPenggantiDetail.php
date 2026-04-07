<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XPenggantiDetail extends Model
{
    protected $guarded = ['id'];

    public function pack()
    {
        return $this->belongsTo(XPenggantiPack::class, 'x_pengganti_pack_id');
    }
}
