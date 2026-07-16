<?php
session_start();
require "koneksi.php";

// ── PROTEKSI KONEKSI DATABASE ──
if ($db_error) {
    die("Koneksi database gagal: " . htmlspecialchars($db_error));
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: data.php");
    exit;
}

// ── AMBIL DATA SURAT LAMA (PREPARED STATEMENTS) ──
$data = null;
$stmt = $conn->prepare("SELECT * FROM surat_kehilangan WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
}

if (!$data) {
    $_SESSION['flash_msg'] = ['text' => 'Data tidak ditemukan!', 'type' => 'danger'];
    header('Location: data.php');
    exit;
}

// ── PROSES UPDATE DATA ──
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomer_surat            = trim($_POST['nomer_surat'] ?? '');
    $bulan                  = trim($_POST['bulan'] ?? '');
    $tahun                  = trim($_POST['tahun'] ?? '');
    $nopo                    = strtoupper(trim($_POST['nopo'] ?? ''));
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
        header('Location: edit.php?id=' . $id);
        exit;
    } else {
        // Cek duplikasi sebelum UPDATE (abaikan record milik sendiri)
        $chk = $conn->prepare("SELECT id FROM surat_kehilangan WHERE (nopo = ? OR nomor_rangka = ? OR nomor_mesin = ?) AND id != ? LIMIT 1");
        if (!$chk) {
            die("Error prepare duplicate check: " . $conn->error);
        }
        $chk->bind_param("sssi", $nopo, $nomor_rangka, $nomor_mesin, $id);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $chk->close();
            $_SESSION['flash_msg'] = ['text' => 'Gagal Update! Nomor Polisi, Nomor Rangka, atau Nomor Mesin sudah dipakai oleh data kendaraan lain.', 'type' => 'danger'];
            header('Location: edit.php?id=' . $id);
            exit;
        }
        $chk->close();

        // Update 16 kolom data secara aman menggunakan prepared statements
        $query = "UPDATE surat_kehilangan SET 
            nomer_surat = ?, 
            bulan = ?, 
            tahun = ?, 
            nopo = ?, 
            merk = ?, 
            jenis = ?, 
            tahun_pembuatan = ?, 
            warna = ?, 
            nomor_rangka = ?, 
            nomor_mesin = ?, 
            bpkb = ?, 
            polres = ?, 
            nomor_surat_keterangan = ?, 
            tanggal_tahun_lapor = ?, 
            jenissurat = ?, 
            jenis_surat = ?, 
            taggalttd = ? 
            WHERE id = ?";
        
        $upd = $conn->prepare($query);
        if ($upd) {
            $upd->bind_param("sssssssssssssssssi", 
                $nomer_surat, $bulan, $tahun, $nopo, $merk, $jenis, $tahun_pembuatan, $warna, 
                $nomor_rangka, $nomor_mesin, $bpkb, $polres, $nomor_surat_keterangan, 
                $tanggal_tahun_lapor, $jenissurat, $jenis_surat, $taggalttd, 
                $id
            );
            if ($upd->execute()) {
                $upd->close();
                $_SESSION['flash_msg'] = ['text' => 'Perubahan data berhasil disimpan!', 'type' => 'success'];
                header('Location: data.php');
                exit;
            } else {
                $_SESSION['flash_msg'] = ['text' => 'Gagal memperbarui data: ' . $upd->error, 'type' => 'danger'];
                header('Location: edit.php?id=' . $id);
                exit;
            }
        } else {
            die("Error prepare statement: " . $conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat Kehilangan Bid TIK POLRI</title>
    <link rel="manifest" href="manifest.json?v=3">
    <link rel="icon" type="image/jpeg" href="logo_tik.jpg?v=3">
    
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts Outfit & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #111827; /* Dark Charcoal/Black */
            --primary-light: #1f2937; /* Gray 800 */
            --accent-color: #dc2626; /* Red */
            --accent-hover: #b91c1c; /* Dark Red */
            --bg-color: #f1f5f9; /* Slate 100 (Metallic Silver feel) */
            --card-bg: #ffffff;
            --border-color: #cbd5e1; /* Slate 300 */
            --text-main: #374151; /* Gray 700 */
            --text-muted: #6b7280; /* Gray 500 */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* ── HEADER ── */
        .app-header {
            background-color: var(--primary-color);
            color: #ffffff;
            padding: 20px 0;
            border-bottom: 4px solid var(--accent-color);
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15);
        }
        .logo-box {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-box img {
            height: 52px;
            object-fit: contain;
        }

        /* ── CARDS & LAYOUT ── */
        .card-premium {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            overflow: hidden;
        }
        .section-legend {
            background-color: #f1f5f9;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-color);
        }

        /* ── FORM CONTROLS ── */
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
            outline: none;
        }

        /* ── BUTTONS ── */
        .btn-accent {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            color: #ffffff;
            border: none;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            transition: all 0.2s;
        }
        .btn-accent:hover {
            background: linear-gradient(135deg, var(--accent-hover), #991b1b);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
        }
        .btn-accent:active {
            transform: translateY(0);
        }
        .btn-secondary-custom {
            background-color: #f1f5f9;
            color: var(--text-main);
            border: 1.5px solid var(--border-color);
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-secondary-custom:hover {
            background-color: #e2e8f0;
            color: var(--primary-color);
        }
    </style>
</head>
<body>

    <!-- ── HEADER ── -->
    <header class="app-header mb-5">
        <div class="container" style="max-width: 1000px;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-box">
                        <img src="logo_tik_polri.png" alt="Logo TIK POLRI">
                    </div>
                    <div>
                        <h1 class="h4 mb-0 fw-bold tracking-tight">Edit Data Surat</h1>
                        <p class="small mb-0 text-white-50">Mengubah Surat Kehilangan Nopol: <?= htmlspecialchars($data['nopo']) ?></p>
                    </div>
                </div>
                <a href="data.php" class="btn btn-outline-light d-flex align-items-center gap-2" style="border-radius: 10px; padding: 10px 20px; font-weight: 600;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </header>

    <!-- ── MAIN CONTENT ── -->
    <main class="container mb-5" style="max-width: 1000px;">
        
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_msg']['type']) ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($_SESSION['flash_msg']['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash_msg']); ?>
        <?php endif; ?>

        <div class="card-premium p-4">
            <h2 class="h5 fw-bold text-primary-color mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-accent-color"></i> Formulir Perubahan Data
            </h2>
            
            <form method="POST">
                
                <!-- BAGIAN A: DATA SURAT -->
                <div class="section-legend">
                    <i class="bi bi-file-earmark-text"></i> A. Data Surat
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="nomer_surat" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomer_surat" name="nomer_surat" value="<?= htmlspecialchars($data['nomer_surat']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Bulan (Romawi) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="bulan" name="bulan" value="<?= htmlspecialchars($data['bulan']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tahun" name="tahun" value="<?= htmlspecialchars($data['tahun']) ?>" required>
                    </div>
                </div>

                <!-- BAGIAN B: IDENTITAS KENDARAAN -->
                <div class="section-legend">
                    <i class="bi bi-car-front-fill"></i> B. Identitas Kendaraan
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="nopo" class="form-label">Nomor Polisi (Nopol) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="nopo" name="nopo" value="<?= htmlspecialchars($data['nopo']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="merk" class="form-label">Merk / Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="merk" name="merk" value="<?= htmlspecialchars($data['merk']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="jenis" class="form-label">Jenis / Model <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis" name="jenis" value="<?= htmlspecialchars($data['jenis']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan Kendaraan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" value="<?= htmlspecialchars($data['tahun_pembuatan'] ?? '') ?>" min="1900" max="<?= date('Y') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="warna" class="form-label">Warna <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="warna" name="warna" value="<?= htmlspecialchars($data['warna']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="bpkb" class="form-label">Nomor BPKB <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="bpkb" name="bpkb" value="<?= htmlspecialchars($data['bpkb']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nomor_rangka" class="form-label">Nomor Rangka <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="nomor_rangka" name="nomor_rangka" value="<?= htmlspecialchars($data['nomor_rangka']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nomor_mesin" class="form-label">Nomor Mesin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="nomor_mesin" name="nomor_mesin" value="<?= htmlspecialchars($data['nomor_mesin']) ?>" required>
                    </div>
                </div>

                <!-- BAGIAN C: DATA PELAPORAN -->
                <div class="section-legend">
                    <i class="bi bi-shield-shaded"></i> C. Data Pelaporan
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label for="polres" class="form-label">Polres Pelapor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="polres" name="polres" value="<?= htmlspecialchars($data['polres']) ?>" required>
                    </div>
                    <div class="col-md-7">
                        <label for="nomor_surat_keterangan" class="form-label">Nomor Surat Keterangan Polisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomor_surat_keterangan" name="nomor_surat_keterangan" value="<?= htmlspecialchars($data['nomor_surat_keterangan']) ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label for="tanggal_tahun_lapor" class="form-label">Tanggal Lapor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tanggal_tahun_lapor" name="tanggal_tahun_lapor" value="<?= htmlspecialchars($data['tanggal_tahun_lapor']) ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="jenissurat" class="form-label">Jenis Surat (Untuk Paragraf Tengah) <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenissurat" name="jenissurat" required>
                            <option value="BPKB" <?= $data['jenissurat'] === 'BPKB' ? 'selected' : '' ?>>BPKB</option>
                            <option value="STNK" <?= $data['jenissurat'] === 'STNK' ? 'selected' : '' ?>>STNK</option>
                            <option value="BPKB dan STNK" <?= $data['jenissurat'] === 'BPKB dan STNK' ? 'selected' : '' ?>>BPKB dan STNK</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_surat" class="form-label">Jenis Surat (Untuk Paragraf Penutup) <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_surat" name="jenis_surat" required>
                            <option value="BPKB" <?= $data['jenis_surat'] === 'BPKB' ? 'selected' : '' ?>>BPKB</option>
                            <option value="STNK" <?= $data['jenis_surat'] === 'STNK' ? 'selected' : '' ?>>STNK</option>
                            <option value="BPKB dan STNK" <?= $data['jenis_surat'] === 'BPKB dan STNK' ? 'selected' : '' ?>>BPKB dan STNK</option>
                        </select>
                    </div>
                </div>

                <!-- BAGIAN D: PENANDATANGANAN -->
                <div class="section-legend">
                    <i class="bi bi-pencil-fill"></i> D. Penandatanganan
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="taggalttd" class="form-label">Tanggal TTD <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="taggalttd" name="taggalttd" value="<?= htmlspecialchars($data['taggalttd']) ?>" required>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-color);">

                <div class="d-flex justify-content-end gap-3">
                    <a href="data.php" class="btn btn-secondary-custom">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <a href="proses.php?action=download&id=<?= $id ?>" class="btn d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-weight:700;padding:12px 22px;border-radius:10px;box-shadow:0 4px 12px rgba(22,163,74,.3);transition:all .2s;text-decoration:none;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                        <i class="bi bi-printer-fill"></i> Cetak Dokumen Word
                    </a>
                    <button type="submit" class="btn btn-accent d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer class="text-center py-4 border-top bg-white mt-auto" style="border-color: var(--border-color) !important;">
        <div class="container">
            <span class="small text-muted">&copy; <?= date('Y') ?> Sistem Informasi Samsat &mdash; Dokumen Surat Keterangan Kehilangan.</span>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Otomatis ubah input nopo/rangka/mesin/bpkb jadi uppercase saat diketik
        document.querySelectorAll('.text-uppercase').forEach(function(input) {
            input.addEventListener('input', function() {
                var cursorPosition = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(cursorPosition, cursorPosition);
            });
        });
    </script>
</body>
</html>