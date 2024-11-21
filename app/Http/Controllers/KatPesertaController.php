<?php

namespace App\Http\Controllers;

use App\Models\KatPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

class KatPesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = KatPeserta::all();
        confirmDelete('Hapus Data Kategori Peserta!', "Apakah anda yakin untuk menghapus?");
        return view('admin.kat_peserta.index', [
            'data' => $data,
            'title' => 'Kategori Peserta',
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
        return view('admin.kat_peserta.formulir', [
            'next' => 'store',
            'title' => 'Tambah Kategori Peserta',
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
        //
        $reqData = $request->only('id_lomba', 'judul', 'ref_kecepatan', 'no_peserta_mulai', 'no_peserta_prefix', 'aktif');
        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'judul' => 'required|min:3|unique:lombas,judul,id_lomba',
            'ref_kecepatan' => 'required|numeric',
        ],[
            'judul.required' => 'Judul Kategori Peserta tidak boleh kosong',
            'judul.min' => 'Judul Kategori Peserta minimal 3 Karakter',
            'judul.unique' => 'Judul Kategori Peserta telah terdaftar',

            'ref_kecepatan.required' => 'Referensi Kecepatan tidak boleh kosong',
            'ref_kecepatan.numeric' => 'Referensi Kecepatan adalah angka',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        KatPeserta::create($reqData);
        return redirect('kat_peserta')->withSuccess('Data Kategori Peserta berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KatPeserta  $katPeserta
     * @return \Illuminate\Http\Response
     */
    public function show(KatPeserta $katPeserta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KatPeserta  $katPeserta
     * @return \Illuminate\Http\Response
     */
    public function edit(KatPeserta $katPeserta, $id)
    {
        //
        return view('admin.kat_peserta.formulir', [
            'data'=> $katPeserta->find($id),
            'next' => 'update',
            'title' => 'Edit Kategori Peserta',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KatPeserta  $katPeserta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KatPeserta $katPeserta)
    {
        //
        //dd($request->all());
        $id = $request->id;
        //$reqData = $request->only('judul', 'ket', 'tahun', 'aktif', 'jml_pos');
        $reqData = $request->only('id_lomba', 'judul', 'ref_kecepatan', 'no_peserta_mulai', 'no_peserta_prefix', 'aktif');
        //dd($reqData);

        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'judul' => 'required|min:3|unique:lombas,judul,'.$id,
            'ref_kecepatan' => 'required|numeric',
        ],[
            'judul.required' => 'Judul Kategori Peserta tidak boleh kosong',
            'judul.min' => 'Judul Kategori Peserta minimal 3 Karakter',
            'judul.unique' => 'Judul Kategori Peserta telah terdaftar',

            'ref_kecepatan.required' => 'Referensi Kecepatan tidak boleh kosong',
            'ref_kecepatan.numeric' => 'Referensi Kecepatan adalah angka',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $katPeserta->find($id)->update($reqData);
        return redirect('kat_peserta')->withSuccess('Data Kategori Peserta berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KatPeserta  $katPeserta
     * @return \Illuminate\Http\Response
     */
    public function destroy(KatPeserta $katPeserta, $id)
    {
        //
        KatPeserta::find($id)->delete();
        return redirect('kat_peserta')->withSuccess('Data Kategori Peserta berhasil dihapus');
    }
}
