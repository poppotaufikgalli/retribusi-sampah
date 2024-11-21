<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Lomba;
use App\Models\Konfig;
use App\Models\KatPeserta;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $jnsJuri = [
        1 => "Administrator",
        2 => "Kepala Dinas",
        3 => "Admin Bidang",
        4 => "Koordinator Lapangan",
        5 => "Juru Pungut",
        6 => "Bendahara",
    ];

    /*public function GetKategoriLomba(){
        return Lomba::where('aktif', 1)->get();
    }

    public function GetKategoriPeserta($id=null){
        if($id==null){
            return KatPeserta::where('aktif', 1)->get();
        }else{
            return KatPeserta::where('aktif', 1)->where('id', $id)->get();
        }
    }*/

    public function GetKonfigurasi(){
        return Konfig::where('aktif', 1)->first();
    }
}
