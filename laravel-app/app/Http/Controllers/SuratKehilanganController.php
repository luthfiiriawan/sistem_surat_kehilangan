<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratKehilanganRequest;
use App\Models\SuratKehilangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            $query->whereDate('created_at', '>=', $request->input('start_date'))
                ->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $surats = $query->latest()->get();

        return view('surat-kehilangan.index', compact('surats'));
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

        SuratKehilangan::create($data);

        return redirect()->route('surat-kehilangan.index')->with('success', 'Data berhasil disimpan.');
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
        $templatePath = base_path('..\template_surat.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template surat tidak ditemukan.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValues($suratKehilangan->toArray());

        $tmpPath = storage_path('app/temp/' . uniqid('surat_', true) . '.docx');
        Storage::disk('local')->makeDirectory('temp');
        $templateProcessor->saveAs($tmpPath);

        return response()->download($tmpPath, 'SKET_Kehilangan_' . $suratKehilangan->nopo . '.docx')->deleteFileAfterSend(true);
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
            $query->whereDate('created_at', '>=', $request->input('start_date'))
                ->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $rows = $query->orderBy('id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Surat Kehilangan');

        $headers = ['NO', 'POLRES', 'NO POLISI', 'MERK / BRAND', 'JENIS KENDARAAN', 'TAHUN', 'NOMOR RANGKA', 'NOMOR MESIN', 'NOMOR BPKB', 'NO LAPORAN'];
        $sheet->fromArray([$headers], null, 'A2');
        $sheet->setCellValue('A1', 'DATA SURAT KETERANGAN KEHILANGAN');
        $sheet->mergeCells('A1:J1');

        $sheet->fromArray($rows->map(function ($row) {
            return [
                $row->id,
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

        $tmpPath = storage_path('app/temp/' . uniqid('excel_', true) . '.xlsx');
        Storage::disk('local')->makeDirectory('temp');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tmpPath);

        return response()->download($tmpPath, 'DataSuratKehilangan.xlsx')->deleteFileAfterSend(true);
    }
}
