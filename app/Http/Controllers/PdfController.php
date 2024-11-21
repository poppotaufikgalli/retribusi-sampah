<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiKarcis;
use App\Models\Karcis;
use Illuminate\Http\Request;

use Pdf;

class PdfController extends Controller
{
    //
    public function registrasi_karcis($id)
    {
        //
        $registrasi_karcis = RegistrasiKarcis::find($id);
        $data = [
            'data' => $registrasi_karcis,
            'karcis' => Karcis::where('id_registrasi_karcis', $registrasi_karcis->id)->get(),
            'tagihan' => Karcis::where('id_registrasi_karcis', $registrasi_karcis->id)->get(),
            'title' => 'Cetak Tanda Terima'
        ];
        
        //dd($data);
        //Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = Pdf::loadView('pdf.registrasi_karcis', $data);
        return $pdf->stream('registrasi_karcis.pdf');
        //return view('pdf.registrasi_karcis', $data);
    }

    public function piutang()
    {
        //
        //$registrasi_karcis = RegistrasiKarcis::find($id);
        $data = [
            //'data' => $registrasi_karcis,
            'data' => null,
            //'karcis' => Karcis::where('id_registrasi_karcis', $registrasi_karcis->id)->get(),
            //'tagihan' => Karcis::where('id_registrasi_karcis', $registrasi_karcis->id)->get(),
            'title' => 'Cetak Piutang'
        ];
        
        //dd($data);
        //Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = Pdf::loadView('pdf.piutang', $data);
        return $pdf->stream('piutang.pdf');
        //return view('pdf.registrasi_karcis', $data);
    }
}
