@extends('layouts.master1')
@section('title', $title ?? 'Penerimaan Juru Pungut')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('laporan.piutang.tagihan')}}">
                    @csrf
                    <div class="row mb-1">
                        <label class="col-sm-2">NPWRD</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control form-control-sm" id="npwrd" name="npwrd" value="{{isset($filter['npwrd']) ? $filter['npwrd'] : ''}}" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-2">Tanggal Tagihan</label>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-1">
                                <div class="input-group-text gap-2">
                                    <input class="form-radio-input mt-0" type="radio" id="stgl" name="stglbln" value="tgl" {{ isset($reqData['stglbln']) && $reqData['stglbln'] == 'tgl' ? 'checked' : '' }}>
                                </div>
                                <input type="date" class="form-control form-control-sm" id="tgl1" name="tgl1" value="{{isset($filter['tgl1']) ? $filter['tgl1'] : date('Y-m-d')}}" />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-2">Bulan Tagihan</label>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-1">
                                <div class="input-group-text gap-2">
                                    <input class="form-radio-input mt-0" type="radio" id="sbln" name="stglbln" value="bln" {{ isset($reqData['stglbln']) && $reqData['stglbln'] == 'bln' ? 'checked' : '' }}>
                                </div>
                                <input type="month" class="form-control form-control-sm" id="bln1" name="bln1" value="{{isset($filter['bln1']) ? $filter['bln1'] : date('Y-m')}}" />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                            <a href="{{route('laporan.piutang.tagihan')}}" class="btn btn-sm btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered table-sm display" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nomor Tagihan / SKRD</th>
                                <th width="15%">Tanggal Tagihan / SKRD</th>
                                <th width="20%">NPWRD</th>
                                <th width="25%">Nama WR</th>
                                <th width="10%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($_jml = 0)
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{$value->no_skrd}}</td>
                                        <td class="text-center">{{$value->tgl_skrd->format('d-m-Y')}}</td>
                                        <td class="text-center">{{$value->npwrd}}</td>
                                        <td>{{$value->wajib_retribusi?->nama}}</td>
                                        <td class="text-end">{{Str::currency($value->jml)}}</td>
                                    </tr>
                                    @php($_jml = $_jml + $value->jml)
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
                                <th class="text-end">{{Str::currency($_jml)}}</th>
                            </tr>
                        </tfoot>
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
                    },
                    pageLength: 25,
                });
            }
        });
    </script>
@endsection