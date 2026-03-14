<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHcsReceivingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['sortir', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nomor_bon' => ['required', 'string'],
            'tanggal_penerimaan' => ['required', 'date'],
            'pecahan' => ['required', 'in:S,T,U,V,W,X,Y'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'gilir' => ['required', 'in:Gilir 1,Gilir 2,Gilir 3'],
            'mesin' => ['required', 'string'],
            'supplier' => ['required', 'in:Rikyet,Cutpack'],
            'batch' => ['required', 'string', 'size:7'],
            'seri' => ['required', 'string'],
            'emisi' => ['required', 'date_format:Y'],
            'tahun_anggaran' => ['required', 'in:2024,2025,2026,2027'],
            'repass' => ['nullable', 'in:repass'],
            'packs' => ['required', 'array'],
            'packs.*' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
