<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    use HasFactory;

    protected $table = 'mutasi_stok';

    // Matikan timestamps bawaan karena tabel ini menggunakan kolom 'waktu'
    public $timestamps = false;

    protected $fillable = [
        'batch_id',
        'jenis',
        'jumlah',
        'catatan',
        'user_id',
        'waktu',
    ];

    // Relasi ke BatchStok
    public function batchStok()
    {
        return $this->belongsTo(BatchStok::class, 'batch_id');
    }

    // Relasi ke User pelaku mutasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
