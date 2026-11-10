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
        Schema::create('barang', function (Blueprint $table) {
            $table->comment('Master barang; total stok = SUM(jumlah_sisa) batch aktif (AB-01)');
            $table->bigIncrements('id');
            $table->string('kode_sku', 50)->comment('Unik untuk barang aktif (AB-07)');
            $table->string('nama', 150)->index('idx_barang_nama');
            $table->enum('kategori', ['Makanan', 'Minuman', 'Obat', 'Kebutuhan Rumah', 'Lainnya'])->index('idx_barang_kategori');
            $table->string('satuan', 30)->comment('mis. pcs, box, pack');
            $table->unsignedInteger('stok_minimum')->default(0)->comment('Ambang Stok Menipis (AB-04)');
            $table->unsignedBigInteger('created_by')->nullable()->index('fk_barang_created_by')->comment('Pembuat data — basis otorisasi hapus staf (AB-08)');
            $table->unsignedBigInteger('deleted_by')->nullable()->index('fk_barang_deleted_by');
            $table->softDeletes();
            $table->timestamp('deleted_key', 6)->nullable()->storedAs('ifnull(`deleted_at`,_utf8mb4\'1999-12-31 23:59:59.000000\')');
            $table->timestamps();

            $table->unique(['kode_sku', 'deleted_key'], 'uq_barang_sku_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
