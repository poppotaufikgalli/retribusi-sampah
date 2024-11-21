@extends('layouts.master1')
@section('title',"Konfigurasi")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('konfig.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-3">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm" id="tahun" name="tahun" value="{{isset($data) ? $data->tahun : old('tahun')}}" oninput="this.value=this.value.replace(/[^\d]/,'')" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="target" class="col-sm-2 col-form-label">Target APBD (Murni)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="target" name="target" value="{{isset($data) ? $data->target : old('target')}}" oninput="this.value=this.value.replace(/[^\d]/,'')" required>
                        </div>
                        <label for="target_p" class="col-sm-2 col-form-label">Target APBD-P</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="target_p" name="target_p" value="{{isset($data) ? $data->target_p : old('target_p')}}" oninput="this.value=this.value.replace(/[^\d]/,'')">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="aktif" class="col-sm-2 col-form-label">Tahun Aktif</label>
                        <div class="col-sm-10">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="aktif">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
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
        });
    </script>
@endsection