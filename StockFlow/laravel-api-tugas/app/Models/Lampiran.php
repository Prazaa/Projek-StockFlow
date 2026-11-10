<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasFactory;

    protected $table = 'lampiran';

    protected $fillable = [
        'batch_id',
        'jenis',
        'path',
    ];

    public function batchStok()
    {
        return $this->belongsTo(BatchStok::class, 'batch_id');
    }
}
