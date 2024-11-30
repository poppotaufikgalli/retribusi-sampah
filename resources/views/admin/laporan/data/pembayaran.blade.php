@extends('layouts.master1')
@section('title', $title ?? 'Pembayaran')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('laporan.data.pembayaran')}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row mb-1">
                                <div class="col-sm-12">
                                    @if(isset($listLaporan))
                                        @foreach($listLaporan as $key => $value)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="id_laporan" value="{{$key}}" id="radio_{{$key}}" {{isset($reqData['id_laporan']) && $reqData['id_laporan'] == $key ? 'checked' : '' }} onchange="setValue({{$key}})">
                                                <label class="form-check-label" for="radio_{{$key}}">
                                                    {{$value}}
                                                </label>
                                                @if($key == 0)
                                                <div class="input-group input-group-sm mb-1">
                                                    <div class="input-group-text gap-2">
                                                        <label for="npwrd" class="col-sm-2">NPWRD</label>
                                                    </div>
                                                    <input class="form-control form-control-sm" type="text" id="npwrd" name="npwrd" value="{{isset($reqData['npwrd']) ? $reqData['npwrd'] : ''}}" required {{isset($reqData['id_laporan']) && $reqData['id_laporan'] == 0 ? '' : 'disabled' }} />
                                                </div>
                                                @endif
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
                                <div class="input-group input-group-sm mb-1">
                                    <div class="input-group-text gap-2">
                                        <input class="form-radio-input mt-0" type="radio" id="sthn" name="stglbln" value="thn" {{ isset($reqData['stglbln']) && $reqData['stglbln'] == 'thn' ? 'checked' : '' }}>
                                        <label class="col-sm-2 form-radio-label" for="sthn">
                                            Tahun
                                        </label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="thn" name="thn" value="{{isset($reqData['thn']) ? $reqData['thn'] : date('Y')}}" />
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
                    <iframe src="{{ route('laporan.data.pembayaran.pdf', $reqData) }}">
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
        function setValue(value) {
            if(value == 0){
                document.getElementById("npwrd").removeAttribute('disabled') 
            }else{
                document.getElementById("npwrd").setAttribute('disabled', 'disabled') 
                //document.getElementById("npwrd").setAttribute('required', true) 
            }
        }
    </script>
@endsection