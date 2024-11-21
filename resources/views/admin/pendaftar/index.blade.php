@extends('layouts.master1')
@section('title',"Data Pendaftar")
@section('subtitle',$subtitle->judul ?? 'Semua')
@section('content')
	<div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-2">
                    <label class="col-md-2">Kategori Lomba</label>
                    <div class="col-md-9">
                        <select class="form-control form-control-sm" id="selKategoriLomba">
                            <option value="0" {{$id_lomba == 0 ? 'selected': ''}}>Semua</option>
                            @if($katLomba)
                                @foreach($katLomba as $key => $value)
                                    <option value="{{$value->id}}" {{$id_lomba == $value->id ? 'selected': ''}}>{{$value->judul}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1">
                        <!--<a class="btn btn-sm btn-primary {{$id_lomba == 0 ? 'disabled' : ''}}" href="{{route('pendaftar.create', ['id_lomba'=> $id_lomba])}}" >Tambah</a>-->
                        <button id="btnTambahPendaftar" class="btn btn-sm btn-primary" {{$id_lomba == 0 || $id_peserta == null ? 'disabled' : ''}}>Tambah</button>
                    </div>
                    <label class="col-md-2">Kategori Peserta</label>
                    <div class="col-md-9">
                        <select class="form-control form-control-sm" id="selKategoriPeserta">
                            <option value="" {{$id_peserta == null ? 'selected': ''}}>Semua</option>
                            @if($katPeserta)
                                @foreach($katPeserta as $key => $value)
                                    <option value="{{$value->id}}" {{$id_peserta == $value->id ? 'selected': ''}}>{{$value->judul}}</option>
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
                                <th width="5%">No</th>
                                <th width="10%">No Peserta</th>
                                <th width="30%">Nama Regu/Instansi</th>
                                <th width="30%">Kategori</th>
                                <th>PIC/No.WA</th>
                                <th width="5%">Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if(isset($data))
    							@foreach($data as $key => $value)
    		                        <tr>
    		                            <td>{{ ($key+1) }}</td>
                                        <td class="text-center">{{$value->no_peserta}}</td>
    		                            <td>{{$value->nama}}</td>
                                        <td>{{$value->lomba?->judul}} - {{$value->kategori_peserta?->judul}}</td>
                                        <td>{{$value->pic}}/ {{$value->telp}}</td>
    		                            <td>
                                            <div class="d-flex justify-content-center">
                                                @if($value->aktif == -1)
                                                    <div class="bg-danger px-2 py-1 text-white bg-opacity-75">
                                                        <i class="bx bx-x"></i>
                                                    </div>
                                                @elseif($value->aktif == 1)
                                                    <div class="bg-success px-2 py-1 text-white bg-opacity-75">
                                                        <i class="bx bx-check"></i>
                                                    </div>
                                                @else
                                                    <a href="{{route('pendaftar.edit', ['id' => $value->id])}}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bx bx-detail"></i>
                                                    </a>
                                                @endif      
                                            </div>
                                        </td>
                                        <td>
                                            @if(Auth::user()->gid == 1)
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('pendaftar.edit', ['id_lomba' => $value->id_lomba , 'id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{route('pendaftar.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
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

            document.getElementById("selKategoriLomba").addEventListener('change', function() {
                //var value = this.value
                window.location.href = "/pendaftar/"+this.value
            })

            document.getElementById("selKategoriPeserta").addEventListener('change', function() {
                //var value = this.value
                //var id_lomba = '{{$id_lomba}}';
                window.location.href = "/pendaftar/{{$id_lomba}}/"+this.value
            })

            document.getElementById("btnTambahPendaftar").addEventListener('click', function() {
                //var value = this.value
                //var id_lomba = '{{$id_lomba}}';
                //var id_peserta = '{{$id_peserta}}'
                window.location.href = "/pendaftar/{{$id_lomba}}/create/{{$id_peserta}}";
            })

        });
    </script>
@endsection