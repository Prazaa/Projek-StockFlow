<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatAktivitas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_aktivitas';

    // Matikan timestamps bawaan (pakai kolom 'waktu')
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'aksi',
        'entitas',
        'entitas_id',
        'keterangan',
        'waktu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
