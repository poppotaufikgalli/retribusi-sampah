@extends('layouts.master1')
@section('title', $title ?? 'Penerimaan Juru Pungut')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('laporan.data.retribusi')}}">
                    @csrf
                    <div class="row mb-1">
                        <div class="col-sm-12">
                            @if(isset($listLaporan))
                                @foreach($listLaporan as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="laporan" value="{{$key}}" id="radio_{{$key}}" {{isset($id_laporan) && $id_laporan == $key ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radio_{{$key}}">
                                            {{$value}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-sm-12 ms-4">
                            <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                        </div>
                    </div>
                </form>
                <hr>
                @if(isset($id_laporan))
                <div class="ratio ratio-4x3">
                    <iframe src="{{ route('laporan.data.retribusi.pdf', ['id' => $id_laporan]) }}">
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