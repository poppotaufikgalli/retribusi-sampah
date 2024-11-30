<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\Lomba;
use App\Models\KatPeserta;
use App\Models\Konfig;
use App\Models\PosJuri;
use App\Models\Penilaian;
use App\Models\User;
use App\Models\Pembayaran;
use App\Models\Karcis;
use App\Models\Wilayah;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;

use DB;
use App\Rules\ReCaptcha;
use PDF;

class MainController extends Controller
{
    public function index()
    {
        return view("main");
    }

    public function main($tahun=null){
        $selTahun = $tahun == null  ? date('Y') : $tahun;

        $p_thn = Pembayaran::selectRaw('year(tgl_bayar) as tahun, sum(total) as total_tahun')->whereRaw('year(tgl_bayar) = "'. $selTahun.'"')->groupBy('tahun')->orderBy('tahun', 'desc')->pluck('total_tahun', 'tahun');
        $p_bln = Pembayaran::selectRaw('year(tgl_bayar) as tahun, month(tgl_bayar) as bulan, sum(total) as total_bulan')->whereRaw('year(tgl_bayar) ="'.$selTahun.'"')->groupBy('tahun', 'bulan')->orderBy('tahun', 'desc')->pluck('total_bulan', 'bulan');
        $p_today = Pembayaran::selectRaw('Date(tgl_bayar) as tgl_bayar, sum(total) as total_today')->whereRaw('Date(tgl_bayar) = "'. date('Y-m-d').'"')->groupBy('tgl_bayar')->pluck('total_today', 'tgl_bayar');
        $d_today = Pembayaran::whereRaw('Date(tgl_bayar) = "'. date('Y-m-d').'"')->get();
        $selData = Konfig::where('tahun', $selTahun)->first();

        return view('admin.main', [
            'p_thn' => $p_thn,
            'p_bln' => $p_bln,
            'p_today' => $p_today,
            'd_today' => $d_today,
            'data' => Konfig::all(),
            'selTahun' => $selTahun,
            'selData' => $selData,
        ]);
    }

    public function main2($tahun=null){
        $selTahun = $tahun == null  ? date('Y') : $tahun;

        $d_karcis = Karcis::all();
        $d_jungut = User::with(['wilayah_kerja_juru_pungut', 'karcis'])->where('gid', 5)->get();
        
        return view('admin.main2', [
            'd_wilayah' => Wilayah::all(),
            'd_jungut' => $d_jungut,
            //'m_karcis' => $m_karcis,
        ]);
    }

    public function daftarPeserta($id=0)
    {
        return view("daftarPeserta", [
            "selPeserta" => KatPeserta::find($id),
            "selid" => $id,
            "katPeserta" => KatPeserta::all(),
            'data' => Pendaftar::where('id_peserta', $id)->orderByRaw('CONVERT(no_peserta, SIGNED) desc')->get(),
        ]);
    }

    public function formPendaftaranPeserta($id_lomba, $id_peserta=null)
    {
        $data = Konfig::where('aktif', 1)->first();

        $now = strtotime(date("Y-m-d H:i:s"));
        $tgl_buka = strtotime($data->tgl_buka);
        $tgl_tutup = strtotime($data->tgl_tutup);

        //dd($now, $tgl_buka, $tgl_tutup);
        //dd($now >= $tgl_buka, $now <= $tgl_tutup, date("Y-m-d H:i:s",$now), date("Y-m-d H:i:s",$tgl_buka));

        if($now >= $tgl_buka && $now <= $tgl_tutup) {
            return view("formPendaftaranPeserta", [
                'id_lomba' => $id_lomba,
                'id_peserta' => $id_peserta,
                'lomba' => Lomba::find($id_lomba),
                'katPeserta' => KatPeserta::where('id_lomba', $id_lomba)->get(),
            ]);
        } else {
            if($now < $tgl_buka) {
                return redirect()->route('index')->with('errors', "Pendaftaran Belum Dibuka, Agar mendaftar kembali setelah Pendaftaran dibuka"); 
            }else if($now > $tgl_tutup){
                return redirect()->route('index')->with('errors', "Pendaftaran Telah Ditutup"); 
            }else{
                return redirect()->route('index')->with('errors', "Pendaftaran Tidak Tersedia"); 
            }
        }    
    }

