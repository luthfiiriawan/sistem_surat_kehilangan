<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Surat Kehilangan Bid TIK POLRI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #111827;
            --primary-light: #1f2937;
            --accent-color: #dc2626;
            --accent-hover: #b91c1c;
            --bg-color: #f1f5f9;
            --card-bg: #ffffff;
            --border-color: #cbd5e1;
            --text-main: #374151;
            --text-muted: #6b7280;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-color); color: var(--text-main); position: relative; min-height: 100vh; }
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
        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; }
        .app-header { background: var(--primary-color); color: #fff; border-bottom: 4px solid var(--accent-color); }
        .card-premium { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 4px 20px rgba(15,23,42,0.03); }
        .section-legend { background: #f1f5f9; color: var(--primary-color); font-weight: 700; padding: 8px 16px; border-radius: 8px; border-left: 4px solid var(--accent-color); }
        .btn-accent { background: linear-gradient(135deg, var(--accent-color), var(--accent-hover)); color: #fff; border: none; font-weight: 700; border-radius: 14px; }
        .btn-accent:hover { color: #fff; }
        .btn-filter { background: #dc2626; border: none; color: #fff; font-weight: 700; border-radius: 16px; min-height: 48px; padding: 0 1.4rem; }
        .btn-filter:hover { background: #b91c1c; color: #fff; }
        .btn-export { background: #fff; border: 1px solid #d1d5db; color: var(--primary-color); font-weight: 700; }
        .btn-export:hover { background: #f8fafc; }
        .form-control, .form-select { border: 1.5px solid var(--border-color); border-radius: 16px; padding: 10px 14px; }
        .form-control.form-control-lg { min-height: 46px; }
        .input-group .form-control { border: none; min-height: 46px; }
        .form-control:focus { box-shadow: none; border-color: var(--accent-color); }
        .input-group { border: 1.5px solid var(--border-color); border-radius: 16px; overflow: hidden; }
        .input-group-text { background: #f8fafc; border: none; color: var(--text-muted); min-width: 52px; justify-content: center; }
        .input-group .form-control { border: none; }
        .filter-panel { background: #fff; border: 1px solid #d1d5db; border-radius: 16px; padding: 20px; box-shadow: 0 8px 25px rgba(15,23,42,0.05); }
        .filter-dropdown { position: relative; display: inline-block; }
        .filter-dropdown-panel { position: absolute; right: 0; top: calc(100% + 12px); width: 340px; background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 20px 60px rgba(15,23,42,0.16); padding: 20px; z-index: 20; display: none; }
        .filter-dropdown-panel.show { display: block; }
        .filter-dropdown-panel h6 { font-size: 0.85rem; letter-spacing: .08em; text-transform: uppercase; color: #6b7280; margin-bottom: 16px; }
        .preset-list button { width: 100%; text-align: left; border-radius: 12px; padding: 12px 14px; border: 1px solid #e5e7eb; background: #fff; color: #111827; transition: all 0.2s ease; }
        .preset-list button:hover { background: #f8fafc; border-color: #d1d5db; }
        .preset-list button.active { background: linear-gradient(135deg, var(--accent-color), var(--accent-hover)); color: #fff; border-color: var(--accent-color); font-weight: 600; box-shadow: 0 4px 12px rgba(220,38,38,0.25); }
        .preset-list button.active:hover { background: linear-gradient(135deg, var(--accent-hover), #991b1b); color: #fff; }
        .filter-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(220, 38, 38, 0.08);
            border: 1.5px solid rgba(220, 38, 38, 0.3);
            color: var(--accent-color);
            border-radius: 50px;
            padding: 6px 14px 6px 12px;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            animation: fadeIn 0.2s ease;
        }
        .filter-badge .badge-clear {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--accent-color);
            color: #fff;
            font-size: 0.7rem;
            cursor: pointer;
            border: none;
            padding: 0;
            line-height: 1;
            transition: background 0.15s ease;
            flex-shrink: 0;
        }
        .filter-badge .badge-clear:hover { background: var(--accent-hover); }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }
        .table-premium thead th { background: #081c3c; color: #fff; font-size: 0.82rem; text-transform: uppercase; letter-spacing: .5px; border-color: rgba(255,255,255,.08); }
        .table-premium td { vertical-align: middle; }
        .table-premium td.no-surat-cell { max-width: 285px; white-space: normal; word-break: break-word; padding-right: 0.8rem; }
        .table-premium td.no-surat-cell span { display: inline-block; line-height: 1.35; }
        .meta-label { display: block; color: #6b7280; font-size: 0.92rem; opacity: .7; margin-top: 0.35rem; line-height: 1.3; }
        .section-title { border-left: 4px solid var(--accent-color); padding-left: 12px; }
        .empty-state { text-align: center; color: var(--text-muted); padding: 70px 0; }
        .empty-state .bi { font-size: 3rem; color: #9ca3af; }
        .empty-state h4 { margin-top: 16px; font-weight: 700; color: var(--primary-color); }
        .empty-state p { margin-bottom: 0; font-size: 0.95rem; }
        .table-premium tbody td { vertical-align: middle; }
        .table-premium tbody tr:hover { background: #f8fafc; }
        .empty-state { text-align: center; color: var(--text-muted); padding: 60px 0; }
        .empty-state .bi { font-size: 2.5rem; color: #9ca3af; }
        .empty-state h4 { margin-top: 18px; font-weight: 700; color: var(--primary-color); }
        .empty-state p { margin-bottom: 0; }
        .feedback-modal .modal-dialog { max-width: 520px; }
        .feedback-modal .modal-content { border: none; border-radius: 24px; overflow: hidden; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.2); }
        .feedback-modal .modal-header { background: #f8fafc; border-bottom: 1px solid rgba(203, 213, 225, 0.9); padding: 1.5rem 2rem; }
        .feedback-modal .modal-title { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.35rem; color: var(--primary-color); }
        .feedback-modal .modal-body { padding: 2rem 2.25rem 2.25rem; color: #4b5563; line-height: 1.7; text-align: center; }
        .feedback-modal .modal-body p { font-size: 1.05rem; margin-bottom: 0; }
        .feedback-modal .modal-footer { border-top: 1px solid rgba(203, 213, 225, 0.9); padding: 1.25rem 2rem 1.75rem; justify-content: center; }
        .feedback-modal .modal-footer .btn { min-width: 160px; padding: 0.75rem 1.5rem; font-size: 1rem; }
        .feedback-modal .feedback-icon { width: 80px; height: 80px; border-radius: 20px; background: rgba(22, 163, 74, 0.12); color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; }
        .confirm-modal .modal-dialog { max-width: 560px; }
        .confirm-modal .modal-content { border: none; border-radius: 24px; overflow: hidden; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.2); }
        .confirm-modal .modal-header { background: #f8fafc; border-bottom: 1px solid rgba(203, 213, 225, 0.9); padding: 1.5rem 2rem; }
        .confirm-modal .modal-title { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.35rem; color: var(--primary-color); }
        .confirm-modal .modal-body { padding: 2rem 2.25rem 2.25rem; color: #4b5563; line-height: 1.7; text-align: center; }
        .confirm-modal .modal-body p { font-size: 1.05rem; }
        .confirm-modal .modal-body .fw-semibold { font-size: 1.15rem; }
        .confirm-modal .modal-footer { border-top: 1px solid rgba(203, 213, 225, 0.9); padding: 1.25rem 2rem 1.75rem; gap: 0.75rem; justify-content: center; }
        .confirm-modal .modal-footer .btn { min-width: 160px; padding: 0.75rem 1.5rem; font-size: 1rem; }
        .confirm-modal .confirm-icon { width: 80px; height: 80px; border-radius: 20px; background: rgba(220, 38, 38, 0.1); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; }
        .premium-pagination .page-link {
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            margin: 0 3px;
            padding: 8px 14px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 10px !important;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(15,23,42,0.02);
        }
        .premium-pagination .page-link:hover {
            background-color: var(--primary-light);
            color: #fff;
            border-color: var(--primary-light);
        }
        .premium-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            border-color: var(--accent-color);
            color: #fff;
            box-shadow: 0 4px 12px rgba(220,38,38,0.25);
        }
        .premium-pagination .page-item.disabled .page-link {
            color: var(--text-muted);
            opacity: 0.5;
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(15,23,42,0.03);
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-height: 0;
        }
        .stat-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
        .stat-card-icon.total { background: rgba(17, 24, 39, 0.08); color: var(--primary-color); }
        .stat-card-icon.stnk { background: rgba(37, 99, 235, 0.1); color: #2563eb; }
        .stat-card-icon.bpkb { background: rgba(22, 163, 74, 0.1); color: #16a34a; }
        .stat-card-icon.keduanya { background: rgba(124, 58, 237, 0.1); color: #7c3aed; }
        .stat-card-icon.lainnya { background: rgba(234, 88, 12, 0.1); color: #ea580c; }
        .stat-card-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1.1;
        }
        .stat-card-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            line-height: 1.3;
            margin-top: 0.15rem;
        }
        @media (max-width: 991.98px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 575.98px) {
            .stats-grid { grid-template-columns: 1fr; }
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
                    <h1 class="h4 mb-1 fw-bold">Sistem Surat Kehilangan</h1>
                    <p class="mb-0 small text-white-50">Daftar Hasil Pengecekan Kehilangan BPKB / STNK</p>
                </div>
            </div>
            <a href="{{ route('surat-kehilangan.create') }}" class="btn btn-accent px-4"><i class="bi bi-plus-circle me-2"></i>Buat Surat</a>
        </div>
    </div>
</header>

<div class="container mb-5 content-layer" style="max-width: 1200px;">

    <div class="card-premium p-4 mb-4">
        <div class="section-legend mb-3"><i class="bi bi-search"></i> Filter & Pencarian</div>
        <form method="GET" action="{{ route('surat-kehilangan.index') }}">
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control" placeholder="Cari nopol / bpkb / merk" value="{{ request('cari') }}">
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex align-items-center justify-content-end gap-2 flex-wrap">
                        <div id="active-filter-badge" class="filter-badge" style="display:none !important;">
                            <i class="bi bi-calendar3" style="font-size:0.8rem;"></i>
                            <span id="active-filter-label"></span>
                            <button type="button" class="badge-clear" onclick="clearDateFilter()" title="Hapus filter tanggal">&times;</button>
                        </div>
                        <div class="filter-dropdown" style="position:relative;">
                            <button type="button" id="toggle-filter-btn" class="btn btn-filter" onclick="toggleFilterPanel()">
                                Filter <i class="bi bi-chevron-down ms-2"></i>
                            </button>
                            <div id="filter-panel" class="filter-dropdown-panel">
                                <h6>Filter date range</h6>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small text-uppercase text-muted">From</label>
                                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-uppercase text-muted">To</label>
                                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                                    </div>
                                </div>
                                <div class="preset-list d-grid gap-2 mb-3">
                                    <button type="button" class="btn btn-light" onclick="setPreset('today')" data-preset="today">Today</button>
                                    <button type="button" class="btn btn-light" onclick="setPreset('yesterday')" data-preset="yesterday">Yesterday</button>
                                    <button type="button" class="btn btn-light" onclick="setPreset('thisMonth')" data-preset="thisMonth">This Month</button>
                                    <button type="button" class="btn btn-light" onclick="setPreset('pastMonth')" data-preset="pastMonth">Past Month</button>
                                    <button type="button" class="btn btn-light" onclick="setPreset('past3Months')" data-preset="past3Months">Past 3 Months</button>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-accent flex-grow-1">Apply</button>
                                    @if(request('start_date') || request('end_date'))
                                        <a href="{{ route('surat-kehilangan.index') }}" class="btn btn-outline-secondary">Reset</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon total"><i class="bi bi-files"></i></div>
            <div>
                <div class="stat-card-value" id="stat-total">{{ $stats['total'] }}</div>
                <div class="stat-card-label">Jumlah Surat</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon stnk"><i class="bi bi-card-text"></i></div>
            <div>
                <div class="stat-card-value" id="stat-stnk">{{ $stats['stnk'] }}</div>
                <div class="stat-card-label">Surat STNK</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon bpkb"><i class="bi bi-journal-check"></i></div>
            <div>
                <div class="stat-card-value" id="stat-bpkb">{{ $stats['bpkb'] }}</div>
                <div class="stat-card-label">Surat BPKB</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon keduanya"><i class="bi bi-collection"></i></div>
            <div>
                <div class="stat-card-value" id="stat-keduanya">{{ $stats['keduanya'] }}</div>
                <div class="stat-card-label">Surat Keduanya</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon lainnya"><i class="bi bi-three-dots"></i></div>
            <div>
                <div class="stat-card-value" id="stat-lainnya">{{ $stats['lainnya'] }}</div>
                <div class="stat-card-label">Surat Lainnya</div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0 fw-bold section-title">Daftar Surat</h2>
        <a href="{{ route('surat-kehilangan.export-excel', request()->only(['cari', 'start_date', 'end_date'])) }}" class="btn btn-export"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</a>
    </div>

    <div class="card-premium overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-premium">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Surat</th>
                        <th>No. Polisi</th>
                        <th>Merk/Tipe</th>
                        <th>No BPKB</th>
                        <th>Jenis Dokumen</th>
                        <th>Tanggal TTD</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $index => $surat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="no-surat-cell"><span>{{ $surat->nomer_surat }}</span></td>
                            <td><strong>{{ $surat->nopo }}</strong></td>
                            <td>
                                {{ $surat->merk }}
                                <span class="meta-label">{{ $surat->jenis }}</span>
                            </td>
                            <td>{{ $surat->bpkb }}</td>
                            <td>
                                {{ $surat->jenissurat }}
                                @if($surat->jenis_surat)
                                    <span class="meta-label">{{ $surat->jenis_surat }}</span>
                                @endif
                            </td>
                            <td>{{ $surat->taggalttd }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('surat-kehilangan.edit', $surat) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                                    <a href="{{ route('surat-kehilangan.download', $surat) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i></a>
                                    <form action="{{ route('surat-kehilangan.destroy', $surat) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-nopo="{{ $surat->nopo }}"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <h4>Belum ada data.</h4>
                                    <p>Gunakan filter di atas atau buat surat baru untuk melihat daftar laporan kehilangan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light flex-wrap gap-2">
            <div id="pagination-info" class="small text-muted">
                Menampilkan 0 - 0 dari 0 data
            </div>
            <nav aria-label="Navigasi Halaman">
                <ul id="pagination-controls" class="pagination pagination-sm mb-0 premium-pagination">
                    <!-- Tombol halaman akan di-render di sini oleh JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

@if(session('success'))
<div class="modal fade feedback-modal" id="feedback-modal" tabindex="-1" aria-labelledby="feedback-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedback-modal-title">Berhasil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="feedback-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <p class="fw-semibold text-dark">{{ session('success') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-accent px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade confirm-modal" id="confirm-delete-modal" tabindex="-1" aria-labelledby="confirm-delete-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-delete-title">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <p class="mb-2 fw-semibold text-dark">Apakah Anda yakin ingin menghapus data laporan ini?</p>
                <p class="mb-0">No. Polisi: <span class="fw-semibold text-dark" id="delete-nopo-label"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-accent px-4" id="btn-confirm-delete">Ya, Hapus Data</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let debounceTimer;
let pendingDeleteForm = null;
const searchInput = document.querySelector('input[name="cari"]');
const tableBody = document.querySelector('.table-premium tbody');
const confirmDeleteModal = document.getElementById('confirm-delete-modal');

// Pagination variables
let currentSurats = @json($surats);
let currentStats = @json($stats);
let currentPage = 1;
const itemsPerPage = 10;

function debounce(func, wait) {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(func, wait);
}

async function performSearch(query) {
    try {
        const startDate = document.querySelector('input[name="start_date"]')?.value || '';
        const endDate = document.querySelector('input[name="end_date"]')?.value || '';
        const response = await fetch(`{{ route('surat-kehilangan.search') }}?query=${encodeURIComponent(query)}&start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`);
        const data = await response.json();
        currentSurats = data.surats;
        currentStats = data.stats;
        updateStatsCards(currentStats);
        renderTable(currentSurats, 1);
    } catch (error) {
        console.error('Error fetching search results:', error);
    }
}

function renderTable(surats, page = 1) {
    currentPage = page;
    const paginationInfo = document.getElementById('pagination-info');
    
    if (surats.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="bi bi-journal-x"></i>
                        <h4>Belum ada data.</h4>
                        <p>Gunakan filter di atas atau buat surat baru untuk melihat daftar laporan kehilangan.</p>
                    </div>
                </td>
            </tr>
        `;
        if (paginationInfo) paginationInfo.textContent = 'Menampilkan 0 - 0 dari 0 data';
        renderPaginationControls(0, 1);
        return;
    }

    const start = (page - 1) * itemsPerPage;
    const end = Math.min(start + itemsPerPage, surats.length);
    const paginatedSurats = surats.slice(start, end);

    tableBody.innerHTML = paginatedSurats.map((surat, index) => {
        const globalIndex = start + index + 1;
        return `
            <tr>
                <td>${globalIndex}</td>
                <td class="no-surat-cell"><span>${escapeHtml(surat.nomer_surat)}</span></td>
                <td><strong>${escapeHtml(surat.nopo)}</strong></td>
                <td>
                    ${escapeHtml(surat.merk)}
                    <span class="meta-label">${escapeHtml(surat.jenis)}</span>
                </td>
                <td>${escapeHtml(surat.bpkb)}</td>
                <td>
                    ${escapeHtml(surat.jenissurat)}
                    ${surat.jenis_surat ? `<span class="meta-label">${escapeHtml(surat.jenis_surat)}</span>` : ''}
                </td>
                <td>${escapeHtml(surat.taggalttd)}</td>
                <td>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="/surat-kehilangan/${surat.id}/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                        <a href="/surat-kehilangan/${surat.id}/download" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i></a>
                        <form action="/surat-kehilangan/${surat.id}" method="POST" class="d-inline">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || ''}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-nopo="${escapeHtml(surat.nopo)}"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    if (paginationInfo) {
        paginationInfo.textContent = `Menampilkan ${start + 1} - ${end} dari ${surats.length} data`;
    }
    renderPaginationControls(surats.length, page);
}

function renderPaginationControls(totalItems, page) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationControls = document.getElementById('pagination-controls');
    
    if (!paginationControls) return;
    
    if (totalPages <= 1) {
        paginationControls.innerHTML = '';
        return;
    }
    
    let html = '';
    
    // Button Previous
    html += `
        <li class="page-item ${page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${page - 1}); return false;" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, page - Math.floor(maxVisiblePages / 2));
    let endPage = startPage + maxVisiblePages - 1;
    
    if (endPage > totalPages) {
        endPage = totalPages;
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        html += `
            <li class="page-item ${page === i ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `;
    }
    
    // Button Next
    html += `
        <li class="page-item ${page === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${page + 1}); return false;" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;
    
    paginationControls.innerHTML = html;
}

window.changePage = function(page) {
    const totalPages = Math.ceil(currentSurats.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    renderTable(currentSurats, page);
    const tableWrapper = document.querySelector('.table-responsive');
    if (tableWrapper) {
        tableWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
};

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateStatsCards(stats) {
    if (!stats) return;

    const mapping = {
        total: 'stat-total',
        stnk: 'stat-stnk',
        bpkb: 'stat-bpkb',
        keduanya: 'stat-keduanya',
        lainnya: 'stat-lainnya',
    };

    Object.entries(mapping).forEach(([key, elementId]) => {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = stats[key] ?? 0;
        }
    });
}

function toggleFilterPanel() {
    const panel = document.getElementById('filter-panel');
    panel.classList.toggle('show');
}

function closeFilterPanel() {
    const panel = document.getElementById('filter-panel');
    panel.classList.remove('show');
}

function setPreset(range) {
    const start = document.querySelector('input[name="start_date"]');
    const end = document.querySelector('input[name="end_date"]');
    const today = new Date();
    const pad = (n) => n.toString().padStart(2, '0');
    const format = (date) => `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}`;
    let from, to;
    if (range === 'today') {
        from = new Date(today);
        to = new Date(today);
    } else if (range === 'yesterday') {
        from = new Date(today);
        from.setDate(from.getDate() - 1);
        to = new Date(from);
    } else if (range === 'thisMonth') {
        from = new Date(today.getFullYear(), today.getMonth(), 1);
        to = new Date(today);
    } else if (range === 'pastMonth') {
        from = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
        to = new Date(today);
    } else if (range === 'past3Months') {
        from = new Date(today.getFullYear(), today.getMonth() - 3, today.getDate());
        to = new Date(today);
    }
    if (from && to) {
        start.value = format(from);
        end.value = format(to);
    }

    // Highlight tombol preset yang aktif
    document.querySelectorAll('.preset-list button').forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.querySelector(`.preset-list button[data-preset="${range}"]`);
    if (activeBtn) activeBtn.classList.add('active');
}

function formatDateDisplay(dateStr) {
    if (!dateStr) return '';
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const d = new Date(dateStr);
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

function updateFilterBadge() {
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput   = document.querySelector('input[name="end_date"]');
    const badge      = document.getElementById('active-filter-badge');
    const label      = document.getElementById('active-filter-label');
    if (!badge || !label) return;

    const startVal = startInput?.value || '';
    const endVal   = endInput?.value   || '';

    if (startVal && endVal) {
        label.textContent = `${formatDateDisplay(startVal)}  –  ${formatDateDisplay(endVal)}`;
        badge.style.removeProperty('display');
    } else {
        badge.style.setProperty('display', 'none', 'important');
    }
}

function clearDateFilter() {
    document.querySelector('input[name="start_date"]').value = '';
    document.querySelector('input[name="end_date"]').value   = '';
    document.querySelectorAll('.preset-list button').forEach(btn => btn.classList.remove('active'));
    updateFilterBadge();
    // Submit form to reload page without date filter
    document.querySelector('form[method="GET"]').submit();
}

document.addEventListener('click', function(event) {
    const filterWrapper = document.querySelector('#filter-panel');
    const toggleBtn = document.getElementById('toggle-filter-btn');
    if (!filterWrapper.contains(event.target) && !toggleBtn.contains(event.target)) {
        closeFilterPanel();
    }
});

// Close filter panel when form is submitted
document.querySelector('form[method="GET"]').addEventListener('submit', function() {
    closeFilterPanel();
});

searchInput.addEventListener('input', function() {
    const query = this.value;
    debounce(() => performSearch(query), 300);
});

tableBody.addEventListener('click', function (event) {
    const deleteBtn = event.target.closest('.btn-delete');
    if (!deleteBtn) {
        return;
    }

    pendingDeleteForm = deleteBtn.closest('form');
    document.getElementById('delete-nopo-label').textContent = deleteBtn.dataset.nopo || '-';
    new bootstrap.Modal(confirmDeleteModal).show();
});

document.getElementById('btn-confirm-delete').addEventListener('click', function () {
    if (pendingDeleteForm) {
        pendingDeleteForm.submit();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    updateStatsCards(currentStats);
    renderTable(currentSurats, 1);
    updateFilterBadge();
    @if(session('success'))
    new bootstrap.Modal(document.getElementById('feedback-modal')).show();
    @endif
});
</script>
</body>
</html>
