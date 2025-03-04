<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Str;

class Pembayaran extends Model
{
    use HasFactory;

    public function wajib_retribusi()
    {
        return $this->hasOne(WajibRetribusi::class, 'id', 'id_wr');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function jenis_pembayaran()
    {
        return $this->hasOne(JenisPembayaran::class, 'id', 'jns');
    }

    public function tipe_pembayaran()
    {
        return $this->hasOne(TipePembayaran::class, 'id', 'tipe');
    }

    protected $dates = ['tgl_bayar'];

    protected $appends = ['jml_rp', 'denda_rp', 'total_rp'];

    public function getJmlRpAttribute(){
        return Str::currency($this->jml);
    }

    public function getDendaRpAttribute(){
        return Str::currency($this->denda);
    }

    public function getTotalRpAttribute(){
        return Str::currency($this->total);
    }

    protected $fillable = [
        'jns',
        'tipe',
        'id_wr',
        'npwrd',
        'no_karcis',
        'id_karcis',
        'thn',
        'bln',
        'tgl',
        'jml',
        'denda',
        'total',
        'pembayaran_ke',
        'tgl_bayar',
        'id_user',
        'verif',
        'verif_id',
        'filename',
        'gbr',
    ];
}
