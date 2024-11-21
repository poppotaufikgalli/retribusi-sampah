@extends('layouts.master1')
@section('title', $title ?? '')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nomor Peserta</label>
                    <div class="col-sm-4">
                        <form method="POST" action="{{route('penilaian', ['id' => $id])}}">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="inputEmail3" name="no_peserta" value="{{$no_peserta}}">
                                <button class="btn btn-sm btn-primary px-4">
                                    <i class="bx bx-search"></i>
                                </button>                           
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary px-4">Cari</button>
                    </div>
                </div>
                <hr>
                <table class="table table-sm table-bordered">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>Nomor Peserta</th>
                            <th>Nama Regu/Instansi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>{{$dataPeserta ? $dataPeserta->no_peserta :  ''}}</td>
                            <td>{{$dataPeserta ? $dataPeserta->nama :  ''}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm table-bordered">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>Waktu Start</th>
                            <th>Waktu Finish</th>
                            <th>Waktu Tempuh</th>
                            <th>+/-</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>
                                00:00:00
                                <button class="btn btn-sm btn-primary">
                                    <i class="bx bx-timer"></i>    
                                </button>
                            </td>
                            <td>
                                00:00:00
                                <button class="btn btn-sm btn-success">
                                    <i class="bx bx-timer"></i>    
                                </button>
                            </td>
                            <td>00:00:00</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm table-bordered">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>Nilai Pos 1</th>
                            <th>Nilai Pos 2</th>
                            <th>Nilai Pos 3</th>
                            <th>Nilai Pos 4</th>
                            <th>Nilai Pos 5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>0<button class="btn btn-sm btn-primary"><i class="bx bx-flag"></i></button></td>
                            <td>0<button class="btn btn-sm btn-primary"><i class="bx bx-flag"></i></button></td>
                            <td>0<button class="btn btn-sm btn-primary"><i class="bx bx-flag"></i></button></td>
                            <td>0<button class="btn btn-sm btn-primary"><i class="bx bx-flag"></i></button></td>
                            <td>0<button class="btn btn-sm btn-primary"><i class="bx bx-flag"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('konfig.'.$next)}}">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Waktu Start</label>
                        <div class="col-sm-4">
                            <input type="time" class="form-control form-control-sm" id="inputEmail3" name="tgl_buka" value="{{isset($data) ? $data->tgl_buka->format('Y-m-d') : old('tgl_buka')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Waktu Finish</label>
                        <div class="col-sm-4">
                            <input type="time" class="form-control form-control-sm" id="inputEmail3" name="tgl_tutup" value="{{isset($data) ? $data->tgl_tutup->format('Y-m-d') : old('tgl_tutup')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nilai Pos 1</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control form-control-sm" id="inputEmail3" name="min_no_peserta" value="{{isset($data) ? $data->min_no_peserta : old('min_no_peserta')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nilai Pos 2</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control form-control-sm" id="inputEmail3" name="min_no_peserta" value="{{isset($data) ? $data->min_no_peserta : old('min_no_peserta')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nilai Pos 3</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control form-control-sm" id="inputEmail3" name="min_no_peserta" value="{{isset($data) ? $data->min_no_peserta : old('min_no_peserta')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nilai Pos 4</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control form-control-sm" id="inputEmail3" name="min_no_peserta" value="{{isset($data) ? $data->min_no_peserta : old('min_no_peserta')}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nilai Pos 5</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control form-control-sm" id="inputEmail3" name="min_no_peserta" value="{{isset($data) ? $data->min_no_peserta : old('min_no_peserta')}}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    @if($next == 'update' && $data->aktif == 0)
                        <a href="{{route('konfig.destroy', ['id' => $data->id])}}" class="btn btn-danger" data-confirm-delete="true">Hapus</a>
                    @endif
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