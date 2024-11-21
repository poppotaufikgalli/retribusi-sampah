@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('karcis.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-2">
                        <label for="tgl_penyerahan" class="col-sm-2 col-form-label">Tanggal Penyerahan</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control form-control-sm" id="tgl_penyerahan" name="tgl_penyerahan" value="{{isset($data) ? $data->tgl_penyerahan->format('Y-m-d') : (old('tgl_penyerahan') == '' ? date('Y-m-d') : old('tgl_penyerahan') )}}" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="tahun" name="tahun" value="{{isset($data) ? $data->tahun : old('tahun')}}" oninput="this.value=this.value.replace(/[^\d]/,'')" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="harga" name="harga" value="{{isset($data) ? $data->harga : old('harga')}}" oninput="this.value=this.value.replace(/[^\d]/,'')" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="id_wilayah" class="col-sm-2 col-form-label">Wilayah Kerja</label>
                        <div class="col-sm-10">
                            <select class="form-control form-control-sm" name="id_wilayah" id="id_wilayah">
                                <option value="" selected disabled>Pilih Wilayah Kerja</option>
                                @if(isset($wilayah_kerja))
                                    @foreach($wilayah_kerja as $key => $value)
                                        @if((isset($data) && $data->id_wilayah == $value->id) || old('id_wilayah') == $value->id)
                                            <option value="{{$value->id}}" data-koordinator="{{$value->koordinator?->id}}" data-juru_pungut="{{$value->juru_pungut?->id}}" selected>{{$value->nama}} - {{$value->deskripsi}}</option>
                                        @else
                                            <option value="{{$value->id}}" data-koordinator="{{$value->koordinator?->id}}" data-juru_pungut="{{$value->juru_pungut?->id}}">{{$value->nama}} - {{$value->deskripsi}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="koordinator" class="col-sm-2 col-form-label">Koordinator</label>
                        <div class="col-sm-4">
                            <input type="hidden" id="id_user_koordinator" name="id_user_koordinator" value="{{isset($data) ? $data->id_user_koordinator : old('id_user_koordinator')}}">
                            <select class="form-control form-control-sm" id="koordinator" disabled>
                                <option value="" selected disabled>List Koordinator</option>
                                @if(isset($lsKoordinator))
                                    @foreach($lsKoordinator as $key => $value)
                                        @if((isset($data) && $data->id_user_koordinator == $value->id) || old('id_user_koordinator') == $value->id)
                                            <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                        @else
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="juru_pungut" class="col-sm-3 col-form-label">Juru Pungut</label>
                        <div class="col-sm-3">
                            <input type="hidden" id="id_user_juru_pungut" name="id_user_juru_pungut" value="{{isset($data) ? $data->id_user_juru_pungut : old('id_user_juru_pungut')}}">
                            <select class="form-control form-control-sm" id="juru_pungut" disabled>
                                <option value="" selected disabled>List Juru Pungut</option>
                                @if(isset($lsJuruPungut))
                                    @foreach($lsJuruPungut as $key => $value)
                                        @if((isset($data) && $data->id_user_juru_pungut == $value->id) || old('id_user_juru_pungut') == $value->id)
                                            <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                        @else
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="no_karcis_awal" class="col-sm-2 col-form-label">Nomor Karcis</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="no_karcis_awal" name="no_karcis_awal" oninput="this.value=this.value.replace(/[^\d]/,'')" value="{{isset($data) ? $data->no_karcis_awal : old('no_karcis_awal')}}" placeholder="nomor awal" required>
                                <span class="input-group-text py-0">s/d</span>
                                <input type="text" class="form-control form-control-sm" id="no_karcis_akhir" name="no_karcis_akhir" oninput="this.value=this.value.replace(/[^\d]/,'')" value="{{isset($data) ? $data->no_karcis_akhir : old('no_karcis_akhir')}}" placeholder="nomor akhir" required>
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
        var id_wilayah = document.getElementById('id_wilayah');

        if(id_wilayah.value != ''){
            //console.log(id_wilayah)
            //id_wilayah.change()
        }

        document.getElementById('id_wilayah').addEventListener('change', function(e) {            
            var option = this.options[this.selectedIndex];
            document.getElementById('koordinator').value = option.dataset.koordinator
            document.getElementById('juru_pungut').value = option.dataset.juru_pungut
            document.getElementById('id_user_koordinator').value = option.dataset.koordinator
            document.getElementById('id_user_juru_pungut').value = option.dataset.juru_pungut
        })
    });
    </script>
@endsection