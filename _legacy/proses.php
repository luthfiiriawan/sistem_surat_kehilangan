<?php
session_start();
require "koneksi.php";

if ($db_error) {
    die("Koneksi database gagal: " . htmlspecialchars($db_error));
}

$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    die("Error: Library PHPWord belum terinstal. Silakan jalankan 'composer install' di server Anda.");
}
require_once $autoload;

// ── HANDLER GET: Download/Cetak Dokumen dari Record yang Ada ──
if (isset($_GET['action']) && $_GET['action'] === 'download' && isset($_GET['id'])) {
    $dl_id = (int)$_GET['id'];
    if ($dl_id <= 0) {
        die("ID tidak valid.");
    }

    $dlStmt = $conn->prepare("SELECT * FROM surat_kehilangan WHERE id = ? LIMIT 1");
    if (!$dlStmt) {
        die("Error prepare statement: " . $conn->error);
    }
    $dlStmt->bind_param("i", $dl_id);
    $dlStmt->execute();
    $dlResult = $dlStmt->get_result();
    $dlData   = $dlResult->fetch_assoc();
    $dlStmt->close();

    if (!$dlData) {
        die("Data dengan ID tersebut tidak ditemukan.");
    }

    $templateFile = __DIR__ . '/template_surat.docx';
    if (!file_exists($templateFile)) {
        die("Error: File template_surat.docx tidak ditemukan di direktori proyek.");
    }

    try {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templateFile);

        $templateProcessor->setValue('nomer_surat',            $dlData['nomer_surat']);
        $templateProcessor->setValue('bulan',                  $dlData['bulan']);
        $templateProcessor->setValue('tahun',                  $dlData['tahun']);
        $templateProcessor->setValue('nopo',                   $dlData['nopo']);
        $templateProcessor->setValue('merk',                   $dlData['merk']);
        $templateProcessor->setValue('jenis',                  $dlData['jenis']);
        $templateProcessor->setValue('tahun_pembuatan',        $dlData['tahun_pembuatan'] ?? '');
        $templateProcessor->setValue('warna',                  $dlData['warna']);
        $templateProcessor->setValue('nomor_rangka',           $dlData['nomor_rangka']);
        $templateProcessor->setValue('nomor_mesin',            $dlData['nomor_mesin']);
        $templateProcessor->setValue('bpkb',                   $dlData['bpkb']);
        $templateProcessor->setValue('polres',                 $dlData['polres']);
        $templateProcessor->setValue('nomor_surat_keterangan', $dlData['nomor_surat_keterangan']);
        $templateProcessor->setValue('tanggal_tahun_lapor',    $dlData['tanggal_tahun_lapor']);
        $templateProcessor->setValue('jenissurat',             $dlData['jenissurat']);
        $templateProcessor->setValue('jenis_surat',            $dlData['jenis_surat']);
        $templateProcessor->setValue('taggalttd',              $dlData['taggalttd']);

        $tempDir = __DIR__ . '/temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $safeNopo     = preg_replace('/[^A-Za-z0-9]/', '_', $dlData['nopo']);
        $filename     = "SKET_Kehilangan_" . $safeNopo . ".docx";
        $tempFilePath = $tempDir . '/' . uniqid('tmp_dl_', true) . '.docx';

        $templateProcessor->saveAs($tempFilePath);

        if (!file_exists($tempFilePath) || filesize($tempFilePath) === 0) {
            die("Error: File Word sementara gagal dibentuk.");
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($tempFilePath));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');

        readfile($tempFilePath);
        @unlink($tempFilePath);
        exit;

    } catch (Exception $e) {
        die("Error saat mengolah dokumen Word: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    // 1. Tangkap semua data dengan nama variabel yang konsisten
    $nomer_surat            = trim($_POST['nomer_surat'] ?? '');
    $bulan                  = trim($_POST['bulan'] ?? '');
    $tahun                  = trim($_POST['tahun'] ?? '');
    $nopo                   = strtoupper(trim($_POST['nopo'] ?? ''));
    $merk                   = trim($_POST['merk'] ?? '');
    $jenis                  = trim($_POST['jenis'] ?? '');
    $tahun_pembuatan        = trim($_POST['tahun_pembuatan'] ?? '');
    $warna                  = trim($_POST['warna'] ?? '');
    $nomor_rangka           = strtoupper(trim($_POST['nomor_rangka'] ?? ''));
    $nomor_mesin            = strtoupper(trim($_POST['nomor_mesin'] ?? '')); 
    $bpkb                   = strtoupper(trim($_POST['bpkb'] ?? ''));
    $polres                 = trim($_POST['polres'] ?? '');
    $nomor_surat_keterangan = trim($_POST['nomor_surat_keterangan'] ?? '');
    $tanggal_tahun_lapor    = trim($_POST['tanggal_tahun_lapor'] ?? '');
    $jenissurat             = trim($_POST['jenissurat'] ?? '');
    $jenis_surat            = trim($_POST['jenis_surat'] ?? '');
    $taggalttd              = trim($_POST['taggalttd'] ?? '');

    // Cek field kosong
    if (
        $nomer_surat === '' || $bulan === '' || $tahun === '' || $nopo === '' || 
        $merk === '' || $jenis === '' || $tahun_pembuatan === '' || $warna === '' || $nomor_rangka === '' || 
        $nomor_mesin === '' || $bpkb === '' || $polres === '' || $nomor_surat_keterangan === '' || 
        $tanggal_tahun_lapor === '' || $jenissurat === '' || $jenis_surat === '' || $taggalttd === ''
    ) {
        $_SESSION['flash_msg'] = ['text' => 'Semua data wajib diisi! Pastikan seluruh kolom terisi.', 'type' => 'warning'];
        header('Location: index.php');
        exit;
    }

    // 2. Cek duplikasi nopo, nomor_rangka, atau nomor_mesin sebelum INSERT
    $chk = $conn->prepare("SELECT id FROM surat_kehilangan WHERE nopo = ? OR nomor_rangka = ? OR nomor_mesin = ? LIMIT 1");
    if (!$chk) {
        die("Error prepare duplicate check: " . $conn->error);
    }
    $chk->bind_param("sss", $nopo, $nomor_rangka, $nomor_mesin);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) {
        $chk->close();
        $_SESSION['flash_msg'] = ['text' => 'Gagal! Nomor Polisi, Nomor Rangka, atau Nomor Mesin sudah terdaftar di database.', 'type' => 'danger'];
        header('Location: index.php');
        exit;
    }
    $chk->close();

    // 3. Siapkan Query SQL menggunakan Prepared Statements (Aman dari SQL Injection)
    $query = "INSERT INTO surat_kehilangan (nomer_surat, bulan, tahun, nopo, merk, jenis, tahun_pembuatan, warna, nomor_rangka, nomor_mesin, bpkb, polres, nomor_surat_keterangan, tanggal_tahun_lapor, jenissurat, jenis_surat, taggalttd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error pada query database: " . $conn->error);
    }

    // Bind Param (17 variabel string)
    $stmt->bind_param("sssssssssssssssss", 
        $nomer_surat, $bulan, $tahun, $nopo, $merk, $jenis, $tahun_pembuatan, $warna, 
        $nomor_rangka, $nomor_mesin, $bpkb, $polres, $nomor_surat_keterangan, 
        $tanggal_tahun_lapor, $jenissurat, $jenis_surat, $taggalttd
    );

    if ($stmt->execute()) {
        $stmt->close();

        // 3. Proses Generate Word dari Template
        $templateFile = __DIR__ . '/template_surat.docx';
        if (!file_exists($templateFile)) {
            die("Error: File template_surat.docx tidak ditemukan di direktori proyek.");
        }

        try {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templateFile);

            $templateProcessor->setValue('nomer_surat', $nomer_surat);
            $templateProcessor->setValue('bulan', $bulan);
            $templateProcessor->setValue('tahun', $tahun);
            $templateProcessor->setValue('nopo', $nopo);
            $templateProcessor->setValue('merk', $merk);
            $templateProcessor->setValue('jenis', $jenis);
            $templateProcessor->setValue('tahun_pembuatan', $tahun_pembuatan);
            $templateProcessor->setValue('warna', $warna);
            $templateProcessor->setValue('nomor_rangka', $nomor_rangka);
            $templateProcessor->setValue('nomor_mesin', $nomor_mesin);
            $templateProcessor->setValue('bpkb', $bpkb);
            $templateProcessor->setValue('polres', $polres);
            $templateProcessor->setValue('nomor_surat_keterangan', $nomor_surat_keterangan);
            $templateProcessor->setValue('tanggal_tahun_lapor', $tanggal_tahun_lapor);
            $templateProcessor->setValue('jenissurat', $jenissurat);
            $templateProcessor->setValue('jenis_surat', $jenis_surat);
            $templateProcessor->setValue('taggalttd', $taggalttd);

            // 4. Download File Otomatis Melalui Penyimpanan Sementara (Temp File)
            $tempDir = __DIR__ . '/temp';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $safeNopo = preg_replace('/[^A-Za-z0-9]/', '_', $nopo);
            $filename = "SKET_Kehilangan_" . $safeNopo . ".docx";
            $tempFilePath = $tempDir . '/' . uniqid('tmp_', true) . '.docx';

            $templateProcessor->saveAs($tempFilePath);

            if (!file_exists($tempFilePath) || filesize($tempFilePath) === 0) {
                die("Error: File Word sementara gagal dibentuk.");
            }

            // Bersihkan output buffer untuk mencegah file korup
            if (ob_get_level()) {
                ob_end_clean();
            }

            header("Content-Description: File Transfer");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($tempFilePath));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            header('Pragma: public');

            readfile($tempFilePath);
            @unlink($tempFilePath); // Hapus file sementara dari server
            exit;

        } catch (Exception $e) {
            die("Error saat mengolah dokumen Word: " . $e->getMessage());
        }

    } else {
        $_SESSION['flash_msg'] = ['text' => 'Gagal menyimpan data ke database! Silakan coba lagi.', 'type' => 'danger'];
        header('Location: index.php');
        exit;
    }
}
?>