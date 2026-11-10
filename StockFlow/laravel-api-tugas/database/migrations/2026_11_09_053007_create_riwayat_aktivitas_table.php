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
        Schema::create('riwayat_aktivitas', function (Blueprint $table) {
            $table->comment('Riwayat aktivitas pengguna (immutable, NFR-08)');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('idx_riwayat_user')->comment('NULL bila akun pernah terhapus; keterangan tetap utuh');
            $table->enum('aksi', ['login', 'logout', 'tambah', 'ubah', 'hapus', 'hapus_massal', 'impor', 'pengeluaran_stok', 'penanganan_kedaluwarsa', 'ubah_pengaturan'])->index('idx_riwayat_aksi');
            $table->string('entitas', 50)->nullable()->comment('barang | batch_stok | users | pengaturan');
            $table->unsignedBigInteger('entitas_id')->nullable();
            $table->text('keterangan')->nullable()->comment('Contoh hapus massal: "Menghapus 3 batch" (5.9 C)');
            $table->dateTime('waktu')->useCurrent()->index('idx_riwayat_waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_aktivitas');
    }
};
