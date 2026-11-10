<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW `v_perlu_ditangani` AS 
            SELECT 
                bt.id AS batch_id,
                bt.barang_id AS barang_id,
                b.kode_sku AS kode_sku,
                b.nama AS nama_barang,
                bt.nomor_batch AS nomor_batch,
                bt.jumlah_sisa AS jumlah_sisa,
                b.satuan AS satuan,
                bt.tanggal_kedaluwarsa AS tanggal_kedaluwarsa,
                (TO_DAYS(bt.tanggal_kedaluwarsa) - TO_DAYS(CURDATE())) AS sisa_hari 
            FROM batch_stok bt 
            JOIN barang b ON b.id = bt.barang_id AND b.deleted_at IS NULL 
            WHERE bt.deleted_at IS NULL AND bt.status = 'Kedaluwarsa'
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_perlu_ditangani`");
    }
};