<?php

namespace App\Http\Controllers;

use App\Models\Penyerahan;
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

class PenyerahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $filter = $request->only('id_user_juru_pungut', 'sbln', 'semua');
        $karcis  = Karcis::with(['juru_pungut' => function ($query) {
            $query->select('id','name');
        }, 'koordinator' => function($query){
            $query->select('id','name');
        }])->select(
            DB::raw('1 as jns'),
            DB::raw('"Karcis" as route'),
            'id',
            'harga as harga',
            DB::raw('(no_karcis_akhir - no_karcis_awal +1) as jml'),
            'tgl_penyerahan',
            'id_user_koordinator',
            'id_user_juru_pungut',
            DB::raw('concat(no_karcis_awal, " s/d ", no_karcis_akhir) as ket'),
        )->where(function($query) use ($request){
            if(Auth::user()->gid == 5){
                $query->where('id_user_juru_pungut', Auth::id());
            }

            if(isset($request->id_user_juru_pungut)){
                $query->where('id_user_juru_pungut', $request->id_user_juru_pungut);
            }

            if(isset($request->sbln)){
                $query->whereRaw("DATE_FORMAT(tgl_penyerahan, '%Y-%m') = '". $request->sbln."'");
            }else{
                if(!isset($request->semua)){
                    $query->whereRaw("DATE_FORMAT(tgl_penyerahan, '%Y-%m') = '". date('Y-m') ."'");    
                }
            }
        })->where('stts', 1)->orderBy('tgl_penyerahan', 'desc')->get();

        $tagihan = Tagihan::with(['juru_pungut' => function ($query) {
            $query->select('id','name');
        }, 'koordinator' => function($query){
            $query->select('id','name');
        }])->select(
            DB::raw('2 as jns'),
            DB::raw('"SKRD" as route'),
            'id',
            'jml as harga',
            DB::raw('"1" as jml'),
            'tgl_penyerahan',
            'id_user_koordinator',
            'id_user_juru_pungut',
            DB::raw('concat(no_skrd, "<br>", "Tgl. ", date_format(tgl_skrd, "%d-%m-%Y") ) as ket'),
        )->where(function($query)use($request) {
            if(isset($request->snpwrd)){
                $query->where('npwrd', $request->snpwrd);
            }

            if(isset($request->sbln)){
                $thn = date('Y', strtotime($request->sbln));
                $bln = date('m', strtotime($request->sbln));
                $query->where('bln', $bln)->where('thn', $thn);
            }else{
                if(!isset($request->semua)){
                    $query->where('bln', date('m'))->where('thn', date('Y'));
                }
            }
            if(isset($request->id_user_juru_pungut)){
                $query->where('id_user_juru_pungut', $request->id_user_juru_pungut);
            }
        })->where(function($query){
            $query->where('stts', 0)->where('stts_byr', 0);
        })->orderBy('tgl_penyerahan', 'desc')->get();

        $data = $karcis->merge($tagihan)->sortByDesc('tgl_penyerahan')->values();

        return view('admin.penyerahan.index', [
            'data' => $data,
            'filter' => $filter,
            'title' => 'Penyerahan Karcis dan Tagihan',
            'lsJuruPungut' => User::where('gid', 5)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_user_koordinator=null)
    {
        //
        $juruPungut = Wilayah::select('id_user_juru_pungut')->where('id_user_koordinator', $id_user_koordinator)->get();
        return view('admin.penyerahan.formulir', [
            'next' => 'store',
            'title' => 'Tambah Penyerahan Karcis dan Tagihan',
            'id_user_koordinator' => $id_user_koordinator,
            'lsKoordinator' => User::where('gid', 4)->where('aktif',1)->get(),
            'lsJuruPungut' => User::where('gid', 5)->where('aktif',1)->where(function($query) use ($id_user_koordinator){
                if(isset($id_user_koordinator)){
                    $query->join("wilayahs", "wilayahs.id_user_koordinator");
                }
            })->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reqData = $request->only('no_serah_terima', 'deskripsi', 'id_user_koordinator', 'id_user_juru_pungut', 'tgl_penyerahan');
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'no_serah_terima' => 'sometimes|unique:penyerahan,no_serah_terima',
        ],[
            'no_serah_terima.unique' => 'Nomor Serah Terima telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Penyerahan::create($reqData);
        return redirect('penyerahan')->withSuccess('Data Penyerahan Karcis dan Tagihan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penyerahan  $penyerahan
     * @return \Illuminate\Http\Response
     */
    public function show(Penyerahan $penyerahan, Request $request, $route=null, $id=null)
    {
        //
        $filter = $request->only('id_user_juru_pungut', 'sbln', 'semua');
        $_filter = urlencode(serialize($filter));

        $page = $route == null ? 'show' : 'show2';

        return view('admin.penyerahan.'.$page, [
            '_filter' => $_filter,
            'route' => $route,
            'id' => $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penyerahan  $penyerahan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penyerahan $penyerahan, $id)
    {
        //
        $data = $penyerahan->find($id);
        return view('admin.penyerahan.formulir', [
            'next' => 'update',
            'title' => 'Edit Penyerahan Karcis dan Tagihan',
            'lsKoordinator' => User::where('gid', 4)->where('aktif',1)->get(),
            'lsJuruPungut' => User::where('gid', 5)->where('aktif',1)->get(),
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penyerahan  $penyerahan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penyerahan $penyerahan)
    {
        //
        $id = $request->id;
        $reqData = $request->only('no_serah_terima', 'deskripsi', 'id_user_koordinator', 'id_user_juru_pungut', 'tgl_penyerahan');
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'no_serah_terima' => 'sometimes|unique:penyerahan,no_serah_terima,'.$id,
        ],[
            'no_serah_terima.unique' => 'Nomor Serah Terima telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $penyerahan->find($id)->update($reqData);
        return redirect('penyerahan')->withSuccess('Data Penyerahan Karcis dan Tagihan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penyerahan  $penyerahan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penyerahan $penyerahan, $id)
    {
        //
        $penyerahan->find($id)->delete();
        return redirect('penyerahan')->withSuccess('Data Penyerahan Karcis dan Tagihan berhasil dihapus');
    }

    public function create_item($jns, $id_registrasi_karcis){
        $njns = $jns == 1 ? 'Karcis' : 'Tagihan';
        return view('admin.penyerahan.formulir2', [
            'next' => 'store',
            'title' => 'Tambah Penyerahan '.$njns,
            'lsKoordinator' => User::where('gid', 4)->where('aktif',1)->get(),
            'lsJuruPungut' => User::where('gid', 5)->where('aktif',1)->get(),
        ]);
    }
}
