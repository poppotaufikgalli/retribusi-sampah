<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Pendaftar;
use App\Models\Lomba;
use App\Models\Penilaian;
use App\Models\JuriKategori;
use App\Models\Diskualifikasi;
use DB;
use Auth;

class DiskualifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $data = Pendaftar::where(function($query) use($id){
            if($id > 0){
                $query->where('id_lomba', $id);
            }
        })->get();

        $lomba = Lomba::find($id);

        $diskualifikasi = Diskualifikasi::select(
            'id_pendaftar',
            DB::raw('count(alasan) as count_alasan'),
        )->groupBy(['id_pendaftar'])->pluck('count_alasan','id_pendaftar');

        //dd($dataPenilaian);

        return view("admin.diskualifikasi.index", [
            'id' => $id,
            'data' => $data,
            'subtitle' => $lomba,
            'posJuri' => JuriKategori::where('id_lomba', $id)->get(),
            'diskualifikasi' => $diskualifikasi,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        return view('admin.diskualifikasi.formulir', [
            'id_lomba' => $id,
        ]);
    }

    public function search(Request $request)
    {
        $reqData = $request->only('no_peserta', 'id_lomba');
        //dd($request->all());
        $pendaftar = Pendaftar::where('id_lomba', $reqData['id_lomba'])->where('no_peserta', $reqData['no_peserta'])->first();
        //dd($data);

        if($pendaftar){
            $id = $pendaftar->id;
            return redirect()->route('diskualifikasi.show', ['id'=> $id]);
        }else{
            return redirect()->back()->with('errors', "Data Tidak ditemukan");
        }
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        //dd(asset('storage/public'));
        $data = Pendaftar::find($id);
        $diskualifikasi = Diskualifikasi::where('id_pendaftar', $id)->get();
        
        return view('admin.diskualifikasi.formulir', [
            'data' => $data,
            'diskualifikasi' => $diskualifikasi,
            'next' => 'update',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $id = $request->id;
        $reqData = $request->only('alasan', 'ket');

        $validator = Validator::make($reqData, [
            'alasan' => 'required',
        ],[
            'alasan.required' => 'Alasan tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        $reqData['id_pendaftar'] = $id;
        $reqData['uid'] = Auth::id();
        $reqData['doc'] = $this->doUpload($request);
        
        Diskualifikasi::create($reqData);
        Pendaftar::where('id', $id)->update(['diskualifikasi' => 1]);
        return redirect()->back()->withSuccess('Pencatatan Diskualifikasi berhasil ditambahkan');
    }

    public function doUpload(Request $request)
    {
        $request->validate([
            'file' => 'sometimes|nullable|mimes:png,jpg,jpeg|max:2048'
        ],[
            'file.mimes' => 'File gambar tidak valid',
            'file.max' => 'Ukuran melebihi batas. Maksimal 2mb'
        ]);

        $file = $request->file('file');
        
        if($file){
            $fileName = $file->hashName();
            $file->storeAs('public', $fileName);  
            return $fileName;  
        }
        

        /*File::create([
            'original_name' => $file->getClientOriginalName(),
            'generated_name' => $fileName
        ]);*/

        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
