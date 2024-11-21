<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuriKategori extends Model
{
    use HasFactory;

    public function juri()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected $fillable = [
        'user_id',
        'id_lomba',
    ];
}
