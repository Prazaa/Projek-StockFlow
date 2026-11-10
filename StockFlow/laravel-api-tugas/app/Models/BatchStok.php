<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchStok extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batch_stok';

    protected $fillable = [
        'barang_id',
        'nomor_batch',
        'jumlah_awal',
        'jumlah_sisa',
        'tanggal_kedaluwarsa',
        'status',
        'status_penanganan',
        'catatan_penanganan',
        'created_by',
        'deleted_by',
    ];

    // Relasi ke Barang (Belongs To)
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Relasi ke MutasiStok (One to Many)
    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'batch_id');
    }

    // Relasi ke Lampiran Foto (One to Many)
    public function lampiran()
    {
        return $this->hasMany(Lampiran::class, 'batch_id');
    }
}
