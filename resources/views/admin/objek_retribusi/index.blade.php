@extends('layouts.master1')
@section('title', $title ?? "Lomba Gerak Jalan Proklamasi")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('objek_retribusi.create')}}" class="btn btn-sm btn-primary">Tambah</a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th width="30%">Nama Objek Retribusi</th>
                                <th width="20%">Jenis Retribusi</th>
                                <th width="30%">Deskripsi</th>
                                <th width="10%">Tarif Perda</th>
                                <th width="10%">Insidentil/Harian/Perkegiatan</th>
                                <th width="10%">Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>                                    
                                        <td>{{$value->nama}}</td>
                                        <td>{{$value->jenis_retribusi->nama}}</td>
                                        <td>{{$value->deskripsi}}</td>
                                        <td class="text-end">{{ Str::currency($value->tarif)}}</td>
                                        <td class="text-center">{{$value->insidentil != 1 ? '-' : 'Ya'}}</td>
                                        <td class="text-center">{{$value->aktif != 1 ? 'Tidak' : ''}} Aktif</td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('objek_retribusi.edit', ['id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{route('objek_retribusi.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
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
                }
            });
        }
    });
    </script>
@endsection