    public function daftarUmum(Request $request)
    {
        //dd($request);
        $reqData = $request->only('id_lomba', 'id_peserta', 'nama', 'alamat', 'pic', 'telp', 'ketua', 'telp_ketua','jns_instansi', 'g-recaptcha-response');
        //dd($reqData);

        $reqData['aktif'] = 1;

        $validator = Validator::make($reqData, [
            'id_lomba' => 'required',
            'id_peserta' => 'required',
            'nama' => [
                'required',
                'min:3',
                Rule::unique('pendaftars')->where(function ($query) use($reqData) {
                    return $query->where('id_lomba', $reqData['id_lomba'])
                    ->where('nama', $reqData['nama']);
                }),
            ],
            'alamat' => 'required|min:3',
            'pic' => 'required|min:3',
            'telp' => 'required|numeric|min:3',
            'ketua' => 'required|min:3',
            'telp_ketua' => 'required|numeric|min:3',
            'g-recaptcha-response' => ['required', new ReCaptcha],
        ],[
            'id_lomba.required' => 'Kategori Lomba tidak boleh kosong',
            'id_peserta.required' => 'Kategori Peserta tidak boleh kosong',

            'no_peserta.unique' => 'Nomor Peserta telah terdaftar',
            
            'nama.required' => 'Nama Regu / Instansi tidak boleh kosong',
            'nama.min' => 'Nama Regu / Instansi minimal 3 Karakter',
            'nama.unique' => 'Nama Regu / Instansi telah terdaftar',

            'alamat.required' => 'Alamat tidak boleh kosong',
            'alamat.min' => 'Alamat minimal 3 Karakter',

            'pic.required' => 'Nama PIC tidak boleh kosong',
            'pic.min' => 'Nama PIC minimal 3 Karakter',

            'telp.required' => 'Nomor Telp/WA PIC tidak boleh kosong',
            'telp.numeric' => 'Nomor Telp/WA PIC tidak valid',
            'telp.min' => 'Nomor Telp/WA PIC minimal 3 Karakter',

            'ketua.required' => 'Nama Ketua tidak boleh kosong',
            'ketua.min' => 'Nama Ketua minimal 3 Karakter',

            'telp_ketua.required' => 'Nomor Telp/WA Ketua Regu tidak boleh kosong',
            'telp_ketua.numeric' => 'Nomor Telp/WA Ketua Regu tidak valid',
            'telp_ketua.min' => 'Nomor Telp/WA Ketua Regu minimal 3 Karakter',
            'g-recaptcha-response.required' => 'Google ReCaptcha tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        //$peserta = Pendaftar::where('id_lomba', $reqData['id_lomba'])->where('id_peserta', $reqData['id_peserta'])->get();
        $reqData['no_peserta'] = $this->getNomorPeserta($reqData);
        //dd($reqData['no_peserta']);

        Pendaftar::create($reqData);
        return redirect('daftar-peserta/'.$reqData['id_peserta'])->withSuccess('Pendaftar telah berhasil dilakukan');
    }

    public function rekapHasil($id_peserta=null)
    {
        $data = Pendaftar::where(function($query) use ($id_peserta){
            if($id_peserta != null){
                $query->where('id_peserta', $id_peserta);
            }
        })->orderBy('total', 'desc')->get();
        $katPeserta = KatPeserta::all();

        $a = Penilaian::select(
            'id_pendaftar',
            'id_nilai',
            DB::raw('sum(nilai) as sum_nilai'),
            DB::raw('count(nilai) as count_nilai'),
        )->groupBy(['id_pendaftar', 'id_nilai'])->get();

        $dataPenilaian = [];

        foreach ($a as $key => $value) {
            if($value->id_nilai == 1){
                $dataPenilaian[$value->id_pendaftar][$value->id_nilai] = $value->sum_nilai;
            }else{
                /*if($value->count_nilai == $lomba->jml_pos){
                    $dataPenilaian[$value->id_pendaftar][$value->id_nilai] = $value->sum_nilai / $lomba->jml_pos;
                }else{
                    $dataPenilaian[$value->id_pendaftar][$value->id_nilai] = $value->sum_nilai / $lomba->jml_pos ." [".$value->count_nilai."/".$lomba->jml_pos."]";
                } */
                $dataPenilaian[$value->id_pendaftar][$value->id_nilai] = $value->sum_nilai / 10;   
            }
        }
        
        return view('admin.rekapHasil', [
            'data' => $data,
            'katPeserta' => $katPeserta,
            'id_peserta' => $id_peserta,
            'penilaian' => $dataPenilaian,
        ]);
    }

    public function rekapPos($id_lomba=null, $id_juri=null)
    {
        $posJuri = User::whereHas('juri_kategori', function($query) use($id_lomba){
            $query->where('juri_kategoris.id_lomba', $id_lomba);
        })->where('gid', 2)->where('aktif', 1)->get();

        $data = KatPeserta::with('pendaftar')->with('pendaftar.penilaian')->where('id_lomba', $id_lomba)->get();

        if($data && $id_juri != null){
            foreach ($data as $key => $value) {
                if($value->pendaftar){
                    $sudah = [];
                    $belum = [];
                    $pendaftar = $value->pendaftar;
                    foreach ($pendaftar as $k => $v) {
                        $arr_id_juri = $v->penilaian->pluck('id_juri');

                        if(in_array($id_juri, $arr_id_juri->toArray())){
                            $sudah[] = $v->no_peserta;
                        }else{
                            $belum[] = $v->no_peserta;
                        }
                    }

                    $value['sudah'] = implode(", ", $sudah);
                    $value['belum'] = implode(", ", $belum);
                }
            }
        }
        
        return view('admin.rekapPos', [
            'data' => $data,
            'posJuri' => $posJuri,
            'id_lomba' => $id_lomba,
            'id_juri' => $id_juri,
        ]);
    }

    private function getNomorPeserta($data){
        //dd($data);
        if(isset($data['jns_instansi']) && $data['jns_instansi'] != ""){
            $no_peserta = "100".$data['jns_instansi'];
            $a = Pendaftar::where('no_peserta', $no_peserta)->where('id_lomba', $data['id_lomba'])->where('id_peserta', $data['id_peserta'])->get();
            
            if(count($a) == 0){
                return $no_peserta;
            }else{
                $b = Pendaftar::select('no_peserta')->where('id_lomba', $data['id_lomba'])->where('id_peserta', $data['id_peserta'])->orderByRaw('CONVERT(no_peserta, SIGNED) asc')->get();
                foreach($b as $key => $value){
                    $no_peserta = $value->no_peserta +1;    
                }

                if($no_peserta < 1005){
                    $no_peserta = 1005;
                }

                return $no_peserta;
            }
        }

        $peserta = Pendaftar::select('no_peserta')->where('id_lomba', $data['id_lomba'])->where('id_peserta', $data['id_peserta'])->orderByRaw('CONVERT(no_peserta, SIGNED) asc')->get();
        //dd($peserta);

        if(count($peserta) > 0){
            foreach($peserta as $key => $value){
                $no_peserta = $value->no_peserta +1;    
            }
            return $no_peserta;
        }else{
            $c = KatPeserta::find($data['id_peserta']);    

            $no_peserta = $c->no_peserta_prefix .(sprintf('%03d', $c->no_peserta_mulai));
            return $no_peserta;
        }
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed'],
        ],[
            'password.required' => "Password tidak boleh kosong",
            'password.confirmed' => "Password tidak sama"
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->withSuccess('Password berhasil diubah. Silahkan login kembali untuk mencoba password baru');
    }

    public function resetPasswordUser(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed'],
        ],[
            'password.required' => "Password tidak boleh kosong",
            'password.confirmed' => "Password tidak sama"
        ]);

        $findUser = User::find($request->uid);

        if($findUser){
            $findUser->update([
                'password' => Hash::make($validated['password']),
            ]);
        }else{
            return back()->withError('Terjadi Kesalahan. Silahkan Ulangi Proses');
        }

        return back()->withSuccess('Password User berhasil diubah');
    }
}
