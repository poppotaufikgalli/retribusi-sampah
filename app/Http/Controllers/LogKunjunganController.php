<?php

namespace App\Http\Controllers;

use App\Models\LogKunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

use Auth;
use DB;

class LogKunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $filter = request()->only('stgl_bayar_awal','stgl_bayar_akhir','snpwrd');

        $data = [
            'title'         => 'Log Kunjungan',
            //'currentRoute'  => request()->route()->getName(),
            'filter'        => [
                'stgl_bayar_awal'   => $filter['stgl_bayar_awal'] ?? date('Y-m-d'),
                'stgl_bayar_akhir'  => $filter['stgl_bayar_akhir'] ?? date('Y-m-d'),
                'snpwrd'            => $filter['snpwrd'] ?? '',
            ],
        ];

        $data['data'] = LogKunjungan::where(function($query)use($filter) {
            if(isset($filter['snpwrd']) && $filter['snpwrd'] != ""){
                $query->where('npwrd', $filter['snpwrd']);
            }

            if(isset($filter['stgl_bayar_awal']) && $filter['stgl_bayar_awal'] != ""){
                $query->where(DB::raw("DATE_FORMAT(tgl_kunjungan, '%Y-%m-%d')"), '>=', $filter['stgl_bayar_awal']);
            }else{
                $query->where(DB::raw("DATE_FORMAT(tgl_kunjungan, '%Y-%m-%d')"), '>=', date('Y-m-d'));
            }

            if(isset($filter['stgl_bayar_akhir']) && $filter['stgl_bayar_akhir'] != ""){
                $query->where(DB::raw("DATE_FORMAT(tgl_kunjungan, '%Y-%m-%d')"), '<=', $filter['stgl_bayar_akhir']);
            }else{
                $query->where(DB::raw("DATE_FORMAT(tgl_kunjungan, '%Y-%m-%d')"), '<=', date('Y-m-d'));
            }

        })->orderBy('created_at', 'desc')->get();

        confirmDelete('Hapus Data Log Kunjungan', "Apakah anda yakin untuk menghapus?");
        return view('admin.log_kunjungan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $reqData = $request->only('npwrd','jns','keterangan','tgl_kunjungan', 'bln', 'thn', 'no_tagihan');

        $validator = Validator::make($reqData, [
            'npwrd' => 'required|unique:log_kunjungans,npwrd,jns,tgl_kunjungan',
            'jns' => 'required',
            'tgl_kunjungan' => 'required',
        ],[
            'npwrd.required' => 'Wajib Retribusi tidak valid',
            'npwrd.unique' => 'Data Kunjungan telah terdaftar',
            'jns.required' => 'Jenis Keterangan Kunjungan tidak boleh kosong',
            'tgl_kunjungan.required' => 'Tanggal Kunjungan tidak boleh kosong',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $reqData['id_user_juru_pungut'] = Auth::id();
        LogKunjungan::create($reqData);
        return redirect()->back()->withSuccess('Data Log Kunjungan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogKunjungan  $logKunjungan
     * @return \Illuminate\Http\Response
     */
    public function show(LogKunjungan $logKunjungan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogKunjungan  $logKunjungan
     * @return \Illuminate\Http\Response
     */
    public function edit(LogKunjungan $logKunjungan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogKunjungan  $logKunjungan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogKunjungan $logKunjungan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogKunjungan  $logKunjungan
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogKunjungan $logKunjungan)
    {
        //
    }
}
