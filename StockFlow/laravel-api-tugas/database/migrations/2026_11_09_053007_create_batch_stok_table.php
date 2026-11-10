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
        Schema::create('batch_stok', function (Blueprint $table) {
            $table->comment('Batch stok per barang dengan tanggal kedaluwarsa masing-masing');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('barang_id');
            $table->string('nomor_batch', 100);
            $table->unsignedInteger('jumlah_awal')->comment('Jumlah saat batch dibuat');
            $table->unsignedInteger('jumlah_sisa')->comment('Stok tersedia batch; tidak boleh negatif (AB-06)');
            $table->date('tanggal_kedaluwarsa')->index('idx_batch_kedaluwarsa');
            $table->enum('status', ['Aman', 'Mendekati Kedaluwarsa', 'Kedaluwarsa', 'Ditangani'])->default('Aman')->index('idx_batch_status')->comment('FR-20 / diagram status Bagian 4.3');
            $table->enum('status_penanganan', ['Dimusnahkan', 'Dikembalikan ke Pemasok', 'Lainnya'])->nullable()->comment('FR-26; wajib terisi saat status = Ditangani');
            $table->text('catatan_penanganan')->nullable()->comment('Wajib bila status_penanganan = Lainnya');
            $table->unsignedBigInteger('created_by')->nullable()->index('fk_batch_created_by')->comment('Pembuat data — basis otorisasi hapus staf (AB-08)');
            $table->unsignedBigInteger('deleted_by')->nullable()->index('fk_batch_deleted_by');
            $table->softDeletes();
            $table->timestamp('deleted_key', 6)->nullable()->storedAs('ifnull(`deleted_at`,_utf8mb4\'1999-12-31 23:59:59.000000\')');
            $table->timestamps();

            $table->index(['barang_id', 'tanggal_kedaluwarsa'], 'idx_batch_barang_exp');
            $table->unique(['barang_id', 'nomor_batch', 'deleted_key'], 'uq_batch_nomor_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_stok');
    }
};
