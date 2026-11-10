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
        Schema::create('lampiran', function (Blueprint $table) {
            $table->comment('Lampiran gambar bukti batch dan bukti penanganan kedaluwarsa');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_id')->index('idx_lampiran_batch');
            $table->enum('jenis', ['bukti_batch', 'bukti_penanganan']);
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};
