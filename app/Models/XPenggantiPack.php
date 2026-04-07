<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XPenggantiPack extends Model
{
    protected $guarded = ['id'];

    public function seri()
    {
        return $this->belongsTo(XPenggantiSeri::class, 'x_pengganti_seri_id');
    }

    public function details()
    {
        return $this->hasMany(XPenggantiDetail::class, 'x_pengganti_pack_id')->orderBy('slot');
    }
}
