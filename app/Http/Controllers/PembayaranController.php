<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\WajibRetribusi;
use App\Models\Karcis;
use App\Models\Tagihan;
use App\Models\TipePembayaran;
use App\Models\JenisKeteranganKunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

use Auth;
use DB;
use Storage;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $filter = request()->only('stgl_bayar_awal','stgl_bayar_akhir','snpwrd');

        $data = [
            'title'         => 'Pembayaran',
            'currentRoute'  => request()->route()->getName(),
            'filter'        => [
                'stgl_bayar_awal'   => $filter['stgl_bayar_awal'] ?? date('Y-m-d'),
                'stgl_bayar_akhir'  => $filter['stgl_bayar_akhir'] ?? date('Y-m-d'),
                'snpwrd'            => $filter['snpwrd'] ?? '',
            ],
        ];

        if (request()->route()->named('pembayaran.verifikasi')) {
            $data['title'] = "Verifikasi Pembayaran";
        }elseif (request()->route()->named('pembayaran.batal')) {
            $data['title'] = "Pembatalan Pembayaran";
        }

        $pembayaran = Pembayaran::where(function($query)use($filter) {
            if(isset($filter['snpwrd']) && $filter['snpwrd'] != ""){
                $query->where('npwrd', $filter['snpwrd']);
            }

            if(isset($filter['stgl_bayar_awal']) && $filter['stgl_bayar_awal'] != ""){
                //$query->where('tgl_bayar', '>=', $filter['stgl_bayar_awal']);
                $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), '>=', $filter['stgl_bayar_awal']);
            }else{
                //$query->where('tgl_bayar', '>=', date('Y-m-d'));
                $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), '>=', date('Y-m-d'));
            }

            if(isset($filter['stgl_bayar_akhir']) && $filter['stgl_bayar_akhir'] != ""){
                //$query->where('tgl_bayar', '<=', $filter['stgl_bayar_akhir']);
                $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), '<=', $filter['stgl_bayar_akhir']);
            }else{
                //$query->where('tgl_bayar', '<=', date('Y-m-d'));
                $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), '<=', date('Y-m-d'));
            }

        })->orderBy('created_at', 'desc')->get();

        //dd($pembayaran);

        $data['data'] = $pembayaran;

        confirmDelete('Batal Data Pembayaran!', "Apakah anda yakin untuk membatalkan pembayaran?");
        
        return view('admin.pembayaran.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($npwrd=null)
    {
        //
        $id_user = Auth::id();
        $currentRoute = request()->route()->getName();
        if($currentRoute == 'pembayaran.bulanan'){
            $jns = "bulanan";
            //$wr = ;

            $data = [
                'next' => 'store',
                'title' => 'Tambah Pembayaran '.ucfirst($jns),
                'jns' => $jns,
                'wr' => WajibRetribusi::join('objek_retribusis', 'objek_retribusis.id', 'wajib_retribusis.id_objek_retribusi')->leftJoin('pemiliks', 'pemiliks.id', 'wajib_retribusis.id_pemilik')->join('wilayahs', 'wilayahs.id', 'wajib_retribusis.id_wilayah')->select('wajib_retribusis.id', 'wajib_retribusis.npwrd', 'wajib_retribusis.nama', 'objek_retribusis.id as id_objek_retribusi', 'pemiliks.id as id_pemilik', 'wajib_retribusis.id_wilayah')->where(function($query){
                    if(Auth::user()->gid == '5'){
                        $id_wilayah = Auth::user()->wilayah_kerja_juru_pungut->pluck('id');
                        $query->whereIn('id_wilayah', $id_wilayah);
                    }
                })->get(),
            ];
            if($npwrd != null){
                $wr = WajibRetribusi::find($npwrd);
                $data['data'] = $wr;
                $data['karcis'] = Karcis::with(['juru_pungut'])
                ->select('id', 'no_karcis_awal', 'no_karcis_akhir', 'harga', 'id_user_juru_pungut')->where(function($query){
                    if(Auth::user()->gid == '5'){
                        $query->where('id_user_juru_pungut', Auth::id());
                    }
                })->where('stts', 1)->where('harga', $wr->objek_retribusi->tarif)->get();
                //dd($data['karcis']);
            }
            
        }elseif($currentRoute == 'pembayaran.tagihan'){
            $jns = "tagihan";
            $data = [
                'next' => 'store',
                'title' => 'Tambah Pembayaran '.ucfirst($jns),
                'jns' => $jns,
                //'data' => $wr,
                'tagihan' => Tagihan::where('stts_byr', 0)->where(function($query){
                    if(Auth::user()->gid == '5'){
                        $query->where('id_user_juru_pungut', Auth::id());
                    }
                })->get(),
            ];
            if($npwrd != null){
                $data['data'] = Tagihan::find($npwrd);
            }

        }elseif($currentRoute == 'pembayaran.insidentil'){
            $jns = "insidentil";
            $data = [
                'next' => 'store',
                'title' => 'Tambah Pembayaran '.ucfirst($jns),
                'jns' => $jns,
                'wr' => WajibRetribusi::with(['objek_retribusi'])->where(function($query){
                    if(Auth::user()->gid == '5'){
                        $id_wilayah = Auth::user()->wilayah_kerja_juru_pungut->pluck('id');
                        $query->whereIn('id_wilayah', $id_wilayah);
                    }
                })->whereRelation('objek_retribusi', 'insidentil', '=', 1)->get(),
            ];
            if($npwrd != null){
                $wr = WajibRetribusi::find($npwrd);
                $data['data'] = $wr;
                $data['karcis'] = Karcis::with(['juru_pungut'])
                ->select('id', 'no_karcis_awal', 'no_karcis_akhir', 'harga', 'id_user_juru_pungut')->where(function($query){
                    if(Auth::user()->gid == '5'){
                        $query->where('id_user_juru_pungut', Auth::id());
                    }
                })->where('stts', 1)->where('harga', $wr->objek_retribusi->tarif)->get();
                //dd($data['karcis']);
            }
        }

        $data['tipe_pembayaran'] = TipePembayaran::all();
        $data['listKeterangan'] = JenisKeteranganKunjungan::pluck('nama', 'id');
        //dd($currentRoute);
        return view('admin.pembayaran.formulir.'.$jns, $data);
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
        $reqData = $request->only('npwrd', 'jns', 'tipe', 'tgl', 'bln', 'thn', 'jml', 'denda', 'total', 'tgl_bayar','id_karcis', 'no_karcis', 'file');
        $reqData['id_wr'] = $request->id;
        $reqData['tgl_bayar'] = $reqData['tgl_bayar']."T00:00:00Z";
        //dd($reqData);
        $validator = Validator::make($reqData, [
            //'npwrd' => 'required|unique:pembayarans,npwrd,bln,thn',
            'id_wr' => [
                'required',
                Rule::unique('pembayarans')->where(function ($query) use($reqData) {
                    return $query->where('id_wr', $reqData['id_wr'])
                    //->where('jns', $reqData['jns'])
                    ->where('tgl', $reqData['tgl'])
                    ->where('bln', $reqData['bln'])
                    ->where('thn', $reqData['thn']);
                }),
            ],
            'tipe' => 'required',
            'bln' => 'required',
            'thn' => 'required',
            'total' => 'required',
            'no_karcis' => [
                'required',
                Rule::unique('pembayarans')->where(function ($query) use($reqData) {
                    return $query->where('no_karcis', $reqData['no_karcis'])
                    ->where('thn', $reqData['thn'])
                    ->where('jml', $reqData['jml']);
                }),
            ],
            'file' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ],[
            'id_wr.required' => 'Pembayaran tidak valid / npwrd tidak valid',
            'id_wr.unique' => 'Pembayaran sudah pernah dilakukan',
            'tipe.required' => 'Tipe Pembayaran tidak boleh kosong',
            'bln.required' => 'Bulan tidak boleh kosong',
            'thn.required' => 'Tahun tidak boleh kosong',
            'total.required' => 'Total Pembayaran tidak valid / tidak boleh kosong',
            'no_karcis.required' => 'Nomor Karcis tidak valid',
            'no_karcis.unique' => 'Nomor Karcis sudah terdaftar',
            'file.image' => 'File karcis / bukti bayar bukan gambar',
            'file.mime' => 'File karcis / bukti bayar tidak sesuai format',
            'file.max' => 'File karcis / bukti bayar melebihi ukuran yang ditentukan',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $reqData['id_user'] = Auth::id();

        $result = Pembayaran::create($reqData);

        if($reqData['jns'] == 2){
            Tagihan::find($reqData['id_karcis'])->update(['stts_byr' => 1, 'stts' => 1]);
        }
        
        if($request->hasFile('file'))
        {
            $filenameWithExt    = $request->file('file')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore    = $result->id.".".$extension;
            $path               = $request->file('file')->storeAs('public/pembayaran/', $fileNameToStore);    
            //$reqData['gbr']     = $fileNameToStore;                        
        } 

        return redirect('pembayaran')->withSuccess('Data Pembayaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pembayaran $pembayaran, $id=0)
    {
        //
        $file = $id.'.jpeg';
        //dd(Storage::exists('public/pembayaran/'.$file));
        if (Storage::exists('public/pembayaran/'.$file) == false) {
            //Storage::delete($file);
            $file = $id.'.jpg';
        }

        return view('admin.pembayaran.show', [
            'next'  => 'update',
            'title' => 'Verifikasi  Pembayaran',
            'data'  => $pembayaran->find($id),
            'file'  => $file,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        //
        if($request->tipe == 'verif'){
            $reqData = $request->only('id', 'verif', 'no_karcis');
            $reqData['verif_id'] = Auth::id();

            $pembayaran->find($reqData['id'])->update($reqData);

            return redirect('pembayaran/verifikasi')->withSuccess('Data pembayaran berhasil diverifikasi');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pembayaran $pembayaran, $id)
    {
        //
        $data = $pembayaran->find($id);

        if($data->jns == 2){
            Tagihan::find($data->id_karcis)->update(['stts_byr' => 0]);
        }

        $data->delete();

        return redirect('pembayaran')->withSuccess('Data pembayaran berhasil dihapus');
    }
}
