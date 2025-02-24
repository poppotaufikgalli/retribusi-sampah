<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pembayaran;
use App\Models\User;
use App\Models\JenisRetribusi;
use App\Models\ObjekRetribusi;
use App\Models\WajibRetribusi;
use App\Models\Karcis;
use App\Models\Tagihan;
use App\Models\Pengembalian;

use DB;
use Pdf;

class LaporanController extends Controller
{
    //
    public function piutangTagihan(Request $request){
        $reqData = $request->only('npwrd', 'tgl1', 'bln1', 'stglbln');
        $data = Tagihan::where('stts_byr', 0)->where(function($query) use($request){
            if(isset($request->npwrd)){
                $query->where('npwrd', '=', $request->npwrd);
            }

            if(isset($request->tgl1) && $request->stglbln == 'tgl'){
                $query->where(DB::raw("DATE_FORMAT(tgl_skrd, '%Y-%m-%d')"), $request->tgl1);
            }

            if(isset($request->bln1) && $request->stglbln == 'bln'){
                $query->where(DB::raw("DATE_FORMAT(tgl_skrd, '%Y-%m')"), $request->bln1);
            }
        })->get();

        return view('admin.laporan.piutang.perTagihan', [
            'data' => $data,
            'title' => 'Piutang Per Tagihan',
            'reqData' => $reqData, 
            'tgl1' => $request->tgl1 ? $request->tgl1 : '',
            'tgl2' => $request->tgl2 ? $request->tgl2 : '',
        ]);
    }

    private $listLaporan = [
        [
            'Daftar Wajib Retribusi',
            'Rekapitulasi Wajib Retribusi Berdasarkan Objek Retribusi dan Jenis Retribusi',
            'Rekapitulasi Wajib Retribusi Berdasarkan Kecamatan dan Kelurahan',
            'Rekapitulasi Wajib Retribusi Berdasarkan Wilayah Kerja',
        ],
        [
            'Penerimaan Juru Pungut',
            'Rekapitulasi Penerimaan Juru Pungut',
            'Serah Terima Penerimaan dari Juru Pungut ke Koordinator',
            'Serah Terima Penerimaan dari Koordinator',
            'Rekapitulasi Penerimaan per Bulan',
        ],
        [
            'Data Pembayaran per Wajib Retribusi',
            'Data Pembayaran Wajib Retribusi per Objek Retribusi',
            'Data Pembayaran per Objek Retribusi',
            'Data Pembayaran per Jenis Retribusi',
        ]
    ];

    public function dataRetribusi(Request $request)
    {
        $id_laporan = $request->laporan;
        $id_objek_retribusi = $request->id_objek_retribusi ?? 0;
        //$reqData = $request->only('id_laporan', 'id_objek_retribusi');
        return view('admin.laporan.data.retribusi', [
            'title' => 'Laporan Data Retribusi',
            'listLaporan' => $this->listLaporan[0],
            'objek_retribusi' => ObjekRetribusi::all(),
            'id_laporan' => $id_laporan ?? null,
            'id_objek_retribusi' => $id_objek_retribusi,
        ]);
    }

    public function dataPenerimaan(Request $request)
    {
        $reqData = $request->only('id_laporan', 'tgl', 'bln', 'id_user');

        $reqData['stglbln'] = isset($request->stglbln) ? $request->stglbln : 'tgl';
        
        return view('admin.laporan.data.penerimaan', [
            'title' => 'Laporan Data Penerimaan',
            'listLaporan' => $this->listLaporan[1],
            'reqData' => $reqData,
            'juru_pungut' => User::where('gid', 5)->get(),
        ]);
    }

    public function dataPembayaran(Request $request)
    {
        $reqData = $request->only('id_laporan', 'tgl', 'bln', 'thn', 'npwrd');

        $reqData['stglbln'] = isset($request->stglbln) ? $request->stglbln : 'tgl';

        return view('admin.laporan.data.pembayaran', [
            'title' => 'Laporan Data Pembayaran',
            'listLaporan' => $this->listLaporan[2],
            'reqData' => $reqData,
        ]);
    }

