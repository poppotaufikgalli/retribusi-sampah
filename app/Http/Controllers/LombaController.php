<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\PosJuri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

class LombaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Lomba::all();
        confirmDelete('Hapus Data Lomba!', "Apakah anda yakin untuk menghapus?");
        return view('admin.lomba.index', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.lomba.formulir', [
            'next' => 'store',
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
        $reqData = $request->only('judul', 'ket', 'tahun', 'aktif', 'jml_pos');
        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'judul' => 'required|min:3|unique:lombas,judul',
            'ket' => 'sometimes|nullable|min:3',
        ],[
            'judul.required' => 'Judul Lomba tidak boleh kosong',
            'judul.min' => 'Judul Lomba minimal 3 Karakter',
            'judul.unique' => 'Judul Lomba telah terdaftar',

            'ket.min' => 'Keterangan Lomba minimal 3 Karakter',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Lomba::create($reqData);
        return redirect('lomba')->withSuccess('Data Kategori Lomba berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lomba  $lomba
     * @return \Illuminate\Http\Response
     */
    public function show(Lomba $lomba)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lomba  $lomba
     * @return \Illuminate\Http\Response
     */
    public function edit(Lomba $lomba, $id)
    {
        //
        return view('admin.lomba.formulir', [
            'data'=> $lomba->find($id),
            'next' => 'update',
            'title' => 'Edit Kategori Lomba',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lomba  $lomba
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lomba $lomba)
    {
        $id = $request->id;
        $reqData = $request->only('judul', 'ket', 'tahun', 'aktif', 'jml_pos');
        //dd($reqData);

        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'judul' => 'required|min:3|unique:lombas,judul,'.$id,
            'ket' => 'sometimes|nullable|min:3',
        ],[
            'judul.required' => 'Judul Lomba tidak boleh kosong',
            'judul.min' => 'Judul Lomba minimal 3 Karakter',
            'judul.unique' => 'Judul Lomba telah terdaftar',

            'ket.min' => 'Keterangan Lomba minimal 3 Karakter',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $lomba->find($id)->update($reqData);
        return redirect('lomba')->withSuccess('Data Kategori Lomba berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lomba  $lomba
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lomba $lomba, $id)
    {
        //Alert::success('Berhasil', "Data Kategori Lomba berhasil dihapus");
        //return redirect('/lomba');
        //dd($request);
        //alert()->question('Are you sure?','You won\'t be able to revert this!');
        Lomba::find($id)->delete();
        return redirect('lomba')->withSuccess('Data Kategori Lomba berhasil dihapus');
    }
}
