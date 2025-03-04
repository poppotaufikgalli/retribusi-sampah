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
                            <input type="hidden" name="id" id="id" value="{{isset($data) ? $data->id : ''}}">
                            <input type="hidden" name="npwrd" id="npwrd" value="{{isset($data) ? $data->npwrd : ''}}">
                            <input type="hidden" name="jns" value="1">
                            <div class="row mb-1">
                                <label for="npwrd1" class="col-sm-3 col-form-label">NPWRD</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" id="snpwrd" class="form-control form-control-sm" value="{{isset($data) ? $data->npwrd : ''}}" disabled>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalWR">
                                            <i class="bx bx-search"></i>
                                            Cari Wajib Retribusi
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="nama1" class="col-sm-3 col-form-label">Nama WR</label>
                                <div class="col-sm-9">
                                    <input type="text" id="snama" class="form-control form-control-sm" value="{{isset($data) ? $data->nama : ''}}" disabled>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="nama1" class="col-sm-3 col-form-label">Objek - Jenis WR</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" id="objek_retribusi" class="form-control form-control-sm" value="{{isset($data) ? $data->objek_retribusi?->nama : ''}}" disabled>
                                        <input type="text" id="jenis_retribusi" class="form-control form-control-sm" value="{{isset($data) ? $data->objek_retribusi?->jenis_retribusi?->nama : ''}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-1">
                                <label for="tgl" class="col-sm-3 col-form-label">Tgl-Bln-Thn</label>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control form-control-sm" id="tgl" name="tgl" value="{{date('d')}}" required>
                                        <select class="form-control form-control-sm" id="bln" name="bln" required>
                                            <option value="" disabled selected>Pilih</option>
                                            @for($i=1;$i<=12;$i++)
                                                <option value="{{$i}}" {{date('m') == $i ? 'selected' : ''}}>{{ $i}}</option>
                                            @endfor
                                        </select>
                                        <input type="number" class="form-control form-control-sm" id="thn" name="thn" value="{{date('Y')}}" required>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row mb-1">
                                <label for="jml" class="col-sm-3 col-form-label">Jumlah</label>
                                <div class="col-sm-3">
                                    <input type="hidden" class="form-control form-control-sm" id="jml" name="jml" value="{{isset($data) ? $data->objek_retribusi?->tarif : ''}}">
                                    <input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->objek_retribusi?->tarif : ''}}" disabled>
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
                            <div class="row mb-1">
                                <label for="pembayaran_ke" class="col-sm-3 col-form-label">Pembayaran Ke</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm" id="pembayaran_ke" name="pembayaran_ke" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-1">
                                <label for="no_tiket" class="col-sm-3 col-form-label">Nomor Karcis</label>
                                @if(isset($karcis))
                                    @if(count($karcis) > 0)
                                        @php($listKarcis = explode(", ", $karcis[0]->list_karcis))
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm" id="no_karcis" name="no_karcis" value="{{isset($karcis) ? $listKarcis[0] : old('no_karcis')}}">
                                                <input type="hidden" class="form-control form-control-sm" id="id_karcis" name="id_karcis" value="{{isset($karcis) ? $karcis[0]->id : old('id_karcis')}}">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTiket">
                                                    <i class="bx bxs-coupon"></i>
                                                    Ambil Karcis
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-sm-9">
                                            <label for="no_tiket" class="col-form-label text-danger"><i>Anda Tidak Memiliki Karcis Sesuai Jumlah Pembayaran</i></label>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-sm-9">
                                        <label for="no_tiket" class="col-form-label text-danger"><i>Anda Tidak Memiliki Karcis Sesuai Jumlah Pembayaran</i></label>
                                    </div>
                                @endif
                            </div>
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
        <div class="modal fade" id="modalWR" tabindex="-1" aria-labelledby="modalWRLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalWRLabel">Cari Wajib Retribusi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table w-100 table-sm small table-stiped table-sm" id="datatablesWp">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>NPWRD</th>
                                        <th>Nama Wajib Retribusi</th>
                                        <th>Objek - Jenis Retribusi</th>
                                        <th>Tarif Retribusi</th>
                                        <th>Pemilik</th>
                                        <th>Wilayah Kerja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($wr))
                                        @foreach($wr as $key => $value)
                                            <tr>
                                                <td class="text-center">{{$value->id}}</td>
                                                <td class="text-center">{{$value->npwrd}}</td>
                                                <td class="text-center">{{$value->nama}}</td>
                                                <td class="text-center">{{$value->objek_retribusi?->nama}} - {{$value->objek_retribusi?->jenis_retribusi?->nama}}</td>
                                                <td class="text-center">{{$value->objek_retribusi?->tarif_rp}}</td>
                                                <td class="text-center">{{$value->pemilik?->nama}}</td>
                                                <td class="text-center">{{$value->wilayah_kerja?->nama}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnCloseModal" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalTiket" tabindex="-1" aria-labelledby="modalTiket" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalTiket">Cari Daftar Karcis</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table w-100 table-sm small table-hover table-sm" id="datatablesTiket">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Nomor Karcis</th>
                                        <th>Harga</th>
                                        <th>Nomor Karcis</th>
                                        <th>ID Karcis</th>
                                        <th>Juru Pungut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($karcis))
                                        @foreach($karcis as $key => $value)
                                            @if( strlen($value->list_karcis) ){
                                                @php($listKarcis = explode(", ", $value->list_karcis))
                                                @foreach($listKarcis as $k => $v)
                                                    <tr>
                                                        <td class="text-center">{{$value->no_karcis_awal}} s/d {{$value->no_karcis_akhir}}</td>
                                                        <td class="text-center">{{Str::currency($value->harga)}}</td>
                                                        <td class="text-center">{{$v}}</td>
                                                        <td class="text-center">{{$value->id}}</td>
                                                        <td class="text-center">{{$value->juru_pungut?->name}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
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

            const datatablesWp = document.getElementById('datatablesWp');
            if (datatablesWp) {
                let table = new DataTable(datatablesWp);

                table.on('click', 'tbody tr', function () {
                    let data = table.row(this).data();
                    window.location.href = '/pembayaran/create/insidentil/'+data[0]
                });
            }

            const datatablesTiket = document.getElementById('datatablesTiket');
            if (datatablesTiket) {
                let tableA = new DataTable(datatablesTiket);

                tableA.on('click', 'tbody tr', function () {
                    let data = tableA.row(this).data();
                    //console.log(data)
                    //window.location.href = '/pembayaran/create/{{$jns}}/'+data.npwrd
                    document.getElementById('no_karcis').value = data[2]
                    document.getElementById('id_karcis').value = data[3]

                    document.getElementById('btnCloseModalA').click();
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