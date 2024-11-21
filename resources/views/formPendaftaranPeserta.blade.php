@extends('layouts.master')
@section('title',"judul")
@section('content')
	@include('partials.menu')
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<main id="main">
		<!-- ======= Frequently Asked Questions Section ======= -->
		<section id="" class="contact section">
			<div class="container">
				<div class="mt-4"/>
				<div class="section-title">
						<h2>Form Pendaftaran Peserta</h2>
						<h3>{{$lomba?->judul}}</h3>
						<p>Isilah form berikut sesuai dengan data kepesertaan</p>
				</div>

				<form action="{{route('daftar-umum')}}" method="post" role="form" class="php-email-form">
					@csrf
					<div class="form-group">
						<select class="form-control" name="id_lomba" id="id_lomba" required>
							<option value="" disabled selected>Pilihan Kategori Lomba</option>
							@if($katLomba)
								@foreach($katLomba as $key => $value)
									<option value="{{$value->id}}" {{$id_lomba == $value->id ? 'selected' : ''}}>{{$value->judul}}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="form-group">
						<select class="form-control" name="id_peserta" id="id_peserta" required>
							<option value="" disabled selected>Pilihan Kategori Peserta</option>
							@if($katPeserta)
								@foreach($katPeserta as $key => $value)
									<option value="{{$value->id}}" {{$id_peserta == $value->id ? 'selected' : ''}}>{{$value->judul}}</option>
								@endforeach
							@endif
						</select>
					</div>
					@if($id_peserta == 9 || $id_peserta == 1)
					<div class="form-group">
						<select class="form-control" name="jns_instansi" id="jns_instansi" required>
							<option value="" disabled selected>Pilihan Jenis Instansi</option>
							<option value="1">TNI AD</option>
							<option value="2">TNI AL</option>
							<option value="3">TNI AU</option>
							<option value="4">POLRI</option>
							<option value="5">LAINNYA</option>
						</select>
					</div>
					@endif
					<div class="form-group">
						<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Regu/Instansi" value="{{old('nama')}}" required>
					</div>
					<div class="form-group">
						<textarea class="form-control" name="alamat" rows="5" placeholder="Alamat" required>{{old('alamat')}}</textarea>
					</div>
					<div class="row">
						<div class="col-md-6 form-group">
							<input type="text" name="pic" class="form-control" id="pic" placeholder="Nama Kontak PIC" value="{{old('pic')}}" required>
						</div>
						<div class="col-md-6 form-group mt-md-0">
							<input type="text" class="form-control" name="telp" id="telp" placeholder="Nomor Telepon/Wa PIC" value="{{old('telp')}}" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 form-group">
							<input type="text" name="ketua" class="form-control" id="ketua" placeholder="Nama Ketua Regu" value="{{old('ketua')}}" required>
						</div>
						<div class="col-md-6 form-group mt-md-0">
							<input type="text" class="form-control" name="telp_ketua" id="telp_ketua" placeholder="Nomor Telepon/Wa Ketua Regu" value="{{old('telp_ketua')}}" required>

						</div>
					</div>
					<div class="row">
						<div class="col-md-6 form-group form-check">
							<input type="checkbox" name="pakta" class="" id="pakta" required>
							<label class="form-check-label fw-semibold" for="pakta">Saya Bersedia mengisi <a href="{{asset('PAKTA.INTEGRITAS.docx')}}" target="_blank" class="text-decoration-underline">Pakta Integritas</a></label>
						</div>
					</div>
					<div class="d-flex justify-content-center align-items-center mb-2">
						<div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
					</div>
					<div class="text-center">
						<button type="submit">Simpan</button>
					</div>
				</form>
			</div>
		</section><!-- End Frequently Asked Questions Section -->
	</main>
@endsection
@section('js-content')
	<script>
		window.addEventListener('DOMContentLoaded', event => {
			
			const $recaptcha = document.querySelector('#g-recaptcha-response');
		  	if ($recaptcha) {
		    	$recaptcha.setAttribute('required', 'required');
		  	}

		  	document.getElementById('telp').addEventListener('input', function (evt) {
			    this.value = this.value.replace(/\D+/g, '');
			});

			document.getElementById('telp_ketua').addEventListener('input', function (evt) {
			    this.value = this.value.replace(/\D+/g, '');
			});

			document.getElementById("id_lomba").addEventListener('change', function(){
				var id = this.value
				window.location.href = "/form-pendaftaran-peserta/"+id;
			})

			document.getElementById("id_peserta").addEventListener('change', function(){
				var id_lomba = document.getElementById("id_lomba").value;
				var id = this.value
				window.location.href = "/form-pendaftaran-peserta/"+id_lomba+"/"+id;
			})
		})
	</script>
@endsection