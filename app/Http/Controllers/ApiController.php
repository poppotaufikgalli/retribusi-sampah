<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\KatPeserta;
use App\Models\Pendaftar;
use App\Models\Pemilik;
use App\Models\WajibRetribusi;
use App\Models\Karcis;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\Pembayaran;
use App\Models\LogKunjungan;
use App\Models\JenisKeteranganKunjungan;

use Auth;
use DB;
use Str;

class ApiController extends Controller
{
    //
    public function api_login(Request $request)
    {
        if (! Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized',
                'ok' => false,
            ], 401);
        }

        $user = User::where('username', $request->username)->where('gid', 5)->firstOrFail();

        if($user->device_id != "" && $user->device_id != $request->device_id ){
            return response()->json([
                'message' => 'Device is Unauthorized',
                'ok' => false,
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'name' => $user->name,
            'ok' => true,
            'access_token' => $token,
        ]);
    }

    public function api_logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success',
            'ok' => true,
        ]);
    }

    public function getPemilik()
    {
        $retval = Pemilik::paginate(10);
        return response()->json($retval, 200);
    }

    public function getWajibPajak(){
        $retval['data'] = WajibRetribusi::with(['pemilik', 'objek_retribusi', 'wilayah_kerja', 'wilayah_kerja.koordinator', 'wilayah_kerja.juru_pungut'])->where('aktif', 1)->get();
        return response()->json($retval, 200);
    }

    public function getKarcis($id_registrasi_karcis=null){
        if($id_registrasi_karcis == null){
            $retval['data'] = Karcis::where('id_registrasi_karcis', $id_registrasi_karcis)->get();
        }else{
            $retval['data'] = Karcis::where('id_registrasi_karcis', $id_registrasi_karcis)->get();    
        }
        
        return response()->json($retval, 200);
    }

    public function getListTagihan(){
        $retval['data'] = Tagihan::all();
        //$retval['user'] = auth()->user();
        return response()->json($retval, 200);
    }

    public function getTransaksi(Request $request){
        $user_id = auth('sanctum')->user()->id;
        $retval['data'] = Pembayaran::with(['wajib_retribusi', 'wajib_retribusi.objek_retribusi'])
            ->where(DB::raw('DATE(tgl_bayar)'), $request['tgl_bayar'])
            ->where('id_user', $user_id)
            ->orderBy('created_at', 'desc')->get();
        $retval['total'] = $retval['data']->sum('jml');
        $retval['total_rp'] = Str::rupiah($retval['total']);
        $retval['last'] = $retval['data']->last();
        //$retval['last_rp'] = Str::rupiah($retval['last']);
        return response()->json($retval, 200);
    }

    public function getRekapTransaksi(Request $request){
        $user_id = auth('sanctum')->user()->id;
        $retval['user_id'] = $user_id;
        //$user_id = $id;
        //$request['tgl_bayar'] = '2024-11-16';
        $month = date_format(date_create($request['tgl_bayar']), 'm/Y');
        //dd($month);
        $data = Pembayaran::where(DB::raw("DATE_FORMAT(tgl_bayar, '%m/%Y')"), $month)
            ->where('id_user', $user_id)
            ->orderBy('created_at', 'desc')->get();
        $retval['bulan'] = $data->sum('jml');
        $retval['bulan_rp'] = Str::rupiah($retval['bulan']);
        $hari = $data->filter(function($item) use($request){
            return date_format(date_create($item->tgl_bayar), 'Y-m-d') == date_format(date_create($request['tgl_bayar']), 'Y-m-d');
        });
        $retval['hari'] = $hari->sum('jml') ?? 0;
        $retval['hari_rp'] = Str::rupiah($retval['hari']);
        $retval['last'] = $hari->first()->jml ?? 0;
        $retval['last_rp'] = Str::rupiah($retval['last']);
        return response()->json($retval, 200);
    }

    public function getWajibRetribusi(Request $request){
        $id_wilayah = auth('sanctum')->user()->wilayah_kerja_juru_pungut->pluck('id');
        $retval['data'] = WajibRetribusi::with(['objek_retribusi', 'karcis'])->where(function($query) use($request){
            if(isset($request->npwrd)){
                $query->where('npwrd', $request->npwrd);
            }
        //})->whereIn('id_wilayah', $id_wilayah)->where('aktif', 1)->get();
        })->where('aktif', 1)->get();
        
        return response()->json($retval, 200);
    }

    public function getWajibRetribusi2(Request $request){
        //$id_wilayah = auth('sanctum')->user()->wilayah_kerja_juru_pungut->pluck('id');
        $retval['data'] = WajibRetribusi::with(['objek_retribusi:id,nama,deskripsi,tarif'])
            ->select('id', 'npwrd', 'nama', 'id_objek_retribusi')
            ->where('aktif', 1)->get();
        
        return response()->json($retval, 200);
    }

    public function getKarcis2(Request $request){
        $user_id = auth('sanctum')->user()->id;
        $tarif = $request->tarif;
        $retval['data'] = Karcis::select('id', 'tahun', 'no_karcis_awal', 'no_karcis_akhir', 'harga', 'id_user_juru_pungut')
        ->where('id_user_juru_pungut', $user_id)
        ->where('stts', 1)
        ->where('tahun', date('Y'))
        ->where('harga', $tarif)->first();

        return response()->json($retval, 200);
    }

    public function getTagihan(Request $request){
        $user_id = auth('sanctum')->user()->id;
        $retval['user_id'] =$user_id;
        if(isset($request->id)){
            $retval['data'] = Tagihan::with(['juru_pungut', 'registrasi_karcis', 'wajib_retribusi'])->where(function($query) use ($request){
                if(isset($request->stts_byr)){
                    $query->where('stts_byr', $request->stts_byr);
                }
            })->find($request->id);
        }else{
            $retval['data'] = Tagihan::with(['juru_pungut', 'registrasi_karcis', 'wajib_retribusi'])->where(function($query) use ($request){
                if(isset($request->npwrd)){
                    $query->where('npwrd', $request->npwrd);
                }

                if(isset($request->stts_byr)){
                    $query->where('stts_byr', $request->stts_byr);
                }
            })->where('id_user_juru_pungut', $user_id)->get();
        }
        return response()->json($retval, 200);
    }

    public function getLogKunjungan(Request $request){
        $retval['data'] = LogKunjungan::with(['wajib_retribusi', 'jenis_keterangan'])->where('npwrd', $request['npwrd'])->orderBy('created_at', 'desc')->get();
        return response()->json($retval, 200);
    }

    public function pembayaran(Request $request){
        $retval['data'] = Pembayaran::where('npwrd', $request['npwrd'])
            ->where('thn', $request['thn'])
            ->where('bln', $request['bln'])
            ->where('tgl', $request['tgl'])
            ->first();
        return response()->json($retval, 200);
    }

    public function pembayaran_store(Request $request){
        $reqData = $request->only('id_wr', 'npwrd', 'jns', 'tipe', 'tgl', 'bln', 'thn', 'jml', 'denda', 'total', 'tgl_bayar','id_karcis', 'no_karcis', 'file');
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
            $retval['ok'] = false;
            $retval['msg'] = $validator->messages()->all()[0];
            return response()->json($retval, 200);
        }

        if($reqData['jns'] == 3){
            Tagihan::find($reqData['id_karcis'])->update(['stts' => 1]);
            if($request->hasFile('file'))
            {
                $filenameWithExt    = $request->file('file')->getClientOriginalName();
                $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension          = $request->file('file')->getClientOriginalExtension();
                $fileNameToStore    = $reqData['id_karcis']."_".$reqData['bln']."_".$reqData['thn'].".".$extension;
                $path               = $request->file('file')->storeAs('public/penyerahan/', $fileNameToStore);
                $retval['gbr']      = $path;
            } 
            LogKunjungan::create([
                'npwrd' => $reqData['npwrd'],
                'jns' => 4,
                'keterangan' => 'Penyerahan SKRD No.'.$reqData['no_karcis'],
                'tgl_kunjungan' => $reqData['tgl_bayar'], 
                'bln' => $reqData['bln'], 
                'thn' => $reqData['thn'], 
                'no_tagihan' => $reqData['no_karcis'],
                'id_user_juru_pungut' => auth('sanctum')->user()->id,
            ]);
        }else{
            $reqData['id_user'] = auth('sanctum')->user()->id;
        
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
                $retval['gbr']      = $path;
            } 
        }

        $retval['data'] = $request->all();
        $retval['ok'] = true;
        return response()->json($retval, 200);
    }

    public function getUserInfo(Request $request){
        $id = auth('sanctum')->user()->id;
        $retval['data'] = User::with(['wilayah_kerja_juru_pungut', 'karcis'])->find($id);

        return response()->json($retval, 200);
    }

    public function getJnsKeteranganKunjungan(){
        $retval['data'] = JenisKeteranganKunjungan::all();

        return response()->json($retval, 200);
    }

    public function kunjungan_store(Request $request)
    {
        //
        $reqData = $request->only('id_wr', 'npwrd','jns','keterangan','tgl_kunjungan');
        //$retval['ok'] = true;
        // /return response()->json($reqData, 200);

        $validator = Validator::make($reqData, [
            'id_wr' => [
                'required',
                Rule::unique('log_kunjungans')->where(function ($query) use($reqData) {
                    return $query->where('id_wr', $reqData['id_wr'])
                    //->where('jns', $reqData['jns'])
                    ->where('jns', $reqData['jns'])
                    ->where('tgl_kunjungan', $reqData['tgl_kunjungan']);
                }),
            ],
            //'npwrd' => 'required|unique:log_kunjungans,npwrd,jns,tgl_kunjungan',
            'jns' => 'required',
            'tgl_kunjungan' => 'required',
        ],[
            'id_wr.required' => 'Pembayaran tidak valid / npwrd tidak valid',
            'id_wr.unique' => 'Pembayaran sudah pernah dilakukan',
            //'npwrd.required' => 'Wajib Retribusi tidak valid',
            //'npwrd.unique' => 'Data Kunjungan telah terdaftar',
            'jns.required' => 'Jenis Keterangan Kunjungan tidak boleh kosong',
            'tgl_kunjungan.required' => 'Tanggal Kunjungan tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            $retval['ok'] = false;
            $retval['msg'] = $validator->messages()->all()[0];
            return response()->json($retval, 200);
        }
        
        $reqData['id_user_juru_pungut'] = auth('sanctum')->user()->id;
        $idx = LogKunjungan::create($reqData)->id;

        if($request->hasFile('file'))
        {
            $filenameWithExt    = $request->file('file')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore    = $idx."_kunjungan.".$extension;
            $path               = $request->file('file')->storeAs('public/kunjungan/', $fileNameToStore);
            $retval['gbr']      = $path;
        } 

        $retval['data'] = $request->all();
        $retval['ok'] = true;
        return response()->json($retval, 200);
    }

    public function device_update(Request $request)
    {
        $id = auth('sanctum')->user()->id;
        $device_id = $request->device_id;
        $user = User::find($id)->update(['device_id' => $device_id]);
        $retval['data'] = $user;
        $retval['device_id'] = $device_id;
        $retval['ok'] = true;
        return response()->json($retval, 200);
    }
}
