<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcsKhazaiRegistration extends Model
{
    protected $table = 'registrasi_penerimaan_hcs';

    protected $guarded = ['id'];

    protected $casts = [
        'packs_data' => 'array',
        'tanggal_pembuatan' => 'date',
    ];

    public function petugasKhazai()
    {
        return $this->belongsTo(User::class, 'petugas_khazai_id');
    }
}
