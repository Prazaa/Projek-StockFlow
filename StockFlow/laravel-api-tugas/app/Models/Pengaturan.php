<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';


    protected $primaryKey = 'kunci';
    public $incrementing = false;
    protected $keyType = 'string';

    // Hanya menggunakan updated_at tanpa created_at
    const CREATED_AT = null;

    protected $fillable = [
        'kunci',
        'nilai',
    ];
}
