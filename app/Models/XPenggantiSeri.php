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

    /**
     * Warna Tailwind berdasarkan Denominasi (Pecahan)
     */
    public function getPecahanColorClassAttribute(): string
    {
        return [
            'S' => 'bg-lime-500',    // 1.000
            'T' => 'bg-slate-500',   // 2.000
            'U' => 'bg-orange-500',  // 5.000
            'V' => 'bg-purple-600',  // 10.000
            'W' => 'bg-emerald-600', // 20.000
            'X' => 'bg-blue-600',    // 50.000
            'Y' => 'bg-rose-600',    // 100.000
        ][$this->pecahan] ?? 'bg-gray-500';
    }

    /**
     * Warna Hex berdasarkan Denominasi (untuk PDF/Print)
     */
    public function getPecahanColorHexAttribute(): string
    {
        return [
            'S' => '#84cc16', // lime-500
            'T' => '#64748b', // slate-500
            'U' => '#f97316', // orange-500
            'V' => '#9333ea', // purple-600
            'W' => '#059669', // emerald-600
            'X' => '#2563eb', // blue-600
            'Y' => '#e11d48', // rose-600
        ][$this->pecahan] ?? '#64748b';
    }
}
