@extends('layouts.master1')
@section('title',"Tambah Pengguna")
@section('subtitle',"")
@section('content')
	<div class="container-fluid px-4">
		<div class="card mb-4">
			<div class="card-body">
				<form method="POST" action="{{route('user.'.$next)}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
					<div class="row mb-3">
						<label for="name" class="col-sm-2 col-form-label">Nama</label>
						<div class="col-sm-10">
							<input type="text" class="form-control form-control-sm" id="name" name="name" value="{{isset($data) ? $data->name : old('name')}}" required>
						</div>
					</div>
					<div class="row mb-3">
						<label for="username" class="col-sm-2 col-form-label">Username</label>
						<div class="col-sm-10">
							<input type="text" class="form-control form-control-sm" id="username" name="username" value="{{isset($data) ? $data->username : old('username')}}" required>
						</div>
					</div>
					@if($next == "store")
					<div class="row mb-3">
						<label for="password" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control form-control-sm" id="password" name="password" value="">
						</div>
					</div>
					@endif
					<div class="row mb-3">
						<label for="gid" class="col-sm-2 col-form-label">Group</label>
						<div class="col-sm-10">
							<select class="form-control form-control-sm" name="gid">
								<option value="" selected disabled>Pilih Group</option>
								@foreach($jnsJuri as $key => $val)
									<option value="{{$key}}" {{isset($data) && $data->gid == $key ? "selected" : ""}}>{{$val}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row mb-3">
						<label for="foto" class="col-sm-2 col-form-label">Foto</label>
						<div class="col-sm-10">
							<label for="foto">
								@if(isset($data) && $data->foto)
									<img src="{{asset('storage/'.$data->foto)}}" id="imgPreview" style="max-width: 300px;" class="border rounded" alt="...">	
								@else
									<img src="{{asset('img/Profile_avatar_placeholder_large.png')}}" id="imgPreview" style="max-width: 300px;" class="border rounded" alt="...">	
								@endif
								
							</label>
							<input type="file" class="visually-hidden" id="foto" name="foto" accept="image/*">
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-sm-10 offset-sm-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="gridCheck1" name="aktif" {{isset($data) && $data->aktif == 1 ? 'checked' : ''}}>
								<label class="form-check-label" for="gridCheck1">
									Aktif
								</label>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-sm-10 offset-sm-2">
							<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
							<a href="{{route('user')}}" type="button" class="btn btn-sm btn-secondary">Batal</a>
							@if($next == "update" && Auth::user()->gid == 1)
								<a href="#" class="btn btn-sm btn-danger float-end" data-bs-toggle="modal" data-bs-target="#gantiPasswordModal" data-bs-uid="{{$data->id}}">Ganti Password</a>
							@endif
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="gantiPasswordModal" aria-labelledby="gantiPasswordModal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="gantiPasswordModal">Ganti Password</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form method="POST" action="{{route('password.reset2')}}">
						@csrf
						<div class="modal-body">
							<input type="hidden" name="uid" id="uid">
							<div class="row mb-3">
								<label for="password" class="col-sm-2 col-form-label">Password</label>
								<div class="col-sm-10">
									<input type="password" class="form-control form-control-sm" id="password" name="password" value="">
								</div>
							</div>
							<div class="row mb-3">
								<label for="password_confirmation" class="col-sm-2 col-form-label">Ulangi Password</label>
								<div class="col-sm-10">
									<input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" value="">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
							<button type="submit" class="btn btn-primary">Simpan</button>
						</div>
					</form>
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
				new DataTable(datatablesSimple);
			}

			const gantiPasswordModal = document.getElementById('gantiPasswordModal')
			if (gantiPasswordModal) {
			  	gantiPasswordModal.addEventListener('show.bs.modal', event => {
					// Button that triggered the modal
					const button = event.relatedTarget
					// Extract info from data-bs-* attributes
					const uid = button.getAttribute('data-bs-uid')
					// If necessary, you could initiate an Ajax request here
					// and then do the updating in a callback.

					// Update the modal's content.
					//const modalTitle = gantiPasswordModal.querySelector('.modal-title')
					const modalBodyInputUid = gantiPasswordModal.querySelector('.modal-body input#uid')

					//modalTitle.textContent = `New message to ${recipient}`
					modalBodyInputUid.value = uid
			  	})
			}

			document.getElementById("foto").addEventListener('change', function(argument) {
				const [file] = this.files
				const blah = document.getElementById("imgPreview");

				if(file){
					blah.src = URL.createObjectURL(file)
				}
			})
		});
	</script>
@endsection