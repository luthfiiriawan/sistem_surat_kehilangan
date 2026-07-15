<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_kehilangans', function (Blueprint $table) {
            $table->id();
            $table->string('nomer_surat');
            $table->string('bulan');
            $table->string('tahun');
            $table->string('nopo')->unique();
            $table->string('merk');
            $table->string('jenis');
            $table->string('tahun_pembuatan');
            $table->string('warna');
            $table->string('nomor_rangka')->unique();
            $table->string('nomor_mesin')->unique();
            $table->string('bpkb');
            $table->string('polres');
            $table->string('nomor_surat_keterangan');
            $table->string('tanggal_tahun_lapor');
            $table->string('jenissurat');
            $table->string('jenis_surat');
            $table->string('taggalttd');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_kehilangans');
    }
};
