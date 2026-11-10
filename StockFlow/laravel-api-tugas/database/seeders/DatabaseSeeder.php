<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Account Pemilik / Admin
        User::create([
            'nama' => 'Pemilik StockFlow',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'peran' => 'pemilik',
            'aktif' => 1,
        ]);

        // Account Staf Gudang
        User::create([
            'nama' => 'Staf Gudang',
            'username' => 'staf',
            'password' => Hash::make('password123'),
            'peran' => 'staf',
            'aktif' => 1,
        ]);
    }
}