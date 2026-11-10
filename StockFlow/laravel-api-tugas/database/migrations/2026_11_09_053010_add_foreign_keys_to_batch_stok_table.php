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
        Schema::table('batch_stok', function (Blueprint $table) {
            $table->foreign(['barang_id'], 'fk_batch_barang')->references(['id'])->on('barang')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['created_by'], 'fk_batch_created_by')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['deleted_by'], 'fk_batch_deleted_by')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_stok', function (Blueprint $table) {
            $table->dropForeign('fk_batch_barang');
            $table->dropForeign('fk_batch_created_by');
            $table->dropForeign('fk_batch_deleted_by');
        });
    }
};
