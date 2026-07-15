<?php

use App\Http\Controllers\SuratKehilanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('surat-kehilangan.index');
});

Route::resource('surat-kehilangan', SuratKehilanganController::class);
Route::get('surat-kehilangan/{suratKehilangan}/download', [SuratKehilanganController::class, 'download'])->name('surat-kehilangan.download');
Route::get('surat-kehilangan/export-excel', [SuratKehilanganController::class, 'exportExcel'])->name('surat-kehilangan.export-excel');
