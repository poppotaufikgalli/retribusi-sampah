@extends('layouts.master1')
@section('title', $title ?? 'Tambah Pendaftar')
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('pendaftar.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="row mb-3">
                        <label for="id_lomba" class="col-sm-2 col-form-label">Pilihan Kategori Lomba</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="id_lomba" id="id_lomba" value="{{$id_lomba}}">
                            <select class="form-control form-control-sm" name="sid_lomba" id="sid_lomba" disabled>
                                @if($katLomba)
                                    @foreach($katLomba as $key => $value)
                                        <option value="{{$value->id}}" {{$id_lomba == $value->id ? 'selected': ''}}>{{$value->judul}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="id_peserta" class="col-sm-2 col-form-label">Pilihan Kategori Peserta</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="id_peserta" id="id_peserta" value="{{$id_peserta}}">
                            <select class="form-control form-control-sm" name="sid_peserta" id="sid_peserta" disabled>
                                <option value="">Kategori Peserta</option>
                                @if($katPeserta)
                                    @foreach($katPeserta as $key => $value)
                                        <option value="{{$value->id}}" data-prefix="{{$value->no_peserta_prefix}}" {{$id_peserta == $value->id ? 'selected': ''}}>{{$value->judul}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="no_peserta" class="col-sm-2 col-form-label">Nomor Peserta</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="no_peserta" name="no_peserta" value="{{isset($data) ? $data->no_peserta : old('no_peserta')}}" placeholder="Kosongkan untuk menggunakan Nomor Peserta Terakhir">
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#nomorPesertaModal">Cek Nomor Peserta</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Regu / Instansi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="nama" name="nama" value="{{isset($data) ? $data->nama : old('nama')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control form-control-sm" id="alamat" name="alamat">{{isset($data) ? $data->alamat : old('alamat')}}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="pic" class="col-sm-2 col-form-label">Nama Kontak / PIC</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="pic" name="pic" value="{{isset($data) ? $data->pic : old('pic')}}" required>
                        </div>
                        <label for="telp" class="col-sm-2 col-form-label">Nomor WA PIC</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="telp" name="telp" value="{{isset($data) ? $data->telp : old('telp')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ketua" class="col-sm-2 col-form-label">Nama Ketua Regu</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="ketua" name="ketua" value="{{isset($data) ? $data->ketua : old('ketua')}}" required>
                        </div>
                        <label for="telp_ketua" class="col-sm-2 col-form-label">Nomor WA Ketua Regu</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" id="telp_ketua" name="telp_ketua" value="{{isset($data) ? $data->telp_ketua : old('telp_ketua')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck1" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="gridCheck1">
                                    Verifikasi
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            <a href="{{route('pendaftar', ['id' => $id_lomba])}}" type="button" class="btn btn-sm btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="nomorPesertaModal" aria-labelledby="nomorPesertaModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="nomorPesertaModal">Nomor Peserta</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm small table-hover table-sm" width="100%" id="datatablesSimple">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No Peserta</th>
                                    <th>Nama Regu/Instansi</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

            var id_lomba = document.getElementById("id_lomba").value;
            var id_peserta = document.getElementById("id_peserta").value;

            var table = new DataTable('#datatablesSimple', {
                destroy: true,
                ajax: '/api/cekNomorPeserta/'+id_lomba+'/'+id_peserta,
                columnDefs: [{
                    data: null,
                    targets: -1,
                    render: function (a, b, data, d) {
                        if (data[1] == "") {
                            return "<button class='btn btn-sm btn-primary' data-bs-dismiss='modal'>Pilih</button>";
                        }
                        return "";
                    }
                }],
            });

            table.on('click', 'button', function (e) {
                let data = table.row(e.target.closest('tr')).data();
                document.getElementById('no_peserta').value = data[0];
            });

            const nomorPesertaModal = document.getElementById('nomorPesertaModal');
            if (nomorPesertaModal) {
                nomorPesertaModal.addEventListener('show.bs.modal', event => {
                    table.ajax.reload();
                })
            }
        });
    </script>
@endsection