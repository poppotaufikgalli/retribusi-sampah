@extends('layouts.master1')
@section('title', $title ?? "Tambah Kategori Lomba")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('lomba.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="tahun" name="tahun" value="{{$konfig->tahun}}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Judul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="judul" name="judul" value="{{isset($data) ? $data->judul : old('judul')}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control form-control-sm" id="ket" name="ket">{{isset($data) ? $data->ket : old('ket')}}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Jumlah Pos Penilaian</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control form-control-sm" id="jml_pos" name="jml_pos" value="{{isset($data) ? $data->jml_pos : old('jml_pos')}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck1" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="gridCheck1">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">{{$next ?? "Simpan"}}</button>
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