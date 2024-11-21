<?php

namespace App\Http\Controllers;

use App\Models\Konfig;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

class KonfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tahun = "")
    {
        $next = 'store';
        $data = Konfig::where('tahun', $tahun)->first();

        confirmDelete("Menghapus Konfigurasi", "Apakah anda yakin untuk menghapus data ini?");

        return view('admin.konfig.index', [
            'data' => $data,
            'next' => $next,
            'listData' => Konfig::all(),
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
        return view('admin.konfig.formulir', [
            
            //'katPeserta' => KatPeserta::where('id_lomba', $id_lomba)->get(),
            //'no_peserta_max' => $no_peserta_max,
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
        $reqData = $request->only('tahun', 'target', 'target_p', 'aktif');

        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        
        $validator = Validator::make($reqData, [
            'tahun' => 'required|unique:konfigs,tahun',
            'target' => 'required'
        ],[
            'tahun.required' => 'Tahun tidak boleh kosong',
            'tahun.unique' => 'Tahun telah terdaftar',
            'target.required' => 'Target murni tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        Konfig::create($reqData);
        return redirect('konfig')->withSuccess('Data Konfigurasi Lomba berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Konfig  $konfig
     * @return \Illuminate\Http\Response
     */
    public function show(Konfig $konfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Konfig  $konfig
     * @return \Illuminate\Http\Response
     */
    public function edit(Konfig $konfig, $id)
    {
        //
        return view('admin.konfig.formulir', [
            
            //'katPeserta' => KatPeserta::where('id_lomba', $id_lomba)->get(),
            //'no_peserta_max' => $no_peserta_max,
            'data' => Konfig::find($id),
            'next' => 'update',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Konfig  $konfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Konfig $konfig)
    {
        $id = $request->id;
        $reqData = $request->only('tahun', 'target', 'target_p', 'aktif');

        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        
        $validator = Validator::make($reqData, [
            'tahun' => 'required|unique:konfigs,tahun,'.$id,
            'target' => 'required',
        ],[
            'tahun.required' => 'Tahun tidak boleh kosong',
            'tahun.unique' => 'Tahun telah terdaftar',
            'target.required' => 'Target murni tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        Konfig::find($id)->update($reqData);
        return redirect('konfig')->withSuccess('Data Konfigurasi Lomba berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Konfig  $konfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(Konfig $konfig, $id)
    {
        //
        $delete = $konfig->find($id);
        if($delete->aktif == 1){
            return back()->withError('Data tidak dihapus. Data Konfigurasi adalah data Aktif');    
        }

        $delete->delete();
        return redirect('konfig')->withSuccess('Data Konfigurasi Lomba berhasil dihapus');
    }
}
