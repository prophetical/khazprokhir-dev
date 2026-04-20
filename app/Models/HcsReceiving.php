<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcsReceiving extends Model
{
    protected $guarded = ['id'];

    public function packs()
    {
        return $this->hasMany(Pack::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function barcode()
    {
        if ($this->barcode_token) {
            return HcsKhazaiRegistration::where('barcode_token', $this->barcode_token)->first();
        }

        // Fallback untuk data lama atau manual entry
        return HcsKhazaiRegistration::where('nomor_bon', $this->nomor_bon)
            ->where('batch', $this->batch)
            ->where('seri', $this->seri)
            ->latest()
            ->first();
    }
}
