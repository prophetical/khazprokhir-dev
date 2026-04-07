<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XPenggantiCutpackPack extends Model
{
    protected $guarded = ['id'];

    public function seri()
    {
        return $this->belongsTo(XPenggantiSeri::class, 'x_pengganti_seri_id');
    }

    public function details()
    {
        return $this->hasMany(XPenggantiCutpackDetail::class, 'x_pengganti_cutpack_pack_id');
    }
}
