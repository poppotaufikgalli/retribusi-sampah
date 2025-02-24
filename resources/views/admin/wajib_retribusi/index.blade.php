@extends('layouts.master1')
@section('title',"Data Wajib Retribusi")
@section('subtitle',$subtitle->judul ?? 'Semua')
@section('content')
	<div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                 <form method="POST" action="{{route('wajib_retribusi')}}" id="frmWR">
                    @csrf
                    <div class="row g-2 mb-2">
                        <label class="col-md-2">Jenis Retribusi</label>
                        <div class="col-md-8">
                            <select class="form-control form-control-sm" id="selJenisRetribusi" name="id_jenis_retribusi">
                                <option value="0" {{$id_jenis_retribusi == 0 ? 'selected': ''}}>Semua</option>
                                @if($jenis_retribusi)
                                    @foreach($jenis_retribusi as $key => $value)
                                        <option value="{{$value->id}}" {{$id_jenis_retribusi == $value->id ? 'selected': ''}}>{{$value->nama}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{route('wajib_retribusi.create', [
                                'id_jenis_retribusi' => $id_jenis_retribusi ?? 0,
                                'id_objek_retribusi' => $id_objek_retribusi ?? 0
                            ])}}" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus-circle"></i>
                                Tambah
                            </a>
                        </div>
                        <label class="col-md-2">Objek Retribusi</label>
                        <div class="col-md-8">
                            <select class="form-control form-control-sm" id="selObjekRetribusi" name="id_objek_retribusi">
                                <option value="" {{$id_objek_retribusi == null ? 'selected': ''}}>Semua</option>
                                @if($objek_retribusi)
                                    @foreach($objek_retribusi as $key => $value)
                                        <option value="{{$value->id}}" {{$id_objek_retribusi == $value->id ? 'selected': ''}}>{{$value->nama}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <label class="col-md-2">Nama / NPWRD</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" name="nama" id="nama" value="{{$nama}}" placeholder="cari dengan Nama Wajib Retribusi atau NPWRD">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bx bx-search-alt"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-stiped table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">NPWRD</th>
                                <th width="15%">Nama Wajib Retribusi</th>
                                <th width="15%">Alamat WR</th>
                                <th width="15%">Objek Retribusi</th>
                                <th width="15%">Jenis Retribusi</th>
                                <th width="15%">Pemilik</th>
                                <th width="15%">Wilayah Kerja</th>
                                <th width="5%">Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if(isset($data))
    							@foreach($data as $key => $value)
    		                        <tr>
                                        <td class="text-center">{{ ($data->perPage() * ($data->currentPage() - 1)) + ($key +1) }}</td>
                                        <td class="text-center">{{$value->npwrd}}</td>
    		                            <td>{{$value->nama}}</td>
                                        <td>{{$value->alamat}}</td>
                                        <td>{{$value->objek_retribusi?->nama}}</td>
                                        <td>{{$value->objek_retribusi?->jenis_retribusi?->nama}}</td>
                                        <td>{{$value->nama_pemilik}}</td>
                                        <td>{{$value->nama_wilayah}}</td>
    		                            <td>
                                            <div class="d-flex justify-content-center">
                                                @if($value->aktif == -1)
                                                    <a href="{{route('wajib_retribusi.show', ['id_objek_retribusi' => $value->id_objek_retribusi, 'id' => $value->id])}}" class="bg-danger border px-2 py-1 text-white bg-opacity-75 ">
                                                        <i class="bx bx-x"></i>
                                                    </a>
                                                @elseif($value->aktif == 1)
                                                    <a href="{{route('wajib_retribusi.show', ['id_objek_retribusi' => $value->id_objek_retribusi, 'id' => $value->id])}}" class="bg-success border px-2 py-1 text-white bg-opacity-75 ">
                                                        <i class="bx bx-check"></i>
                                                    </a>
                                                @else
                                                    <a href="{{route('wajib_retribusi.show', ['id_objek_retribusi' => $value->id_objek_retribusi, 'id' => $value->id])}}" class="border border-info px-2 py-1 text-info ">
                                                        <i class="bx bx-detail"></i>
                                                    </a>
                                                @endif      
                                            </div>
                                        </td>
                                        <td>
                                            @if(Auth::user()->gid == 1)
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('wajib_retribusi.edit', ['id_objek_retribusi' => $value->id_objek_retribusi, 'id' => $value->id] )}}" class="bg-warning border border-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{route('wajib_retribusi.destroy', ['id' => $value->id] )}}" class="bg-danger border border-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
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
                    <div class="mt-3">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', event => {
           document.getElementById("selJenisRetribusi").addEventListener('change', function() {
                //var value = this.value
                //window.location.href = "/wajib_retribusi/"+this.value
                document.getElementById('frmWR').submit()
            })

            document.getElementById("selObjekRetribusi").addEventListener('change', function() {
                //var value = this.value
                //window.location.href = "/wajib_retribusi/{{$id_jenis_retribusi}}/"+this.value
                document.getElementById('frmWR').submit()
            })

        });
    </script>
@endsection