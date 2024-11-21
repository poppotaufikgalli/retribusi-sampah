@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('wilayah.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Wilayah Kerja</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="nama" name="nama" value="{{isset($data) ? $data->nama : old('nama')}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="deskripsi" name="deskripsi" value="{{isset($data) ? $data->deskripsi : old('deskripsi')}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">List Koordinator</label>
                        <div class="col-sm-10">
                            @if(isset($lsKoordinator))
                                @foreach($lsKoordinator as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="id_user_koordinator" id="koor_{{$value->id}}" value="{{$value->id}}" {{isset($data) && $data->id_user_koordinator == $value->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="koor_{{$value->id}}">
                                            {{$value->name}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">List Juru Pungut</label>
                        <div class="col-sm-10">
                            @if(isset($lsJuruPungut))
                                @foreach($lsJuruPungut as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="id_user_juru_pungut" id="jungut_{{$value->id}}" value="{{$value->id}}" {{isset($data) && $data->id_user_juru_pungut ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jungut_{{$value->id}}">
                                            {{$value->name}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <!--<div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="aktif">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>-->
                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary text-capitalize">{{$next ?? "Simpan"}}</button>
                        </div>
                    </div>
                </form>
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