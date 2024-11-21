<?php

namespace App\Http\Controllers;

use App\Models\Karcis;
use App\Models\RegistrasiKarcis;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Str;
use Auth;

class KarcisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = [
            'title' => 'Karcis',
            'filter' => $request->only('id_user_juru_pungut', 'sbln', 'semua'),
            'lsJuruPungut' => User::where('gid', 5)->get(),
            'data'  => Karcis::where(function($query) use ($request){
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
            })->orderBy('created_at', 'desc')->get(),
        ];
        
        confirmDelete('Hapus Data Karcis', "Apakah anda yakin untuk menghapus?");
        return view('admin.karcis.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = [
            'next' => 'store',
            'title' => 'Tambah Karcis',
            'wilayah_kerja' => Wilayah::all(),
            'lsKoordinator' => User::where('gid', 4)->get(),
            'lsJuruPungut' => User::where('gid', 5)->get(),
            //'lsSerahTerima' => RegistrasiKarcis::all(),
        ];

        return view('admin.karcis.formulir', $data);
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
        $reqData = $request->only('id_registrasi_karcis', 'tgl_penyerahan', 'tahun', 'harga', 'no_karcis_awal', 'no_karcis_akhir', 'id_wilayah', 'id_user_koordinator', 'id_user_juru_pungut');
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'tgl_penyerahan' => 'required|date',
            'tahun' => 'required|numeric',
            //'harga' => 'required|numeric',
            'harga' => [
                'required',
                'numeric',
                function (string $attribute, mixed $value, Closure $fail) use($reqData) {
                    $result = Karcis::where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->where('id_user_koordinator', $reqData['id_user_koordinator'])->where('stts',1)->count();
                    if ($result > 0) {
                        $fail('Nomor Karcis dengan Harga '.Str::rupiah($reqData['harga']).' dan Tahun '.$reqData['tahun'].' masih tersedia');
                    }
                },
            ],
            'id_wilayah' => 'required',
            'no_karcis_awal' => [
                'required',
                'numeric',
                //'unique:registrasi_karcis,no_karcis_awal,tahun,harga',
                function (string $attribute, mixed $value, Closure $fail) use($reqData) {
                    $result = Karcis::where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->whereRaw($reqData['no_karcis_awal'].' between no_karcis_awal and no_karcis_akhir')->count();
                    if ($result > 0) {
                        $fail("Nomor Karcis Awal telah terdaftar");
                    }
                },
                function (string $attribute, mixed $value, Closure $fail) use($reqData) {
                    $result = Karcis::where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->whereRaw('no_karcis_awal between '.$reqData['no_karcis_awal'].' and '.$reqData['no_karcis_akhir'])->count();
                    if ($result > 0) {
                        $fail("Nomor Karcis Awal telah terdaftar.");
                    }
                },
            ],
            'no_karcis_akhir' => [
                'required',
                'numeric',
                //'unique:registrasi_karcis,no_karcis_akhir,tahun,harga',
                'gte:no_karcis_awal',
                function (string $attribute, mixed $value, Closure $fail) use($reqData) {
                    $result = Karcis::where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->whereRaw($reqData['no_karcis_akhir'].' between no_karcis_awal and no_karcis_akhir')->count();
                    if ($result > 0) {
                        $fail("Nomor Karcis Akhir telah terdaftar");
                    }
                },
                function (string $attribute, mixed $value, Closure $fail) use($reqData) {
                    $result = Karcis::where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->whereRaw('no_karcis_akhir between '.$reqData['no_karcis_awal'].' and '.$reqData['no_karcis_akhir'])->count();
                    if ($result > 0) {
                        $fail("Nomor Karcis Akhir telah terdaftar.");
                    }
                },
            ],
        ],[
            'tgl_penyerahan.required' => 'Tanggal Penyerahan tidak boleh kosong',
            'tgl_penyerahan.date' => 'Tanggal Penyerahan tidak valid',
            'tahun.required' => 'Tahun tidak boleh kosong',
            'tahun.numeric' => 'Tahun adalah angka',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.numeric' => 'Harga adalah angka',
            'id_wilayah.required' => 'Wilayah Kerja tidak boleh kosong',
            'no_karcis_awal.required' => 'Nomor Karcis Awal tidak boleh kosong',
            'no_karcis_awal.numeric' => 'Nomor Karcis Awal adalah angka',
            'no_karcis_awal.unique' => 'Nomor Karcis Awal tidak valid/telah terdaftar',
            'no_karcis_akhir.required' => 'Nomor Karcis Akhir tidak boleh kosong',
            'no_karcis_akhir.numeric' => 'Nomor Karcis Akhir adalah angka',
            'no_karcis_akhir.unique' => 'Nomor Karcis Akhir tidak valid/telah terdaftar',
            'no_karcis_akhir.gte' => 'Nomor Karcis Akhir harus lebih besar dari Nomor Karcis Awal',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Karcis::create($reqData);

        return redirect('karcis')->withSuccess('Data Karcis berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Karcis  $karcis
     * @return \Illuminate\Http\Response
     */
    public function show(Karcis $karcis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Karcis  $karcis
     * @return \Illuminate\Http\Response
     */
    public function edit(Karcis $karcis, $id)
    {
        //
        return view('admin.karcis.formulir', [
            'next' => 'update',
            'title' => 'Edit Karcis',
            'data' => $karcis->find($id),
            //'lsSerahTerima' => RegistrasiKarcis::all(),
            'wilayah_kerja' => Wilayah::all(),
            'lsKoordinator' => User::where('gid', 4)->get(),
            'lsJuruPungut' => User::where('gid', 5)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Karcis  $karcis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Karcis $karcis)
    {
        //
        $id = $request->id;
        $reqData = $request->only('id_registrasi_karcis', 'tgl_penyerahan', 'tahun', 'harga', 'no_karcis_awal', 'no_karcis_akhir', 'id_user_juru_pungut', 'id_wilayah', 'id_user_koordinator');
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'tgl_penyerahan' => 'required|date',
            'tahun' => 'required|numeric',
            //'harga' => 'required|numeric',
            'harga' => [
                'required',
                'numeric',
                function (string $attribute, mixed $value, Closure $fail) use($reqData, $id) {
                    $result = Karcis::where('id', '<>', $id)->where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->where('stts',1)->count();
                    if ($result > 0) {
                        $fail('Nomor Karcis dengan Harga '.Str::rupiah($reqData['harga']).' dan Tahun '.$reqData['tahun'].' masih tersedia');
                    }
                },
            ],
            'id_wilayah' => 'required',
            'no_karcis_awal' => [
                'required',
                'numeric',
                function (string $attribute, mixed $value, Closure $fail) use($reqData, $id) {
                    $result = Karcis::where('id', '<>', $id)->where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->whereRaw($reqData['no_karcis_awal'].' between no_karcis_awal and no_karcis_akhir')->count();
                    if ($result > 0) {
                        $fail("Nomor Karcis Awal telah terdaftar");
                    }
                },
            ],
            'no_karcis_akhir' => [
                'required',
                'numeric',
                'gte:no_karcis_awal',
                function (string $attribute, mixed $value, Closure $fail) use($reqData, $id) {
                    $result = Karcis::where('id', '<>', $id)->where('tahun', $reqData['tahun'])->where('harga', $reqData['harga'])->whereRaw($reqData['no_karcis_akhir'].' between no_karcis_awal and no_karcis_akhir')->count();
                    if ($result > 0) {
                        $fail("Nomor Karcis Akhir telah terdaftar");
                    }
                },
            ],
        ],[
            'tgl_penyerahan.required' => 'Tanggal Penyerahan tidak boleh kosong',
            'tgl_penyerahan.date' => 'Tanggal Penyerahan tidak valid',
            'tahun.required' => 'Tahun tidak boleh kosong',
            'tahun.numeric' => 'Tahun adalah angka',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.numeric' => 'Harga adalah angka',
            'id_wilayah.required' => 'Wilayah Kerja tidak boleh kosong',
            'no_karcis_awal.required' => 'Nomor Karcis Awal tidak boleh kosong',
            'no_karcis_awal.numeric' => 'Nomor Karcis Awal adalah angka',
            'no_karcis_akhir.required' => 'Nomor Karcis Akhir tidak boleh kosong',
            'no_karcis_akhir.numeric' => 'Nomor Karcis Akhir adalah angka',
            'no_karcis_akhir.gte' => 'Nomor Karcis Akhir harus lebih besar dari Nomor Karcis Awal',
        ]);
        //dd($validator);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $karcis->find($id)->update($reqData);
        
        return redirect('karcis')->withSuccess('Data Karcis berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Karcis  $karcis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Karcis $karcis, $id)
    {
        //
        $karcis->find($id)->delete();
        return redirect('karcis')->withSuccess('Data Karcis berhasil dihapus');
    }
}
