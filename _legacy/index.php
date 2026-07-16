<?php session_start(); ?>
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
            --accent-color: #dc2626; /* Red (matching TIK POLRI flag tip) */
            --accent-hover: #b91c1c; /* Darker Red */
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
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        .card-premium-header {
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 24px;
            font-weight: 600;
            color: var(--primary-color);
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
        .form-control::placeholder {
            color: #cbd5e1;
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
        }
        .btn-secondary-custom:hover {
            background-color: #e2e8f0;
            color: var(--primary-color);
        }

        /* ── WIDGETS ── */
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .bg-success-custom { background-color: #10b981; }
        .bg-danger-custom { background-color: #ef4444; }
        .bg-warning-custom { background-color: #f59e0b; }
    </style>
</head>
<body>

    <!-- ── HEADER ── -->
    <header class="app-header mb-5">
        <div class="container" style="max-width: 1200px;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-box">
                        <img src="logo_tik_polri.png" alt="Logo TIK POLRI">
                    </div>
                    <div>
                        <h1 class="h4 mb-0 fw-bold tracking-tight">Sistem Surat Kehilangan</h1>
                        <p class="small mb-0 text-white-50">Generate Surat Keterangan Pengecekan Kehilangan BPKB / STNK</p>
                    </div>
                </div>
                <a href="data.php" class="btn btn-outline-light d-flex align-items-center gap-2" style="border-radius: 10px; padding: 10px 20px; font-weight: 600;">
                    <i class="bi bi-table"></i> Lihat Data Surat
                </a>
            </div>
        </div>
    </header>

    <!-- ── MAIN CONTENT ── -->
    <main class="container mb-5" style="max-width: 1200px;">
        
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_msg']['type']) ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($_SESSION['flash_msg']['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash_msg']); ?>
        <?php endif; ?>

        <div class="row g-4">
            
            <!-- KOLOM KIRI: FORM (8 KOLOM) -->
            <div class="col-lg-8">
                <div class="card-premium p-4">
                    <h2 class="h5 fw-bold text-primary-color mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-accent-color"></i> Formulir Pembuatan Surat
                    </h2>
                    
                    <form action="proses.php" method="POST" id="formSurat">
                        
                        <!-- BAGIAN A: DATA SURAT -->
                        <div class="section-legend">
                            <i class="bi bi-file-earmark-text"></i> A. Data Surat
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="nomer_surat" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomer_surat" name="nomer_surat" placeholder="Cth: 123" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="bulan" class="form-label">Bulan (Romawi) <span class="text-danger">*</span></label>
                                <select class="form-select" id="bulan" name="bulan" required>
                                    <option value="" disabled selected>-- Pilih Bulan --</option>
                                    <option value="I">I</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="V">V</option>
                                    <option value="VI">VI</option>
                                    <option value="VII">VII</option>
                                    <option value="VIII">VIII</option>
                                    <option value="IX">IX</option>
                                    <option value="X">X</option>
                                    <option value="XI">XI</option>
                                    <option value="XII">XII</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tahun" name="tahun" value="<?= date('Y') ?>" required>
                            </div>
                        </div>

                        <!-- BAGIAN B: IDENTITAS KENDARAAN -->
                        <div class="section-legend">
                            <i class="bi bi-car-front-fill"></i> B. Identitas Kendaraan
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="nopo" class="form-label">Nomor Polisi (Nopol) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="nopo" name="nopo" placeholder="Cth: B 1234 CD" required>
                            </div>
                            <div class="col-md-6">
                                <label for="merk" class="form-label">Merk / Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="merk" name="merk" placeholder="Cth: Honda Vario 150" required>
                            </div>
                            <div class="col-md-4">
                                <label for="jenis" class="form-label">Jenis / Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jenis" name="jenis" placeholder="Cth: Sepeda Motor" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan Kendaraan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" placeholder="Contoh: 2020" min="1900" max="<?= date('Y') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="warna" class="form-label">Warna <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="warna" name="warna" placeholder="Cth: Hitam" required>
                            </div>
                            <div class="col-md-4">
                                <label for="bpkb" class="form-label">Nomor BPKB <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="bpkb" name="bpkb" placeholder="Cth: N-123456" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nomor_rangka" class="form-label">Nomor Rangka <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="nomor_rangka" name="nomor_rangka" placeholder="Masukkan nomor rangka" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nomor_mesin" class="form-label">Nomor Mesin <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="nomor_mesin" name="nomor_mesin" placeholder="Masukkan nomor mesin" required>
                            </div>
                        </div>

                        <!-- BAGIAN C: DATA PELAPORAN -->
                        <div class="section-legend">
                            <i class="bi bi-shield-shaded"></i> C. Data Pelaporan
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="polres" class="form-label">Polres Pelapor <span class="text-danger">*</span></label>
                                <select class="form-select" id="polres" name="polres" required>
                                    <option value="" disabled selected>-- Pilih Polres Pelapor --</option>
                                    <option value="Polrestabes Bandung">Polrestabes Bandung</option>
                                    <option value="Polresta Bandung">Polresta Bandung</option>
                                    <option value="Polresta Bogor Kota">Polresta Bogor Kota</option>
                                    <option value="Polres Bogor">Polres Bogor</option>
                                    <option value="Polresta Cirebon">Polresta Cirebon</option>
                                    <option value="Polres Cirebon Kota">Polres Cirebon Kota</option>
                                    <option value="Polres Sukabumi Kota">Polres Sukabumi Kota</option>
                                    <option value="Polres Sukabumi">Polres Sukabumi</option>
                                    <option value="Polres Tasikmalaya Kota">Polres Tasikmalaya Kota</option>
                                    <option value="Polres Tasikmalaya">Polres Tasikmalaya</option>
                                    <option value="Polres Cimahi">Polres Cimahi</option>
                                    <option value="Polres Ciamis">Polres Ciamis</option>
                                    <option value="Polres Pangandaran">Polres Pangandaran</option>
                                    <option value="Polres Garut">Polres Garut</option>
                                    <option value="Polres Cianjur">Polres Cianjur</option>
                                    <option value="Polres Purwakarta">Polres Purwakarta</option>
                                    <option value="Polres Karawang">Polres Karawang</option>
                                    <option value="Polres Subang">Polres Subang</option>
                                    <option value="Polres Sumedang">Polres Sumedang</option>
                                    <option value="Polres Indramayu">Polres Indramayu</option>
                                    <option value="Polres Majalengka">Polres Majalengka</option>
                                    <option value="Polres Kuningan">Polres Kuningan</option>
                                    <option value="Polres Banjar">Polres Banjar</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label for="nomor_surat_keterangan" class="form-label">Nomor Surat Keterangan Polisi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor_surat_keterangan" name="nomor_surat_keterangan" placeholder="Cth: SKTLK/B/123/VI/2026/SPKT" required>
                            </div>
                            <div class="col-md-5">
                                <label for="tanggal_tahun_lapor" class="form-label">Tanggal Lapor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tanggal_tahun_lapor" name="tanggal_tahun_lapor" placeholder="Cth: 05 Juni 2026" required>
                            </div>

                            <div class="col-md-6">
                                <label for="jenissurat" class="form-label">Jenis Surat (Untuk Paragraf Tengah) <span class="text-danger">*</span></label>
                                <select class="form-select" id="jenissurat" name="jenissurat" required>
                                    <option value="" disabled selected>-- Pilih Jenis --</option>
                                    <option value="BPKB">BPKB</option>
                                    <option value="STNK">STNK</option>
                                    <option value="BPKB dan STNK">BPKB dan STNK</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_surat" class="form-label">Jenis Surat (Untuk Paragraf Penutup) <span class="text-danger">*</span></label>
                                <select class="form-select" id="jenis_surat" name="jenis_surat" required>
                                    <option value="" disabled selected>-- Pilih Jenis --</option>
                                    <option value="BPKB">BPKB</option>
                                    <option value="STNK">STNK</option>
                                    <option value="BPKB dan STNK">BPKB dan STNK</option>
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
                                <input type="text" class="form-control" id="taggalttd" name="taggalttd" placeholder="Cth: 05 Juni 2026" required>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: var(--border-color);">

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" class="btn btn-secondary-custom">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                            </button>
                            <button type="submit" class="btn btn-accent d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-arrow-down-fill"></i> Simpan & Generate Word
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- KOLOM KANAN: STATS & INFO (4 KOLOM) -->
            <div class="col-lg-4">
                
                <!-- Status Sistem Widget -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <i class="bi bi-cpu text-primary-color me-2"></i> Status Sistem
                    </div>
                    <div class="card-body p-4">
                        <?php
                        require_once 'koneksi.php';
                        $dbStatus = ($db_error === null);
                        $templateExists = file_exists(__DIR__ . '/template_surat.docx');
                        $phpwordExists = file_exists(__DIR__ . '/vendor/autoload.php');
                        ?>
                        
                        <div class="d-flex flex-column gap-3">
                            <!-- Database Status -->
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-database text-muted"></i>
                                    <span class="small font-outfit fw-medium">Koneksi Database</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot <?= $dbStatus ? 'bg-success-custom' : 'bg-danger-custom' ?>"></span>
                                    <span class="small fw-semibold"><?= $dbStatus ? 'Aktif' : 'Gagal' ?></span>
                                </div>
                            </div>
                            <?php if (!$dbStatus): ?>
                                <div class="alert alert-danger py-2 px-3 small mb-0">
                                    <strong>Detail Error:</strong><br>
                                    <code style="font-size: 0.75rem; word-break: break-all;"><?= htmlspecialchars($db_error) ?></code>
                                </div>
                            <?php endif; ?>

                            <!-- Template Docx Status -->
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-word text-muted"></i>
                                    <span class="small font-outfit fw-medium">Template Word (.docx)</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot <?= $templateExists ? 'bg-success-custom' : 'bg-danger-custom' ?>"></span>
                                    <span class="small fw-semibold"><?= $templateExists ? 'Ditemukan' : 'Hilang' ?></span>
                                </div>
                            </div>
                            <?php if (!$templateExists): ?>
                                <div class="alert alert-warning py-2 px-3 small mb-0">
                                    Letakkan file <code>template_surat.docx</code> di folder root proyek.
                                </div>
                            <?php endif; ?>

                            <!-- PHPWord library Status -->
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-journal-code text-muted"></i>
                                    <span class="small font-outfit fw-medium">Library PHPWord</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot <?= $phpwordExists ? 'bg-success-custom' : 'bg-danger-custom' ?>"></span>
                                    <span class="small fw-semibold"><?= $phpwordExists ? 'Terinstal' : 'Belum Ada' ?></span>
                                </div>
                            </div>
                            <?php if (!$phpwordExists): ?>
                                <div class="alert alert-warning py-2 px-3 small mb-0">
                                    Jalankan perintah <code>composer install</code> di direktori ini.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Petunjuk Pengisian Widget -->
                <div class="card-premium">
                    <div class="card-premium-header">
                        <i class="bi bi-info-circle text-primary-color me-2"></i> Petunjuk Pengisian
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3" style="font-size: 0.85rem; line-height: 1.6;">
                            <li class="d-flex gap-2">
                                <i class="bi bi-check2-circle text-success fs-5"></i>
                                <span>Isi seluruh kolom yang bertanda bintang (<span class="text-danger">*</span>) karena wajib diisi.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check2-circle text-success fs-5"></i>
                                <span>Nomor Polisi otomatis dikonversi menjadi huruf kapital dan digunakan sebagai nama file Word unduhan Anda.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check2-circle text-success fs-5"></i>
                                <span>Setelah mengklik <strong>Simpan & Generate</strong>, data akan masuk database dan unduhan file Word langsung terpicu otomatis.</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

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

        // Hubungkan pilihan select Jenis Surat Tengah dan Penutup jika ingin disamakan otomatis
        document.getElementById('jenissurat').addEventListener('change', function() {
            var penutup = document.getElementById('jenis_surat');
            if (penutup && !penutup.value) {
                penutup.value = this.value;
            }
        });
    </script>
</body>
</html>