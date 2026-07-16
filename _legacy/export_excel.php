<?php
require "koneksi.php";

if ($db_error) {
    die("Koneksi database gagal: " . htmlspecialchars($db_error));
}

$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    die("Error: Library PhpSpreadsheet belum terinstal. Jalankan 'composer install'.");
}
require_once $autoload;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

// ── TANGKAP PARAMETER FILTER ──
$start_date    = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date      = isset($_GET['end_date'])   ? trim($_GET['end_date'])   : '';
$keyword       = isset($_GET['cari'])       ? trim($_GET['cari'])       : '';
$filter_active = ($start_date !== '' && $end_date !== '');

// ── AMBIL DATA DENGAN PREPARED STATEMENTS ──
$rows       = [];
$conditions = [];
$types      = '';
$params     = [];

if ($keyword !== '') {
    $conditions[] = "(nopo LIKE ? OR bpkb LIKE ? OR merk LIKE ?)";
    $searchParam  = "%" . $keyword . "%";
    $types       .= 'sss';
    $params[]     = $searchParam;
    $params[]     = $searchParam;
    $params[]     = $searchParam;
}

if ($filter_active) {
    $conditions[] = "DATE(created_at) >= ?";
    $conditions[] = "DATE(created_at) <= ?";
    $types       .= 'ss';
    $params[]     = $start_date;
    $params[]     = $end_date;
}

$whereClause = count($conditions) > 0
    ? 'WHERE ' . implode(' AND ', $conditions)
    : '';

$query = "SELECT * FROM surat_kehilangan {$whereClause} ORDER BY id ASC";

if (!empty($params)) {
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
    }
} else {
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
}

// ── BUAT SPREADSHEET ──
$spreadsheet = new Spreadsheet();
$sheet       = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Surat Kehilangan');

// ── DEFINISI KOLOM ──
$headers = [
    'A' => 'NO',
    'B' => 'POLRES',
    'C' => 'NO POLISI',
    'D' => 'MERK / BRAND',
    'E' => 'JENIS KENDARAAN',
    'F' => 'TAHUN',
    'G' => 'NOMOR RANGKA',
    'H' => 'NOMOR MESIN',
    'I' => 'NOMOR BPKB',
    'J' => 'NO LAPORAN',
];

// ── HEADER TITLE ROW ──
$filterLabel = $filter_active
    ? "Periode: " . $start_date . " s/d " . $end_date
    : "Semua Data";

$sheet->mergeCells('A1:J1');
$sheet->setCellValue('A1', 'DATA SURAT KETERANGAN KEHILANGAN — ' . strtoupper($filterLabel));
$sheet->getStyle('A1')->applyFromArray([
    'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '111827']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
]);
$sheet->getRowDimension(1)->setRowHeight(30);

// ── HEADER KOLOM (Row 2) ──
foreach ($headers as $col => $label) {
    $sheet->setCellValue($col . '2', $label);
}

$headerStyle = [
    'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
    'borders'   => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'B91C1C']],
    ],
];
$sheet->getStyle('A2:J2')->applyFromArray($headerStyle);
$sheet->getRowDimension(2)->setRowHeight(22);

// ── ISI DATA ──
$rowNum = 3;
$no     = 1;
foreach ($rows as $r) {
    $sheet->setCellValue('A' . $rowNum, $no++);
    $sheet->setCellValue('B' . $rowNum, $r['polres']                 ?? '');
    $sheet->setCellValue('C' . $rowNum, $r['nopo']                   ?? '');
    $sheet->setCellValue('D' . $rowNum, $r['merk']                   ?? '');
    $sheet->setCellValue('E' . $rowNum, $r['jenis']                  ?? '');
    $sheet->setCellValue('F' . $rowNum, $r['tahun_pembuatan']        ?? '');
    $sheet->setCellValue('G' . $rowNum, $r['nomor_rangka']           ?? '');
    $sheet->setCellValue('H' . $rowNum, $r['nomor_mesin']            ?? '');
    $sheet->setCellValue('I' . $rowNum, $r['bpkb']                   ?? '');
    $sheet->setCellValue('J' . $rowNum, $r['nomor_surat_keterangan'] ?? '');

    // Zebra striping
    $fillColor = ($no % 2 === 0) ? 'F8FAFC' : 'FFFFFF';
    $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->applyFromArray([
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $fillColor]],
        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        'borders'   => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
        ],
    ]);
    $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C' . $rowNum)->getFont()->setBold(true);

    $sheet->getRowDimension($rowNum)->setRowHeight(18);
    $rowNum++;
}

// ── LEBAR KOLOM OTOMATIS ──
$colWidths = ['A' => 6, 'B' => 28, 'C' => 14, 'D' => 22, 'E' => 18, 'F' => 10, 'G' => 24, 'H' => 24, 'I' => 16, 'J' => 32];
foreach ($colWidths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// ── FREEZE HEADER ──
$sheet->freezePane('A3');

// ── NAMA FILE ──
$fileSuffix = $filter_active
    ? $start_date . '_sd_' . $end_date
    : date('Y-m-d');
$filename = "DataSuratKehilangan_" . $fileSuffix . ".xlsx";

// ── STREAM DOWNLOAD ──
if (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
