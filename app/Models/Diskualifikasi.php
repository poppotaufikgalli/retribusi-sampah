<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskualifikasi extends Model
{
    use HasFactory;

    public function juri()
    {
        return $this->hasOne(User::class, 'id', 'uid');
    }

    protected $fillable = [
        'id_pendaftar',
        'alasan',
        'ket',
        'uid',
        'doc',
    ];
}
