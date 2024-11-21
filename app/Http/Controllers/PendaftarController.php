<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\Lomba;
use App\Models\KatPeserta;
use App\Models\Penilaian;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

use Auth;

class PendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_lomba=0, $id_peserta=null)
    {
        $data = Pendaftar::where(function($query) use($id_lomba, $id_peserta){
            if($id_lomba > 0){
                $query->where('id_lomba', $id_lomba);
            }

            if($id_peserta != null){
                $query->where('id_peserta', $id_peserta);
            }
        })->orderByRaw('cast(no_peserta as unsigned)')->get();
        
        confirmDelete('Hapus Data pendaftar!', "Apakah anda yakin untuk menghapus?");
        
        return view("admin.pendaftar.index", [
            'id_lomba' => $id_lomba,
            'id_peserta' => $id_peserta,
            'data' => $data,
            'katPeserta' => KatPeserta::where('id_lomba', $id_lomba)->get(),
            'subtitle' => Lomba::find($id_lomba),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_lomba, $id_peserta)
    {
        //
        //$max = null;
        //$pendaftar = Pendaftar::orderByDesc('no_peserta')->where('id_lomba', $id_lomba)->where('id_peserta', $id_peserta)->first();
        //$no_peserta_max = $pendaftar->no_peserta;

        return view('admin.pendaftar.formulir', [
            'id_lomba' => $id_lomba,
            'id_peserta' => $id_peserta,
            'katPeserta' => KatPeserta::where('id_lomba', $id_lomba)->get(),
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
        $reqData = $request->only('id_lomba', 'id_peserta', 'no_peserta', 'nama', 'alamat', 'pic', 'telp', 'aktif', 'ketua', 'telp_ketua');
        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'id_lomba' => 'required',
            'id_peserta' => 'required',
            'no_peserta' => [
                'sometimes',
                Rule::unique('pendaftars')->where(function ($query) use($reqData) {
                    return $query->where('id_lomba', $reqData['id_lomba'])
                    ->where('no_peserta', $reqData['no_peserta']);
                }),
            ],
            'nama' => [
                'required',
                'min:3',
                Rule::unique('pendaftars')->where(function ($query) use($reqData) {
                    return $query->where('id_lomba', $reqData['id_lomba'])
                    ->where('nama', $reqData['nama']);
                }),
            ],
            'alamat' => 'sometimes|nullable|min:3',
            'pic' => 'sometimes|nullable|min:3',
            'telp' => 'sometimes|nullable|min:3',
        ],[
            'nama.required' => 'Kategori Lomba tidak boleh kosong',

            'no_peserta.unique' => 'Nomor Peserta telah terdaftar',
            
            'nama.required' => 'Nama Regu / Instansi tidak boleh kosong',
            'nama.min' => 'Nama Regu / Instansi minimal 3 Karakter',
            'nama.unique' => 'Nama Regu / Instansi telah terdaftar',

            'alamat.min' => 'Alamat minimal 3 Karakter',
            'pic.min' => 'Nama PIC minimal 3 Karakter',
            'telp.min' => 'Nomor Telp/WA minimal 3 Karakter',
        ]);

        if($reqData['no_peserta'] == ""){
            $peserta = Pendaftar::select('no_peserta')->where('id_lomba', $reqData['id_lomba'])->where('id_peserta', $reqData['id_peserta'])->orderByRaw('CONVERT(no_peserta, SIGNED) desc')->first();
            //dd($peserta);

            if($peserta){
                $reqData['no_peserta'] = $peserta->no_peserta +1;
            }else{
                $c = KatPeserta::find($reqData['id_peserta']);    

                $no_peserta = $c->no_peserta_prefix .(sprintf('%03d', $c->no_peserta_mulai));
                $reqData['no_peserta'] = $no_peserta;
            }
        }

        $reqData['verif_id'] = Auth::user()->id;

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Pendaftar::create($reqData);
        return redirect('pendaftar/'.$reqData['id_lomba'])->withSuccess('Data Pendaftaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pendaftar  $pendaftar
     * @return \Illuminate\Http\Response
     */
    public function show(pendaftar $pendaftar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pendaftar  $pendaftar
     * @return \Illuminate\Http\Response
     */
    public function edit(pendaftar $pendaftar, $id_lomba, $id)
    {
        //
        $data = $pendaftar::find($id);
        return view('admin.pendaftar.formulir', [
            'id_lomba' => $id_lomba,
            'id_peserta' => $data->id_peserta,
            'next' => 'update',
            'data' => $data,
            'katPeserta' => KatPeserta::where('id_lomba', $id_lomba)->get(),
            'title' => "Edit Pendaftar",
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pendaftar  $pendaftar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pendaftar $pendaftar)
    {
        //
        $id = $request->id;
        $id_lomba = $request->id_lomba;
        $reqData = $request->only('no_peserta', 'nama', 'alamat', 'pic', 'telp', 'aktif', 'ketua', 'telp_ketua');
        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $validator = Validator::make($reqData, [
            'no_peserta' => [
                'sometimes',
                Rule::unique('pendaftars')->where(function ($query) use($reqData, $id_lomba) {
                    return $query->where('id_lomba', $id_lomba)
                    ->where('no_peserta', $reqData['no_peserta']);
                })->ignore($id),
            ],
            'nama' => [
                'required',
                'min:3',
                Rule::unique('pendaftars')->where(function ($query) use($reqData, $id_lomba) {
                    return $query->where('id_lomba', $id_lomba)
                    ->where('nama', $reqData['nama']);
                })->ignore($id),
            ],
            'alamat' => 'sometimes|nullable|min:3',
            'pic' => 'sometimes|nullable|min:3',
            'telp' => 'sometimes|nullable|min:3',
        ],[
            'nama.required' => 'Kategori Lomba tidak boleh kosong',

            'no_peserta.unique' => 'Nomor Peserta telah terdaftar',
            
            'nama.required' => 'Nama Regu / Instansi tidak boleh kosong',
            'nama.min' => 'Nama Regu / Instansi minimal 3 Karakter',
            'nama.unique' => 'Nama Regu / Instansi telah terdaftar',

            'alamat.min' => 'Alamat minimal 3 Karakter',
            'pic.min' => 'Nama PIC minimal 3 Karakter',
            'telp.min' => 'Nomor Telp/WA minimal 3 Karakter',
        ]);

        if($reqData['no_peserta'] == ""){
            $peserta = Pendaftar::select('no_peserta')->where('id_lomba', $reqData['id_lomba'])->where('id_peserta', $reqData['id_peserta'])->orderByRaw('CONVERT(no_peserta, SIGNED) desc')->first();
            //dd($peserta);

            if($peserta){
                $reqData['no_peserta'] = $peserta->no_peserta +1;
            }else{
                $c = KatPeserta::find($reqData['id_peserta']);    

                $no_peserta = $c->no_peserta_prefix .(sprintf('%03d', $c->no_peserta_mulai));
                $reqData['no_peserta'] = $no_peserta;
            }
        }

        $reqData['verif_id'] = Auth::id();

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Pendaftar::find($id)->update($reqData);
        return redirect('pendaftar/'.$id_lomba)->withSuccess('Data Pendaftaran berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pendaftar  $pendaftar
     * @return \Illuminate\Http\Response
     */
    public function destroy(pendaftar $pendaftar, $id)
    {
        Pendaftar::find($id)->delete();
        Penilaian::where('id_pendaftar', $id)->delete();
        return redirect('pendaftar')->withSuccess('Data Pendaftaran berhasil dihapus');
    }
}
