# Dokumentasi Integrasi Laravel

Aplikasi ini sekarang tersedia dalam versi Laravel yang mengadopsi fungsi-fungsi sistem lama:

- Form pembuatan surat
- Penyimpanan data ke database MySQL
- Tampilan daftar data
- Edit dan hapus data
- Download dokumen Word
- Ekspor Excel

## Struktur utama

- app/Http/Controllers/SuratKehilanganController.php
- app/Models/SuratKehilangan.php
- app/Http/Requests/StoreSuratKehilanganRequest.php
- database/migrations/2026_07_13_024924_create_surat_kehilangans_table.php
- resources/views/surat-kehilangan/\*
- routes/web.php

## Jalankan aplikasi

```bash
php artisan serve
```

Akses di browser:

```text
http://127.0.0.1:8000/surat-kehilangan
```
