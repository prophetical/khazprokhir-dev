<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetTahunan extends Model
{
    use HasFactory;

    protected $table = 'target_tahunan';

    protected $fillable = [
        'pecahan',
        'tahun_anggaran',
        'tahun_emisi',
        'target',
    ];
}
