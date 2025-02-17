@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('karcis.create')}}" class="btn btn-sm btn-primary">Tambah</a>
                </div>
                <div class="card card-body">
                    <form method="POST" action="{{route('karcis')}}">
                        @csrf
                        <div class="row mb-1">
                            <label for="nama" class="col-sm-2 col-form-label">Juru Pungut</label>
                            <div class="col-sm-5">
                                <select class="form-select form-select-sm" id="id_user_juru_pungut" name="id_user_juru_pungut">
                                    <option value="" selected disabled>Semua Juru Pungut</option>
                                    @if(isset($lsJuruPungut))
                                        @foreach($lsJuruPungut as $key => $value)
                                            <option value="{{$value->id}}" {{ isset($filter['id_user_juru_pungut']) && $filter['id_user_juru_pungut'] == $value->id ? 'selected' : '' }}>{{$value->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="nama" class="col-sm-2 col-form-label">Bulan & Tahun Penyerahan</label>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <input type="month" class="form-control form-control-sm" id="sbln" name="sbln" value="{{$filter['sbln'] ?? date('Y-m')}}" {{ isset($filter['semua']) && $filter['semua'] == 'on' ? 'disabled' : '' }}>
                                    <div class="input-group-text gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" id="semua" name="semua" {{ isset($filter['semua']) && $filter['semua'] == 'on' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="semua">
                                            Semua Bulan
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <label for="nama" class="col-sm-2 col-form-label">Tahun Karcis</label>
                            <div class="col-sm-3">
                               <select class="form-select form-select-sm" id="tahun" name="tahun">
                                    <option value="" selected>Semua Tahun</option>
                                    @for($i= date('Y'); $i >= 2023; $i--)
                                        <option value="{{$i}}" {{ isset($filter['tahun']) && $filter['tahun'] == $i ? 'selected' : '' }}>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                                <a href="{{route('karcis')}}" class="btn btn-sm btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th width="10%">Juru Pungut</th>
                                <th width="10%">Tanggal Penyerahan</th>
                                <th width="10%">Tahun</th>
                                <th width="10%">Harga</th>
                                <th width="15%">Nomor Karcis</th>
                                <th width="5%">Karcis digunakan</th>
                                <th width="15%">Wilayah</th>
                                <th width="10%">Koordinator</th>
                                <th width="5%">Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ ($key+1) }}</td>
                                        <td class="text-center">{{$value->juru_pungut?->name}}</td>
                                        <td class="text-center">{{$value->tgl_penyerahan->format('d-m-Y')}}</td>
                                        <td class="text-center">{{$value->tahun}}</td>
                                        <td class="text-center">{{Str::currency($value->harga)}}</td>
                                        <td class="text-center">{{$value->no_karcis_awal}} s/d {{$value->no_karcis_akhir}}</td>
                                        @php($digunakan = implode(", ", $value->pembayaran->pluck('no_karcis')->toArray()))
                                        <td class="text-center">{{$digunakan}}</td>
                                        <td class="text-center">{{$value->wilayah_kerja?->nama}}</td>
                                        <td class="text-center">{{$value->koordinator?->name}}</td>
                                        <td class="text-center">
                                            @if($value->stts == 1)
                                                <i class="bx bxs-check-square text-success fs-4"></i>
                                            @else
                                                <i class="bx bxs-x-square text-secondary fs-4"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$digunakan && $value->stts == 1)
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{route('karcis.edit', ['id' => $value->id] )}}" class="bg-warning px-2 py-1 text-dark bg-opacity-75 text-decoration-none">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <a href="{{route('karcis.destroy', ['id' => $value->id] )}}" class="bg-danger px-2 py-1 text-white bg-opacity-75 text-decoration-none" data-confirm-delete="true">
                                                        <i class="bx bx-x-circle"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
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

            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                new DataTable(datatablesSimple, {
                    layout: {
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging',
                    }
                });
            }

            document.getElementById("semua").addEventListener('change', function(){
                document.getElementById('sbln').disabled = this.checked
            })
        });
    </script>
@endsection