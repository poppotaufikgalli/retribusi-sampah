@extends('layouts.master1')
@section('title',"Data Penjurian")
@section('subtitle',$subtitle->ket ?? 'Semua')
@section('content')
	<div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <a class="btn btn-sm btn-primary" href="{{route('penilaian.create',['id' => $id])}}">Tambah</a>
                <hr>
                <div class="table-responsive">
                    <table class="table small table-stiped table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="3%">No</th>
                                <th width="8%">No Peserta</th>
                                <th width="15%">Nama Regu/Instansi</th>
                                <th width="10%">Waktu Start</th>
                                <th width="10%">Waktu Finish</th>
                                <th width="10%">Waktu Tempuh</th>
                                <th width="10%">Nilai Waktu</th>
                                <th width="10%">Keutuhan Barisan</th>
                                <th width="10%">Kerapian</th>
                                <th width="10%">Semangat</th>
                                <th width="10%">Total</th>
                                <th width="5%"></th>
                                <!--<th rowspan="2" width="10%">Diskualifikasi</th>-->
                            </tr>
                        </thead>
                        <tbody>
                        	@if(isset($data))
    							@foreach($data as $key => $value)
    		                        <tr>
    		                            <td>{{ ($key+1) }}</td>
                                        <td class="text-center">
                                            <a href="{{route('penilaian.show', ['id' => $value->id])}}" class="text-decoration-none">{{$value->no_peserta}}</a>
                                        </td>
    		                            <td>{{$value->nama}}</td>
                                        <td class="text-center">{{$value->waktu_start ? $value->waktu_start->format('H:i:s') : ''}}</td>
                                        <td class="text-center">{{$value->waktu_finish ? $value->waktu_finish->format('H:i:s') : ''}}</td>
    		                            <td class="text-center">{{$value->waktu_tempuh ? gmdate('H:i:s',$value->waktu_tempuh) : ''}}</td>

                                        @php($a=$penilaian[$value->id][1] ?? 0)
                                        @php($b=floatval($penilaian[$value->id][2] ?? 0))
                                        @php($c=floatval($penilaian[$value->id][3] ?? 0))
                                        @php($d=floatval($penilaian[$value->id][4] ?? 0))

                                        <td class="text-center">{{$a}}</td>

                                        <td class="text-center">{{$penilaian[$value->id][2] ?? ''}}</td>
                                        <td class="text-center">{{$penilaian[$value->id][3] ?? ''}}</td>
                                        <td class="text-center">{{$penilaian[$value->id][4] ?? ''}}</td>
                                        
                                        @php($total= $a + $b + $c + $d ?? 0)
                                        

                                        <td class="text-center">{{$value->total}}</td>
                                        <td>
                                            @if($total != $value->total)
                                                <a href="{{route('penilaian.update.ulang', ['id_pendaftar' => $value->id, 'jml_pos' => $value->lomba->jml_pos] )}}" class="bg-info px-2 py-1 text-white bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-refresh"></i>
                                                </a>
                                            @endif
                                        </td>
    		                        </tr>
    		                    @endforeach
    		                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', event => {
            // Simple-DataTables
            // https://github.com/fiduswriter/Simple-DataTables/wiki

            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                let table = new DataTable(datatablesSimple, {
                    layout: {
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging',
                    }
                });

                /*table.on('click', 'tr', function(){
                    var id = table.row(this).data()[0]

                    window.location.href = "/penilaian/show/"+id
                })*/
            }
        });
    </script>
@endsection