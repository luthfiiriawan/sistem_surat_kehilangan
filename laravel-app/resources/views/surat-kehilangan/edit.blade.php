<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color:#111827; --primary-light:#1f2937; --accent-color:#dc2626; --accent-hover:#b91c1c; --bg-color:#f1f5f9; --card-bg:#fff; --border-color:#cbd5e1; --text-main:#374151; }
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
        .card-premium { background:var(--card-bg); border:1px solid var(--border-color); border-radius:16px; box-shadow:0 4px 20px rgba(15,23,42,0.03); }
        .section-legend { background:#f1f5f9; color:var(--primary-color); font-weight:700; padding:8px 16px; border-radius:8px; display:inline-flex; align-items:center; gap:8px; margin-bottom:20px; border-left:4px solid var(--accent-color); }
        .form-label { font-size:0.82rem; font-weight:600; color:var(--primary-color); margin-bottom:6px; }
        .form-control, .form-select { border:1.5px solid var(--border-color); border-radius:10px; padding:10px 14px; }
        .btn-accent { background:linear-gradient(135deg, var(--accent-color), var(--accent-hover)); color:#fff; border:none; font-weight:700; }
    </style>
</head>
<body>
<header class="app-header py-4 mb-4">
    <div class="container" style="max-width: 1200px;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo_tik_polri.png') }}" alt="Logo TIK POLRI" style="height: 52px; width: auto; opacity: 0.9;">
                <div>
                    <h1 class="h4 mb-1 fw-bold">Edit Surat</h1>
                    <p class="mb-0 small text-white-50">Perbarui Data Surat Kehilangan</p>
                </div>
            </div>
            <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-outline-light"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>
</header>

<div class="container mb-5 content-layer" style="max-width: 1200px;">
    <form action="{{ route('surat-kehilangan.update', $suratKehilangan) }}" method="POST" class="card-premium p-4">
        @csrf
        @method('PUT')
        <div class="section-legend"><i class="bi bi-file-earmark-text"></i> A. Data Surat</div>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><label class="form-label">Nomor Surat <span class="text-danger">*</span></label><input name="nomer_surat" class="form-control" value="{{ $suratKehilangan->nomer_surat }}" required></div>
            <div class="col-md-4"><label class="form-label">Bulan <span class="text-danger">*</span></label><input name="bulan" class="form-control" value="{{ $suratKehilangan->bulan }}" required></div>
            <div class="col-md-4"><label class="form-label">Tahun <span class="text-danger">*</span></label><input name="tahun" class="form-control" value="{{ $suratKehilangan->tahun }}" required></div>
        </div>

        <div class="section-legend"><i class="bi bi-car-front-fill"></i> B. Identitas Kendaraan</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6"><label class="form-label">Nomor Polisi (Nopol) <span class="text-danger">*</span></label><input name="nopo" class="form-control text-uppercase" value="{{ $suratKehilangan->nopo }}" required></div>
            <div class="col-md-6"><label class="form-label">Merk / Type <span class="text-danger">*</span></label><input name="merk" class="form-control" value="{{ $suratKehilangan->merk }}" required></div>
            <div class="col-md-6"><label class="form-label">Jenis <span class="text-danger">*</span></label><input name="jenis" class="form-control" value="{{ $suratKehilangan->jenis }}" required></div>
            <div class="col-md-6"><label class="form-label">Tahun Pembuatan <span class="text-danger">*</span></label><input name="tahun_pembuatan" class="form-control" value="{{ $suratKehilangan->tahun_pembuatan }}" required></div>
            <div class="col-md-6"><label class="form-label">Warna <span class="text-danger">*</span></label><input name="warna" class="form-control" value="{{ $suratKehilangan->warna }}" required></div>
            <div class="col-md-6"><label class="form-label">Nomor Rangka <span class="text-danger">*</span></label><input name="nomor_rangka" class="form-control" value="{{ $suratKehilangan->nomor_rangka }}" required></div>
            <div class="col-md-6"><label class="form-label">Nomor Mesin <span class="text-danger">*</span></label><input name="nomor_mesin" class="form-control" value="{{ $suratKehilangan->nomor_mesin }}" required></div>
            <div class="col-md-6"><label class="form-label">BPKB <span class="text-danger">*</span></label><input name="bpkb" class="form-control" value="{{ $suratKehilangan->bpkb }}" required></div>
        </div>

        <div class="section-legend"><i class="bi bi-shield-check"></i> C. Informasi Surat</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6"><label class="form-label">Polres <span class="text-danger">*</span></label><input name="polres" class="form-control" value="{{ $suratKehilangan->polres }}" required></div>
            <div class="col-md-6"><label class="form-label">Nomor Surat Keterangan <span class="text-danger">*</span></label><input name="nomor_surat_keterangan" class="form-control" value="{{ $suratKehilangan->nomor_surat_keterangan }}" required></div>
            <div class="col-md-6"><label class="form-label">Tanggal / Tahun Lapor <span class="text-danger">*</span></label><input name="tanggal_tahun_lapor" class="form-control" value="{{ $suratKehilangan->tanggal_tahun_lapor }}" required></div>
            <div class="col-md-6"><label class="form-label">Jenis Surat <span class="text-danger">*</span></label><input name="jenissurat" class="form-control" value="{{ $suratKehilangan->jenissurat }}" required></div>
            <div class="col-md-6"><label class="form-label">Jenis Surat Detail <span class="text-danger">*</span></label><input name="jenis_surat" class="form-control" value="{{ $suratKehilangan->jenis_surat }}" required></div>
            <div class="col-md-6"><label class="form-label">Tanggal TTD <span class="text-danger">*</span></label><input name="taggalttd" class="form-control" value="{{ $suratKehilangan->taggalttd }}" required></div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-accent px-4">Perbarui</button>
            <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
