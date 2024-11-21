<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;


class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Wilayah::all();
        confirmDelete('Hapus Data Wilayah Kerja', "Apakah anda yakin untuk menghapus?");
        return view('admin.wilayah.index', [
            'data' => $data,
            'title' => 'Wilayah Kerja Retribusi',
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
        return view('admin.wilayah.formulir', [
            'next' => 'store',
            'title' => 'Tambah Wilayah Kerja',
            'lsKoordinator' => User::where('gid', 4)->where('aktif',1)->get(),
            'lsJuruPungut' => User::where('gid', 5)->where('aktif',1)->get(),
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
        $reqData = $request->only('nama', 'deskripsi', 'id_user_koordinator', 'id_user_juru_pungut');

        $validator = Validator::make($reqData, [
            'nama' => 'required|min:3|unique:wilayahs,nama',
            'id_user_koordinator' => 'required',
            'id_user_juru_pungut' => 'required',
        ],[
            'nama.required' => 'Nama Wilayah Kerja tidak boleh kosong',
            'nama.min' => 'Nama Wilayah Kerja minimal 3 Karakter',
            'nama.unique' => 'Nama Wilayah Kerja telah terdaftar',

            'id_user_koordinator.required' => 'Koordinator tidak boleh kosong',
            'id_user_juru_pungut.required' => 'Juru Pungut tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Wilayah::create($reqData);
        return redirect('wilayah')->withSuccess('Data Wilayah Kerja berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wilayah  $wilayah
     * @return \Illuminate\Http\Response
     */
    public function show(Wilayah $wilayah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wilayah  $wilayah
     * @return \Illuminate\Http\Response
     */
    public function edit(Wilayah $wilayah, $id)
    {
        //
        $data = Wilayah::find($id);
        return view('admin.wilayah.formulir', [
            'next' => 'update',
            'title' => 'Tambah Wilayah Kerja',
            'lsKoordinator' => User::where('gid', 4)->where('aktif',1)->get(),
            'lsJuruPungut' => User::where('gid', 5)->where('aktif',1)->get(),
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wilayah  $wilayah
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wilayah $wilayah)
    {
        //
        $id = $request->id;
        $reqData = $request->only('nama', 'deskripsi', 'id_user_koordinator', 'id_user_juru_pungut');

        $validator = Validator::make($reqData, [
            'nama' => 'required|min:3|unique:wilayahs,nama,'.$id,
            //'nama' => 'required|min:3|unique:jenis_retribusis,nama,'.$id,
            'id_user_koordinator' => 'required',
            'id_user_juru_pungut' => 'required',
        ],[
            'nama.required' => 'Nama Wilayah Kerja tidak boleh kosong',
            'nama.min' => 'Nama Wilayah Kerja minimal 3 Karakter',
            'nama.unique' => 'Nama Wilayah Kerja telah terdaftar',

            'id_user_koordinator.required' => 'Koordinator tidak boleh kosong',
            'id_user_juru_pungut.required' => 'Juru Pungut tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $wilayah->find($id)->update($reqData);
        return redirect('wilayah')->withSuccess('Data Wilayah Kerja berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wilayah  $wilayah
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wilayah $wilayah, $id)
    {
        //
        $wilayah->find($id)->delete();
        return redirect('wilayah')->withSuccess('Data Wilayah Kerja berhasil dihapus');
    }
}
