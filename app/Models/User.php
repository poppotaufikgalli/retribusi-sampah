<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function juri_kategori()
    {
        return $this->hasMany(JuriKategori::class, 'user_id', 'id')
            ->join('lombas', 'lombas.id', '=', 'juri_kategoris.id_lomba');
    }

    public function wilayah_kerja_koordinator(){
        return $this->hasMany(Wilayah::class, 'id_user_koordinator', 'id');
    }

    public function wilayah_kerja_juru_pungut(){
        return $this->hasMany(Wilayah::class, 'id_user_juru_pungut', 'id');
    }

    public function karcis(){
        return $this->hasMany(Karcis::class, 'id_user_juru_pungut', 'id')->where('stts',1);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'gid',
        'foto',
        'aktif',
        'device_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
