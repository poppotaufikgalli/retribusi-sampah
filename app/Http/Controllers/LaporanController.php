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

use DB;
use Pdf;

class LaporanController extends Controller
{
    //
    /*public function penerimaanJuruPungut(Request $request){
        $data = Pembayaran::with(['user', 'jenis_pembayaran'])->select(
            'id_user',
            'jns',
            'tgl_bayar',
            DB::raw('group_concat(no_karcis) as list_karcis'),
            DB::raw('sum(total) as total'),
        )->where(function($query) use($request){
            if(isset($request->tgl)){
                $query->where('tgl_bayar', $request->tgl);
            }else{
                $query->where('tgl_bayar', date('Y-m-d'));
            }

            if(isset($request->id_user)){
                $query->where('id_user', $request->id_user);
            }
        })->groupBy(['id_user', 'jns', 'tgl_bayar'])->get();

        return view('admin.laporan.penerimaan.perJuruPungut', [
            'data' => $data,
            'title' => 'Penerimaan Per Juru Pungut',
            'juru_pungut' => User::where('gid', 5)->get(),
            'id_user' => $request->id_user ? $request->id_user : '',
            'tgl' => $request->tgl ? $request->tgl : date('Y-m-d'),
        ]);
    }

    public function penerimaanJenis(Request $request){
        $data = Pembayaran::select(
            'jenis_retribusis.id as id_jenis_retribusi',
            'jenis_retribusis.nama as nama_jenis_retribusi',
            'pembayarans.tgl_bayar as tgl_bayar',
            DB::raw('sum(total) as total'),
        )->join('wajib_retribusis', 'wajib_retribusis.npwrd', 'pembayarans.npwrd')->join('objek_retribusis', 'objek_retribusis.id', 'wajib_retribusis.id_objek_retribusi')->join('jenis_retribusis', 'jenis_retribusis.id', 'objek_retribusis.id_jenis_retribusi')
        ->where(function($query) use($request){
            if(isset($request->tgl)){
                $query->where('pembayarans.tgl_bayar', $request->tgl);
            }else{
                $query->where('pembayarans.tgl_bayar', date('Y-m-d'));
            }

            if(isset($request->sjns)){
                $query->where('jenis_retribusis.id', $request->sjns);
            }
        })->groupBy(['jenis_retribusis.id', 'pembayarans.tgl_bayar'])->get();

        return view('admin.laporan.penerimaan.perJenis', [
            'data' => $data,
            'title' => 'Penerimaan Per Jenis Retribusi',
            'jenis_retribusi' => JenisRetribusi::all(),
            'sjns' => $request->sjns ? $request->sjns : '',
            'tgl' => $request->tgl ? $request->tgl : date('Y-m-d'),
        ]);
    }

    public function penerimaanObjek(Request $request){
        $data = Pembayaran::select(
            'objek_retribusis.id as id_objek_retribusi',
            'objek_retribusis.nama as nama_objek_retribusi',
            'pembayarans.tgl_bayar as tgl_bayar',
            DB::raw('sum(total) as total'),
        )->join('wajib_retribusis', 'wajib_retribusis.npwrd', 'pembayarans.npwrd')->join('objek_retribusis', 'objek_retribusis.id', 'wajib_retribusis.id_objek_retribusi')
        ->where(function($query) use($request){
            if(isset($request->tgl)){
                $query->where('pembayarans.tgl_bayar', $request->tgl);
            }else{
                $query->where('pembayarans.tgl_bayar', date('Y-m-d'));
            }

            if(isset($request->sobjek)){
                $query->where('objek_retribusis.id', $request->sobjek);
            }
        })->groupBy(['objek_retribusis.id', 'pembayarans.tgl_bayar'])->get();

        return view('admin.laporan.penerimaan.perObjek', [
            'data' => $data,
            'title' => 'Penerimaan Per Objek Retribusi',
            'objek_retribusi' => ObjekRetribusi::all(),
            'sjns' => $request->sjns ? $request->sjns : '',
            'tgl' => $request->tgl ? $request->tgl : date('Y-m-d'),
        ]);
    }*/

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
        ]
        
    ];

    public function dataRetribusi(Request $request)
    {
        $id_laporan = $request->laporan;
        return view('admin.laporan.data.retribusi', [
            'title' => 'Laporan Data Retribusi',
            'listLaporan' => $this->listLaporan[0],
            'id_laporan' => $id_laporan ?? null,
        ]);
    }

    public function dataPenerimaan(Request $request)
    {
        $reqData = $request->only('id_laporan', 'tgl', 'bln', 'id_user');

        $reqData['stglbln'] = isset($request->stglbln) ? $request->stglbln : 'tgl';
        //dd($reqData);
        
        return view('admin.laporan.data.penerimaan', [
            'title' => 'Laporan Data Penerimaan',
            'listLaporan' => $this->listLaporan[1],
            //'id_laporan' => $reqData['id_laporan'] ?? null,
            'reqData' => $reqData,
            'juru_pungut' => User::where('gid', 5)->get(),
        ]);
    }

    public function pdfRetribusi($id=null)
    {
        $data = [
            'id' => $id,
            'title' => 'Laporan Retribusi',
            'judul' => $this->listLaporan[0][$id],
        ];

        if($id == 0){
            $data['data'] = WajibRetribusi::orderBy('id_kecamatan', 'asc')->orderBy('id_kelurahan', 'asc')->get();
            $pdf = Pdf::loadView('admin.laporan.data.pdf.retribusi', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(false);
            return $pdf->stream('dataRetribusi.pdf');    
        }elseif($id == 1){
            $data['data'] = WajibRetribusi::join('objek_retribusis', 'objek_retribusis.id', 'id_objek_retribusi')
            ->select(
                'id_objek_retribusi',
                DB::raw('count(npwrd) as jml'),
            )->orderBy('objek_retribusis.id_jenis_retribusi', 'asc')
            ->orderBy('id_objek_retribusi', 'asc')
            ->groupBy('id_objek_retribusi')->get();
            $pdf = Pdf::loadView('admin.laporan.data.pdf.retribusi', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(false);
            return $pdf->stream('dataRetribusi.pdf');
        }elseif($id == 2){
            $data['data'] = WajibRetribusi::select(
                'id_kecamatan',
                'id_kelurahan',
                DB::raw('count(npwrd) as jml'),
            )->orderBy('id_kecamatan', 'asc')->orderBy('id_kelurahan', 'asc')
            ->groupBy(['id_kecamatan', 'id_kelurahan'])->get();
            $pdf = Pdf::loadView('admin.laporan.data.pdf.retribusi', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(false);
            return $pdf->stream('dataRetribusi.pdf');
        }elseif($id == 3){
            $data['data'] = WajibRetribusi::select(
                'id_wilayah',
                DB::raw('count(npwrd) as jml'),
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

    /*public function pdfPembayaran(Request $request)
    {
        $id = $request->id_laporan;
        $data = [
            'id' => $id,
            'tgl' => date('d-m-Y',strtotime($request->tgl)),
            'id_user' => $request->id_user,
            'title' => 'Laporan Retribusi',
            'judul' => $this->listLaporan[1][$id],
        ];

        if($id == 0){
            $data['data'] = Pembayaran::where(function($query) use($request) {
                if(isset($request->tgl)){
                    $query->where('tgl_bayar', $request->tgl);
                }

                if(isset($request->id_user)){
                    $query->where('id_user', $request->id_user);
                }
            })->orderBy('tgl_bayar', 'asc')->orderBy('id_user', 'asc')->get();
            $pdf = Pdf::loadView('admin.laporan.serahterima.pdf.pembayaran', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('pembayaran.pdf'); 
        }elseif($id == 1){
            $data['data'] = Pembayaran::with(['user', 'jenis_pembayaran'])->select(
                'id_user',
                'jns',
                'tgl_bayar',
                DB::raw('group_concat(no_karcis) as list_karcis'),
                DB::raw('sum(total) as total'),
            )->where(function($query) use($request){
                if(isset($request->tgl)){
                    $query->where('tgl_bayar', $request->tgl);
                }else{
                    $query->where('tgl_bayar', date('Y-m-d'));
                }

                if(isset($request->id_user)){
                    $query->where('id_user', $request->id_user);
                }
            })->orderBy('tgl_bayar', 'asc')->orderBy('id_user', 'asc')
            ->groupBy(['id_user', 'jns', 'tgl_bayar'])->get();

            $pdf = Pdf::loadView('admin.laporan.serahterima.pdf.pembayaran', $data)
                ->setPaper('a4', 'potrait')
                ->setOption('fontDir', public_path('/fonts'))
                ->setWarnings(true);
            return $pdf->stream('pembayaran.pdf');
        }elseif($id == 2){
           
        }
    }*/

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
}
