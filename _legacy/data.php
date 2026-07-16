<?php
session_start();
require "koneksi.php";

// ── PROTEKSI KONEKSI DATABASE ──
if ($db_error) {
    $dbStatus = false;
} else {
    $dbStatus = true;
}

// ── TANGKAP PARAMETER FILTER TANGGAL ──
$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date   = isset($_GET['end_date'])   ? trim($_GET['end_date'])   : '';
$filter_active = ($start_date !== '' && $end_date !== '');

// ── AMBIL STATISTIK (dengan filter tanggal jika aktif) ──
$total = 0;
$bpkb_count = 0;
$stnk_count = 0;
$both_count = 0;

if ($dbStatus) {
    if ($filter_active) {
        $stmtCount = $conn->prepare(
            "SELECT COUNT(*) as total,
                    SUM(jenis_surat='BPKB') as bpkb,
                    SUM(jenis_surat='STNK') as stnk,
                    SUM(jenis_surat='BPKB dan STNK') as keduanya
             FROM surat_kehilangan
             WHERE DATE(created_at) >= ? AND DATE(created_at) <= ?"
        );
        if ($stmtCount) {
            $stmtCount->bind_param("ss", $start_date, $end_date);
            $stmtCount->execute();
            $counts = $stmtCount->get_result()->fetch_assoc();
            $stmtCount->close();
        }
    } else {
        $counts = $conn->query(
            "SELECT COUNT(*) as total,
                    SUM(jenis_surat='BPKB') as bpkb,
                    SUM(jenis_surat='STNK') as stnk,
                    SUM(jenis_surat='BPKB dan STNK') as keduanya
             FROM surat_kehilangan"
        )->fetch_assoc();
    }
    if ($counts) {
        $total       = (int)($counts['total']    ?? 0);
        $bpkb_count  = (int)($counts['bpkb']     ?? 0);
        $stnk_count  = (int)($counts['stnk']     ?? 0);
        $both_count  = (int)($counts['keduanya'] ?? 0);
    }
}

// ── PENCARIAN & AMBIL DATA (PREPARED STATEMENTS - ANTI SQL INJECTION) ──
$rows    = [];
$keyword = isset($_GET['cari']) ? trim($_GET['cari']) : '';

