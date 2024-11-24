@extends('layouts.master1')
@section('title',"Dashboard")
@section('subtitle',"")
@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-end align-items-center gap-2 mb-2">
            <label class="label">Tahun Anggaran</label>
            <div>
                <select class="form-control form-control-sm" id="selTahun">
                    @if($data)
                        @foreach($data as $key => $value)
                            <option value="{{$value->tahun}}" {{$selTahun == $value->tahun ? 'selected' : ''}}>{{$value->tahun}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row mb-2">
            @if($selData)
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <div class="card h-100">
                        <div class="d-flex h-100">
                            <div class="text-bg-secondary d-flex justify-content-center align-items-center">
                                <i class="bx bx-target-lock" style="font-size: 40px;"></i>
                            </div>
                            <div class="card-body">
                                @if($selData->target_p > 0)
                                    <p class="card-text">Target APBD-P {{$selData->tahun}}</p>
                                    <h5 class="card-title">{{$selData->target_p_rp}}</h5>
                                    <p class="card-text"><small>APBD {{$selData->target_rp}}</small></p>
                                @else
                                    <p>Target APBD {{$selData->tahun}}</p>
                                    <h5 class="card-title">{{$selData->target_rp}}</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <div class="card h-100">
                        <div class="d-flex h-100">
                            <div class="text-bg-success d-flex justify-content-center align-items-center">
                                <i class="bx bx-diamond" style="font-size: 40px;"></i>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Realisasi</p>
                                <h5 class="card-title">{{Str::currency($p_thn[$selTahun] ?? 0)}}</h5>
                                <p class="card-text"><small>
                                    @if($selData->target_p > 0)
                                        {{(($p_thn[$selTahun] ?? 0) / $selData->target) * 100}} %
                                    @else
                                        {{(($p_thn[$selTahun] ?? 0) / $selData->target_p) * 100}} %
                                    @endif
                                </small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <div class="card h-100">
                        <div class="d-flex h-100">
                            <div class="text-bg-warning d-flex justify-content-center align-items-center">
                                <i class="bx bx-calendar-event" style="font-size: 40px;"></i>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Penerimaan Bulan Ini</p>
                                @php($selBln = date('m'))
                                <h5 class="card-title">{{Str::currency($p_bln[$selBln] ?? 0)}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <div class="card h-100">
                        <div class="d-flex h-100">
                            <div class="text-bg-info d-flex justify-content-center align-items-center">
                                <i class="bx bx-calendar-star" style="font-size: 40px;"></i>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Penerimaan Hari Ini</p>
                                @php($selToday = date('Y-m-d'))
                                <h5 class="card-title">{{Str::currency($p_today[$selToday] ?? 0)}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="d-flex mb-1">
            <div id="container" style="width:100%; height:400px;"></div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="bx bx-table me-1"></i>
                Data Pembayaran
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm small table-striped" id="datatablesSimple">
                        <thead class="table-dark">
                            <tr>
                                <th>NPWRD</th>
                                <th>Nama Wajib Retribusi</th>
                                <th>Alamat</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Tanggal Bayar</th>
                                <th>Juru Pungut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($d_today))
                                @foreach($d_today as $key => $value)
                                    <tr>
                                        <td>{{$value->npwrd}}</td>
                                        <td>{{$value->wajib_retribusi->nama}}</td>
                                        <td>{{$value->wajib_retribusi->alamat}}</td>
                                        <td>{{$value->bln}}</td>
                                        <td>{{$value->thn}}</td>
                                        <td>{{$value->tgl_bayar->format('d-m-Y')}}</td>
                                        <td>{{$value->user?->name}}</td>
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
                        topStart: {
                            buttons: ['excelHtml5', 'pdfHtml5']
                        }
                    }
                });
            }

            document.getElementById("selTahun").addEventListener('change', function(){
                var selTahun = this.value;
                window.location.href = `/main/${selTahun}`
            })
        });

        document.addEventListener('DOMContentLoaded', function () {
            const chart = Highcharts.chart({
                chart: {
                    renderTo: 'container',
                    type: 'line',
                    options3d: {
                        enabled: true,
                        alpha: 5,
                        beta: 15,
                        depth: 50,
                        viewDistance: 25
                    },
                },
                title: {
                    text: 'Realisasi Bulanan'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'Sept', 'Okt', 'Nov', 'Des']
                },
                yAxis: {
                    title: {
                        text: 'Nilai'
                    }
                },
                plotOptions: {
                    column: {
                        depth: 25
                    }
                },
                series: [{
                    name: 'Realisasi',
                    data: getMonthValues(),
                },{
                    name: 'Target',
                    data: getMonthTargetValues(),
                }]
            });
        });

        function getMonthValues(){
            var result = [];
            var data = @json($p_bln);
            var month = new Date()
            var n = 0;
            for (var i = 0; i <= month.getMonth(); i++) {
                n = n + data[i+1] || 0;
                result[i] = n
            }

            return result;
        }

        function getMonthTargetValues(){
            var result = [];
            var target = @json($selData?->target);
            var target_p = @json($selData?->target_p);
            var month = new Date()
            var n = target_p/12;
            for (var i = 0; i < 12; i++) {
                //n = n + data[i+1] || 0;
                result[i] = n *i
            }

            return result;
        }
    </script>
@endsection