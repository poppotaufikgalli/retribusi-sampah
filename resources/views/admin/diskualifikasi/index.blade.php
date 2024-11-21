@extends('layouts.master1')
@section('title',"Data Pelanggaran")
@section('subtitle',$subtitle->ket ?? 'Semua')
@section('content')
	<div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-primary" href="{{route('diskualifikasi.create',['id' => $id])}}">Tambah</a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table small table-stiped table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="2%">No</th>
                                <th width="2%">&nbsp;</th>
                                <th width="10%">No Peserta</th>
                                <th width="10%">Nama Regu/Instansi</th>
                                <th width="10%">Total Pelanggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if(isset($data))
    							@foreach($data as $key => $value)
                                    @if(isset($diskualifikasi[$value->id]))
        		                        <tr>
        		                            <td>{{ ($key+1) }}</td>
                                            <td>
                                                <a href="{{route('diskualifikasi.show', ['id' => $value->id])}}" class="btn btn-sm btn-danger">
                                                    <i class="bx bx-x-circle"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">{{$value->no_peserta}}</td>
                                            <td class="text-center">{{$value->nama}}</td>
                                            <td class="text-center">{{$diskualifikasi[$value->id] ?? ''}}</td>
        		                        </tr>
                                    @endif
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