if ($dbStatus) {
    // Bangun kondisi WHERE secara dinamis
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

    $query = "SELECT * FROM surat_kehilangan {$whereClause} ORDER BY id DESC";

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

        /* ── CARDS & STATS ── */
        .card-premium {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            overflow: hidden;
        }
        .stat-card {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 16px 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.01);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
        }

        /* ── TABLE STYLING ── */
        .table-premium thead th {
            background-color: #f8fafc;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
            padding: 14px 16px;
        }
        .table-premium tbody td {
            padding: 14px 16px;
            font-size: 0.88rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }
        .table-premium tbody tr:hover {
            background-color: #f8fafc;
        }

        /* ── BADGES ── */
        .badge-doc {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
        }
        .badge-bpkb { background-color: #eff6ff; color: #1d4ed8; }
        .badge-stnk { background-color: #ecfdf5; color: #047857; }
        .badge-both { background-color: #fffbeb; color: #b45309; }

        /* ── SEARCH & INPUTS ── */
        .search-input {
            border: 1.5px solid var(--border-color);
            border-radius: 10px 0 0 10px;
            padding: 10px 16px;
            font-size: 0.9rem;
            width: 280px;
        }
        .search-input:focus {
            border-color: var(--accent-color);
            box-shadow: none;
            outline: none;
        }
        .search-btn {
            background-color: var(--primary-color);
            color: #ffffff;
            border: 1.5px solid var(--primary-color);
            border-radius: 0 10px 10px 0;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .search-btn:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
        }

        /* ── ACTIONS ── */
        .btn-action-edit {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-action-edit:hover {
            background-color: #bfdbfe;
            color: #1e3a8a;
        }
        .btn-action-delete {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-action-delete:hover {
            background-color: #fecaca;
            color: #7f1d1d;
        }
        .btn-action-print {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-action-print:hover {
            background-color: #bbf7d0;
            color: #15803d;
        }

        /* ── DATE RANGE FILTER CARD ── */
        .filter-card {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            border: 1px solid #374151;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.12);
        }
        .filter-card .filter-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }
        .filter-card .form-control[type="date"] {
            background-color: #374151;
            border: 1.5px solid #4b5563;
            color: #f9fafb;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 0.88rem;
            transition: all 0.2s;
        }
        .filter-card .form-control[type="date"]:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
            outline: none;
            background-color: #374151;
            color: #f9fafb;
        }
        .filter-card .form-label {
            color: #9ca3af;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .btn-filter {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            color: #ffffff;
            border: none;
            font-weight: 700;
            padding: 9px 22px;
            border-radius: 10px;
            font-size: 0.88rem;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .btn-filter:hover {
            background: linear-gradient(135deg, var(--accent-hover), #991b1b);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
        }
        .btn-reset-filter {
            background-color: #374151;
            color: #d1d5db;
            border: 1.5px solid #4b5563;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.88rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .btn-reset-filter:hover {
            background-color: #4b5563;
            color: #f9fafb;
        }
        .btn-export-excel {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #ffffff;
            border: none;
            font-weight: 700;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.88rem;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .btn-export-excel:hover {
            background: linear-gradient(135deg, #15803d, #166534);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.4);
        }
        .filter-active-badge {
            background-color: rgba(220, 38, 38, 0.15);
            border: 1px solid rgba(220, 38, 38, 0.35);
            color: #fca5a5;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
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
                        <h1 class="h4 mb-0 fw-bold tracking-tight">Arsip Data Surat</h1>
                        <p class="small mb-0 text-white-50">Daftar Hasil Pengecekan Kehilangan BPKB / STNK</p>
                    </div>
                </div>
                <a href="index.php" class="btn btn-outline-light d-flex align-items-center gap-2" style="border-radius: 10px; padding: 10px 20px; font-weight: 600;">
                    <i class="bi bi-plus-circle-fill text-accent-color"></i> + Input Surat Baru
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

        <?php if (!$dbStatus): ?>
            <!-- Alert Gagal Database -->
            <div class="alert alert-danger d-flex align-items-center gap-3 p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                <div>
                    <h5 class="fw-bold mb-1">Koneksi Database Gagal</h5>
                    <p class="mb-0 text-danger-50 small">Pemeriksaan database gagal dilakukan. Pastikan database MySQL aktif dan tabel `surat_kehilangan` telah dibuat.</p>
                </div>
            </div>
        <?php else: ?>

            <!-- ── DATE RANGE FILTER FORM ── -->
            <div class="filter-card">
                <div class="filter-title">
                    <i class="bi bi-calendar3-range"></i> Filter Rentang Tanggal
                    <?php if ($filter_active): ?>
                        <span class="filter-active-badge ms-2">
                            <i class="bi bi-check-circle-fill"></i>
                            Filter Aktif: <?= htmlspecialchars($start_date) ?> s/d <?= htmlspecialchars($end_date) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <form method="GET" action="data.php" class="row g-3 align-items-end">
                    <?php if ($keyword !== ''): ?>
                        <input type="hidden" name="cari" value="<?= htmlspecialchars($keyword) ?>">
                    <?php endif; ?>
                    <div class="col-md-4 col-sm-6">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                               value="<?= htmlspecialchars($start_date) ?>">
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                               value="<?= htmlspecialchars($end_date) ?>">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel-fill"></i> Filter Data
                            </button>
                            <?php
                                $exportParams = [];
                                if ($start_date !== '') $exportParams[] = 'start_date=' . urlencode($start_date);
                                if ($end_date   !== '') $exportParams[] = 'end_date='   . urlencode($end_date);
                                if ($keyword    !== '') $exportParams[] = 'cari='        . urlencode($keyword);
                                $exportHref = 'export_excel.php' . (count($exportParams) > 0 ? '?' . implode('&', $exportParams) : '');
                            ?>
                            <a href="<?= $exportHref ?>" class="btn-export-excel">
                                <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                            </a>
                            <a href="data.php<?= $keyword !== '' ? '?cari=' . urlencode($keyword) : '' ?>" class="btn-reset-filter">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- CARDS STATISTIK (Hanya Desktop) -->
            <div class="row g-3 mb-4">
                <!-- Total -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #f1f5f9; color: var(--primary-color);">
                            <i class="bi bi-files"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0 fw-bold font-outfit"><?= number_format($total) ?></div>
                            <div class="small text-muted font-outfit">Total Surat</div>
                        </div>
                    </div>
                </div>
                <!-- BPKB -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #eff6ff; color: #2563eb;">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0 fw-bold font-outfit"><?= number_format($bpkb_count) ?></div>
                            <div class="small text-muted font-outfit">Kehilangan BPKB</div>
                        </div>
                    </div>
                </div>
                <!-- STNK -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #ecfdf5; color: #059669;">
                            <i class="bi bi-card-text"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0 fw-bold font-outfit"><?= number_format($stnk_count) ?></div>
                            <div class="small text-muted font-outfit">Kehilangan STNK</div>
                        </div>
                    </div>
                </div>
                <!-- Keduanya -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #fffbeb; color: #d97706;">
                            <i class="bi bi-files-alt"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0 fw-bold font-outfit"><?= number_format($both_count) ?></div>
                            <div class="small text-muted font-outfit">Kehilangan Keduanya</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLE CARD -->
            <div class="card-premium p-4">
                
                <!-- FILTER & PENCARIAN -->
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                    <h2 class="h5 mb-0 fw-bold text-primary-color d-flex align-items-center gap-2">
                        <i class="bi bi-list-ul text-accent-color"></i> Daftar Surat Masuk
                    </h2>
                    
                    <form method="GET" action="data.php" class="d-flex align-items-center">
                        <?php if ($filter_active): ?>
                            <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                            <input type="hidden" name="end_date"   value="<?= htmlspecialchars($end_date) ?>">
                        <?php endif; ?>
                        <input type="text" class="search-input" name="cari" placeholder="Cari Nopol, BPKB, atau Merk..." value="<?= htmlspecialchars($keyword) ?>">
                        <button type="submit" class="search-btn d-flex align-items-center gap-1">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <?php if ($keyword !== ''): ?>
                            <?php
                                $resetHref = 'data.php';
                                if ($filter_active) {
                                    $resetHref .= '?start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date);
                                }
                            ?>
                            <a href="<?= $resetHref ?>" class="btn btn-light ms-2 d-flex align-items-center justify-content-center" style="border-radius: 10px; border: 1.5px solid var(--border-color); height: 45px;" title="Reset Pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- TABEL DATA -->
                <div class="table-responsive">
                    <?php if (empty($rows)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 text-white-50"></i>
                            <span class="fw-semibold">Tidak Ada Data Ditemukan</span>
                            <p class="small mb-0 mt-1">Belum ada surat yang terdaftar atau tidak cocok dengan pencarian Anda.</p>
                        </div>
                    <?php else: ?>
                        <table class="table table-hover table-premium mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th>Nomor Surat</th>
                                    <th>Nomor Polisi</th>
                                    <th>Merk / Type</th>
                                    <th>No. BPKB</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Tanggal TTD</th>
                                    <th style="width: 150px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($rows as $row) { 
                                    $jenis_dok = $row['jenissurat'];
                                    $badge_class = 'badge-both';
                                    if ($jenis_dok === 'BPKB') $badge_class = 'badge-bpkb';
                                    if ($jenis_dok === 'STNK') $badge_class = 'badge-stnk';
                                ?>
                                <tr>
                                    <td class="text-muted fw-medium"><?= $no++ ?></td>
                                    <td class="fw-semibold text-primary-color"><?= htmlspecialchars($row['nomer_surat']) ?>/<?= htmlspecialchars($row['bulan']) ?>/<?= htmlspecialchars($row['tahun']) ?></td>
                                    <td>
                                        <span class="badge bg-dark px-2.5 py-1.5 fs-7 font-outfit" style="border-radius: 6px; font-weight: 600;"><?= htmlspecialchars($row['nopo']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-primary-color"><?= htmlspecialchars($row['merk']) ?></div>
                                        <div class="small text-muted" style="font-size: 0.76rem;"><?= htmlspecialchars($row['jenis']) ?></div>
                                    </td>
                                    <td><code style="font-size: 0.82rem; font-weight: 600;" class="text-secondary"><?= htmlspecialchars($row['bpkb']) ?></code></td>
                                    <td>
                                        <span class="badge-doc <?= $badge_class ?>"><?= htmlspecialchars($jenis_dok) ?></span>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($row['taggalttd']) ?></td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-action-edit d-flex align-items-center gap-1">
                                                <i class="bi bi-pencil-fill"></i> Edit
                                            </a>
                                            <a href="proses.php?action=download&id=<?= $row['id'] ?>" class="btn-action-print d-flex align-items-center gap-1">
                                                <i class="bi bi-printer-fill"></i> Cetak
                                            </a>
                                            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-action-delete d-flex align-items-center gap-1" onclick="return confirm('Apakah Anda yakin ingin menghapus data dengan Nomor Polisi <?= htmlspecialchars(addslashes($row['nopo'])) ?>?')">
                                                <i class="bi bi-trash-fill"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

            </div>
        <?php endif; ?>

    </main>

    <!-- ── FOOTER ── -->
    <footer class="text-center py-4 border-top bg-white mt-auto" style="border-color: var(--border-color) !important;">
        <div class="container">
            <span class="small text-muted">&copy; <?= date('Y') ?> Sistem Informasi Samsat &mdash; Dokumen Surat Keterangan Kehilangan.</span>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>