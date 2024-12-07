<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Karcis;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Auth;
use DB;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->only('id_user_juru_pungut', 'sbln', 'semua');
        $filter['stts'] = isset($request->stts) ? $request->stts : 1;
        $data  = Karcis::where(function($query) use ($request){
            if(Auth::user()->gid == 5){
                $query->where('id_user_juru_pungut', Auth::id());
            }

            if(isset($request->id_user_juru_pungut)){
                $query->where('id_user_juru_pungut', $request->id_user_juru_pungut);
            }

            if(isset($request->stts)){
                if($request->stts != -1){
                    $query->where('stts', $request->stts);
                }
            }else{
                $query->where('stts', 1);
                $filter['stts'] = 1;
            }

            if(isset($request->sbln)){
                $query->whereRaw("DATE_FORMAT(tgl_penyerahan, '%Y-%m') = '". $request->sbln."'");
            }else{
                if(!isset($request->semua)){
                    $query->whereRaw("DATE_FORMAT(tgl_penyerahan, '%Y-%m') = '". date('Y-m') ."'");    
                }
            }
        })->orderBy('tgl_penyerahan', 'desc')->get();

        return view('admin.pengembalian.index', [
            'data' => $data,
            'filter' => $filter,
            'title' => 'Pengembalian Karcis',
            'lsJuruPungut' => User::where('gid', 5)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $reqData = $request->only('id_karcis', 'tgl_pengembalian', 'catatan', 'no_karcis_awal', 'no_karcis_akhir', 'no_karcis_pengembalian');
        //dd($reqData);
        $digunakan = $request->digunakan;
        
        $validator = Validator::make($reqData, [
            'id_karcis' => 'required',
            'tgl_pengembalian' => 'required|date',
            'no_karcis_pengembalian' => 'required|numeric|between:'.$digunakan.','.$reqData['no_karcis_akhir'],
        ],[
            'id_karcis.required' => 'Data Pengembalian tidak valid',
            'tgl_pengembalian.required' => 'Tanggal Pengembalian tidak boleh kosong',
            'tgl_pengembalian.date' => 'Tanggal Pengembalian tidak valid',
            'no_karcis_pengembalian.required' => 'Nomor Karcis Akhir tidak boleh kosong',
            'no_karcis_pengembalian.numeric' => 'Nomor Karcis Akhir tidak valid',
            'no_karcis_pengembalian.between' => 'Nomor Karcis Akhir harus diantara '.$digunakan.' s.d '.$reqData['no_karcis_akhir'],
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Pengembalian::create($reqData);
        Karcis::find($request->id_karcis)->update([
            'no_karcis_akhir' => $request->no_karcis_pengembalian,
            'stts' => 0
        ]);
        return redirect('pengembalian')->withSuccess('Data Karcis berhasil dikembalikan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function show(Pengembalian $pengembalian, $id)
    {
        //
        $data = $pengembalian->find($id);

        return view('admin.pengembalian.show', [
            'next'  => 'store',
            'title' => 'Pengembalian Karcis',
            'data'  => $data,
            'route' => '',
            'id'    => $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengembalian $pengembalian, $id)
    {
        //
        $data = Karcis::find($id);

        return view('admin.pengembalian.formulir', [
            'next'  => 'store',
            'title' => 'Pengembalian Karcis',
            'data'  => $data,
            'route' => '',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengembalian $pengembalian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengembalian  $pengembalian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengembalian $pengembalian)
    {
        //
    }
}
