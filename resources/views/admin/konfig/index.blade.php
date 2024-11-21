@extends('layouts.master1')
@section('title',"Konfigurasi")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-2">
                    @if(isset($dataRegistrasi))
                        <a href="{{route('konfig.create', ['id_registrasi_karcis' => $dataRegistrasi->id] )}}" class="btn btn-sm btn-primary">Tambah</a>
                    @else
                        <a href="{{route('konfig.create')}}" class="btn btn-sm btn-primary">Tambah</a>
                    @endif
                </div>
                <hr class="my-1">
                <div class="table-responsive">
                    <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th width="10%">Tahun</th>
                                <th width="10%">Aktif</th>
                                <th width="35%">Target Retribusi (Murni)</th>
                                <th width="35%">Target Retribusi (APBD-P)</th>
                                <th width="10%">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($listData))
                                @foreach($listData as $key => $value)
                                    <tr>
                                        <td class="text-center">{{$key +1}}</td>
                                        <td class="text-center">{{$value->tahun}}</td>
                                        <td class="text-center">{{$value->aktif == 1 ? 'Aktif' : ''}}</td>
                                        <td class="text-center">{{$value->target_rp}}</td>
                                        <td class="text-center">{{$value->target_p_rp}}</td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{route('konfig.edit', ['id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{route('konfig.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
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
            new DataTable(datatablesSimple);
        }
    });
    </script>
@endsection