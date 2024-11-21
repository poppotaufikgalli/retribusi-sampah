<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatPeserta extends Model
{
    use HasFactory;

    public function lomba()
    {
        return $this->hasOne(Lomba::class, 'id', 'id_lomba');
    }

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'id_peserta', 'id')->orderBy('no_peserta');
    }

    protected $fillable = [
        'judul',
        'id_lomba',
        'ket',
        'ref_kecepatan',
        'no_peserta_mulai',
        'no_peserta_prefix',
        'aktif',
    ];
}
