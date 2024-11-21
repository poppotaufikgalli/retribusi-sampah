@extends('layouts.master1')
@section('title', $title ?? "Kategori Lomba")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('lomba.create')}}" class="btn btn-sm btn-primary">Tambah</a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th width="20%">Kategori Lomba</th>
                                <th width="10%">Tahun</th>
                                <th width="40%">Keterangan</th>
                                <th>Jumlah Pos</th>
                                <th width="20%">Pos Juri Penilai</th>
                                <th width="10%">Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{$value->judul}}</td>
                                        <td class="text-center">{{$value->tahun}}</td>
                                        <td>{{$value->ket}}</td>
                                        <td>{{$value->jml_pos}}</td>
                                        <td class="text-center">
                                            <!--<a href="{{route('pos_juri')}}">{{count($value->juri_kategori)}}</a>-->
                                            <div class="d-flex justify-content-between align-items-start">
                                                @if($value->gid == 1)
                                                    <span class="badge text-bg-dark">Semua</span>
                                                @else
                                                    <div class="row row-cols-auto gap-1 ms-0">
                                                    @foreach($value->juri_kategori as $item)
                                                        <span class="col badge text-bg-success">{{$item->name}}</span>
                                                    @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">{{$value->aktif != 1 ? 'Tidak' : ''}} Aktif</td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('lomba.edit', ['id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{route('lomba.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
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