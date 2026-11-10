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
        Schema::create('users', function (Blueprint $table) {
            $table->comment('Akun Pemilik dan Staf gudang (Bagian 2 PRD)');
            $table->bigIncrements('id');
            $table->string('nama', 100);
            $table->string('username', 50)->unique('uq_users_username');
            $table->string('password')->comment('Hash bcrypt, tidak pernah plaintext');
            $table->enum('peran', ['pemilik', 'staf'])->default('staf');
            $table->boolean('aktif')->default(true)->comment('0 = nonaktif: login ditolak, token dicabut (FR-05)');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
