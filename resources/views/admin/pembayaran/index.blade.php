@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route($currentRoute)}}">
                    @csrf
                    @if($currentRoute !== 'pembayaran.batal')
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
                    @endif
                    <div class="row mb-1">
                        <label for="nama" class="col-sm-2 col-form-label">NPWRD</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control form-control-sm" name="snpwrd" value="{{$filter['snpwrd'] ?? ''}}">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                            <a href="{{route($currentRoute)}}" class="btn btn-sm btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
                <hr class="my-1">
                <div class="table-responsive">
                    <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th width="10%">NPWRD</th>
                                <th width="10%">Nama WR</th>
                                <th width="20%">Nomor Karcis</th>
                                <th width="10%">Priode/ Bulan-Tahun</th>
                                <th width="10%">Jumlah</th>
                                <th width="10%">Denda</th>
                                <th width="10%">Total</th>
                                <th width="10%">Tanggal Bayar</th>
                                <th width="10%">Petugas</th>
                                @if($currentRoute == 'pembayaran.verifikasi')
                                <th>Verifikasi Pembayaran</th>
                                @endif
                                @if($currentRoute == 'pembayaran.batal')
                                <th>Batal Bayar</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>                                    
                                        <td>{{$value->npwrd}}</td>
                                        <td class="text-start">{{$value->wajib_retribusi?->nama}}</td>
                                        <td class="text-center">{{$value->no_karcis}}</td>
                                        @if($value->wajib_retribusi?->objek_retribusi?->insidentil == 1)
                                            <td class="text-center">
                                                {{$value->tgl}}-{{$value->bln}}-{{$value->thn}}
                                                <i class="bx bx-info-circle text-info"></i>
                                            </td>
                                        @else
                                            <td class="text-center">{{$value->bln}} - {{$value->thn}}</td>
                                        @endif
                                        <td class="text-end">{{Str::currency($value->jml)}}</td>
                                        <td class="text-end">{{Str::currency($value->denda)}}</td>
                                        <td class="text-end">{{Str::currency($value->total)}}</td>
                                        <td class="text-center">{{$value->tgl_bayar->format('d-m-Y')}}</td>
                                        <td class="text-center">{{$value->user->name}}</td>
                                        @if($currentRoute == 'pembayaran.verifikasi')
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                @if($value->verif == 0)
                                                    <a href="{{route('pembayaran.verifikasi.show', ['id' => $value->id] )}}" class="bg-success px-2 py-1 text-white bg-opacity-75 text-decoration-none">
                                                        <i class="bx bx-check-circle"></i>
                                                    </a>
                                                @elseif($value->verif == 1)
                                                    <span class="text-success"><i class="bx bx-check-circle"></i></span>
                                                @elseif($value->verif == -1)
                                                    <span class="text-danger"><i class="bx bx-x-circle"></i></span>
                                                @endif
                                            </div>
                                        </td>
                                        @endif
                                        @if($currentRoute == 'pembayaran.batal')
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                @if($value->verif == 0 && $value->tgl_bayar == now())
                                                    <a href="{{route('pembayaran.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
                                                        <i class="bx bx-x-circle"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        @endif
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