<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\WajibRetribusi;
use App\Models\RegistrasiKarcis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

use Auth;

class TagihanController extends Controller
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
            'title' => 'SKRD',
            'filter' => $request->only('snpwrd', 'sbln', 'semua'),
            'data' => Tagihan::where(function($query)use($request) {
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

            })->orderBy('created_at', 'desc')->get(),
        ];

        confirmDelete('Hapus Data Tagihan!', "Apakah anda yakin untuk menghapus?");
        
        return view('admin.tagihan.index', $data);
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
            'title' => 'Tambah SKRD',
            'lsKoordinator' => User::where('gid', 4)->get(),
            'lsJuruPungut' => User::where('gid', 5)->get(),
            'lsSerahTerima' => RegistrasiKarcis::all(),
        ];

        return view('admin.tagihan.formulir', $data);
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
        $reqData = $request->only('tgl_penyerahan', 'id_wr', 'npwrd', 'nama', 'bln', 'thn', 'jml', 'tgl_skrd', 'no_skrd', 'file', 'id_user_juru_pungut', 'id_user_koordinator');
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'tgl_penyerahan' => 'required|date',
            'id_wr' => [
                'required',
                Rule::unique('tagihans')->where(function ($query) use($reqData) {
                    return $query->where('id_wr', $reqData['id_wr'])
                    ->where('bln', $reqData['bln'])
                    ->where('thn', $reqData['thn']);
                }),
            ],
            'bln' => 'required',
            //'thn' => 'required',
            'thn' => [
                'required',
                Rule::unique('pembayarans')->where(function ($query) use($reqData) {
                    return $query->where('id_wr', $reqData['id_wr'])
                    ->where('bln', $reqData['bln'])
                    ->where('thn', $reqData['thn']);
                }),
            ],
            'file' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'jml' => 'required|numeric',
        ],[
            'tgl_penyerahan.required' => 'Tanggal Penyerahan tidak boleh kosong',
            'tgl_penyerahan.date' => 'Tanggal Penyerahan tidak valid',
            'id_wr.required' => 'SKRD tidak valid',
            'id_wr.unique' => 'SKRD sudah ada',
            'bln.required' => 'Bulan tidak boleh kosong',
            'thn.required' => 'Tahun tidak boleh kosong',
            'thn.unique' => 'Tagihan '.$reqData['bln'].'/'.$reqData['thn'].' sudah terbayar',
            'file.image' => 'File karcis / bukti bayar bukan gambar',
            'file.mime' => 'File karcis / bukti bayar tidak sesuai format',
            'file.max' => 'File karcis / bukti bayar melebihi ukuran yang ditentukan',
            'jml.required' => 'Jumlah SKRD tidak boleh kosong',
            'jml.numeric' => 'Jumlah SKRD tidak valid',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        if($request->hasFile('file'))
        {
            $filenameWithExt    = $request->file('file')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore    = $reqData['npwrd'].'_'.$reqData['bln'].$reqData['thn'].'_'.time().'.'.$extension;
            //$fileNameToStore    = $result->id.".".$extension;
            $path               = $request->file('file')->storeAs('public/tagihan/', $fileNameToStore);                            
            $reqData['filename_skrd'] = $fileNameToStore;
        } 
        
        $reqData['id_user'] = Auth::id();
        //dd($reqData);
        $result = Tagihan::create($reqData);

        /*if($reqData['id_registrasi_karcis'] != ""){
            return redirect('registrasi_karcis')->withSuccess('Data Tagihan berhasil ditambahkan');    
        }*/
        return redirect('tagihan')->withSuccess('Data SKRD berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function show(Tagihan $tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function edit(Tagihan $tagihan, $id)
    {
        //
        $data = [
            'next'  => 'update',
            'title' => 'Edit SKRD',
            'data'  => $tagihan->find($id),
            'lsKoordinator' => User::where('gid', 4)->get(),
            'lsJuruPungut' => User::where('gid', 5)->get(),
            'lsSerahTerima' => RegistrasiKarcis::all(),
        ];

        return view('admin.tagihan.formulir', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        //
        $id = $request->id;
        $reqData = $request->only('tgl_penyerahan', 'id_wr', 'npwrd', 'bln', 'thn', 'jml', 'tgl_skrd', 'no_skrd', 'file', 'id_user_juru_pungut', 'id_user_koordinator');
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'tgl_penyerahan' => 'required|date',
            'id_wr' => [
                'required',
                Rule::unique('tagihans')->where(function ($query) use($reqData) {
                    return $query->where('id_wr', $reqData['id_wr'])
                    ->where('bln', $reqData['bln'])
                    ->where('thn', $reqData['thn']);
                })->ignore($id),
            ],
            'bln' => 'required',
            'thn' => 'required',
            'file' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'jml' => 'required|numeric',
        ],[
            'tgl_penyerahan.required' => 'Tanggal Penyerahan tidak boleh kosong',
            'tgl_penyerahan.date' => 'Tanggal Penyerahan tidak valid',
            'id_wr.required' => 'SKRD tidak valid',
            'id_wr.unique' => 'SKRD sudah ada',
            'bln.required' => 'Bulan tidak boleh kosong',
            'thn.required' => 'Tahun tidak boleh kosong',
            'file.image' => 'File karcis / bukti bayar bukan gambar',
            'file.mime' => 'File karcis / bukti bayar tidak sesuai format',
            'file.max' => 'File karcis / bukti bayar melebihi ukuran yang ditentukan',
            'jml.required' => 'Jumlah SKRD tidak boleh kosong',
            'jml.numeric' => 'Jumlah SKRD tidak valid',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        if($request->hasFile('file'))
        {
            $filenameWithExt    = $request->file('file')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore    = $reqData['npwrd'].'_'.$reqData['bln'].$reqData['thn'].'_'.time().'.'.$extension;
            //$fileNameToStore    = $result->id.".".$extension;
            $path               = $request->file('file')->storeAs('public/tagihan/', $fileNameToStore);                            
            $reqData['filename_skrd'] = $fileNameToStore;
        } 
        
        $reqData['id_user'] = Auth::id();
        //dd($reqData);
        $tagihan->find($id)->update($reqData);

        return redirect('tagihan')->withSuccess('Data SKRD berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tagihan $tagihan, $id)
    {
        //
        $tagihan->find($id)->delete();
        return redirect('tagihan')->withSuccess('Data SKRD Retribusi berhasil dihapus');
    }
}
