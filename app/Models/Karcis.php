<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Str;

class Karcis extends Model
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

    public function wilayah_kerja()
    {
        return $this->hasOne(Wilayah::class, 'id', 'id_wilayah');
    }

    public function registrasi_karcis()
    {
        return $this->hasOne(RegistrasiKarcis::class, 'id', 'id_registrasi_karcis');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_karcis', 'id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_karcis', 'id');
    }

    protected function getListKarcisAttribute()
    {
        $terpakai = $this->pembayaran->pluck('no_karcis')->toArray();
        $list = [];
        for ($i=$this->no_karcis_awal; $i <= $this->no_karcis_akhir ; $i++) { 
            if(!in_array($i, $terpakai)){
                $list[] = $i;    
            }
        }

        return implode(', ', $list);
    }

    protected function getJmlKarcisAttribute()
    {
        return $this->no_karcis_akhir - $this->no_karcis_awal +1;
    }

    protected function getHargaRpAttribute()
    {
        return Str::rupiah($this->harga);
    }

    protected $appends = [
        'list_karcis',
        'jml_karcis',
        'harga_rp',
    ];

    protected $fillable = [
        'tahun',
        'tgl_penyerahan',
        'harga',
        'id_registrasi_karcis',
        'no_karcis_awal',
        'no_karcis_akhir',
        'id_wilayah',
        'id_user_koordinator',
        'id_user_juru_pungut',
        'stts',
    ];

    protected $casts = [
        'tgl_penyerahan' => 'date',
    ];

    protected static function booted()
    {
        /*static::created(function (Karcis $karcis) {
            $karcis->no_karcis_pembayaran = $karcis->no_karcis_awal;
            $karcis->save();
        });*/
    }
}
