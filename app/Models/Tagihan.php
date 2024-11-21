<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Str;

class Tagihan extends Model
{
    use HasFactory;

    public function koordinator()
    {
        return $this->hasOne(User::class, 'id', 'id_user_koordinator');
    }

    public function juru_pungut()
    {
        return $this->hasOne(User::class, 'id', 'id_user_juru_pungut');
    }

    public function registrasi_karcis()
    {
        return $this->hasOne(RegistrasiKarcis::class, 'id', 'id_registrasi_karcis');
    }

    public function wajib_retribusi()
    {
        return $this->belongsTo(WajibRetribusi::class, 'npwrd');
    }

    protected $dates = ['tgl_penyerahan','tgl_skrd'];

    protected $appends = ['jml_rp'];

    public function getJmlRpAttribute(){
        return Str::currency($this->jml);
    }

    protected $fillable = [
        'tgl_penyerahan',
        'npwrd',
        'no_skrd',
        'tgl_skrd',
        'bln',
        'thn',
        'jml',
        'id_user',
        'stts',
        'stts_byr',
        'filename_skrd',
        'id_registrasi_karcis',
        'id_user_koordinator',
        'id_user_juru_pungut',
    ];
}
