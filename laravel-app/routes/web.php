<?php

use App\Http\Controllers\SuratKehilanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('surat-kehilangan.index');
});

Route::get('surat-kehilangan/export-excel', [SuratKehilanganController::class, 'exportExcel'])->name('surat-kehilangan.export-excel');
Route::get('surat-kehilangan-search', [SuratKehilanganController::class, 'search'])->name('surat-kehilangan.search');
Route::resource('surat-kehilangan', SuratKehilanganController::class);
Route::get('surat-kehilangan/{suratKehilangan}/download', [SuratKehilanganController::class, 'download'])->name('surat-kehilangan.download');
