@extends('layouts.master1')
@section('title',"Tambah Pelanggaran")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        @if(!isset($data))
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="{{route('diskualifikasi.search')}}" >
                        @csrf
                        <div class="row g-2">
                            <label class="col-sm-2">Nomor Peserta</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="id_lomba" value="{{$id_lomba}}" class="form-control">
                                <input type="text" name="no_peserta" class="form-control">
                            </div>
                            <div class="col-sm-10 offset-sm-2">
                                <button class="btn btn-sm btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-2">
                        <label class="col-sm-2">Nomor Peserta</label>
                        <label class="col-sm-10 fw-semibold">{{isset($data) ? $data->no_peserta : ''}}</label>
                        <label class="col-sm-2">Nama Regu / Instansi</label>
                        <label class="col-sm-10 fw-semibold">{{isset($data) ? $data->nama : ''}}</label>
                        <label class="col-sm-2">Kategori Lomba</label>
                        <label class="col-sm-10 fw-semibold">{{isset($data) ? $data->lomba?->judul : ''}}</label>
                        <label class="col-sm-2">Kategori Peserta</label>
                        <label class="col-sm-10 fw-semibold">{{isset($data) ? $data->kategori_peserta?->judul : ''}}</label>
                    </div>
                </div>
            </div>
            @if(Auth::user()->gid == 1 || Auth::user()->gid == 5)
            <div class="card mb-4">
                <h5 class="card-header">
                    Pelanggaran
                </h5>
                <div class="card-body">
                    <form method="POST" action="{{route('diskualifikasi.'.$next)}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <div class="row">
                            <div class="mb-3 col-md-6 col-sm-12">
                                <label for="waktu_start" class="form-label">Alasan</label>
                                <select class="form-control" name="alasan" required>
                                    <option value="" selected>Pilih Alasan</option>
                                    <option>Barisan bubar sebelum memasuki garis finish</option>
                                    <option>Personil barisan tidak sesuai pada Kategori Perlombaan</option>
                                    <option>Salah satu personil atau seluruh anggota regu berlari</option>
                                    <option>Formasi barisan tidak sesuai dengan formasi barisan yang telah ditentukan</option>
                                    <option>Bantuan minum langsung diluar zona pergantian personil</option>
                                    <option>Pergantian Personil diluar regulasi yang telah ditentukan</option>
                                    <option>Melakukan atraksi pada saat memasuki garis finish</option>
                                    <option>Mengganggu barisan peserta lainnya</option>
                                    <option>Menempuh rute diluar ketentuan dari panitia pelaksana</option>
                                    <option>Melanggar kelengkapan dari ketentuan aturan nomor peserta</option>
                                    <option>Menyanyikan lagu – lagu yang mengandung unsur sara dan melanggar norma-norma yang berlaku</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6 col-sm-12">
                                <label for="waktu_finish" class="form-label">Upload Foto</label>
                                <input type="file" class="form-control" id="file" name="file" accept="image/png,image/jpg">
                            </div>
                            <div class="mb-3 col-md-12 col-sm-12">
                                <label for="waktu_finish" class="form-label">Keterangan Lokasi/Waktu Kejadian</label>
                                <input type="text" class="form-control" id="ket" name="ket">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
            @endif
            <div class="card mb-4">
                <h5 class="card-header">
                    Data Diskualifikasi
                </h5>
                <div class="card-body">
                    @if($diskualifikasi)
                        <div class="list-group">
                            @foreach($diskualifikasi as $key => $value)
                                <a href="{{$value->doc ? url('storage/'.$value->doc) : '#'}}" target="_blank" class="list-group-item list-group-item-action" aria-current="true">
                                    <div>
                                        <span class="text-decoration-underline"> {{$value->alasan}}</span>
                                        <span class="badge text-bg-dark float-end">{{$value->juri->name}} | {{$value->created_at->format('d-m-Y H:i')}}
                                    </div>
                                    <span class="small fw-semibold">
                                        {{$value->ket}}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif
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