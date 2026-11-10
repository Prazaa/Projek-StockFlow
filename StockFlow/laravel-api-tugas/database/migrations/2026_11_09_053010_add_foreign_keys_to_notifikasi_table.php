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
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->foreign(['barang_id'], 'fk_notifikasi_barang')->references(['id'])->on('barang')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['batch_id'], 'fk_notifikasi_batch')->references(['id'])->on('batch_stok')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign('fk_notifikasi_barang');
            $table->dropForeign('fk_notifikasi_batch');
        });
    }
};
