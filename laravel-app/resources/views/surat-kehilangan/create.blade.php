<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pembuatan Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color:#111827; --primary-light:#1f2937; --accent-color:#dc2626; --accent-hover:#b91c1c; --bg-color:#f1f5f9; --card-bg:#fff; --border-color:#d1d5db; --text-main:#374151; }
        body { font-family:'Inter',sans-serif; background:var(--bg-color); color:var(--text-main); position: relative; min-height: 100vh; }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url('{{ asset('images/logo_tik_polri.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 70%;
            opacity: 0.07;
            pointer-events: none;
            z-index: 0;
        }
        .content-layer { position: relative; z-index: 1; }
        h1,h2,h3,h4,h5 { font-family:'Outfit',sans-serif; }
        .app-header { background:var(--primary-color); color:#fff; border-bottom:4px solid var(--accent-color); }
        .card-premium { background:var(--card-bg); border:1px solid rgba(203,213,225,0.8); border-radius:18px; box-shadow:0 18px 60px rgba(15,23,42,0.08); }
        .section-panel { background:#fff; border:1px solid rgba(203,213,225,0.9); border-radius:18px; padding:24px; }
        .section-header { display:flex; align-items:center; gap:12px; background:#f8fafc; border:1px solid rgba(203,213,225,0.9); border-radius:14px; padding:14px 18px; margin-bottom:20px; }
        .section-header .badge { width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#1f2937; color:#fff; font-weight:700; }
        .section-title { margin:0; font-size:1rem; font-weight:700; color:var(--primary-color); }
        .section-subtitle { margin:0; font-size:0.9rem; color:#6b7280; }
        .form-label { font-size:0.82rem; font-weight:600; color:var(--primary-color); margin-bottom:8px; }
        .form-control, .form-select { border:1px solid rgba(203,213,225,0.95); border-radius:14px; padding:12px 16px; color:#111827; }
        .form-control { background:#f8fafc; }
        .form-select {
            background-color:#f8fafc;
            padding-right:2.75rem;
            appearance:none;
            -webkit-appearance:none;
            -moz-appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:right 1rem center;
            background-size:1rem;
        }
        .form-control:focus, .form-select:focus { border-color:rgba(220,38,38,0.8); box-shadow:0 0 0 0.15rem rgba(220,38,38,0.12); }
        .form-control::placeholder { color:#9ca3af; }
        .form-select:invalid { color:#9ca3af; }
        .form-select option { color:#111827; }
        #jenissurat-select option[value=""] { color:#9ca3af; }
        #jenissurat-select { color:#9ca3af; }
        #jenissurat-select:valid { color:#111827; }
        .btn-accent { background:linear-gradient(135deg, var(--accent-color), var(--accent-hover)); color:#fff; border:none; font-weight:700; border-radius:14px; min-width:160px; }
        .btn-secondary { background:#6b7280; color:#fff; border:none; font-weight:700; border-radius:14px; }
        .field-note { font-size:0.82rem; color:#6b7280; }
        .jenis-surat-field {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .jenis-surat-field .form-select,
        .jenis-surat-field .form-control {
            flex: 1;
            min-width: 0;
        }
        .jenis-surat-switch {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.92rem;
            color: #fff;
            background: rgba(17,24,39,0.95);
            border: 1px solid rgba(255,255,255,0.9);
            border-radius: 14px;
            padding: 0.7rem 1rem;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 0.4rem;
        }
        .jenis-surat-switch:hover { background: #111827; color: #fff; }
        .confirm-modal .modal-dialog { max-width:560px; }
        .confirm-modal .modal-content { border:none; border-radius:24px; overflow:hidden; box-shadow:0 24px 80px rgba(15,23,42,0.2); }
        .confirm-modal .modal-header { background:#f8fafc; border-bottom:1px solid rgba(203,213,225,0.9); padding:1.5rem 2rem; }
        .confirm-modal .modal-title { font-family:'Outfit',sans-serif; font-weight:700; font-size:1.35rem; color:var(--primary-color); }
        .confirm-modal .modal-body { padding:2rem 2.25rem 2.25rem; color:#4b5563; line-height:1.7; text-align:center; }
        .confirm-modal .modal-body p { font-size:1.05rem; }
        .confirm-modal .modal-footer { border-top:1px solid rgba(203,213,225,0.9); padding:1.25rem 2rem 1.75rem; gap:0.75rem; justify-content:center; }
        .confirm-modal .modal-footer .btn { min-width:160px; padding:0.75rem 1.5rem; font-size:1rem; }
        .confirm-modal .confirm-icon { width:80px; height:80px; border-radius:20px; background:rgba(220,38,38,0.1); color:var(--accent-color); display:flex; align-items:center; justify-content:center; font-size:2.5rem; margin:0 auto 1.5rem; }
        /* Preview Modal */
        .preview-modal .modal-dialog { max-width: 850px; }
        .preview-modal .modal-content { border: none; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 60px rgba(15,23,42,0.15); }
        .preview-modal .modal-header { background: #f8fafc; border-bottom: 1px solid rgba(203,213,225,0.9); padding: 1.25rem 1.75rem; }
        .preview-modal .modal-title { font-family: 'Outfit',sans-serif; font-weight: 700; color: var(--primary-color); }
        .preview-modal .modal-body { padding: 2rem; background: #e2e8f0; }
        #surat-preview-container {
            background: #fff;
            padding: 2.2in 0.5in 1.5in 1.125in;
            min-height: 800px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border-radius: 12px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000000;
            box-sizing: border-box;
            background-color: #ffffff;
            position: relative;
        }
        #surat-preview-container * {
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
        }
        #surat-preview-container p {
            margin-bottom: 8pt;
            text-align: justify;
        }
        #surat-preview-container .preview-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10pt;
        }
        #surat-preview-container .preview-table td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }
        #surat-preview-container .list-indented {
            margin-left: 0.5in;
            margin-bottom: 10pt;
            list-style: none;
            padding-left: 0;
        }
        #surat-preview-container .list-indented li {
            position: relative;
            padding-left: 0.25in;
            text-indent: -0.25in;
            text-align: justify;
            margin-bottom: 6pt;
        }
    </style>
</head>
<body>
<header class="app-header py-4 mb-4">
    <div class="container" style="max-width: 1200px;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('surat-kehilangan.index') }}" style="cursor: pointer;">
                    <img src="{{ asset('images/logo_tik_polri.png') }}" alt="Logo TIK POLRI" style="height: 52px; width: auto; opacity: 0.9;">
                </a>
                <div>
                    <h1 class="h4 mb-1 fw-bold">Formulir Pembuatan Surat</h1>
                    <p class="mb-0 small text-white-50">Sistem Surat Kehilangan Bid TIK POLRI</p>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container mb-5 content-layer" style="max-width: 1200px;">
    <form id="form-surat" action="{{ route('surat-kehilangan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="jenis_surat" id="jenis-surat-value">

        <div class="section-header mb-4">
            <span class="badge">A</span>
            <div>
                <h2 class="section-title">A. Data Surat</h2>
                <p class="section-subtitle">Isikan detail nomor surat dan periode laporan.</p>
            </div>
        </div>
        <div class="section-panel mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                    <input name="nomer_surat" class="form-control" placeholder="Contoh: 11/V/2026" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select name="bulan" class="form-select" required>
                        <option value="" disabled hidden selected>Pilih Bulan (Romawi)</option>
                        <option>I</option>
                        <option>II</option>
                        <option>III</option>
                        <option>IV</option>
                        <option>V</option>
                        <option>VI</option>
                        <option>VII</option>
                        <option>VIII</option>
                        <option>IX</option>
                        <option>X</option>
                        <option>XI</option>
                        <option>XII</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input name="tahun" class="form-control" placeholder="Contoh: 2026" required>
                </div>
            </div>
        </div>

        <div class="section-header mb-4">
            <span class="badge">B</span>
            <div>
                <h2 class="section-title">B. Identitas Kendaraan</h2>
                <p class="section-subtitle">Detail kendaraan yang hilang atau dilaporkan.</p>
            </div>
        </div>
        <div class="section-panel mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nomor Polisi (Nopol) <span class="text-danger">*</span></label>
                    <input name="nopo" class="form-control" placeholder="Contoh: B 1234 ABC" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Merk / Type <span class="text-danger">*</span></label>
                    <input name="merk" class="form-control" placeholder="Contoh: Honda Vario" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <input name="jenis" class="form-control" placeholder="Contoh: Sepeda Motor" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun Pembuatan <span class="text-danger">*</span></label>
                    <input name="tahun_pembuatan" class="form-control" placeholder="Contoh: 2022" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Warna <span class="text-danger">*</span></label>
                    <input name="warna" class="form-control" placeholder="Contoh: Hitam" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Rangka <span class="text-danger">*</span></label>
                    <input name="nomor_rangka" class="form-control" placeholder="Masukkan Nomor Rangka" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Mesin <span class="text-danger">*</span></label>
                    <input name="nomor_mesin" class="form-control" placeholder="Masukkan Nomor Mesin" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">BPKB <span class="text-danger">*</span></label>
                    <input name="bpkb" class="form-control" placeholder="Masukkan Nomor BPKB" required>
                </div>
            </div>
        </div>

        <div class="section-header mb-4">
            <span class="badge">C</span>
            <div>
                <h2 class="section-title">C. Informasi Laporan</h2>
                <p class="section-subtitle">Detail laporan dan tanggal tanda tangan.</p>
            </div>
        </div>
        <div class="section-panel mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Polres Pelapor <span class="text-danger">*</span></label>
                    <select name="polres" class="form-select" required>
                        <option value="" disabled hidden selected>Pilih Polres Pelapor</option>
                        <option>Polres Banjar</option>
                        <option>Polres Bogor</option>
                        <option>Polrestabes Bandung</option>
                        <option>Polresta Bandung</option>
                        <option>Polres Ciamis</option>
                        <option>Polres Cianjur</option>
                        <option>Polres Cimahi</option>
                        <option>Polresta Cirebon</option>
                        <option>Polres Cirebon Kota</option>
                        <option>Polres Garut</option>
                        <option>Polres Indramayu</option>
                        <option>Polres Karawang</option>
                        <option>Polres Kuningan</option>
                        <option>Polres Majalengka</option>
                        <option>Polres Pangandaran</option>
                        <option>Polres Purwakarta</option>
                        <option>Polres Subang</option>
                        <option>Polres Sukabumi</option>
                        <option>Polres Sukabumi Kota</option>
                        <option>Polres Sumedang</option>
                        <option>Polres Tasikmalaya</option>
                        <option>Polres Tasikmalaya Kota</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Surat Keterangan Polisi <span class="text-danger">*</span></label>
                    <input name="nomor_surat_keterangan" class="form-control" placeholder="Masukkan Nomor" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lapor <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_tahun_lapor" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                    <div class="jenis-surat-field">
                        <select id="jenissurat-select" class="form-select" onchange="onJenisSuratChange(this.value)">
                            <option value="" disabled hidden selected>Pilih Jenis Surat</option>
                            <option value="STNK">STNK</option>
                            <option value="BPKB">BPKB</option>
                            <option value="BPKB dan STNK">BPKB dan STNK</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <input type="text" id="jenissurat-custom" class="form-control" placeholder="Tulis jenis surat lainnya" style="display:none;" oninput="syncJenisSuratValue(this.value)">
                        <button type="button" class="jenis-surat-switch" id="jenis-surat-switch" style="display:none;" onclick="showJenisSuratSelect()">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </button>
                    </div>
                    <input type="hidden" name="jenissurat" id="jenissurat-value" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal TTD <span class="text-danger">*</span></label>
                    <input type="date" name="taggalttd" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" id="btn-preview-surat" class="btn btn-outline-primary px-4"><i class="bi bi-eye me-2"></i>Preview Surat</button>
            <button type="button" id="btn-simpan-laporan" class="btn btn-accent px-4">Simpan Laporan</button>
            <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<div class="modal fade confirm-modal" id="confirm-save-modal" tabindex="-1" aria-labelledby="confirm-save-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-save-title">Konfirmasi Simpan Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <p class="mb-0 fw-semibold text-dark">Apakah Anda yakin sudah mengisi data dengan benar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-accent px-4" id="btn-confirm-save">Ya, Simpan Laporan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Periksa Kembali</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade preview-modal" id="preview-surat-modal" tabindex="-1" aria-labelledby="preview-surat-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preview-surat-title">Preview Surat Kehilangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div id="surat-preview-container">
                    <!-- Kop Surat / Header -->
                    <div style="display: flex; align-items: flex-start; justify-content: flex-start; border-bottom: 2.5px double #000000; padding-bottom: 10px; margin-bottom: 20px; position: relative;">
                        <div style="position: absolute; left: 0; top: 0;">
                            <img src="{{ asset('images/logo_tik_polri.png') }}" style="width: 75px; height: auto;" alt="Logo TIK">
                        </div>
                        <div style="width: 100%; text-align: center; font-weight: bold; font-size: 11pt; line-height: 1.25; text-transform: uppercase; padding-left: 80px; padding-right: 20px;">
                            <div>KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
                            <div>DAERAH JAWA BARAT</div>
                            <div style="text-decoration: underline;">BIDANG TEKNOLOGI INFORMASI DAN KOMUNIKASI</div>
                        </div>
                    </div>

                    <!-- Surat Title & Nomor -->
                    <div style="text-align: center; margin-top: 25px; margin-bottom: 25px;">
                        <h4 style="font-size: 13pt; font-weight: bold; text-decoration: underline; margin-bottom: 2px; text-transform: uppercase;">SURAT KETERANGAN HASIL PENGECEKAN</h4>
                        <p style="font-size: 11pt; margin: 0; text-align: center;">Nomor : SKET/ &nbsp; &nbsp; &nbsp; <span id="preview-nomer-surat">-</span> &nbsp; &nbsp; &nbsp; /<span id="preview-bulan">-</span>/YAN 2.4/<span id="preview-tahun">-</span></p>
                    </div>

                    <!-- Opening paragraph -->
                    <p style="text-indent: 0.5in; margin-bottom: 15px; text-align: justify;">Kepala Bidang Teknologi Informasi Dan Komunikasi Polda Jabar dengan ini menerangkan bahwa kendaraan bermotor dengan identitas sebagai berikut :</p>

                    <!-- Identitas Kendaraan -->
                    <table class="preview-table" style="margin-left: 0.5in; width: calc(100% - 0.5in);">
                        <tr>
                            <td style="width: 4%;">1.</td>
                            <td style="width: 32%;">Nomor polisi</td>
                            <td style="width: 3%;">:</td>
                            <td style="font-weight: bold; text-transform: uppercase;"><span id="preview-nopo">-</span></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Merk/ type</td>
                            <td>:</td>
                            <td><span id="preview-merk">-</span></td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Jenis/model</td>
                            <td>:</td>
                            <td><span id="preview-jenis">-</span></td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Tahun pembuatan</td>
                            <td>:</td>
                            <td><span id="preview-tahun-pembuatan">-</span></td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Warna</td>
                            <td>:</td>
                            <td><span id="preview-warna">-</span></td>
                        </tr>
                        <tr>
                            <td>6.</td>
                            <td>Nomor rangka</td>
                            <td>:</td>
                            <td style="font-weight: bold; text-transform: uppercase;"><span id="preview-nomor-rangka">-</span></td>
                        </tr>
                        <tr>
                            <td>7.</td>
                            <td>Nomor mesin</td>
                            <td>:</td>
                            <td style="font-weight: bold; text-transform: uppercase;"><span id="preview-nomor-mesin">-</span></td>
                        </tr>
                        <tr>
                            <td>8.</td>
                            <td>Nomor BPKB</td>
                            <td>:</td>
                            <td style="font-weight: bold; text-transform: uppercase;"><span id="preview-bpkb">-</span></td>
                        </tr>
                    </table>

                    <!-- Statement -->
                    <p style="text-indent: 0.5in; margin-bottom: 15px; text-align: justify;">Setelah dilakukan pengecekan identitas kendaraan bermotor tersebut di atas dengan database ranmor hiltem yang ada pada Subbid Tek Info Bid TIK Polda Jabar, diterangkan bahwa hasil pengecekan identitas kendaraan dimaksud <span style="font-weight: bold;">TIDAK TERDAPAT</span> pada database Ranmor Hiltem.</p>

                    <!-- Basis Laporan -->
                    <p style="margin-bottom: 10px;">Surat keterangan ini dibuat berdasarkan :</p>
                    <ul class="list-indented">
                        <li>a. Juknis Kapolri No.Pol.: Juknis 05/V/1984, tanggal 17 Mei 1984 tentang Sispulahjianta, kendaraan hilang, diketemukan, dicurigai dan rusak berat;</li>
                        <li>b. Surat keterangan tanda bukti melapor kehilangan barang dari <span id="preview-polres" style="font-weight: bold;">-</span> Nomor : <span id="preview-nomor-surat-keterangan" style="font-weight: bold;">-</span>, tanggal <span id="preview-tanggal-lapor" style="font-weight: bold;">-</span> tentang kehilangan <span id="preview-jenissurat" style="font-weight: bold;">-</span> asli.</li>
                    </ul>

                    <!-- Closing paragraph -->
                    <p style="text-indent: 0.5in; margin-bottom: 35px; text-align: justify;">Demikian surat keterangan ini dibuat dengan sebenarnya dan dipergunakan untuk kelengkapan persyaratan administrasi dalam pembuatan duplikat <span id="preview-jenis-surat" style="font-weight: bold;">-</span>.</p>

                    <!-- Signatures / Footer -->
                    <div style="display: flex; justify-content: flex-end; margin-top: 15px;">
                        <div style="width: 320px; font-size: 11pt; line-height: 1.35; position: relative;">
                            <div>Bandung, &nbsp; <span id="preview-tanggal-ttd">-</span></div>
                            <div style="font-weight: bold; text-transform: uppercase; margin-top: 2px;">a.n. KABID TIK POLDA JABAR</div>
                            <div style="font-weight: bold; text-transform: uppercase; padding-left: 20px;">KASUBBID TEK INFO</div>
                            
                            <!-- Stamp and Signature Image Overlay -->
                            <div style="margin-top: -15px; margin-bottom: -35px; margin-left: -10px; position: relative; z-index: 1;">
                                <img src="{{ asset('images/image2.png') }}" style="width: 250px; height: auto;" alt="Tanda Tangan & Stempel">
                            </div>
                            
                            <div style="font-weight: bold; text-decoration: underline; position: relative; z-index: 2; margin-top: 5px;">ALI SADIKIN, S.H, M.A.P, M.Si.</div>
                            <div style="font-weight: bold; position: relative; z-index: 2;">AKBP NRP 71100520</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function formatTanggalIndonesia(dateStr) {
        if (!dateStr) return '-';
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const date = new Date(dateStr + 'T00:00:00');
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }

    function updatePreview() {
        // Get form field values
        document.getElementById('preview-nomer-surat').textContent = document.querySelector('input[name="nomer_surat"]').value || '-';
        document.getElementById('preview-bulan').textContent = document.querySelector('select[name="bulan"]').value || '-';
        document.getElementById('preview-tahun').textContent = document.querySelector('input[name="tahun"]').value || '-';
        
        document.getElementById('preview-nopo').textContent = (document.querySelector('input[name="nopo"]').value || '-').toUpperCase();
        document.getElementById('preview-merk').textContent = document.querySelector('input[name="merk"]').value || '-';
        document.getElementById('preview-jenis').textContent = document.querySelector('input[name="jenis"]').value || '-';
        document.getElementById('preview-tahun-pembuatan').textContent = document.querySelector('input[name="tahun_pembuatan"]').value || '-';
        document.getElementById('preview-warna').textContent = document.querySelector('input[name="warna"]').value || '-';
        document.getElementById('preview-nomor-rangka').textContent = (document.querySelector('input[name="nomor_rangka"]').value || '-').toUpperCase();
        document.getElementById('preview-nomor-mesin').textContent = (document.querySelector('input[name="nomor_mesin"]').value || '-').toUpperCase();
        document.getElementById('preview-bpkb').textContent = (document.querySelector('input[name="bpkb"]').value || '-').toUpperCase();
        
        document.getElementById('preview-polres').textContent = document.querySelector('select[name="polres"]').value || '-';
        document.getElementById('preview-nomor-surat-keterangan').textContent = document.querySelector('input[name="nomor_surat_keterangan"]').value || '-';
        
        document.getElementById('preview-tanggal-lapor').textContent = formatTanggalIndonesia(document.querySelector('input[name="tanggal_tahun_lapor"]').value);
        
        // Get jenis surat value
        let jenisSuratVal = document.getElementById('jenissurat-value').value;
        if (jenisSuratVal === 'Lainnya') {
            jenisSuratVal = document.getElementById('jenissurat-custom').value;
        }
        document.getElementById('preview-jenis-surat').textContent = jenisSuratVal || '-';
        document.getElementById('preview-jenissurat').textContent = jenisSuratVal || '-';
        
        document.getElementById('preview-tanggal-ttd').textContent = formatTanggalIndonesia(document.querySelector('input[name="taggalttd"]').value);
    }

    function syncJenisSuratFields(value) {
        document.getElementById('jenissurat-value').value = value;
        document.getElementById('jenis-surat-value').value = value;
        updatePreview(); // Update preview whenever jenis surat changes
    }

    function onJenisSuratChange(value) {
        const select = document.getElementById('jenissurat-select');
        const custom = document.getElementById('jenissurat-custom');
        const switchBtn = document.getElementById('jenis-surat-switch');

        if (value === 'Lainnya') {
            select.style.display = 'none';
            custom.style.display = 'block';
            custom.value = '';
            syncJenisSuratFields('');
            switchBtn.style.display = 'inline-flex';
            custom.focus();
        } else {
            select.style.display = 'block';
            custom.style.display = 'none';
            syncJenisSuratFields(value);
            switchBtn.style.display = 'none';
            select.style.color = '#111827';
        }
    }

    function showJenisSuratSelect() {
        const select = document.getElementById('jenissurat-select');
        const custom = document.getElementById('jenissurat-custom');
        const switchBtn = document.getElementById('jenis-surat-switch');

        select.style.display = 'block';
        select.value = '';
        select.style.color = '#9ca3af';
        custom.style.display = 'none';
        switchBtn.style.display = 'none';
        syncJenisSuratFields('');
        select.focus();
    }

    function syncJenisSuratValue(value) {
        syncJenisSuratFields(value);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('jenissurat-select');
        select.style.color = '#9ca3af';

        // Add input event listeners to all form fields for real-time preview
        const formFields = document.querySelectorAll('#form-surat input, #form-surat select');
        formFields.forEach(field => {
            field.addEventListener('input', updatePreview);
            field.addEventListener('change', updatePreview);
        });
    });

    document.getElementById('btn-preview-surat').addEventListener('click', function () {
        updatePreview(); // Ensure preview is up-to-date
        new bootstrap.Modal(document.getElementById('preview-surat-modal')).show();
    });

    document.getElementById('btn-simpan-laporan').addEventListener('click', function () {
        const form = document.getElementById('form-surat');
        if (!form.reportValidity()) {
            return;
        }

        new bootstrap.Modal(document.getElementById('confirm-save-modal')).show();
    });

    document.getElementById('btn-confirm-save').addEventListener('click', function () {
        document.getElementById('form-surat').submit();
    });
</script>
</body>
</html>