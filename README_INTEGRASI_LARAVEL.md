# Dokumentasi Aplikasi Surat Kehilangan

## Ringkasan aplikasi lama

Aplikasi ini awalnya berupa sistem PHP native yang menangani alur berikut:

1. Formulir pengisian data surat kehilangan kendaraan.
2. Validasi input dan pencegahan duplikasi pada nomor polisi, nomor rangka, dan nomor mesin.
3. Penyimpanan data ke tabel MySQL bernama surat_kehilangan.
4. Pembuatan dokumen Word dari template .docx.
5. Tampilan daftar data, edit, hapus, dan ekspor Excel.

## Struktur file lama

- \_legacy/index.php → formulir utama
- \_legacy/proses.php → proses insert dan generate dokumen Word
- \_legacy/data.php → daftar data dan pencarian/filter
- \_legacy/edit.php → edit data
- \_legacy/hapus.php → hapus data
- \_legacy/export_excel.php → ekspor Excel
- \_legacy/koneksi.php → koneksi database MySQL

## Integrasi ke Laravel

Versi Laravel yang dibuat berada di folder:

- laravel-app/

### Fitur yang sudah diintegrasikan

- CRUD untuk data surat kehilangan
- Routing resource Laravel
- Validasi request
- Model Eloquent
- Migrasi database
- Halaman daftar, create, edit, dan detail
- Download dokumen Word
- Export Excel
- Pengujian fitur awal

### Struktur penting

- app/Http/Controllers/SuratKehilanganController.php
- app/Models/SuratKehilangan.php
- app/Http/Requests/StoreSuratKehilanganRequest.php
- app/Http/Requests/UpdateSuratKehilanganRequest.php
- database/migrations/2026_07_13_024924_create_surat_kehilangans_table.php
- resources/views/surat-kehilangan/
- routes/web.php

## Cara menjalankan

1. Masuk ke folder Laravel:
   ```bash
   cd laravel-app
   ```
2. Jalankan server:
   ```bash
   php artisan serve
   ```
3. Buka:
   ```text
   http://127.0.0.1:8000/surat-kehilangan
   ```

## Verifikasi yang sudah dilakukan

- Migrasi database berhasil dijalankan.
- Pengujian fitur khusus berhasil:
  ```bash
  php artisan test --filter=SuratKehilanganTest
  ```

## Rekomendasi berikutnya

- Menyamakan tampilan UI Laravel dengan desain legacy yang sudah ada.
- Menambahkan fitur autentikasi pengguna.
- Memperkuat template Word agar lebih presisi sesuai format resmi.
- Menambahkan pagination dan sorting yang lebih baik.
- Mengganti koneksi database ke environment terpisah untuk staging/production.
