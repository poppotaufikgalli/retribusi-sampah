@extends('layouts.master1')
@section('title',"Dashboard Juru Pungut")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="row row-cols-1 row-cols-md-1 g-4">
            @if(isset($d_jungut))
                @foreach($d_jungut as $key => $value)
                <div class="col">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-2">
                                @if($value->foto)
                                    <a href="{{asset('storage/'.$value->foto)}}" class="glightbox">
                                        <img src="{{asset('storage/'.$value->foto)}}" class="img-fluid rounded-start" alt="...">
                                    </a>
                                @else
                                    <img class="img-fluid rounded-start" src="{{asset('img/Profile_avatar_placeholder_large.png')}}" />
                                @endif
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">
                                    <h5 class="card-title">{{$value->name}}</h5>
                                    <table class="table table-bordered table-sm small">
                                        <thead class="table-dark">
                                            <tr class="text-center">
                                                <th>Wilayah Kerja</th>
                                                <th>Jumlah WR</th>
                                                <th>Potensi Penerimaan / Bulan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($value->wilayah_kerja_juru_pungut))
                                                @php($total = 0)
                                                @foreach($value->wilayah_kerja_juru_pungut as $k => $v)
                                                    <tr>
                                                        <td>{{$v->nama}} [{{$v->deskripsi}}]</td>
                                                        <td class="text-center">{{$v->wajib_retribusi->count()}}</td>
                                                        <td class="text-end">{{Str::currency($v->wajib_retribusi->sum('objek_retribusi.tarif'))}}</td>
                                                        @php($total = $total + $v->wajib_retribusi->sum('objek_retribusi.tarif'))
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot class="table-dark">
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th class="text-end">{{Str::currency($total)}}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <table class="table table-bordered table-sm small">
                                        <thead class="table-dark">
                                            <tr class="text-center">
                                                <th>Karcis</th>
                                                <th>Tgl Penyerahan</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                                <th>Potensi</th>
                                                <th>Digunakan</th>
                                                <th>Penerimaan</th>
                                                <th>Piutang</th>
                                            </tr>
                                        </thead>
                                        @if(isset($value->karcis))
                                            <tbody>
                                                @php($totPotensi = 0)
                                                @php($totDigunakan = 0)
                                                @php($totPenerimaan = 0)
                                                @php($totPiutang = 0)
                                                @foreach($value->karcis as $k => $v)
                                                    <tr>
                                                        <td class="text-center">{{$v->no_karcis_awal}} s/d {{$v->no_karcis_akhir}}</td>
                                                        <td class="text-center">{{$v->tgl_penyerahan->format('d-m-Y')}}</td>
                                                        <td class="text-end">{{Str::rupiah($v->harga)}}</td>
                                                        <td class="text-center">{{$v->jml_karcis}}</td>
                                                        @php($potensi = $v->jml_karcis * $v->harga)
                                                        @php($totPotensi = $totPotensi + $potensi)
                                                        <td class="text-end">{{Str::rupiah($potensi)}}</td>
                                                        @php($digunakan = $v->pembayaran->pluck('no_karcis')->count())
                                                        @php($totDigunakan = $totDigunakan +$digunakan)
                                                        <td class="text-center">{{$digunakan}}</td>
                                                        @php($penerimaan = $digunakan * $v->harga)
                                                        @php($totPenerimaan = $totPenerimaan + $penerimaan)
                                                        <td class="text-end">{{Str::rupiah($penerimaan)}}</td>
                                                        @php($piutang = $potensi-$penerimaan)
                                                        @php($totPiutang = $totPiutang + $piutang)
                                                        <td class="text-end">{{Str::rupiah($piutang)}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="d-none">
                                                <tr class="table-dark">
                                                    <th colspan="3">Total</th>
                                                    <th class="text-center">{{$value->karcis->sum('jml_karcis')}}</th>
                                                    <th class="text-end">{{Str::rupiah($totPotensi)}}</th>
                                                    <th class="text-center">{{$totDigunakan}}</th>
                                                    <th class="text-end">{{Str::rupiah($totPenerimaan)}}</th>
                                                    <th class="text-end">{{Str::rupiah($totPiutang)}}</th>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', event => {
            
        });
    </script>
@endsection