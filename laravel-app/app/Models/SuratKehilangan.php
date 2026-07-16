<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // Accessor for edit form (YYYY-MM-DD)
    public function getTanggalTahunLaporFormAttribute()
    {
        return $this->tanggal_tahun_lapor ? Carbon::parse($this->tanggal_tahun_lapor)->format('Y-m-d') : '';
    }

    // Accessor for edit form (YYYY-MM-DD)
    public function getTaggalttdFormAttribute()
    {
        return $this->taggalttd ? Carbon::parse($this->taggalttd)->format('Y-m-d') : '';
    }

    // Accessor for template (Indonesian format)
    public function getTanggalTahunLaporTemplateAttribute()
    {
        return $this->formatTanggalIndonesia($this->tanggal_tahun_lapor);
    }

    // Accessor for template (Indonesian format)
    public function getTaggalttdTemplateAttribute()
    {
        return $this->formatTanggalIndonesia($this->taggalttd);
    }

    private function formatTanggalIndonesia($tanggal)
    {
        if (empty($tanggal)) return '';
        Carbon::setLocale('id');
        return Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
}
