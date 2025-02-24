@extends('layouts.master1')
@section('title', $title ?? "")
@section('subtitle',"")
@section('content')
	<div class="container-fluid px-4">
		<div class="card mb-4">
			<div class="card-body">
				<form method="POST" action="{{route('tagihan.'.$next)}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
					<input type="hidden" name="npwrd" id="npwrd" value="{{isset($data) ? $data->npwrd : old('npwrd')}}">
					<input type="hidden" name="id_wr" id="id_wr" value="{{isset($data) ? $data->id_wr : old('id_wr')}}">
					<div class="row">
						<div class="col-sm-6">
							<div class="row mb-2">
		                        <label for="tgl_penyerahan" class="col-sm-3 col-form-label">Tanggal Penyerahan</label>
		                        <div class="col-sm-4">
		                            <input type="date" class="form-control form-control-sm" id="tgl_penyerahan" name="tgl_penyerahan" value="{{isset($data) ? $data->tgl_penyerahan->format('Y-m-d') : (old('tgl_penyerahan') == '' ? date('Y-m-d') : old('tgl_penyerahan') )}}" required>
		                        </div>
		                    </div>
							<div class="row mb-1">
								<label for="npwrd1" class="col-sm-3 col-form-label">ID/NPWRD</label>
								<div class="col-sm-9">
									<div class="input-group input-group-sm">
										<input type="text" class="form-control form-control-sm" id="snpwrd" value="{{isset($data) ? '['.$data->id_wr.'] '.$data->npwrd : '['.old('id_wr').'] '.old('npwrd')}}" disabled>
										<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
											<i class="bx bx-search"></i>
											Cari WR
										</button>
									</div>
								</div>
							</div>
							<div class="row mb-1">
								<label for="nama1" class="col-sm-3 col-form-label">Nama WR</label>
								<div class="col-sm-9">
									<input type="hidden" name="nama" id="nama" value="{{isset($data) ? ($next == 'update' ? $data->wajib_retribusi->nama :$data->nama) : old('nama')}}">
									<input type="text" class="form-control form-control-sm" id="snama" value="{{isset($data) ? ($next == 'update' ? $data->wajib_retribusi->nama :$data->nama) : old('nama')}}" disabled>
								</div>
							</div>
							<div class="row mb-1">
								<label for="bln" class="col-sm-3 col-form-label">Bulan</label>
								<div class="col-sm-3">
									<select class="form-control form-control-sm" id="bln" name="bln" required>
										@for($i=1;$i<=12;$i++)
											<option value="{{$i}}" {{(isset($data) && $data->bln == $i)  ? 'selected' : ( old('bln') != "" && old('bln') == $i ? 'selected' : (date('m') == $i ? 'selected' : '') ) }}>{{$i}}</option>
										@endfor
									</select>
								</div>
								<label for="thn" class="col-sm-3 col-form-label">Tahun</label>
								<div class="col-sm-3">
									<input type="text" class="form-control form-control-sm" id="thn" name="thn" value="{{isset($data) ? $data->thn : (old('thn') =='' ? date('Y') : old('thn') )}}" oninput="this.value=this.value.replace(/[^\d]/,'')" required>
								</div>
							</div>
							<div class="row mb-1">
								<label for="jml" class="col-sm-3 col-form-label">Jumlah Tagihan</label>
								<div class="col-sm-3">
									<input type="text" class="form-control form-control-sm" id="jml" name="jml" oninput="this.value=this.value.replace(/[^\d]/,'')" value="{{isset($data) ? $data->jml : old('jml')}}">
								</div>
							</div>
							<hr>
							<div class="row mb-1">
								<label for="no_skrd" class="col-sm-3 col-form-label">Nomor SKRD</label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" id="no_skrd" name="no_skrd" value="{{isset($data) ? $data->no_skrd : old('no_skrd')}}" >
								</div>
							</div>
							<div class="row mb-1">
								<label for="tgl_skrd" class="col-sm-3 col-form-label">Tanggal SKRD</label>
								<div class="col-sm-4">
									<input type="date" class="form-control form-control-sm" id="tgl_skrd" name="tgl_skrd" value="{{isset($data) ? $data->tgl_skrd?->format('Y-m-d') : old('tgl_skrd')}}">
								</div>
							</div>
							<hr>
		                    <div class="row mb-2">
		                        <label for="koordinator" class="col-sm-3 col-form-label">Koordinator</label>
		                        <div class="col-sm-4">
		                            <input type="hidden" id="id_user_koordinator" name="id_user_koordinator" value="{{isset($data) ? $data->id_user_koordinator : old('id_user_koordinator')}}">
		                            <select class="form-control form-control-sm" id="koordinator" disabled>
		                                <option value="" selected disabled>List Koordinator</option>
		                                @if(isset($lsKoordinator))
		                                    @foreach($lsKoordinator as $key => $value)
		                                        @if((isset($data) && $data->id_user_koordinator == $value->id) || old('id_user_koordinator') == $value->id)
		                                            <option value="{{$value->id}}" selected>{{$value->name}}</option>
		                                        @else
		                                            <option value="{{$value->id}}">{{$value->name}}</option>
		                                        @endif
		                                    @endforeach
		                                @endif
		                            </select>
		                        </div>
		                        <label for="juru_pungut" class="col-sm-2 col-form-label">Juru Pungut</label>
		                        <div class="col-sm-3">
		                            <input type="hidden" id="id_user_juru_pungut" name="id_user_juru_pungut">
		                            <select class="form-control form-control-sm" id="juru_pungut" disabled>
		                                <option value="" selected disabled>List Juru Pungut</option>
		                                @if(isset($lsJuruPungut))
		                                    @foreach($lsJuruPungut as $key => $value)
		                                        @if((isset($data) && $data->id_user_juru_pungut == $value->id) || old('id_user_juru_pungut') == $value->id)
		                                            <option value="{{$value->id}}" selected>{{$value->name}}</option>
		                                        @else
		                                            <option value="{{$value->id}}">{{$value->name}}</option>
		                                        @endif
		                                    @endforeach
		                                @endif
		                            </select>
		                        </div>
		                    </div>
							<div class="row mb-1">
								<div class="col-sm-9 offset-sm-3">
									<button type="submit" class="btn btn-sm btn-primary text-capitalize">{{$next ?? "Simpan"}}</button>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="row mb-1">
								<label for="file" class="col-sm-3 col-form-label">Upload SKRD</label>
								<div class="col-sm-9">
									<input type="file" class="form-control form-control-sm" name="file">
								</div>
								<div class="col-sm-9 offset-sm-3">
									@if(isset($data) && $data->filename_skrd != "")
										<img id="imgPreview" src="{{isset($data) && $data->filename_skrd != '' ? asset('storage/tagihan/'.$data->filename_skrd) : asset('img/No_Image_Available.jpg')}}" class="glightbox" width="auto" height="300px">
									@endif
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  	<div class="modal-dialog modal-lg">
				<div class="modal-content">
			  		<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Cari Wajib Pajak</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  		</div>
			  		<div class="modal-body">
						<div class="table-responsive">
		                    <table class="table w-100 table-sm small table-stiped table-sm" id="datatablesWp">
		                        <thead class="table-dark text-center">
		                            <tr>
		                                <th>NPWRD</th>
		                                <th>Nama Wajib Retribusi</th>
		                                <th>Objek Retribusi</th>
		                                <th>Pemilik</th>
		                                <th>Wilayah Kerja</th>
		                            </tr>
		                        </thead>
		                    </table>
		                </div>
			  		</div>
			  		<div class="modal-footer">
						<button type="button" id="btnCloseModal" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

			if(document.getElementById("tgl_skrd").value == ""){
				document.getElementById("tgl_skrd").valueAsDate = new Date();    
			}

			const datatablesWp = document.getElementById('datatablesWp');
	        if (datatablesWp) {
	            let table = new DataTable(datatablesWp, {
	                ajax: '{{route("api.getWajibPajak")}}',
	                columns: [
				        { data: 'npwrd' },
				        { data: 'nama' },
				        { data: 'objek_retribusi.nama' },
				        { data: 'pemilik.nama' },
				        { data: 'wilayah_kerja.nama' },
				    ]
	            });

	            table.on('click', 'tbody tr', function () {
				    let data = table.row(this).data();
				 	document.getElementById('id_wr').value = data.id
				 	document.getElementById('npwrd').value = data.npwrd
				 	document.getElementById('snpwrd').value = '['+data.id+'] '+ (data.npwrd == null ? '' : data.npwrd)
				 	document.getElementById('snama').value = data.nama
				 	document.getElementById('nama').value = data.nama
				 	document.getElementById('jml').value = data.objek_retribusi.tarif

				 	document.getElementById('id_user_koordinator').value = data.wilayah_kerja.koordinator.id
	            	document.getElementById('id_user_juru_pungut').value = data.wilayah_kerja.juru_pungut.id
	            	document.getElementById('koordinator').value = data.wilayah_kerja.koordinator.id
	            	document.getElementById('juru_pungut').value = data.wilayah_kerja.juru_pungut.id
				    
				 	document.getElementById("btnCloseModal").click()


				});
	        }
		});
	</script>
@endsection