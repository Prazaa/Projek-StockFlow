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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->comment('Riwayat mutasi stok per batch (audit, tidak diubah lewat aplikasi)');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_id')->index('idx_mutasi_batch');
            $table->enum('jenis', ['masuk', 'keluar', 'penyesuaian', 'penanganan'])->index('idx_mutasi_jenis');
            $table->integer('jumlah')->comment('Positif untuk masuk/keluar/penanganan; penyesuaian = selisih, boleh negatif (FR-13)');
            $table->string('catatan')->nullable()->comment('Alasan penyesuaian / penanganan');
            $table->unsignedBigInteger('user_id')->index('fk_mutasi_user')->comment('Pengguna pelaku (AB-10)');
            $table->dateTime('waktu')->useCurrent()->index('idx_mutasi_waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
