<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKehilangan extends Model
{
    protected $fillable = [
        'nomer_surat',
        'bulan',
        'tahun',
        'nopo',
        'merk',
        'jenis',
        'tahun_pembuatan',
        'warna',
        'nomor_rangka',
        'nomor_mesin',
        'bpkb',
        'polres',
        'nomor_surat_keterangan',
        'tanggal_tahun_lapor',
        'jenissurat',
        'jenis_surat',
        'taggalttd',
    ];
}
