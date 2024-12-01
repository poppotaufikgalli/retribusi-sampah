@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('pembayaran.'.$next)}}" id="formVerifikasi">
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
                            <div class="row mb-1">
                                <label for="bln" class="col-sm-3 col-form-label">Bulan</label>
                                <div class="col-sm-3">
                                    <select class="form-control form-control-sm" disabled>
                                        <option value="" disabled selected>Pilih</option>
                                        @for($i=1;$i<=12;$i++)
                                            <option value="{{$i}}" {{isset($data) && $data->bln == $i ? 'selected' : old('bln')}} >{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <label for="thn" class="col-sm-3 col-form-label">Tahun</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm" value="{{isset($data) ? $data->thn : old('thn')}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="jml" class="col-sm-3 col-form-label">Jumlah</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->jml : ''}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="denda" class="col-sm-3 col-form-label">Denda</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->denda : '0'}}" oninput="this.value=this.value.replace(/[^\d]/,'')" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="total" class="col-sm-3 col-form-label">Total</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm" value="{{isset($data) ? $data->total : '0'}}" disabled>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <label for="no_tiket" class="col-sm-3 col-form-label">Nomor Karcis</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->no_karcis : '0'}}" required>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="tgl_bayar" class="col-sm-3 col-form-label">Tanggal Bayar</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control form-control-sm" value="{{isset($data) ? $data->tgl_bayar->format('Y-m-d') : ''}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="button" class="btn btn-sm btn-success text-capitalize" onclick="verifikasi(1)">
                                        <i class="bx bx-check-circle"></i>
                                        Verifikasi
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row mb-1">
                                <label for="deskripsi" class="col-sm-3 col-form-label">Karcis</label>
                                <div class="col-sm-9">
                                    <img id="imgPreview" src="{{isset($data) ? asset('storage/pembayaran/'.$file) : asset('img/No_Image_Available.jpg')}}" class="glightbox" width="auto" height="300px">
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