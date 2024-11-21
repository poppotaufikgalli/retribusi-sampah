@extends('layouts.master')
@section('title',"judul")
@section('content')
  @include('partials.menu')
	<main id="main">
	<!-- ======= Frequently Asked Questions Section ======= -->
	<section id="" class="section">
		<div class="container">
			<div class="section-title">
				<h2>Daftar Peserta</h2>
				<p>Kategori {{$selPeserta->lomba?->judul ?? ""}}<br/>{{$selPeserta->judul ?? ""}}</p>
			</div>
		<div class="row">
			<div class="col-md-3">
				<div class="faq-list">
				<ul class="list-group list-group-flush">
					@if($katPeserta)
						@php($a="")
						@foreach($katPeserta as $item)	
							@if($a != $item->id_lomba)
								<li class="list-group-item text-bg-secondary mt-2">{{$item->lomba?->judul}}</li>
							@endif
							<li class="list-group-item text-start bg-gerak-jalan {{$selid == $item->id ? 'active' : ''}}">
								<a href="{{route('daftar-peserta', ['id' => $item->id])}}" class="d-flex align-items-center"><i class="bx bx-flag me-2"></i> {{$item->judul}}</a>
							</li>
							@php($a=$item->id_lomba)
						@endforeach
					@endif
				</ul>
			</div>
			</div>
			<div class="col-lg-9">
				<div class="card card-body">
					<table class="table table-striped table-sm" id="datatablesSimple">
						<thead class="table-dark">
							<tr class="text-center">
								<th>No</th>
								<th width="10%" data-order="desc">Nomor Peserta</th>
								<th width="45%">Nama Regu/Instansi</th>
								<th width="40%">Kategori Peserta</th>
							</tr>
						</thead>
						<tbody>
							@if($data)
								@foreach($data as $key => $value)
									<tr>
										<td>{{ ($key+1 ) }}</td>
										<td class="text-center">{{$value->no_peserta}}</td>
										<td>{{$value->nama}}</td>
										<td>{{$value->lomba?->judul}} - {{$value->kategori_peserta?->judul}}</td>
									</tr>
								@endforeach
						@endif	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	  </div>
	</section><!-- End Frequently Asked Questions Section -->
</main>
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
					topStart: 'pageLength',
					topEnd: 'search',
					bottomStart: 'info',
					bottomEnd: 'paging',
				}
			});
		}
	});
	</script>
@endsection