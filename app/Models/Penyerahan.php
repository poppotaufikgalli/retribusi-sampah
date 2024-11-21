<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyerahan extends Model
{
    use HasFactory;

    protected $casts = [
        'tgl_penyerahan' => 'date',
    ];


    public function koordinator()
    {
        return $this->hasOne(User::class, 'id', 'id_user_koordinator');
    }

    public function juru_pungut()
    {
        return $this->hasOne(User::class, 'id', 'id_user_juru_pungut');
    }

    public function karcis()
    {
        return $this->hasMany(Karcis::class, 'id_registrasi_karcis', 'id');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_registrasi_karcis', 'id');
    }

    /*public function list_karcis()
    {
        $list = [];
        for ($i=$this->no_karcis_awal; $i < $this->no_karcis_akhir ; $i++) { 
            $list[] = $i;
        }
        return $list;
    }

    protected function getListKarcisAttribute()
    {
        $list = [];
        for ($i=$this->no_karcis_awal; $i <= $this->no_karcis_akhir ; $i++) { 
            $list[] = $i;
        }

        return implode(', ', $list);
    }

    protected $appends = [
        'ls_karcis',
    ];*/

    protected $fillable = [
        'no_serah_terima',
        'deskripsi',
        'id_user_koordinator',
        'id_user_juru_pungut',
        'tgl_penyerahan',
    ];
}
