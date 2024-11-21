@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('pengembalian.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id_karcis" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-2">
                        <label for="tgl_pengembalian" class="col-sm-2 col-form-label">Tanggal Pengembalian</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control form-control-sm" id="tgl_pengembalian" name="tgl_pengembalian" value="{{ (old('tgl_pengembalian') == '' ? date('Y-m-d') : old('tgl_pengembalian') )}}" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-4">
                            <textarea class="form-control form-control-sm" id="catatan" name="catatan">{{old('catatan')}}</textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="ket" class="col-sm-2 col-form-label">Deskripsi {{$route}}</label>
                        <div class="col-sm-4">
                            <textarea class="form-control form-control-sm" disabled>{{isset($data) ? ' No. '.$data->no_karcis_awal.' s/d '.$data->no_karcis_akhir : ''}}</textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="harga" name="harga" value="{{isset($data) ? Str::rupiah($data->harga) : ''}}" disabled>
                        </div>
                    </div>
                    @php($digunakan = $data->pembayaran->pluck('no_karcis')->toArray())
                    <div class="row mb-2">
                        <label for="harga" class="col-sm-2 col-form-label">Karcis telah digunakan</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="harga" name="harga" value="{{isset($data) ? implode(', ', $digunakan) : ''}}" disabled>
                        </div>
                    </div>
                    @php($max_digunakan = count($digunakan) > 0 ? max($digunakan) : $data->no_karcis_awal)
                    <div class="row mb-2">
                        <label for="no_karcis_pengembalian" class="col-sm-2 col-form-label">Nomor Karcis Akhir</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="hidden" class="form-control form-control-sm" id="no_karcis_awal" name="no_karcis_awal" value="{{isset($data) ? $data->no_karcis_awal : old('no_karcis_awal')}}" placeholder="nomor awal">
                                <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->no_karcis_awal : old('no_karcis_awal')}}" placeholder="nomor awal" disabled>
                                <span class="input-group-text py-0">s/d</span>
                                <input type="hidden" class="form-control form-control-sm" id="no_karcis_akhir" name="no_karcis_akhir"  value="{{isset($data) ? $data->no_karcis_akhir : old('no_karcis_akhir')}}" placeholder="nomor akhir">
                                <input type="hidden" class="form-control form-control-sm" id="digunakan" name="digunakan"  value="{{$max_digunakan ?? old('digunakan')}}">
                                <input type="number" class="form-control form-control-sm" id="no_karcis_pengembalian" name="no_karcis_pengembalian"  value="{{isset($data) ? $max_digunakan : old('no_karcis_pengembalian')}}" placeholder="nomor pengembalian" min="{{$max_digunakan}}" max="{{$data->no_karcis_akhir}}" required>
                            </div>
                            <small class="fst-italic text-secondary">Minimal Nomor Karcis Akhir adalah {{$max_digunakan}} </small>
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