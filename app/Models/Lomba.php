<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lomba extends Model
{
    use HasFactory;

    public function juri_kategori()
    {
        return $this->hasMany(JuriKategori::class, 'id_lomba', 'id')
            ->join('users', 'users.id', '=', 'juri_kategoris.user_id');
    }

    public function kategori_peserta()
    {
        return $this->hasMany(KatPeserta::class, 'id_lomba', 'id');
    }

    protected $fillable = [
        'judul',
        'tahun',
        'ket',
        'aktif',
        'jml_pos',
    ];
}
