@extends('layouts.master1')
@section('title',"Tambah Pos Juri")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('pos_juri.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nama Pos Juri</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="inputEmail3" name="judul" value="{{isset($data) ? $data->judul : old('judul')}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Juri</label>
                        <div class="col-sm-10">
                            <select class="form-control form-control-sm" name="user_id">
                                <option value="" selected>Pilih Juri</option>
                                @if($juri)
                                    @foreach($juri as $key => $value1)
                                        <option value="{{$value1->id}}" {{isset($data) && $data->user_id == $value1->id ? 'selected' : ''}}>{{$value1->username}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Kategori Lomba</label>
                        <div class="col-sm-10">
                            @php($lomba = isset($data) ? explode(',', $data->id_lomba) : [])
                            @if($katLomba)
                                @foreach($katLomba as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="id_lomba[]" value="{{$value->id}}" id="ch-{{$value->id}}" {{in_array($value->id, $lomba) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="ch-{{$value->id}}">
                                            {{$value->judul}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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