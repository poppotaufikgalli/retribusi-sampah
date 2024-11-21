<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Str;

class WajibRetribusi extends Model
{
    use HasFactory, SoftDeletes;

    public function pemilik()
    {
        return $this->hasOne(Pemilik::class, 'id', 'id_pemilik');
    }

    public function objek_retribusi()
    {
        return $this->hasOne(ObjekRetribusi::class, 'id', 'id_objek_retribusi');
    }

    public function wilayah_kerja()
    {
        return $this->hasOne(Wilayah::class, 'id', 'id_wilayah');
    }

    public function karcis()
    {
        //return $this->hasManyThrough(ObjekRetribusi::class, Karcis::class, 'a', 'b', 'c', 'd')->where('karcis.stts', 1);
        return $this->hasMany(Karcis::class, 'id_wilayah', 'id_wilayah')->where('karcis.stts', 1);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'npwrd', 'npwrd');
    }

    public function kecamatan()
    {
        return $this->hasOne(KecamatanKelurahan::class, 'id', 'id_kecamatan');
    }

    public function kelurahan()
    {
        return $this->hasOne(KecamatanKelurahan::class, 'id', 'id_kelurahan');
    }    

    protected $primaryKey = 'npwrd';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'npwrd',
        'nama',
        'id_pemilik',
        'id_kecamatan',
        'id_kelurahan',
        'alamat',
        'lat',
        'lng',
        'id_objek_retribusi',
        'id_wilayah',
        'aktif',
        'foto',
    ];
}
