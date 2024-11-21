@extends('layouts.master1')
@section('title', $title ?? 'Penerimaan Juru Pungut')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('laporan.penerimaan.jenis')}}">
                    @csrf
                    <div class="row mb-1">
                        <label class="col-sm-2">Tanggal</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control form-control-sm" id="tgl" name="tgl" value="{{isset($tgl) ? $tgl : ''}}" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-2">Jenis Retribusi</label>
                        <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="sjns" name="sjns">
                            @if(isset($jenis_retribusi))
                                <option value="" selected>Semua Jenis Retribusi</option> 
                                @foreach($jenis_retribusi as $key => $value)
                                    <option value="{{$value->id}}" {{isset($sjns) && $sjns == $value->id ? 'selected' : ''}}>{{$value->nama}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                            <a href="{{route('laporan.penerimaan.jenis')}}" class="btn btn-sm btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Jenis Retribusi</th>
                                <th width="20%">Tanggal</th>
                                <th width="20%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($_total = 0)
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{$value->nama_jenis_retribusi}}</td>
                                        <td class="text-center">{{$value->tgl_bayar->format('d-m-Y')}}</td>
                                        <td class="text-end">{{Str::currency($value->total)}}</td>
                                    </tr>
                                    @php($_total = $_total + $value->total)
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end">{{Str::currency($_total)}}</th>
                            </tr>
                        </tfoot>
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
                            buttons: [
                                'excelHtml5', 
                                {
                                    extend: 'pdfHtml5',
                                    pageSize: 'A4',
                                    title: 'Laporan Penerimaan Per Jenis Retribusi',
                                },
                            ]
                        },
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging',
                    },
                    pageLength: 25,
                });
            }

            document.getElementById("selKategoriPeserta").addEventListener('change', function() {
                //var value = this.value
                window.location.href = "/rekapPos/"+this.value
            })
        });
    </script>
@endsection