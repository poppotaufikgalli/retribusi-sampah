@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('penyerahan.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-2">
                        <label for="nama" class="col-sm-2 col-form-label">Juru Pungut</label>
                        <div class="col-sm-10">
                            <select class="form-control form-control-sm" name="id_user_juru_pungut" id="id_user_juru_pungut" required>
                                <option value="">Pilih Juru Pungut</option>
                                @if(isset($lsJuruPungut))
                                    @foreach($lsJuruPungut as $key => $value)
                                        <option value="{{$value->id}}" {{isset($data) && $data->id_user_juru_pungut == $value->id ? 'selected': ( old('id_user_juru_pungut') == $value->id ? 'selected' : '' )}}>{{$value->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama" class="col-sm-2 col-form-label">Koordinator</label>
                        <div class="col-sm-10">
                            <select class="form-control form-control-sm" name="id_user_koordinator" id="id_user_koordinator" required>
                                <option value="">Pilih Koordinator</option>
                                @if(isset($lsKoordinator))
                                    @foreach($lsKoordinator as $key => $value)
                                        @if(isset($id_user_koordinator))
                                            <option value="{{$value->id}}" {{$id_user_koordinator == $value->id ? 'selected': ''}}>{{$value->name}}</option>
                                        @else
                                            <option value="{{$value->id}}" {{isset($data) && $data->id_user_koordinator == $value->id ? 'selected': ( old('id_user_koordinator') == $value->id ? 'selected' : '' )}}>{{$value->name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="no_serah_terima" class="col-sm-2 col-form-label">Nomor Serah Terima</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="no_serah_terima" name="no_serah_terima" value="{{isset($data) ? $data->no_serah_terima : ( old('no_serah_terima') ? old('no_serah_terima') : Str::uuid() ) }}" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tgl_penyerahan" class="col-sm-2 col-form-label">Tanggal Penyerahan</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control form-control-sm" id="tgl_penyerahan" name="tgl_penyerahan" value="{{isset($data) ? $data->tgl_penyerahan->format('Y-m-d') : old('tgl_penyerahan')}}" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea id="deskripsi" name="deskripsi" class="form-control form-control-sm">{{isset($data) ? $data->deskripsi : old('deskripsi')}}</textarea>
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

            document.getElementById('id_user_koordinator').addEventListener('change', function() {
                var id_user_koordinator = this.value
                window.location.href="/penyerahan_karcis_tagihan/create/"+id_user_koordinator
            })
        });
    </script>
@endsection