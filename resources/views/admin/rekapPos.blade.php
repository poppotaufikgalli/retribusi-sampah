@extends('layouts.master1')
@section('title', $title ?? 'Rekapitulasi Pos')
@section('subtitle',$subtitle ?? '')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row gap-2">
                    <label class="col-md-2">Kategori Lomba</label>
                    <div class="col-md-9">
                        <select class="form-control form-control-sm" id="selKategoriPeserta">
                            <option value="" selected disabled>Pilih Kategori Peserta</option>
                            @if($katLomba)
                                @foreach($katLomba as $key => $value)
                                    <option value="{{$value->id}}" {{$value->id == $id_lomba ? 'selected' : ''}}>{{$value->judul}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <label class="col-md-2">Pos Juri {{$id_juri}}</label>
                    <div class="col-md-9">
                        <select class="form-control form-control-sm" id="selJuri">
                            <option value="" selected disabled>Pilih Pos Juri</option>
                            @if($posJuri)
                                @foreach($posJuri as $k => $v)
                                    <option value="{{$v->id}}" {{$v->id == $id_juri ? 'selected' : ''}}>{{$v->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered table-sm" id="datatablesSimple">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Kategori</th>
                                <th width="30%">Sudah</th>
                                <th width="30%">Belum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{$value->judul}}</td>
                                        <td>{{$value->sudah}}</td>
                                        <td>{{$value->belum}}</td>
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
                    
                    pageLength: 25,
                });
            }

            document.getElementById("selKategoriPeserta").addEventListener('change', function() {
                //var value = this.value
                window.location.href = "/rekapPos/"+this.value
            })

            document.getElementById("selJuri").addEventListener('change', function() {
                //var value = this.value
                window.location.href = "/rekapPos/{{$id_lomba}}/"+this.value
            })
        });
    </script>
@endsection