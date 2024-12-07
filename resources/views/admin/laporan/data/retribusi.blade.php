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
                                        <input class="form-check-input" type="radio" name="laporan" value="{{$key}}" id="radio_{{$key}}" {{isset($id_laporan) && $id_laporan == $key ? 'checked' : '' }} onchange="setValue({{$key}})">
                                        <label class="form-check-label" for="radio_{{$key}}">
                                            {{$value}}
                                        </label>
                                        @if($key == 0)
                                        <div class="input-group input-group-sm mb-1">
                                            <div class="input-group-text gap-2">
                                                <label for="npwrd" class="col-sm-2">Objek Retribusi</label>
                                            </div>
                                            <select id="id_objek_retribusi" name="id_objek_retribusi" class="form-control form-control-sm" {{$id_objek_retribusi > 0 ? '' : 'disabled'}} required>
                                                @if(isset($objek_retribusi))
                                                    @php($optgroup = "")
                                                    <option value="" selected disabled>Pilih Objek Retribusi</option>
                                                    @foreach($objek_retribusi as $key => $value)
                                                        @if($optgroup != $value->jenis_retribusi?->nama)
                                                            <optgroup label="{{$value->jenis_retribusi?->nama}}"/>
                                                            @php($optgroup = $value->jenis_retribusi?->nama)
                                                        @endif
                                                        <option value="{{$value->id}}" {{$id_objek_retribusi == $value->id ? 'selected' : ''}}>{{$value->nama}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @endif
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
                    <iframe src="{{ route('laporan.data.retribusi.pdf', ['id' => $id_laporan, 'id_objek_retribusi' => $id_objek_retribusi]) }}">
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
                document.getElementById("id_objek_retribusi").removeAttribute('disabled') 
            }else{
                document.getElementById("id_objek_retribusi").value = ""
                document.getElementById("id_objek_retribusi").setAttribute('disabled', 'disabled') 
                //document.getElementById("npwrd").setAttribute('required', true) 
            }
        }
        window.addEventListener('DOMContentLoaded', event => {
            // Simple-DataTables
            // https://github.com/fiduswriter/Simple-DataTables/wiki
        });
    </script>
@endsection