<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

 
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'peran',
        'aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'password' => 'hashed',
    ];

    // Relasi ke Barang yang dibuat user
    public function barangDibuat()
    {
        return $this->hasMany(Barang::class, 'created_by');
    }

    // Relasi ke MutasiStok
    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'user_id');
    }
}