    public function pdfRetribusi($id=null, $id_objek_retribusi)
    {
        $data = [
            'id' => $id,
            'title' => 'Laporan Retribusi',
            'judul' => $this->listLaporan[0][$id],
        ];

        if($id == 0){
            ini_set('max_execution_time', 300);
            ini_set("memory_limit","512M");

            $data['data'] = WajibRetribusi::join('objek_retribusis', 'objek_retribusis.id', 'wajib_retribusis.id_objek_retribusi')->join('jenis_retribusis', 'jenis_retribusis.id', 'objek_retribusis.id_jenis_retribusi')->leftjoin('wilayahs', 'wilayahs.id', 'wajib_retribusis.id_wilayah')->leftJoin('kecamatan_kelurahans as kecamatans', 'kecamatans.id', 'wajib_retribusis.id_kecamatan')->leftJoin('kecamatan_kelurahans as kelurahans', 'kelurahans.id', 'wajib_retribusis.id_kelurahan')->select(
                'wajib_retribusis.id',
                'wajib_retribusis.nama',
                'wajib_retribusis.npwrd',
                'wajib_retribusis.id_kecamatan',
                'kecamatans.nama as nama_kecamatan',
                'wajib_retribusis.id_kelurahan',
                'kelurahans.nama as nama_kelurahan',
                'wajib_retribusis.alamat',
                'objek_retribusis.id as id_objek_retribusi',
                'objek_retribusis.nama as nama_objek_retribusi',
                'jenis_retribusis.id as id_jenis_retribusi',
                'jenis_retribusis.nama as nama_jenis_retribusi',
                'wilayahs.id as id_wilayah',
                'wilayahs.nama as wilayah_kerja',
                )->where('wajib_retribusis.id_objek_retribusi', $id_objek_retribusi)->orderBy('wajib_retribusis.id_kecamatan', 'asc')->orderBy('wajib_retribusis.id_kelurahan', 'asc')->limit(1000)->get()->toArray();
            //dd($data);
            $pdf = Pdf::loadView('admin.laporan.data.pdf.retribusi', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(false);
            return $pdf->stream('dataRetribusi.pdf');    
        }elseif($id == 1){
            $data['data'] = ObjekRetribusi::with(['wajib_retribusi', 'jenis_retribusi'])->get();
            $pdf = Pdf::loadView('admin.laporan.data.pdf.retribusi', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(false);
            return $pdf->stream('dataRetribusi.pdf');
        }elseif($id == 2){
            $data['data'] = WajibRetribusi::select(
                'id_kecamatan',
                'id_kelurahan',
                DB::raw('count(id) as jml'),
            )->orderBy('id_kecamatan', 'asc')
            ->groupBy(['id_kecamatan', 'id_kelurahan'])
            ->orderBy('id_kelurahan', 'asc')->get();
            $pdf = Pdf::loadView('admin.laporan.data.pdf.retribusi', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(false);
            return $pdf->stream('dataRetribusi.pdf');
        }elseif($id == 3){
            $data['data'] = WajibRetribusi::select(
                'id_wilayah',
                DB::raw('count(id) as jml'),
            )->orderBy('id_wilayah', 'asc')
            ->groupBy(['id_wilayah'])->get();
            $pdf = Pdf::loadView('admin.laporan.data.pdf.retribusi', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(false);
            return $pdf->stream('dataRetribusi.pdf');
        }
    }

    public function pdfPenerimaan(Request $request)
    {
        $id = $request->id_laporan;
        $data = [
            'id' => $id,
            'tgl' => $request->stglbln == 'tgl' ? date('d-m-Y',strtotime($request->tgl)) : date('F Y',strtotime($request->bln)),
            'id_user' => $request->id_user,
            'title' => 'Laporan Penerimaan',
            'judul' => $this->listLaporan[1][$id],
        ];

        if($id == 0){
            $data['data'] = Pembayaran::where(function($query) use($request) {
                if(isset($request->tgl) && $request->stglbln == 'tgl'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                }

                if(isset($request->bln) && $request->stglbln == 'bln'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"), $request->bln);
                }

                if(isset($request->id_user)){
                    $query->where('id_user', $request->id_user);
                }
            })->orderBy('id_user', 'asc')->orderBy('tgl_bayar', 'asc')->get();
            $pdf = Pdf::loadView('admin.laporan.data.pdf.penerimaan', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('penerimaan.pdf'); 
        }elseif($id == 1){
            $data['data'] = Pembayaran::with(['user', 'jenis_pembayaran'])->select(
                'id_user',
                'jns',
                'tgl_bayar',
                DB::raw('group_concat(no_karcis) as list_karcis'),
                DB::raw('sum(total) as total'),
            )->where(function($query) use($request){
                if(isset($request->tgl) && $request->stglbln == 'tgl'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                }

                if(isset($request->bln) && $request->stglbln == 'bln'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"), $request->bln);
                }

                if(isset($request->id_user)){
                    $query->where('id_user', $request->id_user);
                }
            })->orderBy('id_user', 'asc')->orderBy('tgl_bayar', 'asc')->groupBy(['id_user', 'jns', 'tgl_bayar'])->get();

            $pdf = Pdf::loadView('admin.laporan.data.pdf.penerimaan', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('penerimaan.pdf');
        }elseif($id == 2){
            $merge = Pembayaran::where(function($query) use($request) {
                if(isset($request->tgl) && $request->stglbln == 'tgl'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                }

                if(isset($request->bln) && $request->stglbln == 'bln'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"), $request->bln);
                }

                if(isset($request->id_user)){
                    $query->where('id_user', $request->id_user);
                }
            })->orderBy('id_user', 'asc')->orderBy('tgl_bayar', 'asc')->get();

            $data['data'] = $merge->groupBy(function($item) {
                return $item->id_user;
            });

            $pdf = Pdf::loadView('admin.laporan.data.pdf.penerimaan', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('penerimaan.pdf'); 
        }elseif($id == 3){
            $merge = Pembayaran::with(['user'])->where(function($query) use($request) {
                if(isset($request->tgl) && $request->stglbln == 'tgl'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                }

                if(isset($request->bln) && $request->stglbln == 'bln'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"), $request->bln);
                }

                if(isset($request->id_user)){
                    $query->where('id_user', $request->id_user);
                }
            })->orderBy('id_user', 'asc')->orderBy('tgl_bayar', 'asc')->get();

            //dd($merge);

            $data['data'] = $merge->groupBy(function($item) {
                return $item->wajib_retribusi?->wilayah_kerja?->koordinator?->name;
            });

            $pdf = Pdf::loadView('admin.laporan.data.pdf.penerimaan', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('penerimaan.pdf');
        }
    }

    /*public function serahTerimaPembayaran(Request $request)
    {
        $reqData = $request->only('id_laporan', 'tgl', 'id_user');
        
        return view('admin.laporan.serahterima.pembayaran', [
            'title' => 'Laporan Serah Terima Pembayaran',
            'listLaporan' => $this->listLaporan[1],
            //'id_laporan' => $reqData['id_laporan'] ?? null,
            'reqData' => $reqData,
            'juru_pungut' => User::where('gid', 5)->get(),
        ]);
    }*/

    public function pdfPembayaran(Request $request)
    {
        //dd($request->all());
        $id = $request->id_laporan;
        $data = [
            'id' => $id,
            'tgl' => $request->stglbln == 'tgl' ? "Tanggal : ".date('d-m-Y',strtotime($request->tgl)) : ( $request->stglbln == 'bln' ? "Bulan : ".date('F Y',strtotime($request->bln)) : "Tahun : ".$request->thn ),
            'title' => 'Laporan Data Pembayaran',
            'judul' => $this->listLaporan[2][$id],
        ];

        if($id == 0){
            $data['data'] = Pembayaran::where(function($query) use($request) {
                if(isset($request->tgl) && $request->stglbln == 'tgl'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                }

                if(isset($request->bln) && $request->stglbln == 'bln'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"), $request->bln);
                }

                if(isset($request->thn) && $request->stglbln == 'thn'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y')"), $request->thn);
                }
            })->where('npwrd', $request->npwrd)->orderBy('npwrd', 'asc')->orderBy('tgl_bayar', 'asc')->get();
            
            $pdf = Pdf::loadView('admin.laporan.data.pdf.pembayaran', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('pembayaran.pdf'); 
        }elseif($id == 1){
            $data['data'] = Pembayaran::where(function($query) use($request) {
                if(isset($request->tgl) && $request->stglbln == 'tgl'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                }

                if(isset($request->bln) && $request->stglbln == 'bln'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y-%m')"), $request->bln);
                }

                if(isset($request->thn) && $request->stglbln == 'thn'){
                    $query->where(DB::raw("DATE_FORMAT(tgl_bayar, '%Y')"), $request->thn);
                }
            })->orderBy('id_wr', 'asc')->orderBy('tgl_bayar', 'asc')->get();

            $pdf = Pdf::loadView('admin.laporan.data.pdf.pembayaran', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('pembayaran.pdf');
        }elseif($id == 2){
            $data['data'] = Pembayaran::select('objek_retribusis.id', 'objek_retribusis.nama', DB::raw('max(jenis_retribusis.nama) as nama_jenis'), DB::raw('sum(pembayarans.total) as total'))
                ->join('wajib_retribusis', 'wajib_retribusis.id', 'id_wr')
                ->join('objek_retribusis', 'wajib_retribusis.id_objek_retribusi', 'objek_retribusis.id')
                ->join('jenis_retribusis', 'objek_retribusis.id_jenis_retribusi', 'jenis_retribusis.id')
                ->groupBy(['objek_retribusis.id', 'objek_retribusis.nama'])
                ->where(function($query) use($request){
                    if(isset($request->tgl) && $request->stglbln == 'tgl'){
                        $query->where(DB::raw("DATE_FORMAT(pembayarans.tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                    }

                    if(isset($request->bln) && $request->stglbln == 'bln'){
                        $query->where(DB::raw("DATE_FORMAT(pembayarans.tgl_bayar, '%Y-%m')"), $request->bln);
                    }
                    if(isset($request->thn) && $request->stglbln == 'thn'){
                        $query->where(DB::raw("DATE_FORMAT(pembayarans.tgl_bayar, '%Y')"), $request->thn);
                    }
                })
                ->get();
            //dd($data['data']);

            $pdf = Pdf::loadView('admin.laporan.data.pdf.pembayaran', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('pembayaran.pdf');
        }elseif($id == 3){
            $data['data'] = Pembayaran::select('jenis_retribusis.id', 'jenis_retribusis.nama', DB::raw('sum(pembayarans.total) as total'))
                ->join('wajib_retribusis', 'wajib_retribusis.id', 'id_wr')
                ->join('objek_retribusis', 'wajib_retribusis.id_objek_retribusi', 'objek_retribusis.id')
                ->join('jenis_retribusis', 'objek_retribusis.id_jenis_retribusi', 'jenis_retribusis.id')
                ->groupBy(['jenis_retribusis.id', 'jenis_retribusis.nama'])
                ->where(function($query) use($request){
                    if(isset($request->tgl) && $request->stglbln == 'tgl'){
                        $query->where(DB::raw("DATE_FORMAT(pembayarans.tgl_bayar, '%Y-%m-%d')"), $request->tgl);
                    }

                    if(isset($request->bln) && $request->stglbln == 'bln'){
                        $query->where(DB::raw("DATE_FORMAT(pembayarans.tgl_bayar, '%Y-%m')"), $request->bln);
                    }
                    if(isset($request->thn) && $request->stglbln == 'thn'){
                        $query->where(DB::raw("DATE_FORMAT(pembayarans.tgl_bayar, '%Y')"), $request->thn);
                    }
                })
                ->get();
            //dd($data['data']);

            $pdf = Pdf::loadView('admin.laporan.data.pdf.pembayaran', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('pembayaran.pdf');
        }
    }

    public function penyerahan($filter){
        $request = unserialize(urldecode($filter));
        //dd($request['id_user_juru_pungut']);
        $karcis  = Karcis::with(['juru_pungut' => function ($query) {
            $query->select('id','name');
        }, 'koordinator' => function($query){
            $query->select('id','name');
        }])->select(
            DB::raw('1 as jns'),
            DB::raw('"Karcis" as route'),
            'id',
            'harga as harga',
            DB::raw('(no_karcis_akhir - no_karcis_awal +1) as jml'),
            'tgl_penyerahan',
            'id_user_koordinator',
            'id_user_juru_pungut',
            'tahun as thn',
            DB::raw('concat(no_karcis_awal, " s/d ", no_karcis_akhir) as ket'),
        )->where(function($query) use ($request){
            if(isset($request['id_user_juru_pungut'])){
                $query->where('id_user_juru_pungut', $request['id_user_juru_pungut']);
            }

            if(isset($request['sbln'])){
                $query->whereRaw("DATE_FORMAT(tgl_penyerahan, '%Y-%m') = '". $request['sbln']."'");
            }else{
                if(!isset($request['semua'])){
                    $query->whereRaw("DATE_FORMAT(tgl_penyerahan, '%Y-%m') = '". date('Y-m') ."'");    
                }
            }
        })->where('stts', 1)->orderBy('tgl_penyerahan', 'desc')->get();

        $tagihan = Tagihan::with(['juru_pungut' => function ($query) {
            $query->select('id','name');
        }, 'koordinator' => function($query){
            $query->select('id','name');
        }])->select(
            DB::raw('2 as jns'),
            DB::raw('"SKRD" as route'),
            'id',
            'jml as harga',
            DB::raw('"1" as jml'),
            'tgl_penyerahan',
            'id_user_koordinator',
            'id_user_juru_pungut',
            'thn',
            DB::raw('concat(no_skrd, "<br>", "Tgl. ", date_format(tgl_skrd, "%d-%m-%Y") ) as ket'),
        )->where(function($query)use($request) {
            if(isset($request['sbln'])){
                $thn = date('Y', strtotime($request['sbln']));
                $bln = date('m', strtotime($request['sbln']));
                $query->where('bln', $bln)->where('thn', $thn);
            }else{
                if(!isset($request['semua'])){
                    $query->where('bln', date('m'))->where('thn', date('Y'));
                }
            }

            if(isset($request['id_user_juru_pungut'])){
                $query->where('id_user_juru_pungut', $request['id_user_juru_pungut']);
            }

        })->where(function($query){
            $query->where('stts', 0)->where('stts_byr', 0);
        })->orderBy('tgl_penyerahan', 'desc')->get();

        $merge = $karcis->merge($tagihan)->sortByDesc('tgl_penyerahan');

        $data['data'] = $merge->groupBy(function($item) {
            return $item->id_user_juru_pungut;
        });

        $pdf = Pdf::loadView('admin.laporan.penyerahan.index', $data)
            ->setPaper('a4', 'potrait')
            ->setOption('fontDir', public_path('/fonts'))
            ->setWarnings(true);
        return $pdf->stream('penyerahan.pdf');
    }

    public function penyerahan_tersendiri($route, $id){
        if($route == 'Karcis'){
            $merge = Karcis::with(['juru_pungut' => function ($query) {
                $query->select('id','name');
            }, 'koordinator' => function($query){
                $query->select('id','name');
            }])->select(
                DB::raw('1 as jns'),
                DB::raw('"Karcis" as route'),
                'id',
                'harga as harga',
                DB::raw('(no_karcis_akhir - no_karcis_awal +1) as jml'),
                'tgl_penyerahan',
                'id_user_koordinator',
                'id_user_juru_pungut',
                'tahun as thn',
                DB::raw('concat(no_karcis_awal, " s/d ", no_karcis_akhir) as ket'),
            )->where('id', $id)->orderBy('tgl_penyerahan', 'desc')->get();

            $data['data'] = $merge->groupBy(function($item) {
                return $item->id_user_juru_pungut;
            });

        }else{
            $merge = Tagihan::with(['juru_pungut' => function ($query) {
                $query->select('id','name');
            }, 'koordinator' => function($query){
                $query->select('id','name');
            }])->select(
                DB::raw('2 as jns'),
                DB::raw('"SKRD" as route'),
                'id',
                'jml as harga',
                DB::raw('"1" as jml'),
                'tgl_penyerahan',
                'id_user_koordinator',
                'id_user_juru_pungut',
                'thn',
                DB::raw('concat(no_skrd, "<br>", "Tgl. ", date_format(tgl_skrd, "%d-%m-%Y") ) as ket'),
            )->where('id', $id)->orderBy('tgl_penyerahan', 'desc')->get();

            $data['data'] = $merge->groupBy(function($item) {
                return $item->id_user_juru_pungut;
            });
        }
        
        $pdf = Pdf::loadView('admin.laporan.penyerahan.index', $data)
            ->setPaper('a4', 'potrait')
            ->setOption('fontDir', public_path('/fonts'))
            ->setWarnings(true);
        return $pdf->stream('pembayaran.pdf');
    }

    public function pengembalian($id){
        $data['data'] = Pengembalian::join('karcis', 'karcis.id', 'pengembalians.id_karcis')
            ->leftJoin('users as koordinator', 'koordinator.id', 'karcis.id_user_koordinator')
            ->leftJoin('users as juru_pungut', 'juru_pungut.id', 'karcis.id_user_juru_pungut')
            ->select(
                DB::raw('1 as jns'),
                DB::raw('"Karcis" as route'),
                'pengembalians.id',
                'pengembalians.id_karcis',
                'karcis.harga as harga',
                'pengembalians.tgl_pengembalian',
                'koordinator.name as nama_koordinator',
                'juru_pungut.name as nama_juru_pungut',
                'karcis.tahun as thn',
                DB::raw('concat(pengembalians.no_karcis_awal +1, " s/d ", pengembalians.no_karcis_akhir) as ket'),
                DB::raw('(pengembalians.no_karcis_akhir - pengembalians.no_karcis_awal) as jml'),
            )->where('pengembalians.id', $id)->orderBy('pengembalians.tgl_pengembalian', 'desc')->get();

        //dd($data);

        $pdf = Pdf::loadView('admin.laporan.pengembalian.index', $data)
            ->setPaper('a4', 'potrait')
            ->setOption('fontDir', public_path('/fonts'))
            ->setWarnings(true);
        return $pdf->stream('pengembalian.pdf');
    }
}
