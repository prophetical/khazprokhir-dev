<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    protected $guarded = ['id'];

    public function hcsReceiving()
    {
        return $this->belongsTo(HcsReceiving::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'created_by');
    }
}
