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
        .form-control, .form-select { border:1px solid rgba(203,213,225,0.95); border-radius:14px; padding:12px 16px; background:#f8fafc; }
        .form-control:focus, .form-select:focus { border-color:rgba(220,38,38,0.8); box-shadow:0 0 0 0.15rem rgba(220,38,38,0.12); }
        .form-control::placeholder { color:#9ca3af; }
        .btn-accent { background:linear-gradient(135deg, var(--accent-color), var(--accent-hover)); color:#fff; border:none; font-weight:700; border-radius:14px; min-width:160px; }
        .btn-secondary { background:#6b7280; color:#fff; border:none; font-weight:700; border-radius:14px; }
        .field-note { font-size:0.82rem; color:#6b7280; }
    </style>
</head>
<body>
<header class="app-header py-4 mb-4">
    <div class="container" style="max-width: 1200px;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo_tik_polri.png') }}" alt="Logo TIK POLRI" style="height: 52px; width: auto; opacity: 0.9;">
                <div>
                    <h1 class="h4 mb-1 fw-bold">Formulir Pembuatan Surat</h1>
                    <p class="mb-0 small text-white-50">Sistem Surat Kehilangan Bid TIK POLRI</p>
                </div>
            </div>
            <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-outline-light"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>
</header>

<div class="container mb-5 content-layer" style="max-width: 1200px;">
    <div class="section-header mb-4">
        <span class="badge">A</span>
        <div>
            <h2 class="section-title">A. Data Surat</h2>
            <p class="section-subtitle">Isikan detail nomor surat dan periode laporan.</p>
        </div>
    </div>
    <form action="{{ route('surat-kehilangan.store') }}" method="POST" class="section-panel mb-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                <input name="nomer_surat" class="form-control" placeholder="Contoh: 11/V/2026" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Bulan <span class="text-danger">*</span></label>
                <select name="bulan" class="form-select" required>
                    <option value="">Pilih Bulan (Romawi)</option>
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
    </form>

    <div class="section-header mb-4">
        <span class="badge">B</span>
        <div>
            <h2 class="section-title">B. Identitas Kendaraan</h2>
            <p class="section-subtitle">Detail kendaraan yang hilang atau dilaporkan.</p>
        </div>
    </div>
    <form action="{{ route('surat-kehilangan.store') }}" method="POST" class="section-panel mb-4">
        @csrf
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
    </form>

    <div class="section-header mb-4">
        <span class="badge">C</span>
        <div>
            <h2 class="section-title">C. Informasi Laporan</h2>
            <p class="section-subtitle">Detail laporan dan tanggal tanda tangan.</p>
        </div>
    </div>
    <form action="{{ route('surat-kehilangan.store') }}" method="POST" class="section-panel mb-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Polres Pelapor <span class="text-danger">*</span></label>
                <select name="polres" class="form-select" required>
                    <option value="">Pilih Polres Pelapor</option>
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
            <div class="col-md-6 position-relative">
                <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                <div style="position:relative;">
                    <select id="jenissurat-select" class="form-select" onchange="onJenisSuratChange(this.value)">
                        <option value="">Pilih Jenis Surat</option>
                        <option value="STNK">STNK</option>
                        <option value="BPKB">BPKB</option>
                        <option value="BPKB dan STNK">BPKB dan STNK</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <input type="text" id="jenissurat-custom" class="form-control" placeholder="Tulis jenis surat lainnya" style="display:none; position:absolute; inset:0; width:100%; height:100%; background:#f8fafc; border:1px solid rgba(203,213,225,0.95);" oninput="syncJenisSuratValue(this.value)">
                </div>
                <input type="hidden" name="jenissurat" id="jenissurat-value" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal TTD <span class="text-danger">*</span></label>
                <input type="date" name="taggalttd" class="form-control" required>
            </div>
        </div>
    </form>

    <div class="d-flex gap-2">
        <button class="btn btn-accent px-4">Simpan Laporan</button>
        <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</div>
<script>
    function onJenisSuratChange(value) {
        const custom = document.getElementById('jenissurat-custom');
        const hidden = document.getElementById('jenissurat-value');

        if (value === 'Lainnya') {
            custom.style.display = 'block';
            custom.value = '';
            hidden.value = '';
            custom.focus();
        } else {
            custom.style.display = 'none';
            hidden.value = value;
        }
    }

    function syncJenisSuratValue(value) {
        document.getElementById('jenissurat-value').value = value;
    }
</script>
</body>
</html>
