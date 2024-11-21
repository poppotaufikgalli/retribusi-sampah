@extends('layouts.master1')
@section('title',"Data Pengguna")
@section('subtitle', '')
@section('content')
	<div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-primary" href="{{route('user.create')}}">Tambah</a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">&nbsp;</th>
                                <th width="20%">Nama</th>
                                <th width="10%">Username</th>
                                <th width="15%">Group</th>
                                <th width="10%">Status</th>
                                <th width="10%">Device ID</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        	@if(isset($data))
    							@foreach($data as $key => $value)
    		                        <tr>
    		                            <td>{{ ($key+1) }}</td>
                                        <td >
                                            @if($value->foto)
                                                <a href="{{asset('storage/'.$value->foto)}}" class="glightbox">
                                                    <img class="img-fluid w-50" src="{{asset('storage/'.$value->foto)}}" />
                                                </a>
                                            @else
                                                <img class="img-fluid w-50" src="{{asset('img/Profile_avatar_placeholder_large.png')}}" />
                                            @endif
                                        </td>
                                        <td align="left">{{$value->name}}</td>
                                        <td>{{$value->username}}</td>
    		                            <td>{{$jnsJuri[$value->gid]}}</td>
    		                            <td>{{$value->aktif == 1 ? '' : 'Tidak'}} Aktif</td>
                                        <td>{{$value->device_id}}</td>
    		                            <td>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('user.edit', ['id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{route('user.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
                                                    <i class="bx bx-x-circle"></i>
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
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging',
                    },
                });
            }
        });
    </script>
@endsection