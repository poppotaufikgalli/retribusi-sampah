@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('log_kunjungan')}}" >
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <input type="hidden" name="verif" id="verif">
                    <input type="hidden" name="tipe" value="verif">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row mb-1">
                                <label for="npwrd1" class="col-sm-3 col-form-label">NPWRD</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->npwrd : ''}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="nama1" class="col-sm-3 col-form-label">Nama WR</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->wajib_retribusi?->nama : ''}}" disabled>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <label for="bln" class="col-sm-3 col-form-label">Bulan</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->thn : old('bln')}}" disabled>
                                </div>
                                <label for="thn" class="col-sm-3 col-form-label">Tahun</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->thn : old('thn')}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="nama1" class="col-sm-3 col-form-label">Nomor Tagihan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->no_tagihan : ''}}" disabled>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <label for="jml" class="col-sm-3 col-form-label">Jenis Keterangan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->jenis_keterangan->nama : ''}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="denda" class="col-sm-3 col-form-label">Keterangan Kunjungan</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control form-control-sm" disabled>{{isset($data) ? $data->keterangan : ''}}</textarea>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="tgl_kunjungan" class="col-sm-3 col-form-label">Tanggal Kunjungan</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control form-control-sm" value="{{isset($data) ? $data->tgl_kunjungan->format('Y-m-d') : ''}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row mb-1">
                                <label for="deskripsi" class="col-sm-3 col-form-label">Gambar</label>
                                <div class="col-sm-9">
                                    <img id="imgPreview" src="{{isset($data) ? asset('storage/kunjungan/'.$file) : asset('img/No_Image_Available.jpg')}}" class="glightbox" width="auto" height="300px">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        function verifikasi(stts) {
            document.getElementById('verif').value = stts
            document.getElementById('formVerifikasi').submit();
            // body...
        }
    </script>
@endsection