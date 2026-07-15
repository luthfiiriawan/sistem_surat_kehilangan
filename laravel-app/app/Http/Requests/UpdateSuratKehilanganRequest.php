<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuratKehilanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomer_surat' => ['required', 'string'],
            'bulan' => ['required', 'string'],
            'tahun' => ['required', 'string'],
            'nopo' => ['required', 'string', 'max:255'],
            'merk' => ['required', 'string'],
            'jenis' => ['required', 'string'],
            'tahun_pembuatan' => ['required', 'string'],
            'warna' => ['required', 'string'],
            'nomor_rangka' => ['required', 'string', 'max:255'],
            'nomor_mesin' => ['required', 'string', 'max:255'],
            'bpkb' => ['required', 'string'],
            'polres' => ['required', 'string'],
            'nomor_surat_keterangan' => ['required', 'string'],
            'tanggal_tahun_lapor' => ['required', 'string'],
            'jenissurat' => ['required', 'string'],
            'jenis_surat' => ['required', 'string'],
            'taggalttd' => ['required', 'string'],
        ];
    }
}
