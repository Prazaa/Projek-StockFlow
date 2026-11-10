<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    // Kolom update tidak digunakan di tabel notifikasi
    const UPDATED_AT = null;

    protected $fillable = [
        'jenis',
        'barang_id',
        'batch_id',
        'pesan',
        'tanggal',
        'ditampilkan_pada',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function batchStok()
    {
        return $this->belongsTo(BatchStok::class, 'batch_id');
    }
}
