<?php

namespace App\Http\Controllers;

use App\Models\ObjekRetribusi;
use App\Models\JenisRetribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

class ObjekRetribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = ObjekRetribusi::all();
        confirmDelete('Hapus Data Objek Retribusi', "Apakah anda yakin untuk menghapus?");
        return view('admin.objek_retribusi.index', [
            'data' => $data,
            'title' => 'Objek Retribusi',
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
        return view('admin.objek_retribusi.formulir', [
            'next' => 'store',
            'jenis_retribusi' => JenisRetribusi::all(),
            'title' => 'Tambah Objek Retribusi',
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
        $reqData = $request->only('nama', 'id_jenis_retribusi', 'deskripsi', 'tarif', 'insidentil', 'aktif');
        if(isset($reqData['insidentil']) && $reqData['insidentil'] == 'on'){
            $reqData['insidentil'] = 1;
        }else{
            $reqData['insidentil'] = 0;
        }

        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'nama' => 'required|min:3|unique:objek_retribusis,nama,id_jenis_retribusi',
            'tarif' => 'required|numeric',
        ],[
            'nama.required' => 'Nama Objek Retribusi tidak boleh kosong',
            'nama.min' => 'Nama Objek Retribusi minimal 3 Karakter',
            'nama.unique' => 'Nama Objek Retribusi telah terdaftar',

            'tarif.required' => 'Tarif tidak boleh kosong',
            'tarif.numeric' => 'Tarif adalah angka',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        ObjekRetribusi::create($reqData);
        return redirect('objek_retribusi')->withSuccess('Data Objek Retribusi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ObjekRetribusi  $objekRetribusi
     * @return \Illuminate\Http\Response
     */
    public function show(ObjekRetribusi $objekRetribusi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ObjekRetribusi  $objekRetribusi
     * @return \Illuminate\Http\Response
     */
    public function edit(ObjekRetribusi $objekRetribusi, $id)
    {
        //
        return view('admin.objek_retribusi.formulir', [
            'data'=> $objekRetribusi->find($id),
            'jenis_retribusi' => JenisRetribusi::all(),
            'next' => 'update',
            'title' => 'Edit Objek Retribusi',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ObjekRetribusi  $objekRetribusi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ObjekRetribusi $objekRetribusi)
    {
        //
        $id = $request->id;
        $reqData = $request->only('nama', 'id_jenis_retribusi', 'deskripsi', 'tarif', 'insidentil', 'aktif');

        if(isset($reqData['insidentil']) && $reqData['insidentil'] == 'on'){
            $reqData['insidentil'] = 1;
        }else{
            $reqData['insidentil'] = 0;
        }

        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'nama' => ['required',
                        'min:3',
                        //'unique:objek_retribusis,nama,id_jenis_retribusi,'.$id.',id'
                        Rule::unique('objek_retribusis')->ignore($id),
            ],
            'tarif' => 'required|numeric',
        ],[
            'nama.required' => 'Nama Objek Retribusi tidak boleh kosong',
            'nama.min' => 'Nama Objek Retribusi minimal 3 Karakter',
            'nama.unique' => 'Nama Objek Retribusi Peserta telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $objekRetribusi->find($id)->update($reqData);
        return redirect('objek_retribusi')->withSuccess('Data Objek Retribusi berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ObjekRetribusi  $objekRetribusi
     * @return \Illuminate\Http\Response
     */
    public function destroy(ObjekRetribusi $objekRetribusi, $id)
    {
        //
        $objekRetribusi->find($id)->delete();
        return redirect('objek_retribusi')->withSuccess('Data Objek Retribusi berhasil dihapus');
    }
}
