@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card card-body">
                    <form method="POST" id="frmPenyerahan">
                        @csrf
                        <div class="row mb-1">
                            <label for="nama" class="col-sm-2 col-form-label">Juru Pungut</label>
                            <div class="col-sm-2">
                                <select class="form-control form-control-sm" id="id_user_juru_pungut" name="id_user_juru_pungut">
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
                        </div>
                        <div class="row mb-1">
                            <label for="stts" class="col-sm-2 col-form-label">Pengembalian</label>
                            <div class="col-sm-3">
                                <select class="form-control form-control-sm" id="stts" name="stts">
                                    <option value="-1" {{ isset($filter['stts']) && $filter['stts'] == -1 ? 'selected' : '' }}>Semua Data</option>
                                    <option value="0" {{ isset($filter['stts']) && $filter['stts'] == 0 ? 'selected' : '' }}>Sudah Saja</option>
                                    <option value="1" {{ isset($filter['stts']) && $filter['stts'] == 1 ? 'selected' : '' }}>Belum Saja</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="button" id="btnSubmit" class="btn btn-sm btn-primary">Cari Data</button>
                                <a href="{{route('pengembalian')}}" class="btn btn-sm btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-sm small table-striped table-sm" id="datatablesSimple">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th width="10%">Juru Pungut</th>
                                    <th width="10%">Nomor Karcis</th>
                                    <th width="10%">Harga</th>
                                    <th width="10%">Karcis digunakan</th>
                                    <th width="10%">Tgl Penyerahan</th>
                                    <th width="10%">Tgl Pengembalian</th>
                                    <th width="10%">Nomor Karcis Pengembalian</th>
                                    <th width="10%">Koordinator</th>
                                    <th width="30%">Catatan</th>
                                    <th>Kembalikan</th>
                                    <th>&nbsp;</th>
                                    <!--<th>Rusak</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data))
                                    @foreach($data as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ ($key+1) }}</td>                
                                            <td class="text-center">{{$value->juru_pungut?->name}}</td>                    
                                            <td class="text-center">No. {{$value->no_karcis_awal}} s.d {{$value->no_karcis_akhir}}</td>
                                            <td class="text-end">{{Str::rupiah($value->harga)}}</td>
                                            @php($digunakan = $value->pembayaran->pluck('no_karcis'))
                                            <td class="text-center">{{ implode(', ', $digunakan->toArray()) }}</td>
                                            <td class="text-center">{{$value->tgl_penyerahan->format('d-m-Y')}}</td>
                                            <td class="text-center">{{$value->pengembalian?->tgl_pengembalian->format('d-m-Y')}}</td>
                                            <td class="text-center">{{$value->pengembalian?->list_karcis}}</td>
                                            <td class="text-center">{{$value->koordinator?->name}}</td>
                                            <td class="text-center">{{$value->pengembalian?->catatan}}</td>
                                            <td>
                                                @if(!$value->stts == 0 && count($digunakan) > 0)
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{route('pengembalian.edit', ['id' => $value->id])}}" class="bg-secondary px-2 py-1 text-white bg-opacity-75 text-decoration-none">
                                                        <i class="bx bx-refresh"></i>
                                                    </a>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->stts == 0 && count($digunakan) > 0)
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{route('pengembalian.show', ['route' => $value->route, 'id' => $value->pengembalian->id])}}" class="bg-info px-2 py-1 text-white bg-opacity-75 text-decoration-none">
                                                        <i class="bx bx-detail"></i>
                                                    </a>
                                                </div>
                                                @endif
                                            </td>
                                            <!--<td>
                                                @if(!$value->pengembalian)
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{route('pengembalian.edit', ['id' => $value->id])}}" class="bg-secondary px-2 py-1 text-white bg-opacity-75 text-decoration-none">
                                                        <i class="bx bxs-discount"></i>
                                                    </a>
                                                </div>
                                                @endif
                                            </td>-->
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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

            document.getElementById("btnSubmit").addEventListener('click', function(e){
                //e.preventDefault()
                //document.getElementById("page").value = "show"
                document.getElementById("frmPenyerahan").action = "{{route('pengembalian')}}"
                document.getElementById("frmPenyerahan").submit()
            })
        });
    </script>
@endsection