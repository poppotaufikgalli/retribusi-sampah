<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Str;

class Konfig extends Model
{
    use HasFactory;

    protected $appends = ['target_rp', 'target_p_rp'];

    public function getTargetRpAttribute(){
        return Str::rupiah($this->target);
    }

    public function getTargetPRpAttribute(){
        return Str::rupiah($this->target_p);
    }

    protected $fillable = [
        'tahun',
        'target',
        'target_p',
        'aktif',
    ];

    protected static function booted()
    {
        static::created(function (Konfig $konfig) {
            if($konfig->aktif == 1){
                Konfig::whereNot('id', $konfig->id)->update(['aktif' => 0]);
            }
        });

        static::updated(function (Konfig $konfig) {
            if($konfig->aktif == 1){
                Konfig::whereNot('id', $konfig->id)->update(['aktif' => 0]);
            }
        });
    }
}
