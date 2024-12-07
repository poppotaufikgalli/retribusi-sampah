<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    public function karcis()
    {
        return $this->hasOne(Karcis::class, 'id', 'id_karcis');
    }

    protected function getListKarcisAttribute()
    {
        //$terpakai = $this->pembayaran->pluck('no_karcis')->toArray();
        $list = [];
        for ($i=$this->no_karcis_pengembalian+1; $i <= $this->no_karcis_akhir ; $i++) { 
            //if(!in_array($i, $terpakai)){
                $list[] = $i;    
            //}
        }

        return implode(', ', $list);
    }

    protected $appends = [
        'list_karcis',
    ];

    protected $fillable = [
        'id_karcis',
        'no_karcis_awal',
        'no_karcis_akhir',
        'no_karcis_pengembalian',
        'catatan',
        'tgl_pengembalian',
    ];

    protected $dates = ['tgl_pengembalian'];
}
