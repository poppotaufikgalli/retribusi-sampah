@extends('layouts.master1')
@section('title',"Tambah Penilaian")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        @if(!isset($data))
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="{{route('penilaian.search')}}" >
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
                        <label class="col-sm-9 fw-semibold">{{isset($data) ? $data->no_peserta : ''}}</label>
                        <div class="col-sm-1">
                            <a href="{{route('penilaian.create', ['id' => $data->lomba->id])}}" class="btn btn-sm btn-primary">
                                <i class="bx bx-search"></i>
                            </a>
                        </div>
                        <label class="col-sm-2">Nama Regu / Instansi</label>
                        <label class="col-sm-10 fw-semibold">{{isset($data) ? $data->nama : ''}}</label>
                        <label class="col-sm-2">Kategori Lomba</label>
                        <label class="col-sm-10 fw-semibold">{{isset($data) ? $data->lomba?->judul : ''}}</label>
                        <label class="col-sm-2">Kategori Peserta</label>
                        <label class="col-sm-10 fw-semibold">{{isset($data) ? $data->kategori_peserta?->judul : ''}}</label>
                        <label class="col-sm-2">Referesi Kecepatan</label>
                        <label class="col-sm-10 fw-semibold">{{$ref_kecepatan}} Km/Jam - {{$waktu_referensi_1}}</label>
                    </div>
                </div>
            </div>
            @if(Auth::user()->gid == 1 || Auth::user()->gid == 3 || Auth::user()->gid == 4)
            <div class="card mb-4">
                <h5 class="card-header">
                    Pencatatan Waktu
                </h5>
                <div class="card-body">
                    <form method="POST" action="{{route('penilaian.'.$next.'.waktu')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="id_nilai" value="1">
                        <input type="hidden" name="id_juri" value="0">
                        <input type="hidden" name="jml_pos" value="{{$data->lomba->jml_pos}}">
                        <div class="row">
                            <div class="mb-3 col-md-3 col-sm-12">
                                <label for="waktu_start" class="form-label">Waktu Start</label>
                                @if(Auth::user()->gid == 1)
                                    <input type="time" class="form-control" id="waktu_start" name="waktu_start" value="{{isset($data) ? $data->waktu_start?->format('H:i:s') : old('waktu_start')}}" step="1">
                                @elseif(Auth::user()->gid == 3)
                                    <input type="time" class="form-control" id="waktu_start" name="waktu_start" value="{{isset($data) ? $data->waktu_start?->format('H:i:s') : old('waktu_start')}}" step="1" {{isset($data) && $data->waktu_start != "" ? 'readonly' : ''}}>
                                @else
                                    <input type="time" class="form-control" id="waktu_start" name="waktu_start" value="{{isset($data) ? $data->waktu_start?->format('H:i:s') : old('waktu_start')}}" step="1" readonly>
                                @endif
                            </div>
                            <div class="mb-3 col-md-3 col-sm-12">
                                <label for="waktu_finish" class="form-label">Waktu Finish</label>
                                @if(Auth::user()->gid == 1)
                                    <input type="time" class="form-control" id="waktu_finish" name="waktu_finish" value="{{isset($data) ? $data->waktu_finish?->format('H:i:s') : old('waktu_finish')}}" step="1">
                                @elseif(Auth::user()->gid == 4)
                                    <input type="time" class="form-control" id="waktu_finish" name="waktu_finish" value="{{isset($data) ? $data->waktu_finish?->format('H:i:s') : old('waktu_finish')}}" step="1" {{isset($data) && $data->waktu_finish != "" ? 'disabled' : ''}}>
                                @else
                                    <input type="time" class="form-control" id="waktu_finish" name="waktu_finish" value="{{isset($data) ? $data->waktu_finish?->format('H:i:s') : old('waktu_finish')}}" step="1" disabled>
                                @endif
                            </div>
                            <div class="mb-3 col-md-2 col-sm-12">
                                <label for="waktu_tempuh" class="form-label">Waktu Tempuh</label>
                                <input type="hidden" class="form-control" id="waktu_tempuh" name="waktu_tempuh" value="{{isset($data) ? $data->waktu_tempuh : ''}}" step="1" readonly>
                                <input type="text" class="form-control" id="waktu_tempuh_1" name="waktu_tempuh_1" value="{{isset($data) && $data->waktu_tempuh != null ? gmdate('H:i:s', $data->waktu_tempuh) : ''}}" step="1" disabled>
                            </div>
                            <div class="mb-3 col-md-2 col-sm-12">
                                <label for="waktu_referensi" class="form-label">Waktu Referensi</label>
                                <input type="hidden" class="form-control" id="waktu_referensi" name="waktu_referensi" value="{{ $waktu_referensi }}" readonly >
                                <input type="text" class="form-control" id="waktu_referensi_1" name="waktu_referensi_1" value="{{ $waktu_referensi_1 }}" disabled>
                            </div>
                             <div class="mb-3 col-md-1 col-sm-12">
                                <label for="waktu_referensi" class="form-label">Selisih Menit</label>
                                <input type="text" class="form-control" value="{{ $selisih }}" disabled>
                            </div>
                            <div class="mb-3 col-md-1 col-sm-12">
                                <label for="nilai_waktu" class="form-label">Nilai</label>
                                <input type="number" class="form-control" id="nilai_waktu" name="nilai_waktu" value="{{$penilaian[1][0] ?? ''}}" disabled>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
            @endif
            @if($posJuri)
                @foreach($posJuri as $key => $value1)
                    @if((Auth::user()->gid == 1) || (Auth::user()->gid == 2 && Auth::user()->id == $value1->juri->id))
                        <div class="card mb-4">
                            <h5 class="card-header">
                                Pencatatan Nilai 
                                <span class="float-end">{{$value1->juri->name}}</span>
                            </h5>
                            <div class="card-body">
                                <form method="POST" action="{{route('penilaian.'.$next.'.pos')}}">
                                    @csrf
                                    <input type="hidden" name="id_juri" value="{{$value1->juri->id}}">
                                    <input type="hidden" name="jml_pos" value="{{$data->lomba->jml_pos}}">
                                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                                    <div class="row row-cols-3">
                                        <div class="mb-3">
                                            <label for="nilai_2" class="form-label">Keutuhan Barisan</label>
                                            <input type="number" class="form-control" id="nilai_2" name="nilai_2" value="{{$penilaian[2][$value1->juri->id] ?? ''}}" min="0" max="100" step="10" {{isset($penilaian[2][$value1->juri->id]) && $penilaian[2][$value1->juri->id] != "" ? (Auth::user()->gid == 1 ? '': 'disabled') : ''}}>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nilai_3" class="form-label">Kerapian</label>
                                            <input type="number" class="form-control" id="nilai_3" name="nilai_3" value="{{$penilaian[3][$value1->juri->id] ?? ''}}" min="0" max="100" step="10" {{isset($penilaian[3][$value1->juri->id]) && $penilaian[3][$value1->juri->id] != "" ? (Auth::user()->gid == 1 ? '': 'disabled') : ''}}>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nilai_4" class="form-label">Semangat</label>
                                            <input type="number" class="form-control" id="nilai_4" name="nilai_4" value="{{$penilaian[4][$value1->juri->id] ?? ''}}" min="0" max="100" step="10" {{isset($penilaian[4][$value1->juri->id]) && $penilaian[4][$value1->juri->id] != "" ? (Auth::user()->gid == 1 ? '': 'disabled') : ''}}>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    @endif  
                @endforeach
            @endif
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