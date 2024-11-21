<?php

namespace App\Http\Controllers;

use App\Models\JenisRetribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

class JenisRetribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = JenisRetribusi::all();
        confirmDelete('Hapus Data Jenis Retribusi', "Apakah anda yakin untuk menghapus?");
        return view('admin.jenis_retribusi.index', [
            'data' => $data,
            'title' => 'Jenis Retribusi',
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
        return view('admin.jenis_retribusi.formulir', [
            'next' => 'store',
            'title' => 'Tambah Jenis Retribusi',
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
        $reqData = $request->only('nama', 'aktif');
        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'nama' => 'required|min:3|unique:jenis_retribusis,nama',
        ],[
            'nama.required' => 'Nama Jenis Retribusi tidak boleh kosong',
            'nama.min' => 'Nama Jenis Retribusi minimal 3 Karakter',
            'nama.unique' => 'Nama Jenis Retribusi telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        JenisRetribusi::create($reqData);
        return redirect('jenis_retribusi')->withSuccess('Data Jenis Retribusi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JenisRetribusi  $jenisRetribusi
     * @return \Illuminate\Http\Response
     */
    public function show(JenisRetribusi $jenisRetribusi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JenisRetribusi  $jenisRetribusi
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisRetribusi $jenisRetribusi, $id)
    {
        //
        return view('admin.jenis_retribusi.formulir', [
            'data'=> $jenisRetribusi->find($id),
            'next' => 'update',
            'title' => 'Edit Jenis Retribusi',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JenisRetribusi  $jenisRetribusi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisRetribusi $jenisRetribusi)
    {
        //
        $id = $request->id;
        $reqData = $request->only('nama', 'aktif');
        //dd($reqData);

        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'nama' => 'required|min:3|unique:jenis_retribusis,nama,'.$id,
        ],[
            'nama.required' => 'Nama Jenis Retribusi tidak boleh kosong',
            'nama.min' => 'Nama Jenis Retribusi minimal 3 Karakter',
            'nama.unique' => 'Nama Jenis Retribusi Peserta telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $jenisRetribusi->find($id)->update($reqData);
        return redirect('jenis_retribusi')->withSuccess('Data Jenis Retribusi berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JenisRetribusi  $jenisRetribusi
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisRetribusi $jenisRetribusi, $id)
    {
        //
        $jenisRetribusi->find($id)->delete();
        return redirect('jenis_retribusi')->withSuccess('Data Jenis Retribusi berhasil dihapus');
    }
}
