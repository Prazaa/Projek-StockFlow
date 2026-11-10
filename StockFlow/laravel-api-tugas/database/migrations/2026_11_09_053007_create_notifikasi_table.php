<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->comment('Catatan notifikasi yang diambil Android via API (FR-24)');
            $table->bigIncrements('id');
            $table->enum('jenis', ['Mendekati Kedaluwarsa', 'Kedaluwarsa', 'Stok Menipis']);
            $table->unsignedBigInteger('barang_id')->nullable()->index('fk_notifikasi_barang')->comment('Selalu terisi; WAJIB untuk jenis Stok Menipis');
            $table->unsignedBigInteger('batch_id')->nullable()->index('fk_notifikasi_batch')->comment('Terisi untuk kedua jenis kedaluwarsa');
            $table->string('pesan');
            $table->date('tanggal')->comment('Hari pembuatan (WIB) — kunci anti-duplikat harian');
            $table->dateTime('ditampilkan_pada')->nullable()->index('idx_notifikasi_belum_tampil')->comment('NULL = belum tampil di ponsel (FR-24)');
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->unique(['jenis', 'barang_id', 'batch_id', 'tanggal'], 'uq_notifikasi_harian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
