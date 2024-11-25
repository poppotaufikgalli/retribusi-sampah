@extends('layouts.master1')
@section('title', $title ?? 'Tambah Pendaftar')
@section('subtitle',"")
@section('content')
	<div class="container-fluid px-4">
		<div class="card mb-4">
			<div class="card-header">
				<div class="row">
					<label for="id_jenis_retribusi" class="offset-sm-8 col-sm-1 col-form-label">Cari NPWRD</label>
					<div class="col-sm-3">
						<input type="search" name="snpwrd" id="snpwrd" class="form-control form-control-sm">    
					</div>
				</div> 
			</div>
			<div class="card-body">
				<form method="POST" action="{{route('wajib_retribusi.'.$next)}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" value="{{isset($data) ? $data->npwrd : ''}}">
					<div class="row mb-1">
						<label for="id_jenis_retribusi" class="col-sm-2 col-form-label">Jenis Retribusi</label>
						<div class="col-sm-10">
							<div class="d-flex gap-2">
								<input type="hidden" name="id_jenis_retribusi" id="id_jenis_retribusi" value="{{$id_jenis_retribusi}}">
								<select class="form-control form-control-sm" name="sid_jenis_retribusi" id="sid_jenis_retribusi" {{$id_jenis_retribusi == 0 || $id_objek_retribusi == null ? '' : 'disabled'}}>
									<option value="">Pilih Jenis Retribusi</option>
									@if($jenis_retribusi)
										@foreach($jenis_retribusi as $key => $value)
											<option value="{{$value->id}}" {{$id_jenis_retribusi == $value->id ? 'selected': ''}}>{{$value->nama}}</option>
										@endforeach
									@endif
								</select>
								@if($id_jenis_retribusi != 0)
								<a href="{{route('wajib_retribusi.create', ['id_jenis_retribusi' => 0, 'id_objek_retribusi' => 0] )}}" class="btn btn-sm btn-danger">
									<i class='bx bx-x'></i>
								</a>
								@endif
							</div>
						</div>
					</div>
					<div class="row mb-1">
						<label for="id_objek_retribusi" class="col-sm-2 col-form-label">Objek Retribusi</label>
						<div class="col-sm-10">
							<div class="d-flex gap-2">
								<input type="hidden" name="id_objek_retribusi" id="id_objek_retribusi" value="{{$id_objek_retribusi}}">
								<select class="form-control form-control-sm" name="sid_objek_retribusi" id="sid_objek_retribusi" {{$id_objek_retribusi == 0 || $id_objek_retribusi == null ? '' : 'disabled'}}>
									<option value="">Pilih Objek Retribusi</option>
									@if($objek_retribusi)
										@foreach($objek_retribusi as $key => $value)
											<option value="{{$value->id}}" {{$id_objek_retribusi == $value->id ? 'selected': ''}}>{{$value->nama}}</option>
										@endforeach
									@endif
								</select>
								@if($id_objek_retribusi != 0)
								<a href="{{route('wajib_retribusi.create', ['id_jenis_retribusi' => $id_jenis_retribusi, 'id_objek_retribusi' => 0] )}}" class="btn btn-sm btn-danger">
									<i class='bx bx-x'></i>
								</a>
								@endif
							</div>
						</div>
					</div>
					<div class="row mb-1">
						<label for="npwrd" class="col-sm-2 col-form-label">NPWRD</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="npwrd" name="npwrd" value="{{isset($data) ? $data->npwrd : old('npwrd')}}" required>
						</div>
					</div>
					<div class="row mb-1">
						<label for="nama" class="col-sm-2 col-form-label">Nama Wajib Retribusi</label>
						<div class="col-sm-10">
							<input type="text" class="form-control form-control-sm" id="nama" name="nama" value="{{isset($data) ? $data->nama : old('nama')}}" required>
						</div>
					</div>
					<div class="row mb-1">
						<label for="npwrd" class="col-sm-2 col-form-label">Status</label>
						<div class="col-sm-4">
							@php($aktif = isset($data) ? ($data->aktif == 1 ? 'Aktif' : ($data->aktif == -1 ? 'Tutup' : 'Belum diaktifkan')) : '')
							<input type="text" class="form-control form-control-sm" value="{{$aktif}}" disabled>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col">
							<div class="row mb-2">
								<label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
								<div class="col-sm-8">
									<textarea class="form-control form-control-sm" id="alamat" name="alamat" required>{{isset($data) ? $data->alamat : old('alamat')}}</textarea>
								</div>
							</div>
							<div class="row mb-1">
								<label for="id_kecamatan" class="col-sm-4 col-form-label">Kecamatan</label>
								<div class="col-sm-8">
									<select class="form-control form-control-sm" name="id_kecamatan" id="id_kecamatan" required>
										<option value="" disabled selected>Pilih Kecamatan</option>
										@if($kecamatan)
											@foreach($kecamatan as $key => $value)
												<option value="{{$value->id}}" {{isset($data) && $data->id_kecamatan == $value->id ? 'selected': ''}}>{{$value->nama}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="row mb-1">
								<label for="id_kelurahan" class="col-sm-4 col-form-label">Kelurahan</label>
								<div class="col-sm-8">
									<select class="form-control form-control-sm" name="id_kelurahan" id="id_kelurahan" required>
										<option value="" disabled selected>Pilih Kelurahan</option>
									</select>
								</div>
							</div>
							<div class="row mb-1">
								<label for="id_wilayah" class="col-sm-4 col-form-label">Wilayah Kerja</label>
								<div class="col-sm-8">
									<select class="form-control form-control-sm" name="id_wilayah" id="id_wilayah">
										<option value="" disabled selected>Pilih Wilayah Kerja</option>
										@if($wilayah_kerja)
											@foreach($wilayah_kerja as $key => $value)
												<option value="{{$value->id}}" {{isset($data) && $data->id_wilayah == $value->id ? 'selected': ''}}>{{$value->nama}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<hr>
							<div class="row mb-1">
								<label for="nama" class="col-sm-4 col-form-label">Nama Pemilik</label>
								<div class="col-sm-8">
									<div class="d-flex gap-2">
										<input type="text" class="form-control form-control-sm" id="nama_pemilik" name="nama_pemilik" value="{{isset($data) ? $data->pemilik->nama : old('nama_pemilik')}}" placeholder="" required disabled>
										<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#pemilikModal">
											<i class='bx bx-search-alt'></i>
										</a>
									</div>
									<input type="hidden" class="form-control form-control-sm" id="id_pemilik" name="id_pemilik" value="{{isset($data) ? $data->id_pemilik : ''}}">
								</div>
							</div>
							<div class="row mb-1">
								<label for="nama" class="col-sm-4 col-form-label">NIK Pemilik (jika ada)</label>
								<div class="col-sm-8">
									<input type="text" class="form-control form-control-sm" id="nik_pemilik" name="nik_pemilik" value="{{isset($data) ? $data->pemilik->nik : old('nik_pemilik')}}" placeholder="" disabled>
								</div>
							</div>
							<div class="row mb-1">
								<label for="nama" class="col-sm-4 col-form-label">No HP. Pemilik</label>
								<div class="col-sm-8">
									<input type="text" class="form-control form-control-sm" id="no_hp_pemilik" name="no_hp_pemilik" value="{{isset($data) ? $data->pemilik->no_hp : old('no_hp_pemilik')}}" placeholder="" disabled>
								</div>
							</div>
							<!--<hr>
							<div class="row mb-1">
								<div class="col-sm-8 offset-sm-4">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="aktif" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
										<label class="form-check-label" for="aktif">
											Aktif
										</label>
									</div>
								</div>
							</div>
							<div class="row mb-1">
								<label for="nama" class="col-sm-4 col-form-label">Nomor SK/Penetapan</label>
								<div class="col-sm-8">
									<input type="text" class="form-control form-control-sm" id="nik_pemilik" name="nik_pemilik" value="{{isset($data) ? $data->pemilik->nik : old('nik_pemilik')}}" placeholder="">
								</div>
							</div>
							<div class="row mb-1">
								<label for="nama" class="col-sm-4 col-form-label">Tanggal SK/Penetapan</label>
								<div class="col-sm-8">
									<input type="date" class="form-control form-control-sm" id="no_hp_pemilik" name="no_hp_pemilik" value="{{isset($data) ? $data->pemilik->no_hp : old('no_hp_pemilik')}}" placeholder="">
								</div>
							</div>
							<div class="row mb-1">
								<label for="nama" class="col-sm-4 col-form-label">TMT Aktif</label>
								<div class="col-sm-8">
									<input type="date" class="form-control form-control-sm" id="nik_pemilik" name="nik_pemilik" value="{{isset($data) ? $data->pemilik->nik : old('nik_pemilik')}}" placeholder="">
								</div>
							</div>-->
						</div>
						<div class="col">
							<div class="row mb-1">
								<label for="telp_ketua" class="col-sm-4 col-form-label">Koordinat Lat / Lng</label>
								<div class="col-sm-8">
									<div class="d-flex gap-2">
										<input type="text" class="form-control form-control-sm" id="lat" name="lat" value="{{isset($data) ? $data->lat : old('lat')}}" placeholder="lat">
										<input type="text" class="form-control form-control-sm" id="lng" name="lng" value="{{isset($data) ? $data->lng : old('lng')}}" placeholder="lng">
										<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#pemilikModal">
											<i class='bx bx-map-alt'></i>
										</a>
									</div>
								</div>
							</div>
							<div class="row mb-1">
								<label for="nop_pbb" class="col-sm-4 col-form-label">NOP PBB</label>
								<div class="col-sm-8">
									<input type="text" class="form-control form-control-sm" id="nop_pbb" name="nop_pbb" value="{{isset($data) ? $data->nop_pbb : old('nop_pbb')}}" oninput="this.value=this.value.replace(/[^\d]/,'')" maxlength="18" placeholder="">
								</div>
							</div>
							<div class="row mb-1">
								<label for="nama" class="col-sm-4 col-form-label">Foto</label>
								<div class="col-sm-8">
									<div class="row g-2">
										<div class="col">
											<input type="file" name="foto" id="foto" accept="image/*" class="form-control form-control-sm mb-2">
										</div>
									</div>
								</div>
								<div class="offset-sm-4 col-sm-8">
									<img id="imgPreview" src="{{isset($data) && $data->foto != '' ? asset('storage/wr/'.$data->foto) : asset('img/No_Image_Available.jpg')}}" class="glightbox" width="auto" height="300px">
								</div>
							</div>
						</div>
					</div>
					@if($edit == true)
						<div class="row mb-1">
							<div class="col-sm-10 offset-sm-2">
								<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
								<a href="{{route('wajib_retribusi', ['id' => $id_objek_retribusi])}}" type="button" class="btn btn-sm btn-secondary">Batal</a>
							</div>
						</div>
					@endif
				</form>
			</div>
		</div>
		<div class="modal fade" id="pemilikModal" aria-labelledby="pemilikModal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="pemilikModal">Cari Data Pemilik</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="d-flex justify-content-end">
							<button id="pemilikBaruBtn" type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Data Pemilik Baru</button>
						</div>
						<table class="table table-sm small table-hover table-sm" width="100%" id="datatablesSimple">
							<thead class="table-dark text-center">
								<tr>
									<th>Nama</th>
									<th>NIK</th>
									<th>Nomor HP</th>
									<th>Pilih</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js-content')
	<script type="text/javascript">
		window.addEventListener('DOMContentLoaded', event => {
			var lsKelurahan = `{!! $kelurahan !!}`;
			// Simple-DataTables
			// https://github.com/fiduswriter/Simple-DataTables/wiki

			//var id_lomba = document.getElementById("id_lomba").value;
			//var id_peserta = document.getElementById("id_peserta").value;
			var sid_kecamatan = `{{$data->id_kecamatan ?? 0}}`
			var selectKelurahan = document.getElementById('id_kelurahan');

			if(sid_kecamatan > 0){
				doKelurahan(sid_kecamatan)
				//document.getElementById("id_kecamatan").click();
			}


			var table = new DataTable('#datatablesSimple', {
				destroy: true,
				ajax: '/api/getPemilik',
				columns: [
					{ data: 'nama' },
					{ data: 'nik' },
					{ data: 'no_hp' },
					{
						data: null,
						render: function (data, type, row) {
							//return '<a href="/edit/' + data.id + '">Edit</a>';
							return "<button class='btn btn-sm btn-primary' data-bs-dismiss='modal'>Pilih</button>";
						}
					},
				],
			});

			table.on('click', 'button', function (e) {
				let data = table.row(e.target.closest('tr')).data();
				document.getElementById('id_pemilik').value = data.id;
				document.getElementById('nama_pemilik').value = data.nama;
				document.getElementById('nik_pemilik').value = data.nik;
				document.getElementById('no_hp_pemilik').value = data.no_hp;
			});

			document.getElementById("pemilikBaruBtn").addEventListener('click', function(){
				document.getElementById('id_pemilik').value = "";
				document.getElementById('nama_pemilik').value = "";
				document.getElementById('nama_pemilik').disabled = false;
				document.getElementById('nik_pemilik').value = "";
				document.getElementById('nik_pemilik').disabled = false;
				document.getElementById('no_hp_pemilik').value = "";
				document.getElementById('no_hp_pemilik').disabled = false;
			})

			document.getElementById("sid_jenis_retribusi").addEventListener('change', function(){
				var id_jenis_retribusi = this.value
				window.location.href = "/wajib_retribusi/"+id_jenis_retribusi+"/create/{{$id_objek_retribusi ?? 0}}";
			})

			document.getElementById("sid_objek_retribusi").addEventListener('change', function(){
				var id_objek_retribusi = this.value
				window.location.href = "/wajib_retribusi/{{$id_jenis_retribusi}}/create/"+id_objek_retribusi;
			})

			document.getElementById("foto").addEventListener('change', function(){
				const [file] = this.files
				if(file){
					document.getElementById("imgPreview").src = URL.createObjectURL(file)	
				}
			})

			const pemilikModal = document.getElementById('pemilikModal');
			if (pemilikModal) {
				pemilikModal.addEventListener('show.bs.modal', event => {
					table.ajax.reload();
				})
			}

			document.getElementById("id_kecamatan").addEventListener('change', function(){
				var id_kecamatan = this.value
				doKelurahan(id_kecamatan)
			})

			function doKelurahan(id_kecamatan){
				//var id_kecamatan = this.value
				var id_kelurahan = `{{$data->id_kelurahan ?? 0}}`
				var list = JSON.parse(lsKelurahan)
				//select.innerHTML = "";
				for (var option in selectKelurahan){
					//console.log(option == "value")
					if(option.value != ""){
						selectKelurahan.remove(option);
					}
				}

				var opt = document.createElement('option');
				opt.value = "";
				opt.disabled = true;
				opt.selected = true;
				opt.innerHTML = "Pilih Kelurahan";
				selectKelurahan.appendChild(opt);
				
				list.forEach(item => {
					if(item.ref == id_kecamatan){
						var opt = document.createElement('option');
						opt.value = item.id;
						opt.innerHTML = item.nama;
						//console.log(id_kelurahan)
						if(id_kelurahan == item.id){
							//console.log(item.id)
							opt.selected = true
						}
						selectKelurahan.appendChild(opt);
					}
				})
			}
		});
	</script>
@endsection