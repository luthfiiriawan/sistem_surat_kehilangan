<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratKehilanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_is_accessible(): void
    {
        $response = $this->get('/surat-kehilangan');

        $response->assertStatus(200);
        $response->assertSee('Sistem Surat Kehilangan');
    }

    public function test_can_create_surat_kehilangan_record(): void
    {
        $response = $this->post('/surat-kehilangan', [
            'nomer_surat' => '123',
            'bulan' => 'I',
            'tahun' => '2026',
            'nopo' => 'B 1234 CD',
            'merk' => 'Honda Vario',
            'jenis' => 'Sepeda Motor',
            'tahun_pembuatan' => '2021',
            'warna' => 'Hitam',
            'nomor_rangka' => 'RANGKA001',
            'nomor_mesin' => 'MESIN001',
            'bpkb' => 'BPKB001',
            'polres' => 'Polres Bandung',
            'nomor_surat_keterangan' => 'SK-001',
            'tanggal_tahun_lapor' => '2026-07-13',
            'jenissurat' => 'BPKB',
            'jenis_surat' => 'BPKB',
            'taggalttd' => '2026-07-13',
        ]);

        $response->assertRedirect('/surat-kehilangan');
        $this->assertDatabaseCount('surat_kehilangans', 1);
    }
}
