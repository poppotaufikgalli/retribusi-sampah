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
                            <div class="col-sm-10 offset-sm-2">
                                <button type="button" id="btnSubmit" class="btn btn-sm btn-primary">Cari Data</button>
                                <button type="button" id="btnCetak" class="btn btn-sm btn-info text-light">Cetak Serah Terima</button>
                                <a href="{{route('penyerahan')}}" class="btn btn-sm btn-secondary">Reset</a>
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
                                    <th width="10%">Jenis</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="10%">Harga</th>
                                    <th width="10%">Total</th>
                                    <th width="10%">Tgl Penyerahan</th>
                                    <th width="10%">Koordinator</th>
                                    <th width="10%">Juru Pungut</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data))
                                    @foreach($data as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ ($key+1) }}</td>                                    
                                            <td class="text-center">{{$value->route}}</td>
                                            <td class="text-center">No. {!! $value->ket !!}</td>
                                            <td class="text-center">{{$value->jml}}</td>
                                            <td class="text-end">{{Str::rupiah($value->harga)}}</td>
                                            <td class="text-end">{{ Str::rupiah($value->jml * $value->harga)}}</td>
                                            <td class="text-center">{{$value->tgl_penyerahan->format('d-m-Y')}}</td>
                                            <td class="text-center">{{$value->koordinator?->name}}</td>
                                            <td class="text-center">{{$value->juru_pungut?->name}}</td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="{{route('penyerahan.show', ['route' => $value->route, 'id' => $value->id])}}" class="bg-info px-2 py-1 text-white bg-opacity-75 text-decoration-none">
                                                        <i class="bx bx-detail"></i>
                                                    </a>
                                                </div>
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
        <!-- Modal -->
        <div class="modal fade" id="modalKarcis" tabindex="-1" aria-labelledby="modalKarcis" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalKarcis">Cari Data Karcis</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table w-100 table-sm small table-stiped table-sm" id="datatablesKarcis">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Nomor Karcis</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnCloseModalA" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalTagihan" tabindex="-1" aria-labelledby="modalTagihan" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalTagihan">Cari Data Tagihan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table w-100 table-sm small table-stiped table-sm" id="datatablesTagihan">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>NomorSKRD</th>
                                        <th>Tgl SKRD</th>
                                        <th>NPWRD</th>
                                        <th>Bulan - Tahun</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnCloseModalB" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script type="text/javascript">
        function show(route, id){
            //console.log(id_user_juru_pungut, tgl_penyerahan)

            //document.getElementById('id_user_juru_pungut').value = id_user_juru_pungut;
            //document.getElementById('sbln').value = tgl_penyerahan;
            //document.getElementById('semua').checked = false;
            //document.getElementById("frmPenyerahan").action = "{{route('penyerahan.show', [])}}"
            //document.getElementById("frmPenyerahan").submit()
        }

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
                document.getElementById("frmPenyerahan").action = "{{route('penyerahan')}}"
                document.getElementById("frmPenyerahan").submit()
            })

            document.getElementById("btnCetak").addEventListener('click', function(e){
                //e.preventDefault()
                //document.getElementById("page").value = "show"
                document.getElementById("frmPenyerahan").action = "{{route('penyerahan.show')}}"
                document.getElementById("frmPenyerahan").submit()
            })

            const modalKarcis = document.getElementById('modalKarcis')
            modalKarcis.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const id_registrasi_karcis = button.getAttribute('data-bs-id')
                //document.getElementById("sel_id_registrasi_karcis").value = id_registrasi_karcis

                const datatablesKarcis = document.getElementById('datatablesKarcis');
                if (datatablesKarcis) {
                    let tableA = new DataTable(datatablesKarcis, {
                        destroy: true,
                        ajax: '/api/getKarcis/'+id_registrasi_karcis,
                        columns: [
                            { data: 'tahun' },
                            { data: null,
                                render: function (data,type,row,meta) {
                                    return data.no_karcis_awal +' s/d '+ data.no_karcis_akhir;
                                },
                            },
                            { data: null,
                                render: function (data,type,row,meta) {
                                    return 'Rp.'+(data.harga).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                },
                            },
                        ]
                    });

                    /*tableA.on('click', 'tbody tr', function () {
                        let data = tableA.row(this).data();
                        
                        //document.getElementById('npwrd').value = data.npwrd
                        document.getElementById('id').value = data.id
                        document.getElementById('tipe').value = "karcis"
                        
                        document.getElementById("btnCloseModalA").click()
                    });*/
                }
            })

            const modalTagihan = document.getElementById('modalTagihan')
            modalTagihan.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const id_registrasi_karcis = button.getAttribute('data-bs-id')
                //document.getElementById("sel_id_registrasi_karcis").value = id_registrasi_karcis

                const datatablesTagihan = document.getElementById('datatablesTagihan');
                if (datatablesTagihan) {
                    let tableB = new DataTable(datatablesTagihan, {
                        destroy: true,
                        ajax: '/api/getTagihan/'+id_registrasi_karcis,
                        columns: [
                            { data: 'no_skrd' },
                            { data: null,
                                render: function (data,type,row,meta) {
                                    var D = Date(data.tgl_skrd).toString().split(" ");
                                    return D[2] + "-" + D[1] + "-" + D[3];
                                },
                            },
                            { data: 'npwrd' },
                            { data: null,
                                render: function (data,type,row,meta) {
                                    return data.bln +' - '+ data.thn;
                                },
                            },
                            { data: null,
                                render: function (data,type,row,meta) {
                                    return 'Rp.'+(data.jml).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                },
                            },
                        ]
                    });

                    /*tableB.on('click', 'tbody tr', function () {
                        let data = tableB.row(this).data();
                        
                        //document.getElementById('npwrd').value = data.npwrd
                        document.getElementById('id').value = data.id
                        document.getElementById('tipe').value = "tagihan"
                        
                        document.getElementById("btnCloseModalB").click()
                    });*/
                }
            })
        });
    </script>
@endsection