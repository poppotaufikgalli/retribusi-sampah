@extends('layouts.master1')
@section('title',"Data Pos Juri")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('pos_juri.create')}}" class="btn btn-sm btn-primary">Tambah</a>
                </div>
                <hr>
                <table class="table table-striped table-sm" id="datatablesSimple">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th width="20%">Nama Pos Juri</th>
                            <th width="20%">Juri</th>
                            <th width="20%">Kategori Lomba</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($data))
                            @foreach($data as $key => $value)
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{$value->judul}}</td>
                                    <td>{{$value->juri->name}}</td>
                                    <td>
                                        @php($lomba = explode(',', $value->id_lomba))
                                        @if($katLomba)
                                            @foreach($katLomba as $key => $value1)
                                                @if(in_array($value1->id, $lomba))
                                                    <span class="badge text-bg-dark">{{$value1->judul}}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{route('pos_juri.edit', ['id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <a href="{{route('pos_juri.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
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