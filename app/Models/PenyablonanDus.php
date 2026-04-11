<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyablonanDus extends Model
{
    protected $table = 'penyablonan_dus';
    protected $fillable = [
        'tanggal', 'gilir', 'pecahan', 'te', 'ta', 'no_awal', 'no_akhir', 'jumlah', 'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
