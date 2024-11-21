<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktifasiWr extends Model
{
    use HasFactory;

    protected $dates = ['tgl_sk', 'tmt_sk'];

    protected $fillable = [
        'npwrd',
        'aktif',
        'no_sk',
        'tgl_sk',
        'tmt_sk',
        'catatan',
        'id_user',
    ];
}
