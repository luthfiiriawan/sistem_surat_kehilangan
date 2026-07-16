@php
    $bulanOptions = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    $polresOptions = [
        'Polres Banjar', 'Polres Bogor', 'Polrestabes Bandung', 'Polresta Bandung', 'Polres Ciamis',
        'Polres Cianjur', 'Polres Cimahi', 'Polresta Cirebon', 'Polres Cirebon Kota', 'Polres Garut',
        'Polres Indramayu', 'Polres Karawang', 'Polres Kuningan', 'Polres Majalengka', 'Polres Pangandaran',
        'Polres Purwakarta', 'Polres Subang', 'Polres Sukabumi', 'Polres Sukabumi Kota', 'Polres Sumedang',
        'Polres Tasikmalaya', 'Polres Tasikmalaya Kota',
    ];
    $jenisSuratOptions = ['STNK', 'BPKB', 'BPKB dan STNK'];
    $isCustomJenisSurat = ! in_array($suratKehilangan->jenissurat, $jenisSuratOptions, true);
@endphp
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
        .form-select { color:#111827; }
        .form-select option { color:#111827; }
        #jenissurat-select option[value=""] { color:#9ca3af; }
        #jenissurat-select { color:#9ca3af; }
        #jenissurat-select:valid { color:#111827; }
        .btn-accent { background:linear-gradient(135deg, var(--accent-color), var(--accent-hover)); color:#fff; border:none; font-weight:700; border-radius:14px; min-width:160px; }
        .btn-secondary { background:#6b7280; color:#fff; border:none; font-weight:700; border-radius:14px; }
        .jenis-surat-field { display:flex; align-items:flex-start; gap:0.75rem; }
        .jenis-surat-field .form-select,
        .jenis-surat-field .form-control { flex:1; min-width:0; }
        .jenis-surat-switch {
            display:inline-flex; align-items:center; gap:0.5rem; font-size:0.92rem; color:#fff;
            background:rgba(17,24,39,0.95); border:1px solid rgba(255,255,255,0.9); border-radius:14px;
            padding:0.7rem 1rem; cursor:pointer; flex-shrink:0; margin-top:0.4rem;
        }
        .jenis-surat-switch:hover { background:#111827; color:#fff; }
        .confirm-modal .modal-dialog { max-width:560px; }
        .confirm-modal .modal-content { border:none; border-radius:24px; overflow:hidden; box-shadow:0 24px 80px rgba(15,23,42,0.2); }
        .confirm-modal .modal-header { background:#f8fafc; border-bottom:1px solid rgba(203,213,225,0.9); padding:1.5rem 2rem; }
        .confirm-modal .modal-title { font-family:'Outfit',sans-serif; font-weight:700; font-size:1.35rem; color:var(--primary-color); }
        .confirm-modal .modal-body { padding:2rem 2.25rem 2.25rem; color:#4b5563; line-height:1.7; text-align:center; }
        .confirm-modal .modal-body p { font-size:1.05rem; }
        .confirm-modal .modal-body .fw-semibold { font-size:1.15rem; }
        .confirm-modal .modal-footer { border-top:1px solid rgba(203,213,225,0.9); padding:1.25rem 2rem 1.75rem; gap:0.75rem; justify-content:center; }
        .confirm-modal .modal-footer .btn { min-width:160px; padding:0.75rem 1.5rem; font-size:1rem; }
        .confirm-modal .confirm-icon { width:80px; height:80px; border-radius:20px; background:rgba(220,38,38,0.1); color:var(--accent-color); display:flex; align-items:center; justify-content:center; font-size:2.5rem; margin:0 auto 1.5rem; }
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
                    <h1 class="h4 mb-1 fw-bold">Edit Surat</h1>
                    <p class="mb-0 small text-white-50">Perbarui Data Surat Kehilangan</p>
                </div>
            </div>
            <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-outline-light"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>
</header>

<div class="container mb-5 content-layer" style="max-width: 1200px;">
    <form id="form-surat" action="{{ route('surat-kehilangan.update', $suratKehilangan) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="jenis_surat" id="jenis-surat-value" value="{{ $suratKehilangan->jenis_surat }}">

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
                    <input name="nomer_surat" class="form-control" placeholder="Contoh: 11/V/2026" value="{{ $suratKehilangan->nomer_surat }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select name="bulan" class="form-select" required>
                        <option value="" disabled hidden {{ $suratKehilangan->bulan ? '' : 'selected' }}>Pilih Bulan (Romawi)</option>
                        @foreach ($bulanOptions as $bulan)
                            <option @selected($suratKehilangan->bulan === $bulan)>{{ $bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input name="tahun" class="form-control" placeholder="Contoh: 2026" value="{{ $suratKehilangan->tahun }}" required>
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
                    <input name="nopo" class="form-control" placeholder="Contoh: B 1234 ABC" value="{{ $suratKehilangan->nopo }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Merk / Type <span class="text-danger">*</span></label>
                    <input name="merk" class="form-control" placeholder="Contoh: Honda Vario" value="{{ $suratKehilangan->merk }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <input name="jenis" class="form-control" placeholder="Contoh: Sepeda Motor" value="{{ $suratKehilangan->jenis }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun Pembuatan <span class="text-danger">*</span></label>
                    <input name="tahun_pembuatan" class="form-control" placeholder="Contoh: 2022" value="{{ $suratKehilangan->tahun_pembuatan }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Warna <span class="text-danger">*</span></label>
                    <input name="warna" class="form-control" placeholder="Contoh: Hitam" value="{{ $suratKehilangan->warna }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Rangka <span class="text-danger">*</span></label>
                    <input name="nomor_rangka" class="form-control" placeholder="Masukkan Nomor Rangka" value="{{ $suratKehilangan->nomor_rangka }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Mesin <span class="text-danger">*</span></label>
                    <input name="nomor_mesin" class="form-control" placeholder="Masukkan Nomor Mesin" value="{{ $suratKehilangan->nomor_mesin }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">BPKB <span class="text-danger">*</span></label>
                    <input name="bpkb" class="form-control" placeholder="Masukkan Nomor BPKB" value="{{ $suratKehilangan->bpkb }}" required>
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
                        <option value="" disabled hidden {{ $suratKehilangan->polres ? '' : 'selected' }}>Pilih Polres Pelapor</option>
                        @if ($suratKehilangan->polres && ! in_array($suratKehilangan->polres, $polresOptions, true))
                            <option selected>{{ $suratKehilangan->polres }}</option>
                        @endif
                        @foreach ($polresOptions as $polres)
                            <option @selected($suratKehilangan->polres === $polres)>{{ $polres }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Surat Keterangan Polisi <span class="text-danger">*</span></label>
                    <input name="nomor_surat_keterangan" class="form-control" placeholder="Masukkan Nomor" value="{{ $suratKehilangan->nomor_surat_keterangan }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lapor <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_tahun_lapor" class="form-control" value="{{ $suratKehilangan->tanggal_tahun_lapor_form }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                    <div class="jenis-surat-field">
                        <select id="jenissurat-select" class="form-select" onchange="onJenisSuratChange(this.value)" style="{{ $isCustomJenisSurat ? 'display:none;' : '' }}">
                            <option value="" disabled hidden>Pilih Jenis Surat</option>
                            @foreach ($jenisSuratOptions as $jenisSurat)
                                <option value="{{ $jenisSurat }}" @selected(! $isCustomJenisSurat && $suratKehilangan->jenissurat === $jenisSurat)>{{ $jenisSurat }}</option>
                            @endforeach
                            <option value="Lainnya" @selected($isCustomJenisSurat)>Lainnya</option>
                        </select>
                        <input type="text" id="jenissurat-custom" class="form-control" placeholder="Tulis jenis surat lainnya" style="{{ $isCustomJenisSurat ? '' : 'display:none;' }}" value="{{ $isCustomJenisSurat ? $suratKehilangan->jenissurat : '' }}" oninput="syncJenisSuratValue(this.value)">
                        <button type="button" class="jenis-surat-switch" id="jenis-surat-switch" style="{{ $isCustomJenisSurat ? '' : 'display:none;' }}" onclick="showJenisSuratSelect()">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </button>
                    </div>
                    <input type="hidden" name="jenissurat" id="jenissurat-value" value="{{ $suratKehilangan->jenissurat }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal TTD <span class="text-danger">*</span></label>
                    <input type="date" name="taggalttd" class="form-control" value="{{ $suratKehilangan->taggalttd_form }}" required>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" id="btn-simpan-laporan" class="btn btn-accent px-4">Perbarui Laporan</button>
            <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<div class="modal fade confirm-modal" id="confirm-save-modal" tabindex="-1" aria-labelledby="confirm-save-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-save-title">Konfirmasi Perbarui Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <p class="mb-0 fw-semibold text-dark">Apakah Anda yakin sudah mengisi data dengan benar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-accent px-4" id="btn-confirm-save">Ya, Perbarui Laporan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Periksa Kembali</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function syncJenisSuratFields(value) {
        document.getElementById('jenissurat-value').value = value;
        document.getElementById('jenis-surat-value').value = value;
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
        @if (! $isCustomJenisSurat)
            select.style.color = '#111827';
        @endif
    });

    document.getElementById('btn-simpan-laporan').addEventListener('click', function () {
        const form = document.getElementById('form-surat');
        if (!form.reportValidity()) {
            return;
        }

        syncJenisSuratFields(document.getElementById('jenissurat-value').value);
        new bootstrap.Modal(document.getElementById('confirm-save-modal')).show();
    });

    document.getElementById('btn-confirm-save').addEventListener('click', function () {
        document.getElementById('form-surat').submit();
    });
</script>
</body>
</html>
