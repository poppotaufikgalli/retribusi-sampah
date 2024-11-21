<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Lomba;
use App\Models\Pendaftar;
use App\Models\JuriKategori;
use App\Models\User;
use App\Models\KatPeserta;
use App\Models\Diskualifikasi;

use Auth;
use DB;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $data = Pendaftar::where(function($query) use($id){
            if($id > 0){
                $query->where('id_lomba', $id);
            }
        })->orderByRaw('cast(no_peserta as unsigned)')->get();

        $lomba = Lomba::find($id);

        $id_juri = Auth::id();
        //dd($id_juri);
        $a = Penilaian::select(
            'id_pendaftar',
            'id_nilai',
            DB::raw('sum(nilai) as sum_nilai'),
            DB::raw('count(nilai) as count_nilai'),
            //DB::raw('group_concat(id_juri, ":", nilai) as pos_nilai'),
            DB::raw('sum(case id_juri when '.$id_juri.' then nilai else 0 end) as pos_nilai'),
        )->groupBy(['id_pendaftar', 'id_nilai'])->get();

        $dataPenilaian = [];

        foreach ($a as $key => $value) {
            if($value->id_nilai == 1){
                $dataPenilaian[$value->id_pendaftar][$value->id_nilai] = $value->sum_nilai;
            }else{
                if($value->count_nilai == $lomba->jml_pos){
                    $dataPenilaian[$value->id_pendaftar][$value->id_nilai] = $value->sum_nilai / $lomba->jml_pos;
                }else{
                    $dataPenilaian[$value->id_pendaftar][$value->id_nilai] = $value->sum_nilai / $lomba->jml_pos ." [".$value->count_nilai."/".$lomba->jml_pos."]";
                }    
            }
        }

        return view("admin.penilaian.index", [
            'id' => $id,
            'data' => $data,
            'subtitle' => $lomba,
            //'posJuri' => JuriKategori::where('id_lomba', $id)->get(),
            'penilaian' => $dataPenilaian,
            //'diskualifikasi' => $diskualifikasi->toArray(),
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
        return view('admin.penilaian.formulir', [
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
            return redirect()->route('penilaian.show', ['id'=> $id]);
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
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function show(Penilaian $penilaian, $id)
    {
        //
        $data = Pendaftar::find($id);
        $katPeserta = KatPeserta::select('ref_kecepatan')->find($data->id_peserta);
        //dd($katPeserta);
        
        $waktu_referensi = 0;
        if($data->id_lomba == 1){
            $waktu_referensi = (8 / $katPeserta->ref_kecepatan) * 3600;
        }else{
            $waktu_referensi = (17 / $katPeserta->ref_kecepatan) * 3600;
        }

        $a = $penilaian->where('id_pendaftar', $id)->get();

        $dataPenilaian = [];

        foreach ($a as $key => $value) {
            $dataPenilaian[$value->id_nilai][$value->id_juri] = $value->nilai;
        }

        $selisih = $data->waktu_tempuh - $waktu_referensi;
        $menit = intVal($selisih/60);
        $detik = $selisih % 60;
        $selisih = $detik > 5 ? $menit +1 : $menit;

        return view('admin.penilaian.formulir', [
            'data' => $data,
            'ref_kecepatan' => $katPeserta->ref_kecepatan,
            'waktu_referensi_1' => gmdate("H:i:s", $waktu_referensi),
            'waktu_referensi' => $waktu_referensi,
            'posJuri' => JuriKategori::whereHas('juri', function($query){
                $query->where('gid', 2)->where('aktif',1);
            })->where('id_lomba', $data->id_lomba)->get(),
            'penilaian' => $dataPenilaian,
            'selisih' => $selisih,
            'next' => 'update',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function edit(Penilaian $penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penilaian $penilaian)
    {
        //
        //dd($request->all());
        /*$id = $request->id;
        $id_lomba = $request->id_lomba;
        $reqData = $request->only('waktu_start', 'waktu_finish');

        if($request->jns == 1){
            $validator = Validator::make($reqData, [
                'waktu_start' => 'required',
                'waktu_finish' => 'sometimes|nullable|after:waktu_start',
            ],[
                'waktu_start.required' => 'Waktu start tidak boleh kosong',
                'waktu_finish.after' => 'Waktu finish tidak boleh sebelum waktu start',
            ]);

            if($validator->fails())
            {
                return back()->with('errors', $validator->messages()->all()[0])->withInput();
            }
            
            Pendaftar::find($id)->update($reqData);
            return redirect('penilaian/'.$id_lomba)->withSuccess('Pencatatan Waktu berhasil ditambahkan');
        }*/
    }

    public function update_diskualifikasi(Request $request)
    {
        //
        //dd($request->all());

    }

    public function update_waktu(Request $request)
    {
        //
        //dd($request->all());
        $id = $request->id;
        $id_nilai = $request->id_nilai;
        $id_juri = $request->id_juri;
        $waktu_referensi = $request->waktu_referensi;

        $reqData = $request->only('waktu_start', 'waktu_finish');

        $validator = Validator::make($reqData, [
            'waktu_start' => 'required',
            'waktu_finish' => 'sometimes|nullable|after:waktu_start',
        ],[
            'waktu_start.required' => 'Waktu start tidak boleh kosong',
            'waktu_finish.after' => 'Waktu finish tidak boleh sebelum waktu start',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        Pendaftar::find($id)->update($reqData);

        //hitung waktu
        if($reqData['waktu_start'] != null && $reqData['waktu_finish'] != null){
            $pendaftar = Pendaftar::find($id);
            $selisih = $pendaftar->waktu_tempuh - $waktu_referensi;

            $nilai = 100;
            $menit = intVal($selisih / 60);
            $detik = $selisih % 60;
            $menit = $detik > 5 ? $menit+1 : $menit;
            //dd($selisih, $menit, $detik);
            if($selisih > 0){
                $nilai = $nilai - ($menit *2);
            }else{
                $nilai = $nilai + $menit ;
            }

            $nilai = $nilai < 0 ? 0 : $nilai;

            Penilaian::upsert([
                [
                    'id_pendaftar' => $id, 
                    'id_juri' => $id_juri,
                    'id_nilai' => $id_nilai,
                    'nilai' => $nilai,
                    'uid' => Auth::id(),
                ]
            ], uniqueBy: ['id_pendaftar', 'id_nilai'], update: ['nilai', 'uid']);
        }

        $this->hitungTotal($id, $request->jml_pos);

        return redirect()->back()->withSuccess('Pencatatan Waktu berhasil ditambahkan');
    }

    public function update_pos(Request $request)
    {
        //
        //dd($request->all());
        $id = $request->id;
        //$id_nilai = $request->id_nilai;
        $id_juri = $request->id_juri;
        $jml_pos = $request->jml_pos;
        //$waktu_referensi = $request->waktu_referensi;

        $reqData = $request->only('nilai_2', 'nilai_3', 'nilai_4');

        $validator = Validator::make($reqData, [
            'nilai_2' => 'required|numeric',
            'nilai_3' => 'required|numeric',
            'nilai_4' => 'required|numeric',
        ],[
            'nilai_2.required' => 'Nilai Keutuhan Barisan tidak boleh kosong',
            'nilai_2.numeric' => 'Nilai Keutuhan Barisan tidak valid',

            'nilai_3.required' => 'Nilai Keutuhan Barisan tidak boleh kosong',
            'nilai_3.numeric' => 'Nilai Keutuhan Barisan tidak valid',

            'nilai_4.required' => 'Nilai Keutuhan Barisan tidak boleh kosong',
            'nilai_4.numeric' => 'Nilai Keutuhan Barisan tidak valid',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        //Pendaftar::find($id)->update($reqData);

        //hitung nilai
        Penilaian::upsert([
                [
                    'id_pendaftar' => $id, 
                    'id_juri' => $id_juri,
                    'id_nilai' => 2,
                    'nilai' => $reqData['nilai_2'],
                    'uid' => Auth::id(),
                ],
                [
                    'id_pendaftar' => $id, 
                    'id_juri' => $id_juri,
                    'id_nilai' => 3,
                    'nilai' => $reqData['nilai_3'],
                    'uid' => Auth::id(),
                ],
                [
                    'id_pendaftar' => $id, 
                    'id_juri' => $id_juri,
                    'id_nilai' => 4,
                    'nilai' => $reqData['nilai_4'],
                    'uid' => Auth::id(),
                ],
            ], uniqueBy: ['id_pendaftar', 'id_nilai'], update: ['nilai', 'uid']);

        $this->hitungTotal($id, $request->jml_pos);

        return redirect()->back()->withSuccess('Pencatatan Nilai Pos berhasil ditambahkan');
    }

    private function hitungTotal($id_pendaftar, $jml_pos)
    {
        $a = Penilaian::select(
            'id_pendaftar',
            'id_nilai',
            DB::raw('sum(nilai) as sum_nilai'),
        )->where('id_pendaftar', $id_pendaftar)->groupBy(['id_pendaftar', 'id_nilai'])->pluck('sum_nilai', 'id_nilai');

        $total = (isset($a[1]) ? $a[1] : 0) + (isset($a[2]) ? $a[2]/$jml_pos : 0) + (isset($a[3]) ? $a[3]/$jml_pos : 0) + (isset($a[4]) ? $a[4]/$jml_pos : 0);

        Pendaftar::where('id', $id_pendaftar)->update(['total' => $total]);
    }

    public function update_ulang($id_pendaftar, $jml_pos)
    {
        $this->hitungTotal($id_pendaftar, $jml_pos);

        return redirect()->back()->withSuccess('Pencatatan Nilai telah dihitung ulang');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penilaian  $penilaian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penilaian $penilaian)
    {
        //
    }
}
