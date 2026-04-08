<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XPenggantiSeri extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function packs()
    {
        return $this->hasMany(XPenggantiPack::class, 'x_pengganti_seri_id');
    }

    /**
     * Label gabungan untuk dropdown di Form Khazai
     */
    public function getLabelAttribute(): string
    {
        return "Batch: {$this->batch} | Seri: {$this->seri} | Pecahan: {$this->pecahan} | TA: {$this->tahun_anggaran} / TE: {$this->tahun_emisi}";
    }

    public function cutpackPacks()
    {
        return $this->hasMany(XPenggantiCutpackPack::class, 'x_pengganti_seri_id');
    }

    public function rikyetPacks()
    {
        return $this->hasMany(XPenggantiRikyetPack::class, 'x_pengganti_seri_id');
    }
}
