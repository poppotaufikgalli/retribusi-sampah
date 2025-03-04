<?php

namespace App\Http\Controllers;

use App\Models\WajibRetribusi;
use App\Models\ObjekRetribusi;
use App\Models\JenisRetribusi;
use App\Models\KecamatanKelurahan;
use App\Models\Wilayah;
use App\Models\Pemilik;
use App\Models\AktifasiWr;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

use Auth;

class WajibRetribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$data = WajibRetribusi::with(['objek_retribusi'])->paginate(20)->withQueryString();
        //dd($data);
        $data = WajibRetribusi::join('objek_retribusis', 'objek_retribusis.id', 'wajib_retribusis.id_objek_retribusi')->join('jenis_retribusis', 'jenis_retribusis.id', 'objek_retribusis.id_jenis_retribusi')->leftJoin('pemiliks', 'pemiliks.id', 'wajib_retribusis.id_pemilik')->leftjoin('wilayahs', 'wilayahs.id', 'wajib_retribusis.id_wilayah')
            ->select('wajib_retribusis.id', 'wajib_retribusis.npwrd', 'wajib_retribusis.nama', 'wajib_retribusis.alamat', 'objek_retribusis.nama as nama_objek', 'objek_retribusis.id as id_objek_retribusi', 'jenis_retribusis.nama as nama_jenis', 'pemiliks.nama as nama_pemilik', 'wilayahs.nama as nama_wilayah', 'wajib_retribusis.aktif' )->where(function($query) use($request){
            //if($id_jenis_retribusi > 0){
            //    $query->where('objek_retribusis.id_jenis_retribusi', $id_jenis_retribusi);
            //}

            if($request->method() == 'POST'){
                $nama = $request->nama;
                $id_jenis_retribusi = $request->id_jenis_retribusi;
                $id_objek_retribusi = $request->id_objek_retribusi;
                
                if($nama != ""){
                    $query->where('wajib_retribusis.nama', 'like', '%'.$nama.'%')->orWhere('wajib_retribusis.npwrd', 'like', '%'.$nama.'%');
                }

                if($id_jenis_retribusi > 0){
                    $query->where('objek_retribusis.id_jenis_retribusi', $id_jenis_retribusi);
                }

                if($id_objek_retribusi != null){
                    $query->where('objek_retribusis.id', $id_objek_retribusi);
                }
            }

            //if($id_objek_retribusi != null){
            //    $query->where('objek_retribusis.id', $id_objek_retribusi);
            //}
        })->orderBy('nama')->paginate(20)->withQueryString();
          
        confirmDelete('Hapus Data Wajib Retribusi', "Apakah anda yakin untuk menghapus?");

        return view("admin.wajib_retribusi.index", [
            'id_jenis_retribusi' => $request->id_jenis_retribusi,
            'id_objek_retribusi' => $request->id_objek_retribusi,
            'nama' => $request->nama,
            'data' => $data,
            'jenis_retribusi' => JenisRetribusi::all(),
            'objek_retribusi' => ObjekRetribusi::where('id_jenis_retribusi', $request->id_jenis_retribusi)->get(),
            //'subtitle' => Lomba::find($id_lomba),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_jenis_retribusi, $id_objek_retribusi)
    {
        //
        return view('admin.wajib_retribusi.formulir', [
            'next' => 'store',
            'id_jenis_retribusi' => $id_jenis_retribusi,
            'id_objek_retribusi' => $id_objek_retribusi,
            'jenis_retribusi' => JenisRetribusi::all(),
            'objek_retribusi' => ObjekRetribusi::where('id_jenis_retribusi', $id_jenis_retribusi)->get(),
            'kecamatan' => KecamatanKelurahan::where('jns', 1)->get(),
            'kelurahan' => KecamatanKelurahan::where('jns', 2)->get(),
            'wilayah_kerja' => Wilayah::all(),
            'pemilik' => Pemilik::all(),
            'edit' => true,
            'title' => 'Tambah Wajib Retribusi',
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
        $reqData = $request->only('id_jenis_retribusi', 'id_objek_retribusi', 'id_kecamatan', 'id_kelurahan', 'npwrd', 'nama', 'alamat', 'id_wilayah', 'lat', 'lng', 'id_pemilik', 'nop_pbb', 'foto');

        //dd($reqData);
        $validator = Validator::make($reqData, [
            'id_jenis_retribusi' => 'required',
            'id_objek_retribusi' => 'required',
            'id_kecamatan' => 'required',
            'id_kelurahan' => 'required',
            /*'npwrd' => [
                'required',
                Rule::unique('wajib_retribusis')->where(function ($query) use($reqData) {
                    return $query->where('npwrd', $reqData['npwrd']);
                }),
            ],*/
            'nama' => [
                'required',
                'min:3',
                Rule::unique('wajib_retribusis')->where(function ($query) use($reqData) {
                    return $query->where('id_objek_retribusi', $reqData['id_objek_retribusi'])
                    ->where('nama', $reqData['nama']);
                }),
            ],
            'alamat' => 'sometimes|nullable|min:3',
            'id_wilayah' => 'required',
            'foto' => 'sometimes|image|mimes:jpeg,png,gif|max:2048',
        ],[
            'id_jenis_retribusi.required' => 'Jenis Retribusi tidak boleh kosong',
            'id_objek_retribusi.required' => 'Objek Retribusi tidak boleh kosong',
            'id_kecamatan.required' => 'Kecamatan tidak boleh kosong',
            'id_kelurahan.required' => 'Kelurahan tidak boleh kosong',

            /*'npwrd.required' => 'NPWRD tidak boleh kosong',
            'npwrd.unique' => 'NPWRD telah terdaftar',*/

            'nama.required' => 'Nama Wajib Retribusi tidak boleh kosong',
            'nama.min' => 'Nama Wajib Retribusi minimal 3 Karakter',
            'nama.unique' => 'Nama Wajib Retribusi telah terdaftar',

            'alamat.min' => 'Alamat minimal 3 Karakter',
            'id_wilayah.required' => 'Wilayah Kerja tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        if($request->hasFile('foto'))
        {
            $filenameWithExt    = $request->file('foto')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('foto')->getClientOriginalExtension();
            $fileNameToStore    = $filename.'_'.time().'.'.$extension;
            $path               = $request->file('foto')->storeAs('public/wr/', $fileNameToStore);                            
            $reqData['foto']    = $fileNameToStore;
        } 

        if($reqData['id_pemilik'] == "" && $reqData['nama'] != ""){
            $createData['nama'] = $request->nama_pemilik;
            $createData['no_hp'] = $request->no_hp_pemilik;
            $createData['nik'] = $request->nik_pemilik;
            $id = Pemilik::create($createData)->id;
            $reqData['id_pemilik'] = $id;
        }
        
        WajibRetribusi::create($reqData);
        return redirect('wajib_retribusi')->withSuccess('Data Wajib Retribusi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WajibRetribusi  $wajibRetribusi
     * @return \Illuminate\Http\Response
     */
    public function show(WajibRetribusi $wajibRetribusi, Request $request, $id_objek_retribusi, $id)
    {
        //
        if($request->method() == 'POST'){
            $reqData = $request->only('npwrd','no_sk', 'tgl_sk', 'tmt_sk', 'catatan', 'aktif');
            //dd($reqData);

            $validator = Validator::make($reqData, [
                'aktif' => 'required',
                /*'npwrd' => [
                    'required',
                    Rule::unique('aktifasi_wrs')->where(function ($query) use($reqData) {
                        return $query->where('npwrd', $reqData['npwrd'])
                        ->where('tmt_sk', $reqData['tmt_sk'])
                        ->where('aktif', $reqData['aktif']);
                    }),
                ],*/
                'no_sk' => 'required|min:3',
                'tgl_sk' => 'required|date',
                'tmt_sk' => 'required|date',
            ],[
                'aktif.required' => 'Status Aktif tidak boleh kosong',
                /*'npwrd.required' => 'NPWRD tidak boleh kosong',
                'npwrd.unique' => 'Status Wajib Retribusi sudah terdaftar',*/
                'no_sk.required' => 'Nomor SK tidak boleh kosong',
                'no_sk.min' => 'Nomor SK minimal 3 Karakter',
                'tgl_sk.required' => 'Tanggal SK tidak boleh kosong',
                'tgl_sk.date' => 'Tanggal SK tidak valid',
                'tmt_sk.required' => 'TMT SK tidak boleh kosong',
                'tmt_sk.date' => 'TMT SK tidak valid',
            ]);

            if($validator->fails())
            {
                return back()->with('errors', $validator->messages()->all()[0])->withInput();
            }

            $reqData['id_user'] = Auth::id();
            AktifasiWr::create($reqData);

            WajibRetribusi::find($id)->update(['aktif' => $reqData['aktif']]);

            return redirect('/wajib_retribusi/'.$id_objek_retribusi.'/show/'.$id)->withSuccess('Data Wajib Retribusi berhasil ditambahkan');
        }

        $data = $wajibRetribusi::with(['objek_retribusi', 'objek_retribusi.jenis_retribusi'])->find($id);
        $aktifasiWr = AktifasiWr::where('npwrd', $data->npwrd)->orderBy('created_at', 'desc')->get();
        
        return view('admin.wajib_retribusi.show', [
            'next' => 'show',
            'id_objek_retribusi' => $id_objek_retribusi,
            'edit' => true,
            'title' => 'Status Wajib Retribusi',
            'data' => $data,
            'aktifasiWr' => $aktifasiWr,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WajibRetribusi  $wajibRetribusi
     * @return \Illuminate\Http\Response
     */
    public function edit(WajibRetribusi $wajibRetribusi, $id_objek_retribusi, $id)
    {
        //
        $data = $wajibRetribusi::with(['objek_retribusi', 'pemilik', 'objek_retribusi.jenis_retribusi'])->find($id);
        //dd($data);
        $id_jenis_retribusi = $data?->objek_retribusi?->jenis_retribusi?->id;
        return view('admin.wajib_retribusi.formulir', [
            'next' => 'update',
            'id_jenis_retribusi' => $id_jenis_retribusi,
            'id_objek_retribusi' => $id_objek_retribusi,
            'jenis_retribusi' => JenisRetribusi::all(),
            'objek_retribusi' => ObjekRetribusi::where('id_jenis_retribusi', $id_jenis_retribusi)->get(),
            'kecamatan' => KecamatanKelurahan::where('jns', 1)->get(),
            'kelurahan' => KecamatanKelurahan::where('jns', 2)->get(),
            'wilayah_kerja' => Wilayah::all(),
            'pemilik' => Pemilik::all(),
            'edit' => true,
            'title' => 'Edit Wajib Retribusi',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WajibRetribusi  $wajibRetribusi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WajibRetribusi $wajibRetribusi)
    {
        //
        $id = $request->id;
        //$id_lomba = $request->id_lomba;
        //$reqData = $request->only('no_peserta', 'nama', 'alamat', 'pic', 'telp', 'aktif', 'ketua', 'telp_ketua');
        $reqData = $request->only('id_jenis_retribusi', 'id_objek_retribusi', 'id_kecamatan', 'id_kelurahan', 'npwrd', 'nama', 'alamat', 'id_wilayah', 'lat', 'lng', 'id_pemilik', 'nop_pbb', 'foto');
        /*if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }*/
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'id_jenis_retribusi' => 'required',
            'id_objek_retribusi' => 'required',
            'id_kecamatan' => 'required',
            'id_kelurahan' => 'required',
            /*'npwrd' => [
                'required',
                Rule::unique('wajib_retribusis')->where(function ($query) use($reqData) {
                    return $query->where('npwrd', $reqData['npwrd']);
                })->ignore($id),
            ],*/
            'nama' => [
                'required',
                'min:3',
                Rule::unique('wajib_retribusis')->where(function ($query) use($reqData) {
                    return $query->where('id_objek_retribusi', $reqData['id_objek_retribusi'])
                    ->where('nama', $reqData['nama']);
                })->ignore($id),
            ],
            'alamat' => 'sometimes|nullable|min:3',
            'id_wilayah' => 'required',
            'foto' => 'sometimes|image|mimes:jpeg,png,gif|max:2048',
        ],[
            'id_jenis_retribusi.required' => 'Jenis Retribusi tidak boleh kosong',
            'id_objek_retribusi.required' => 'Objek Retribusi tidak boleh kosong',
            'id_kecamatan.required' => 'Kecamatan tidak boleh kosong',
            'id_kelurahan.required' => 'Kelurahan tidak boleh kosong',

            //'npwrd.required' => 'NPWRD tidak boleh kosong',
            //'npwrd.unique' => 'NPWRD telah terdaftar',

            'nama.required' => 'Nama Wajib Retribusi tidak boleh kosong',
            'nama.min' => 'Nama Wajib Retribusi minimal 3 Karakter',
            'nama.unique' => 'Nama Wajib Retribusi telah terdaftar',

            'alamat.min' => 'Alamat minimal 3 Karakter',
            'id_wilayah.required' => 'Wilayah Kerja tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        if($request->hasFile('foto'))
        {
            //dd("ABC");
            $filenameWithExt    = $request->file('foto')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('foto')->getClientOriginalExtension();
            $fileNameToStore    = $filename.'_'.time().'.'.$extension;
            $path               = $request->file('foto')->storeAs('public/wr/', $fileNameToStore);                            
            $reqData['foto']    = $fileNameToStore;
        } 

        if($reqData['id_pemilik'] == "" ){
            if($request->nama_pemilik != ""){
                $createData['nama']     = $request->nama_pemilik;
                $createData['no_hp']    = $request->no_hp_pemilik;
                $createData['nik']      = $request->nik_pemilik;
                $id                     = Pemilik::create($createData)->id;
                $reqData['id_pemilik']  = $id;    
            }
            
        }
        
        WajibRetribusi::find($id)->update($reqData);
        //return redirect(`/{$reqData['id_objek_retribusi']}/wajib_retribusi/{$id}`)->withSuccess('Data Wajib Retribusi berhasil diubah');
        return redirect('wajib_retribusi')->withSuccess('Data Wajib Retribusi berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WajibRetribusi  $wajibRetribusi
     * @return \Illuminate\Http\Response
     */
    public function destroy(WajibRetribusi $wajibRetribusi, $id)
    {
        //
        $wajibRetribusi->find($id)->delete();
        return redirect('wajib_retribusi')->withSuccess('Data Wajib Retribusi berhasil dihapus');
    }
}
