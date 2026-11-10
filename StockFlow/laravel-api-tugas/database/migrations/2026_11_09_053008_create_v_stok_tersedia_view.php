<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW `v_stok_tersedia` AS 
            SELECT 
                b.id AS barang_id,
                b.kode_sku AS kode_sku,
                b.nama AS nama,
                b.kategori AS kategori,
                b.satuan AS satuan,
                b.stok_minimum AS stok_minimum,
                COALESCE(SUM(bt.jumlah_sisa), 0) AS total_stok_tersedia,
                (CASE WHEN (COALESCE(SUM(bt.jumlah_sisa), 0) <= b.stok_minimum) THEN 'Stok Menipis' ELSE 'Aman' END) AS status_stok 
            FROM barang b 
            LEFT JOIN batch_stok bt ON (bt.barang_id = b.id AND bt.deleted_at IS NULL AND bt.status <> 'Ditangani' AND bt.tanggal_kedaluwarsa >= CURDATE()) 
            WHERE b.deleted_at IS NULL 
            GROUP BY b.id, b.kode_sku, b.nama, b.kategori, b.satuan, b.stok_minimum
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_stok_tersedia`");
    }
};