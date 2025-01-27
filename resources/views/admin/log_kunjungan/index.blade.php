@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('log_kunjungan')}}">
                    @csrf
                    <div class="row mb-1">
                        <label for="nama" class="col-sm-2 col-form-label">Tanggal Bayar</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="date" class="form-control form-control-sm" name="stgl_bayar_awal" value="{{$filter['stgl_bayar_awal']?? ''}}">
                                <span class="input-group-text">s/d</span>
                                <input type="date" class="form-control form-control-sm" name="stgl_bayar_akhir" value="{{$filter ? $filter['stgl_bayar_akhir'] : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="nama" class="col-sm-2 col-form-label">NPWRD</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control form-control-sm" name="snpwrd" value="{{$filter['snpwrd'] ?? ''}}">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                            <a href="{{route('log_kunjungan')}}" class="btn btn-sm btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
                <hr class="my-1">
                <div class="table-responsive">
                    <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th width="3%">No</th>
                                <th width="10%">Tanggal Kunjungan</th>
                                <th width="10%">NPWRD</th>
                                <th width="15%">Nama WR</th>
                                <th width="10%">Nomor Tagihan / Bulan - Tahun</th>
                                <th width="10%">Jenis</th>
                                <th>Keterangan</th>
                                <th width="10%">Petugas</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>                                    
                                        <td class="text-center">{{$value->tgl_kunjungan->format('d-m-Y')}}</td>
                                        <td class="text-center">{{$value->npwrd}}</td>
                                        <td class="text-center">{{$value->wajib_retribusi?->nama}}</td>
                                        <td class="text-center">{{$value->no_tagihan}} /<br>{{$value->bln}} - {{$value->thn}}</td>
                                        <td class="text-center">{{$value->jns}}-{{$value->jenis_keterangan?->nama}}</td>
                                        <td class="text-center">{{$value->keterangan}}</td>
                                        <td class="text-center">{{$value->user?->name}}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('log_kunjungan.show', ['id' => $value->id] )}}" class="bg-primary px-2 py-1 text-white bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-box"></i>
                                                </a>
                                            </div>
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
            new DataTable(datatablesSimple, {
                layout: {
                    topStart: {
                        buttons: ['excelHtml5', 'pdfHtml5']
                    },
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
            });
        }
    });
    </script>
@endsection