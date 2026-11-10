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
        Schema::table('lampiran', function (Blueprint $table) {
            $table->foreign(['batch_id'], 'fk_lampiran_batch')->references(['id'])->on('batch_stok')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lampiran', function (Blueprint $table) {
            $table->dropForeign('fk_lampiran_batch');
        });
    }
};
