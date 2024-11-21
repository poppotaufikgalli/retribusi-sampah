@extends('layouts.master1')
@section('title', $title ?? 'Rekapitulasi Hasil')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row gap-2">
                    <label class="col-md-2">Kategori Peserta</label>
                    <div class="col-md-9">
                        <select class="form-control form-control-sm" id="selKategoriPeserta">
                            <option value="" selected>Semua</option>
                            @if($katPeserta)
                                @foreach($katPeserta as $key => $value)
                                    <option value="{{$value->id}}" {{$value->id == $id_peserta ? 'selected' : ''}}>{{$value->lomba->judul}} - {{$value->judul}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-stiped table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="2%">No</th>
                                <th width="10%">No Peserta</th>
                                <th width="10%">Nama Regu/Instansi</th>
                                <th width="10%">Kategori</th>
                                <th width="8%">Waktu Mulai</th>
                                <th width="8%">Waktu Finish</th>
                                <th width="8%">Waktu Tempuh</th>
                                <th width="8%">Nilai Waktu</th>
                                <th width="8%">Keutuhan Barisan</th>
                                <th width="8%">Kerapian</th>
                                <th width="8%">Semangat</th>
                                <th width="8%">Total Nilai</th>
                                <th width="8%">Dis?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr class="{{$value->diskualifikasi ? 'text-bg-danger' : ''}}">
                                        <td>{{ ($key+1) }}</td>
                                        <td class="text-center">{{$value->no_peserta}}</td>
                                        <td>{{$value->nama}}</td>
                                        <td>{{$value->lomba?->judul}} - {{$value->kategori_peserta?->judul}}</td>
                                        <td class="text-center">{{$value->waktu_start ? $value->waktu_start->format('H:i:s') : ''}}</td>
                                        <td class="text-center">{{$value->waktu_finish ? $value->waktu_finish->format('H:i:s') : ''}}</td>
                                        <td class="text-center">{{$value->waktu_tempuh != null ? gmdate('H:i:s', $value->waktu_tempuh) : ''}}</td>

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
                                        <td class="text-center">{{$value->diskualifikasi ? 'Ya' : ''}}</td>
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
                new DataTable(datatablesSimple, {
                    layout: {
                        topStart: {
                            //buttons: ['excelHtml5', 'pdfHtml5'],
                            buttons: [
                                {
                                    extend: 'pdfHtml5',
                                    orientation: 'landscape',
                                    pageSize: 'LEGAL'
                                }, 'excelHtml5'
                            ]
                        },
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging',
                    },
                });
            }

            document.getElementById("selKategoriPeserta").addEventListener('change', function() {
                //var value = this.value
                window.location.href = "/rekapHasil/"+this.value
            })
        });
    </script>
@endsection