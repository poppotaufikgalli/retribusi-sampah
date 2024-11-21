@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{route('pembayaran.'.$next)}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="npwrd" id="npwrd" value="{{isset($data) ? $data->npwrd : ''}}">
                            <input type="hidden" name="jns" value="2">
                            <div class="row mb-1">
                                <label for="npwrd1" class="col-sm-3 col-form-label">NPWRD</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" id="snpwrd" class="form-control form-control-sm" value="{{isset($data) ? $data->npwrd : ''}}" disabled>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalTagihan">
                                            <i class="bx bx-search"></i>
                                            Cari Data Tagihan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="nama1" class="col-sm-3 col-form-label">Nama WR</label>
                                <div class="col-sm-9">
                                    <input type="text" id="snama" class="form-control form-control-sm" value="{{isset($data) ? $data->wajib_retribusi?->nama : ''}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="nama1" class="col-sm-3 col-form-label">Objek - Jenis WR</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" id="snama" class="form-control form-control-sm" value="{{isset($data) ? $data->wajib_retribusi?->objek_retribusi?->nama : ''}}" disabled>
                                        <input type="text" id="snama" class="form-control form-control-sm" value="{{isset($data) ? $data->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama : ''}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <label for="bln" class="col-sm-3 col-form-label">Tagihan/ SKRD</label>
                                <div class="col-sm-6">
                                    <input type="hidden" class="form-control form-control-sm" name="no_karcis" value="{{isset($data) ? $data->no_skrd : ''}}">
                                    <input type="hidden" class="form-control form-control-sm" id="id_karcis" name="id_karcis" value="{{isset($data) ? $data->id : ''}}">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->no_skrd : ''}}" disabled>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->tgl_skrd->format('d-m-Y') : ''}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="bln" class="col-sm-3 col-form-label">Bulan - Tahun</label>
                                <div class="col-sm-5">
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" class="form-control form-control-sm" name="bln" value="{{isset($data) ? $data->bln : ''}}" >
                                        <input type="hidden" class="form-control form-control-sm" name="thn" value="{{isset($data) ? $data->thn : ''}}" >
                                        <input type="number" class="form-control form-control-sm" value="{{isset($data) ? $data->bln : ''}}" disabled>
                                        <input type="number" class="form-control form-control-sm" value="{{isset($data) ? $data->thn : ''}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="jml" class="col-sm-3 col-form-label">Jumlah</label>
                                <div class="col-sm-3">
                                    <input type="hidden" class="form-control form-control-sm" id="jml" name="jml" value="{{isset($data) ? $data->jml : ''}}">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->jml : ''}}" disabled>
                                </div>
                                <label for="denda" class="col-sm-3 col-form-label">Denda</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" id="denda" name="denda" value="0" oninput="this.value=this.value.replace(/[^\d]/,'')">
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="total" class="col-sm-3 col-form-label">Total</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm" id="total" name="total" required>
                                </div>
                            </div>
                            <hr>
                             <div class="row mb-1">
                                <label for="tipe" class="col-sm-3 col-form-label">Tipe Pembayaran</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-control-sm" id="tipe" name="tipe" required>
                                        <option value="" disabled selected>Pilih Tipe Pembayaran</option>
                                        @if(isset($tipe_pembayaran))
                                            @foreach($tipe_pembayaran as $key => $value)
                                                <option value="{{$value->id}}" {{$value->id == old('tipe') ? 'selected' : ''}}>{{$value->nama}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="tgl_bayar" class="col-sm-3 col-form-label">Tanggal Bayar</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control form-control-sm" id="tgl_bayar" name="tgl_bayar" required>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="deskripsi" class="col-sm-3 col-form-label">Upload </label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control form-control-sm" name="file">
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-sm btn-primary text-capitalize">{{$next ?? "Simpan"}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Log Kunjungan Wajib Retribusi</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{route('log_kunjungan.store')}}" enctype="multipart/form-data">
                            <input type="hidden" name="npwrd" value="{{isset($data) ? $data->npwrd : ''}}">
                            <input type="hidden" name="bln" value="{{isset($data) ? $data->bln : ''}}">
                            <input type="hidden" name="thn" value="{{isset($data) ? $data->thn : ''}}">
                            <input type="hidden" name="no_tagihan" value="{{isset($data) ? $data->no_skrd : ''}}">
                            @csrf
                            <div class="row mb-1">
                                <label for="jns" class="col-sm-3 col-form-label">Jenis Keterangan</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-control-sm" id="jns" name="jns" required>
                                        <option value="" disabled selected>Pilih Jenis Keterangan Kunjungan</option>
                                        @if(isset($listKeterangan))
                                            @foreach($listKeterangan as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="deskripsi" class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control form-control-sm"></textarea>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="tgl_kunjungan" class="col-sm-3 col-form-label">Tanggal Kunjungan</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control form-control-sm" id="tgl_kunjungan" name="tgl_kunjungan" value="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="deskripsi" class="col-sm-3 col-form-label">Upload </label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control form-control-sm" name="file">
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-sm btn-primary text-capitalize">{{$next ?? "Simpan"}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalTagihan" tabindex="-1" aria-labelledby="modalTagihan" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalTagihan">Cari Daftar Tagihan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table w-100 table-sm small table-stiped table-sm" id="datatablesTagihan">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama WR</th>
                                        <th>NPWPD</th>
                                        <th>Nomor SKRD</th>
                                        <th>Tanggal SKRD</th>
                                        <th>Bulan - Tahun</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($tagihan))
                                        @foreach($tagihan as $key => $value)
                                            <tr>
                                                <td class="text-center">{{$value->id}}</td>
                                                <td class="text-center">{{$value->npwrd}}</td>
                                                <td class="text-center">{{$value->wajib_retribusi?->nama}}</td>
                                                <td class="text-center">{{$value->no_skrd}}</td>
                                                <td class="text-center">{{$value->tgl_skrd?->format('d-m-Y')}}</td>
                                                <td class="text-center">{{$value->bln}} - {{$value->thn}}</td>
                                                <td class="text-center">{{Str::currency($value->jml)}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnCloseModalA" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', event => {
            // Simple-DataTables
            // https://github.com/fiduswriter/Simple-DataTables/wiki

            if(document.getElementById("tgl_bayar").value == ""){
                document.getElementById("tgl_bayar").valueAsDate = new Date();    
            }

            const datatablesTagihan = document.getElementById('datatablesTagihan');
            if (datatablesTagihan) {
                let table = new DataTable(datatablesTagihan);

                table.on('click', 'tbody tr', function () {
                    let data = table.row(this).data();

                    window.location.href = '/pembayaran/create/tagihan/'+data[0]
                });
            }

            document.getElementById('total').value = document.getElementById('jml').value
            
            document.getElementById('denda').addEventListener('input', function() {
                var denda = this.value
                var jml = document.getElementById('jml').value;
                document.getElementById('total').value = parseFloat(jml) + parseFloat(denda)
            }) 
        });
    </script>
@endsection