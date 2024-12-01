<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKunjungan extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user_juru_pungut');
    }

    public function wajib_retribusi()
    {
        return $this->hasOne(WajibRetribusi::class, 'npwrd', 'npwrd');
    }

    public function jenis_keterangan()
    {
        return $this->hasOne(JenisKeteranganKunjungan::class, 'id', 'jns');
    }

    protected $dates = ['tgl_kunjungan'];

    protected $fillable = [
        'npwrd',
        'jns',
        'keterangan',
        'tgl_kunjungan',
        'bln',
        'thn',
        'no_tagihan',
        'id_user_juru_pungut',
        'gbr',
    ];
}
