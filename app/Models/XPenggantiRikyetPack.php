<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XPenggantiRikyetPack extends Model
{
    protected $guarded = ['id'];

    public function seri()
    {
        return $this->belongsTo(XPenggantiSeri::class, 'x_pengganti_seri_id');
    }

    public function details()
    {
        return $this->hasMany(XPenggantiRikyetDetail::class, 'x_pengganti_rikyet_pack_id')->orderBy('slot');
    }
}
