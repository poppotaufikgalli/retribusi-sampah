@extends('layouts.master1')
@section('title', $title ?? 'Penerimaan Juru Pungut')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('laporan.penerimaan.juru_pungut')}}">
                    @csrf
                    <div class="row mb-1">
                        <label class="col-sm-2">Tanggal</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control form-control-sm" id="tgl" name="tgl" value="{{isset($tgl) ? $tgl : ''}}" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-2">Juru Pungut</label>
                        <div class="col-sm-3">
                            <select class="form-control form-control-sm" id="id_user" name="id_user">
                            @if(isset($juru_pungut))
                                <option value="" selected disabled>Pilih Juru Pungut</option> 
                                @foreach($juru_pungut as $key => $value)
                                    <option value="{{$value->id}}" {{isset($id_user) && $id_user == $value->id ? 'selected' : ''}}>{{$value->name}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                            <a href="{{route('laporan.penerimaan.juru_pungut')}}" class="btn btn-sm btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama Juru Pungut</th>
                                <th width="20%">Jenis</th>
                                <th width="20%">Tanggal</th>
                                <th width="20%">Nomor Karcis</th>
                                <th width="20%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($_total = 0)
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{$value->user?->name}}</td>
                                        <td>{{$value->jenis_pembayaran?->nama}}</td>
                                        <td class="text-center">{{$value->tgl_bayar->format('d-m-Y')}}</td>
                                        <td class="text-center">{{$value->list_karcis}}</td>
                                        <td class="text-end">{{Str::currency($value->total)}}</td>
                                    </tr>
                                    @php($_total = $_total + $value->total)
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
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
                                    title: 'Laporan Penerimaan Per Juru Pungut',
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