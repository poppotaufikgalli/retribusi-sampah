@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('tagihan.create')}}" class="btn btn-sm btn-primary">Tambah</a>
                </div>
                <div class="card card-body">
                    <form method="POST" action="{{route('tagihan')}}">
                        @csrf
                        <div class="row mb-1">
                            <label for="snpwrd" class="col-sm-2 col-form-label">NPWRD</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control form-control-sm" name="snpwrd" value="{{$filter['snpwrd'] ?? ''}}">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="sbln" class="col-sm-2 col-form-label">Bulan & Tahun SKRD</label>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <input type="month" class="form-control form-control-sm" id="sbln" name="sbln" value="{{$filter['sbln'] ?? date('Y-m')}}" {{ isset($filter['semua']) && $filter['semua'] == 'on' ? 'disabled' : '' }}>
                                    <div class="input-group-text gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" id="semua" name="semua" {{ isset($filter['semua']) && $filter['semua'] == 'on' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="semua">
                                            Semua Bulan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                                <a href="{{route('tagihan')}}" class="btn btn-sm btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th width="20%">Tgl Penyerahan</th>
                                <th width="10%">SKRD</th>
                                <th width="10%">NPWRD</th>
                                <th width="10%">Nama WR</th>
                                <th width="10%">Bulan</th>
                                <th width="10%">Tahun</th>
                                <th width="10%">Jumlah</th>
                                <th width="10%">Status Bayar</th>
                                <th width="10%">Status Penyerahan ke WR</th>
                                <th width="10%">Juru Pungut</th>
                                <th width="10%">Koordinator</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>                                    
                                        <td class="text-center">{{$value->tgl_penyerahan->format('d-m-Y')}}</td>
                                        <td class="text-center">{{$value->no_skrd}}<br>tgl.{{$value->tgl_skrd->format('d-m-Y')}}</td>
                                        <td class="text-center">{{$value->npwrd}}</td>
                                        <td class="text-center">{{$value->wajib_retribusi?->nama}}</td>
                                        <td class="text-center">{{$value->bln}}</td>
                                        <td class="text-center">{{$value->thn}}</td>
                                        <td class="text-end">{{Str::rupiah($value->jml)}}</td>
                                        @if($value->stts_byr == 1)
                                        <td class="text-center text-success">Sudah Bayar</td>
                                        @else
                                        <td class="text-center text-danger">Belum Bayar</td>
                                        @endif
                                        @if($value->stts == 1)
                                        <td class="text-center text-success">Sudah Diserahkan</td>
                                        @else
                                        <td class="text-center text-danger">Belum Diserahkan</td>
                                        @endif
                                        <td class="text-center">{{$value->juru_pungut?->name}}</td>
                                        <td class="text-center">{{$value->koordinator?->name}}</td>
                                        <td>
                                            @if($value->stts_byr == 0 && $value->stts == 0)
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('tagihan.edit', ['id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{route('tagihan.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
                                                    <i class="bx bx-x-circle"></i>
                                                </a>
                                            </div>
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

            document.getElementById("semua").addEventListener('change', function(){
                document.getElementById('sbln').disabled = this.checked
            })
        });
    </script>
@endsection