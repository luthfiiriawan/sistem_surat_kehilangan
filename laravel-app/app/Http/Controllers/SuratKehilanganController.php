<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratKehilanganRequest;
use App\Models\SuratKehilangan;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class SuratKehilanganController extends Controller
{
    private const JENIS_SURAT_STNK = 'STNK';
    private const JENIS_SURAT_BPKB = 'BPKB';
    private const JENIS_SURAT_KEDUANYA = 'BPKB dan STNK';

    public function index(Request $request)
    {
        $surats = $this->buildFilteredQuery($request)->latest()->get();
        $stats = $this->computeStats($surats);

        return view('surat-kehilangan.index', compact('surats', 'stats'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('query', '');
        $query = $this->buildFilteredQuery($request, $keyword);

        $surats = $query->latest()->get()->map(function ($surat) use ($keyword) {
            // Calculate relevance score to sort by
            $score = 0;
            if (str_starts_with(strtolower($surat->nopo), strtolower($keyword))) $score += 3;
            if (str_starts_with(strtolower($surat->bpkb), strtolower($keyword))) $score += 3;
            if (str_starts_with(strtolower($surat->merk), strtolower($keyword))) $score += 3;
            if (str_contains(strtolower($surat->nopo), strtolower($keyword))) $score += 1;
            if (str_contains(strtolower($surat->bpkb), strtolower($keyword))) $score += 1;
            if (str_contains(strtolower($surat->merk), strtolower($keyword))) $score += 1;
            $surat->relevance_score = $score;
            return $surat;
        })->sortByDesc('relevance_score')->values();

        return response()->json([
            'surats' => $surats,
            'stats' => $this->computeStats($surats),
        ]);
    }

    public function create()
    {
        return view('surat-kehilangan.create');
    }

    public function store(StoreSuratKehilanganRequest $request)
    {
        $data = $request->validated();
        $data['nopo'] = strtoupper($data['nopo']);
        $data['nomor_rangka'] = strtoupper($data['nomor_rangka']);
        $data['nomor_mesin'] = strtoupper($data['nomor_mesin']);
        $data['bpkb'] = strtoupper($data['bpkb']);

        $surat = SuratKehilangan::create($data);

        return redirect()->route('surat-kehilangan.index')->with('success', 'Surat berhasil disimpan.');
    }

    public function show(SuratKehilangan $suratKehilangan)
    {
        return view('surat-kehilangan.show', compact('suratKehilangan'));
    }

    public function edit(SuratKehilangan $suratKehilangan)
    {
        return view('surat-kehilangan.edit', compact('suratKehilangan'));
    }

    public function update(StoreSuratKehilanganRequest $request, SuratKehilangan $suratKehilangan)
    {
        $data = $request->validated();
        $data['nopo'] = strtoupper($data['nopo']);
        $data['nomor_rangka'] = strtoupper($data['nomor_rangka']);
        $data['nomor_mesin'] = strtoupper($data['nomor_mesin']);
        $data['bpkb'] = strtoupper($data['bpkb']);

        $suratKehilangan->update($data);

        return redirect()->route('surat-kehilangan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(SuratKehilangan $suratKehilangan)
    {
        $suratKehilangan->delete();

        return redirect()->route('surat-kehilangan.index')->with('success', 'Data berhasil dihapus.');
    }

    public function download(SuratKehilangan $suratKehilangan)
    {
        $templatePath = base_path('../template_surat.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template surat tidak ditemukan.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Set values with formatted dates for template
        $templateProcessor->setValue('nomer_surat', $suratKehilangan->nomer_surat);
        $templateProcessor->setValue('bulan', $suratKehilangan->bulan);
        $templateProcessor->setValue('tahun', $suratKehilangan->tahun);
        $templateProcessor->setValue('nopo', $suratKehilangan->nopo);
        $templateProcessor->setValue('merk', $suratKehilangan->merk);
        $templateProcessor->setValue('jenis', $suratKehilangan->jenis);
        $templateProcessor->setValue('tahun_pembuatan', $suratKehilangan->tahun_pembuatan);
        $templateProcessor->setValue('warna', $suratKehilangan->warna);
        $templateProcessor->setValue('nomor_rangka', $suratKehilangan->nomor_rangka);
        $templateProcessor->setValue('nomor_mesin', $suratKehilangan->nomor_mesin);
        $templateProcessor->setValue('bpkb', $suratKehilangan->bpkb);
        $templateProcessor->setValue('polres', $suratKehilangan->polres);
        $templateProcessor->setValue('nomor_surat_keterangan', $suratKehilangan->nomor_surat_keterangan);
        $templateProcessor->setValue('tanggal_tahun_lapor', $suratKehilangan->tanggal_tahun_lapor_template);
        $templateProcessor->setValue('jenissurat', $suratKehilangan->jenissurat);
        $templateProcessor->setValue('jenis_surat', $suratKehilangan->jenis_surat);
        $templateProcessor->setValue('taggalttd', $suratKehilangan->taggalttd_template);

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tmpPath = $tempDir . '/' . uniqid('surat_', true) . '.docx';
        $templateProcessor->saveAs($tmpPath);

        $safeNopo = preg_replace('/[^A-Za-z0-9]/', '_', $suratKehilangan->nopo);
        return response()->download($tmpPath, 'SKET_Kehilangan_' . $safeNopo . '.docx')->deleteFileAfterSend(true);
    }

    public function exportExcel(Request $request)
    {
        // Ubah pengurutan agar data yang baru ditambahkan berada di paling atas
        $rows = $this->buildFilteredQuery($request)->latest('id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Surat Kehilangan');

        // Pastikan garis kisi (gridlines) tetap terlihat setelah diberi fill style
        $sheet->setShowGridlines(true);

        // Tulis judul di baris A1
        $sheet->setCellValue('A1', 'DATA SURAT KETERANGAN KEHILANGAN');
        $sheet->mergeCells('A1:R1');

        // Setup Group Headers (Row 2) dan Sub Headers (Row 3)
        $sheet->mergeCells('A2:A3');
        $sheet->setCellValue('A2', 'NO');

        $sheet->mergeCells('B2:G2');
        $sheet->setCellValue('B2', 'INFORMASI SURAT & LAPORAN');

        $sheet->mergeCells('H2:J2');
        $sheet->setCellValue('H2', 'DOKUMEN KEHILANGAN');

        $sheet->mergeCells('K2:R2');
        $sheet->setCellValue('K2', 'IDENTITAS KENDARAAN');

        // Sub Headers (Row 3)
        $subHeaders = [
            'B3' => 'NO. SURAT',
            'C3' => 'BULAN',
            'D3' => 'TAHUN',
            'E3' => 'POLRES',
            'F3' => 'NO. LAPORAN',
            'G3' => 'TANGGAL LAPOR',
            'H3' => 'JENIS DOKUMEN',
            'I3' => 'DETAIL DOKUMEN',
            'J3' => 'TANGGAL TTD',
            'K3' => 'NO. POLISI',
            'L3' => 'MERK / BRAND',
            'M3' => 'JENIS KENDARAAN',
            'N3' => 'TAHUN PEMBUATAN',
            'O3' => 'WARNA',
            'P3' => 'NOMOR RANGKA',
            'Q3' => 'NOMOR MESIN',
            'R3' => 'NOMOR BPKB',
        ];

        foreach ($subHeaders as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Tulis Baris Data mulai dari baris 4 (A4)
        $counter = 1;
        $sheet->fromArray($rows->map(function ($row) use (&$counter) {
            return [
                $counter++,
                $row->nomer_surat,
                $row->bulan,
                $row->tahun,
                $row->polres,
                $row->nomor_surat_keterangan,
                $row->tanggal_tahun_lapor_template,
                $row->jenissurat,
                $row->jenis_surat,
                $row->taggalttd_template,
                $row->nopo,
                $row->merk,
                $row->jenis,
                $row->tahun_pembuatan,
                $row->warna,
                $row->nomor_rangka,
                $row->nomor_mesin,
                $row->bpkb,
            ];
        })->toArray(), null, 'A4');

        $lastRow = 3 + count($rows);

        // Styling untuk Baris Judul (A1)
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1F4E79'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Styling untuk Baris Group Header (A2:R2)
        $sheet->getRowDimension(2)->setRowHeight(28);
        $sheet->getStyle('A2:R2')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ]);

        // Styling untuk Baris Sub Header (A3:R3)
        $sheet->getRowDimension(3)->setRowHeight(24);
        $sheet->getStyle('A3:R3')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2F5597'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ]);

        // Styling khusus untuk sel 'NO' yang di-merge (A2:A3)
        $sheet->getStyle('A2:A3')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ]);

        // Styling untuk Baris Data (A4 s/d selesai)
        for ($i = 4; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(22);
            
            $sheet->getStyle('A' . $i . ':R' . $i)->applyFromArray([
                'font' => [
                    'name' => 'Segoe UI',
                    'size' => 10,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E0E0E0'],
                    ],
                ],
            ]);

            // Zebra striping selang-seling (baris genap diberi latar belakang sangat soft)
            if ($i % 2 === 0) {
                $sheet->getStyle('A' . $i . ':R' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F6FA'],
                    ],
                ]);
            }

            // Perataan kolom tertentu agar rapi
            $sheet->getStyle('A' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // NO
            $sheet->getStyle('C' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // BULAN
            $sheet->getStyle('D' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // TAHUN
            $sheet->getStyle('G' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // TANGGAL LAPOR
            $sheet->getStyle('J' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // TANGGAL TTD
            $sheet->getStyle('K' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // NO POLISI
            $sheet->getStyle('N' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // TAHUN PEMBUATAN
        }

        // Kalkulasi lebar kolom secara dinamis dengan margin aman (+4 karakter) dan batas minimal
        // Dimulai dari baris 3 (sub-headers) ke bawah agar tidak terpengaruh merge cells di baris 1 & 2
        $columns = range('A', 'R');
        foreach ($columns as $col) {
            if ($col === 'A') {
                $sheet->getColumnDimension($col)->setWidth(6);
                continue;
            }
            
            $maxLength = 0;
            // Panjang teks sub-header (Row 3)
            $headerVal = $sheet->getCell($col . '3')->getValue();
            $maxLength = max($maxLength, strlen((string)$headerVal));
            
            // Panjang teks data (Row 4 ke bawah)
            for ($row = 4; $row <= $lastRow; $row++) {
                $cellVal = $sheet->getCell($col . $row)->getValue();
                $maxLength = max($maxLength, strlen((string)$cellVal));
            }
            
            $sheet->getColumnDimension($col)->setWidth(max($maxLength + 4, 12));
        }

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tmpPath = $tempDir . '/' . uniqid('excel_', true) . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpPath);

        return response()->download($tmpPath, 'DataSuratKehilangan.xlsx')->deleteFileAfterSend(true);
    }

    private function buildFilteredQuery(Request $request, ?string $searchKeyword = null)
    {
        $query = SuratKehilangan::query();
        $keyword = $searchKeyword ?? $request->input('cari');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nopo', 'like', "{$keyword}%")
                    ->orWhere('bpkb', 'like', "{$keyword}%")
                    ->orWhere('merk', 'like', "{$keyword}%")
                    ->orWhere('nopo', 'like', "%{$keyword}%")
                    ->orWhere('bpkb', 'like', "%{$keyword}%")
                    ->orWhere('merk', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('tanggal_tahun_lapor', '>=', $request->input('start_date'))
                ->whereDate('tanggal_tahun_lapor', '<=', $request->input('end_date'));
        }

        return $query;
    }

    private function computeStats($surats): array
    {
        $knownTypes = [
            self::JENIS_SURAT_STNK,
            self::JENIS_SURAT_BPKB,
            self::JENIS_SURAT_KEDUANYA,
        ];

        return [
            'total' => $surats->count(),
            'stnk' => $surats->where('jenissurat', self::JENIS_SURAT_STNK)->count(),
            'bpkb' => $surats->where('jenissurat', self::JENIS_SURAT_BPKB)->count(),
            'keduanya' => $surats->where('jenissurat', self::JENIS_SURAT_KEDUANYA)->count(),
            'lainnya' => $surats->reject(fn ($surat) => in_array($surat->jenissurat, $knownTypes, true))->count(),
        ];
    }
}
