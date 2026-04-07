<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XPenggantiCutpackDetail extends Model
{
    protected $guarded = ['id'];

    public function pack()
    {
        return $this->belongsTo(XPenggantiCutpackPack::class, 'x_pengganti_cutpack_pack_id');
    }
}
