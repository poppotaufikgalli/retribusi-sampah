<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KecamatanKelurahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'ref',
        'jns',
    ];
}
