<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'kode_sku',
        'nama',
        'kategori',
        'satuan',
        'stok_minimum',
        'created_by',
        'deleted_by',
    ];

    // Relasi ke BatchStok (One to Many)
    public function batchStok()
    {
        return $this->hasMany(BatchStok::class, 'barang_id');
    }

    // Relasi ke Pembuat Data
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
