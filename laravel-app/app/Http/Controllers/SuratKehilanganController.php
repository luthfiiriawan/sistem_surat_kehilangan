<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratKehilanganRequest;
use App\Models\SuratKehilangan;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class SuratKehilanganController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKehilangan::query();

        if ($request->filled('cari')) {
            $keyword = $request->input('cari');
            $query->where(function ($q) use ($keyword) {
                $q->where('nopo', 'like', "%{$keyword}%")
                    ->orWhere('bpkb', 'like', "%{$keyword}%")
                    ->orWhere('merk', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('tanggal_tahun_lapor', '>=', $request->input('start_date'))
                ->whereDate('tanggal_tahun_lapor', '<=', $request->input('end_date'));
        }

        $surats = $query->latest()->get();

        return view('surat-kehilangan.index', compact('surats'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('query', '');
        $query = SuratKehilangan::query();

        if ($keyword) {
            // Prioritize matches that start with the keyword
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

        return response()->json(['surats' => $surats]);
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
        $query = SuratKehilangan::query();

        if ($request->filled('cari')) {
            $keyword = $request->input('cari');
            $query->where(function ($q) use ($keyword) {
                $q->where('nopo', 'like', "%{$keyword}%")
                    ->orWhere('bpkb', 'like', "%{$keyword}%")
                    ->orWhere('merk', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('tanggal_tahun_lapor', '>=', $request->input('start_date'))
                ->whereDate('tanggal_tahun_lapor', '<=', $request->input('end_date'));
        }

        $rows = $query->orderBy('id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Surat Kehilangan');

        $headers = ['NO', 'POLRES', 'NO POLISI', 'MERK / BRAND', 'JENIS KENDARAAN', 'TAHUN', 'NOMOR RANGKA', 'NOMOR MESIN', 'NOMOR BPKB', 'NO LAPORAN'];
        $sheet->fromArray([$headers], null, 'A2');
        $sheet->setCellValue('A1', 'DATA SURAT KETERANGAN KEHILANGAN');
        $sheet->mergeCells('A1:J1');

        $counter = 1;
        $sheet->fromArray($rows->map(function ($row) use (&$counter) {
            return [
                $counter++,
                $row->polres,
                $row->nopo,
                $row->merk,
                $row->jenis,
                $row->tahun_pembuatan,
                $row->nomor_rangka,
                $row->nomor_mesin,
                $row->bpkb,
                $row->nomor_surat_keterangan,
            ];
        })->toArray(), null, 'A3');

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tmpPath = $tempDir . '/' . uniqid('excel_', true) . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpPath);

        return response()->download($tmpPath, 'DataSuratKehilangan.xlsx')->deleteFileAfterSend(true);
    }
}
