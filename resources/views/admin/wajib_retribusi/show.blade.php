@extends('layouts.master1')
@section('title', $title ?? 'Tambah Pendaftar')
@section('subtitle',"")
@section('content')
	<div class="container-fluid px-4">
		<div class="card mb-4">
			<div class="card-body">
				<form method="POST" action="{{route('wajib_retribusi.show', ['id_objek_retribusi' => $data->id_objek_retribusi, 'id' => $data->id])}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" id="npwrd" name="npwrd" value="{{isset($data) ? $data->npwrd : ''}}">
					<div class="row mb-1">
						<label for="id_jenis_retribusi" class="col-sm-2 col-form-label">Jenis Retribusi</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" name="id_jenis_retribusi" id="id_jenis_retribusi" value="{{$data->objek_retribusi->jenis_retribusi->nama}}" disabled>
						</div>
						<label for="id_objek_retribusi" class="col-sm-2 col-form-label">Objek Retribusi</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" name="id_jenis_retribusi" id="id_jenis_retribusi" value="{{$data->objek_retribusi->nama}}" disabled>
						</div>
					</div>
					<div class="row mb-1">
						<label for="npwrd" class="col-sm-2 col-form-label">NPWRD</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->npwrd : old('npwrd')}}" disabled>
						</div>
						<label class="col-sm-2 col-form-label">Nama WR</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" value="{{isset($data) ? $data->nama : old('nama')}}" disabled>
						</div>
					</div>
					<div class="row mb-1">
						<label for="npwrd" class="col-sm-2 col-form-label">Status</label>
						<div class="col-sm-4">
							@php($aktif = $data->aktif == 1 ? 'Aktif' : ($data->aktif == -1 ? 'Tutup' : 'Belum diaktifkan'))
							<input type="text" class="form-control form-control-sm" value="{{$aktif}}" disabled>
						</div>
					</div>
					<hr>
					<div class="row mb-1">
						<div class="col-sm-10 offset-sm-2">
							<div class="form-check form-check-inline">
							  	<input class="form-check-input" type="radio" name="aktif" id="aktif_1" value="1" {{old('aktif') == 1 ? 'checked' : ''}} required>
							  	<label class="form-check-label" for="aktif_1">Pengaktifan</label>
							</div>
							<div class="form-check form-check-inline">
							  	<input class="form-check-input" type="radio" name="aktif" id="aktif_11" value="-1" {{old('aktif') == -1 ? 'checked' : ''}} required>
							  	<label class="form-check-label" for="aktif_11">Penutupan</label>
							</div>
						</div>
					</div>
					<div class="row mb-1">
						<label for="nama" class="col-sm-2 col-form-label">SK/Penetapan</label>
						<div class="col-sm-8">
							<input type="text" class="form-control form-control-sm" id="no_sk" name="no_sk" value="{{old('no_sk')}}" placeholder="">
						</div>
						<div class="col-sm-2">
							<input type="date" class="form-control form-control-sm" id="tgl_sk" name="tgl_sk" value="{{old('tgl_sk') ?? date('Y-m-d')}}" placeholder="">
						</div>
					</div>
					<div class="row mb-1">
						<label for="nama" class="col-sm-2 col-form-label">TMT Aktif/Penutupan</label>
						<div class="col-sm-2">
							<input type="date" class="form-control form-control-sm" id="tmt_sk" name="tmt_sk" value="{{old('tmt_sk') ?? date('Y-m-d')}}" placeholder="">
						</div>
					</div>
					<div class="row mb-2">
						<label for="nama" class="col-sm-2 col-form-label">Catatan</label>
						<div class="col-sm-10">
							<textarea class="form-control form-control-sm" id="catatan" name="catatan">{{old('catatan')}}</textarea>
						</div>
					</div>
					@if($edit == true)
						<div class="row mb-1">
							<div class="col-sm-10 offset-sm-2">
								<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
								<a href="{{route('wajib_retribusi.show', ['id_objek_retribusi' => $data->id_objek_retribusi, 'id' => $data->id])}}" type="button" class="btn btn-sm btn-secondary">Batal</a>
							</div>
						</div>
					@endif
					<hr>
					<div class="table-responsive">
	                    <table class="table table-sm small table-stiped table-sm" id="datatablesSimple">
	                        <thead class="table-dark text-center">
	                            <tr>
	                                <th width="5%">No</th>
	                                <th width="10%">NPWRD</th>
	                                <th width="15%">Nomor SK</th>
	                                <th width="15%">Tanggal SK</th>
	                                <th width="20%">TMT</th>
	                                <th width="20%">Catatan</th>
	                                <th width="5%">Status</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	@if(isset($aktifasiWr))
	    							@foreach($aktifasiWr as $key => $value)
	    		                        <tr>
	    		                            <td>{{ ($key+1) }}</td>
	                                        <td class="text-center">{{$value->npwrd}}</td>
	                                        <td>{{$value->no_sk}}</td>
	                                        <td class="text-center">{{$value->tgl_sk->format('d-m-Y')}}</td>
	                                        <td class="text-center">{{$value->tmt_sk->format('d-m-Y')}}</td>
	                                        <td>{{$value->catatan}}</td>
	    		                            <td>
	                                            <div class="d-flex justify-content-center">
	                                                @if($value->aktif == -1)
	                                                    <div class="bg-danger px-2 py-1 text-white bg-opacity-75">
	                                                        <i class="bx bx-x"></i>
	                                                    </div>
	                                                @elseif($value->aktif == 1)
	                                                    <div class="bg-success border px-2 py-1 text-white bg-opacity-75">
	                                                        <i class="bx bx-check"></i>
	                                                    </div>
	                                                @else
	                                                    <a href="{{route('wajib_retribusi.show', ['id_objek_retribusi' => $value->id_objek_retribusi, 'npwrd' => $value->npwrd])}}" class="border border-info px-2 py-1 text-info ">
	                                                        <i class="bx bx-detail"></i>
	                                                    </a>
	                                                @endif      
	                                            </div>
	                                        </td>
	    		                        </tr>
	    		                    @endforeach
	    		                @endif
	                        </tbody>
	                    </table>
	                </div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('js-content')
	<script type="text/javascript">
		window.addEventListener('DOMContentLoaded', event => {
			new DataTable('#datatablesSimple');
		});
	</script>
@endsection