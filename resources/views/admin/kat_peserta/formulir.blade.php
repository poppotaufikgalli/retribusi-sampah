@extends('layouts.master1')
@section('title', $title ?? "Tambah Kategori Peserta")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('kat_peserta.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-3">
                        <label for="id_lomba" class="col-sm-2 col-form-label">Pilihan Kategori Lomba</label>
                        <div class="col-sm-10">
                            <select class="form-control form-control-sm" name="id_lomba">
                                @if($katLomba)
                                    @foreach($katLomba as $key => $value)
                                        <option value="{{$value->id}}" {{isset($data) && $data->id_lomba == $value->id ? 'selected': ''}}>{{$value->judul}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="judul" class="col-sm-2 col-form-label">Kategori Peserta</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="judul" name="judul" value="{{isset($data) ? $data->judul : old('judul')}}" required>
                        </div>
                    </div>
                    <!--<div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control form-control-sm" id="inputPassword3" name="ket">{{isset($data) ? $data->ket : old('ket')}}</textarea>
                        </div>
                    </div>-->
                    <div class="row mb-3">
                        <label for="ref_kecepatan" class="col-sm-2 col-form-label">Referensi Kecepatan</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="number" class="form-control form-control-sm" id="ref_kecepatan" name="ref_kecepatan" value="{{isset($data) ? $data->ref_kecepatan : old('ref_kecepatan')}}" required>
                                <span class="input-group-text">Km/jam</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="no_peserta_mulai" class="col-sm-2 col-form-label">Mulai Nomor Peserta</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control form-control-sm" id="no_peserta_mulai" name="no_peserta_mulai" value="{{isset($data) ? $data->no_peserta_mulai : old('no_peserta_mulai')}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="no_peserta_prefix" class="col-sm-2 col-form-label">Prefix Nomor Peserta</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control form-control-sm" id="no_peserta_prefix" name="no_peserta_prefix" value="{{isset($data) ? $data->no_peserta_prefix : old('no_peserta_prefix')}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="aktif">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
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