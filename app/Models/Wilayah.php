<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Wilayah extends Model
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

    public function wajib_retribusi()
    {
        return $this->hasMany(WajibRetribusi::class, 'id_wilayah', 'id');
    }

    /*protected function getLsJuruPungutAttribute()
    {
        if($this->id_user_juru_pungut != ""){
            $result = User::whereIn('id', ($this->id_user_juru_pungut))->get();
            //return $result;
            return $result;
        }

        return null;
    }

    protected function getLsJuruPungutNameAttribute()
    {
        if($this->id_user_juru_pungut != ""){
            $result = User::whereIn('id', ($this->id_user_juru_pungut))->pluck('name');
            //return $result;
            return implode(', ', $result->toArray());
        }

        return null;
    }

    protected $appends = [
        'ls_juru_pungut',
        'ls_juru_pungut_name'
    ];

    protected $casts = [
       'id_user_juru_pungut' => 'array',
    ];*/

    protected $fillable = [
        'nama',
        'deskripsi',
        'id_user_koordinator',
        'id_user_juru_pungut',
        'aktif',
    ];
}
