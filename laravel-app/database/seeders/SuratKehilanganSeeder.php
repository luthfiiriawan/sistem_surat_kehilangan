<?php

namespace Database\Seeders;

use App\Models\SuratKehilangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SuratKehilanganSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['merk' => 'HONDA VARIO 150', 'jenis' => 'SEPEDA MOTOR'],
            ['merk' => 'YAMAHA NMAX 155', 'jenis' => 'SEPEDA MOTOR'],
            ['merk' => 'HONDA BEAT', 'jenis' => 'SEPEDA MOTOR'],
            ['merk' => 'TOYOTA AVANZA', 'jenis' => 'MOBIL PENUMPANG'],
            ['merk' => 'MITSUBISHI XPANDER', 'jenis' => 'MOBIL PENUMPANG'],
            ['merk' => 'HONDA SCOOPY', 'jenis' => 'SEPEDA MOTOR'],
            ['merk' => 'YAMAHA AEROX 155', 'jenis' => 'SEPEDA MOTOR'],
            ['merk' => 'TOYOTA INNOVA REBORN', 'jenis' => 'MOBIL PENUMPANG'],
            ['merk' => 'SUZUKI ERTIGA', 'jenis' => 'MOBIL PENUMPANG'],
            ['merk' => 'HONDA PCX 160', 'jenis' => 'SEPEDA MOTOR'],
            ['merk' => 'DAIHATSU SIGRA', 'jenis' => 'MOBIL PENUMPANG'],
            ['merk' => 'SUZUKI CARRY PICKUP', 'jenis' => 'MOBIL BARANG'],
            ['merk' => 'TOYOTA FORTUNER', 'jenis' => 'MOBIL PENUMPANG'],
            ['merk' => 'MITSUBISHI PAJERO SPORT', 'jenis' => 'MOBIL PENUMPANG'],
            ['merk' => 'HONDA HR-V', 'jenis' => 'MOBIL PENUMPANG'],
        ];

        $colors = ['HITAM', 'PUTIH', 'ABU-ABU', 'MERAKH', 'BIRU', 'PERAK', 'COKELAT', 'HIJAU'];
        $polresList = ['POLRESTABES BANDUNG', 'POLRES BOGOR', 'POLRES METRO BEKASI', 'POLRESTA TANGERANG', 'POLRES KARAWANG', 'POLRES SUBANG'];
        $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        for ($i = 1; $i <= 25; $i++) {
            $brandChoice = $brands[array_rand($brands)];
            
            // Generate realistic plate numbers (unique nopo)
            $nopo = 'D ' . rand(1000, 9999) . ' ' . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
            
            // Unique frame and engine numbers
            $nomorRangka = 'MHK' . strtoupper(bin2hex(random_bytes(7)));
            $nomorMesin = 'JF' . strtoupper(bin2hex(random_bytes(5)));
            
            $bpkbNum = 'BPKB-' . rand(1000000, 9999999) . '-' . chr(rand(65, 90));
            
            $monthIndex = rand(0, 11);
            $romanMonth = $romanMonths[$monthIndex];
            $year = rand(2024, 2026);
            
            $nomerSurat = sprintf("SKET/%d/%s/%d/Bid TIK", rand(100, 999), $romanMonth, $year);
            $nomorSuratKeterangan = sprintf("LK/B/%d/%s/%d/SPKT", rand(10, 200), $romanMonth, $year);

            $dateLapor = Carbon::now()->subDays(rand(1, 90));
            $dateTtd = $dateLapor->copy()->addDays(rand(0, 2));

            SuratKehilangan::create([
                'nomer_surat' => $nomerSurat,
                'bulan' => $romanMonth,
                'tahun' => (string) $year,
                'nopo' => $nopo,
                'merk' => $brandChoice['merk'],
                'jenis' => $brandChoice['jenis'],
                'tahun_pembuatan' => (string) rand(2015, 2024),
                'warna' => $colors[array_rand($colors)],
                'nomor_rangka' => $nomorRangka,
                'nomor_mesin' => $nomorMesin,
                'bpkb' => $bpkbNum,
                'polres' => $polresList[array_rand($polresList)],
                'nomor_surat_keterangan' => $nomorSuratKeterangan,
                'tanggal_tahun_lapor' => $dateLapor->format('Y-m-d'),
                'jenissurat' => 'BPKB',
                'jenis_surat' => 'ASLI',
                'taggalttd' => $dateTtd->format('Y-m-d'),
            ]);
        }
    }
}
