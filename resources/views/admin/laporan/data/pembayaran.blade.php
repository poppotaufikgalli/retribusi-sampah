@extends('layouts.master1')
@section('title', $title ?? 'Penerimaan Juru Pungut')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('laporan.data.penerimaan')}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row mb-1">
                                <div class="col-sm-12">
                                    @if(isset($listLaporan))
                                        @foreach($listLaporan as $key => $value)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="id_laporan" value="{{$key}}" id="radio_{{$key}}" {{isset($reqData['id_laporan']) && $reqData['id_laporan'] == $key ? 'checked' : '' }}>
                                                <label class="form-check-label" for="radio_{{$key}}">
                                                    {{$value}}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="input-group input-group-sm mb-1">
                                    <div class="input-group-text gap-2">
                                        <label class="col-sm-2">Juru Pungut</label>
                                    </div>
                                    <select class="form-control form-control-sm" id="id_user" name="id_user">
                                    @if(isset($juru_pungut))
                                        <option value="" selected>Semua</option> 
                                        @foreach($juru_pungut as $key => $value)
                                            <option value="{{$value->id}}" {{isset($reqData['id_user']) && $reqData['id_user'] == $value->id ? 'selected' : ''}}>{{$value->name}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                                <div class="input-group input-group-sm mb-1">
                                    <div class="input-group-text gap-2">
                                        <input class="form-radio-input mt-0" type="radio" id="stgl" name="stglbln" value="tgl" {{ isset($reqData['stglbln']) && $reqData['stglbln'] == 'tgl' ? 'checked' : '' }}>
                                        <label class="col-sm-2 form-radio-label" for="stgl">
                                            Tanggal
                                        </label>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="tgl" name="tgl" value="{{isset($reqData['tgl']) ? $reqData['tgl'] : date('Y-m-d')}}" />
                                </div>
                                <div class="input-group input-group-sm mb-1">
                                    <div class="input-group-text gap-2">
                                        <input class="form-radio-input mt-0" type="radio" id="sbln" name="stglbln" value="bln" {{ isset($reqData['stglbln']) && $reqData['stglbln'] == 'bln' ? 'checked' : '' }}>
                                        <label class="col-sm-2 form-radio-label" for="sbln">
                                            Bulan
                                        </label>
                                    </div>
                                    <input type="month" class="form-control form-control-sm" id="bln" name="bln" value="{{isset($reqData['bln']) ? $reqData['bln'] : date('Y-m')}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-12 ms-4">
                            <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                        </div>
                    </div>
                </form>
                <hr>
                @if(isset($reqData['id_laporan']))
                <div class="ratio ratio-4x3">
                    <iframe src="{{ route('laporan.data.penerimaan.pdf', $reqData) }}">
                        This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset('folder/file_name.pdf') }}">Download PDF</a>
                    </iframe>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', event => {
            // Simple-DataTables
            // https://github.com/fiduswriter/Simple-DataTables/wiki
        });
    </script>
@endsection