<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Str;

class ObjekRetribusi extends Model
{
    use HasFactory, SoftDeletes;

    public function jenis_retribusi()
    {
        return $this->hasOne(JenisRetribusi::class, 'id', 'id_jenis_retribusi');
    }

    public function wajib_retribusi()
    {
        return $this->hasMany(WajibRetribusi::class, 'id_objek_retribusi', 'id');
    }

    protected $appends = ['tarif_rp'];

    public function getTarifRpAttribute(){
        return Str::currency($this->tarif);
    }

    protected $fillable = [
        'nama',
        'id_jenis_retribusi',
        'deskripsi',
        'tarif',
        'aktif',
        'insidentil',
    ];
}
