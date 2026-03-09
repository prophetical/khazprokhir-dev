<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetBulanan extends Model
{
    use HasFactory;

    protected $table = 'target_bulanan';

    protected $fillable = [
        'pecahan',
        'tahun_anggaran',
        'tahun_emisi',
        'bulan_1',
        'bulan_2',
        'bulan_3',
        'bulan_4',
        'bulan_5',
        'bulan_6',
        'bulan_7',
        'bulan_8',
        'bulan_9',
        'bulan_10',
        'bulan_11',
        'bulan_12',
    ];
}
