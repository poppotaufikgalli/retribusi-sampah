@extends('layouts.master1')
@section('title', $title ?? "Tambah Kategori Peserta")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('objek_retribusi.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-2">
                        <label for="id_jenis_retribusi" class="col-sm-2 col-form-label">Pilihan Kategori Lomba</label>
                        <div class="col-sm-10">
                            <select class="form-control form-control-sm" name="id_jenis_retribusi">
                                @if($jenis_retribusi)
                                    @foreach($jenis_retribusi as $key => $value)
                                        <option value="{{$value->id}}" {{isset($data) && $data->id_jenis_retribusi == $value->id ? 'selected': ''}}>{{$value->nama}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Objek Retribusi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="nama" name="nama" value="{{isset($data) ? $data->nama : old('nama')}}" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control form-control-sm" id="deskripsi" name="deskripsi">{{isset($data) ? $data->deskripsi : old('deskripsi')}}</textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tarif" class="col-sm-2 col-form-label">Tarif</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="number" class="form-control form-control-sm" id="tarif" name="tarif" value="{{isset($data) ? $data->tarif : old('tarif')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="insidentil" name="insidentil" {{isset($data) && $data->insidentil == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="insidentil">
                                    Insidentil/Harian/Per Kegiatan
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="aktif">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
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