<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title></title>
	<link href="{{ public_path('css/pdf.css') }}" rel="stylesheet">
</head>
<body>
	 <div style="width: 100%; text-align: center;">
        @if($id == 0)
        	<h3>{{$judul ?? ''}}</h3>
        	<p>Tanggal : {{$tgl ?? ''}}</p>
	        <table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>Tanggal Penerimaan</th>
	        			<th>NPWRD</th>
	        			<th>Nama WR</th>
	        			<th>Objek Retribusi</th>
	        			<th>Jenis Retribusi</th>
	        			<th>Jenis</th>
	        			<th>Bulan - Tahun</th>
	        			<th>Nomor Karcis</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($id_user = 0)
	        		@php($ttd = [])
	        		@php($total = 0)
	        		@if(isset($data) && count($data) > 0)
		        		@foreach($data as $key => $value)
		        			@if($id_user != $value->id_user)
		                	<tr>
		                		<td colspan="10" style="text-align: left">Juru Pungut : <b>{{$value->user?->name}}</b></td>
		                	</tr>
		                	@php($ttd[] = [$value->user?->name, $value->wajib_retribusi?->wilayah_kerja?->koordinator?->name])
		                	@php($id_user = $value->id_user)
		                	@endif
		        			<tr>
		        				<td>{{ ($key +1) }}</td>  
		        				<td>{{ $value->tgl_bayar->format('d-m-Y') }}</td>  
		        				<td>{{ $value->npwrd }}</td>
		        				<td>{{ $value->wajib_retribusi->nama }}</td>
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->nama }}</td>
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama }}</td>
		        				<td>{{ $value->jenis_pembayaran?->nama }}</td>
		        				<td>{{ $value->bln }}-{{ $value->thn }}</td>
		        				<td>{{ $value->no_karcis }}</td>
		        				@php($total = $total + $value->jml)
		        				<td style="text-align: right;">{{ Str::currency($value->jml) }}</td>
		        			</tr>
		        		@endforeach
		        	@else
		        		<tr>
		        			<td colspan="9" height="100"><i>Tidak Ada Pembayaran</i></td>	
		        			<td style="text-align: right;">{{Str::currency($total)}}</td>
		        		</tr>
		        	@endif
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<th colspan="9">Total</th>
                		<th style="text-align: right;">{{Str::currency($total)}}</th>
                	</tr>
	        	</tfoot>
	        </table>
	    @elseif($id==1)
	    	<h3>{{$judul ?? ''}}</h3>
        	<p>Tanggal : {{$tgl ?? ''}}</p>
	    	<table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>Nama Juru Pungut</th>
	        			<th>Jenis</th>
	        			<th>Tanggal Penerimaan</th>
	        			<th>Nomor Karcis</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($id_user = 0)
	        		@php($ttd = [])
	        		@php($total = 0)
	        		@if(isset($data) && count($data) > 0)
		        		@foreach($data as $key => $value)
		        			@if($id_user != $value->id_user)
			                	@php($ttd[] = [$value->user?->name, $value->wajib_retribusi?->wilayah_kerja?->koordinator?->name])
			                	@php($id_user = $value->id_user)
		                	@endif
		        			<tr>
		        				<td>{{ ($key +1) }}</td>  
		        				<td>{{ $value->user?->name }}</td>
		        				<td>{{ $value->jenis_pembayaran?->nama }}</td>
		        				<td>{{ $value->tgl_bayar->format('d-m-Y') }}</td>  
		        				<td>{{ $value->list_karcis }}</td>
		        				@php($total = $total + $value->total)
		        				<td style="text-align: right;">{{ Str::currency($value->total) }}</td>
		        			</tr>
		        		@endforeach
		        	@else
		        		<tr>
		        			<td colspan="5" height="100"><i>Tidak Ada Pembayaran</i></td>	
		        			<td style="text-align: right;">{{Str::currency($total)}}</td>
		        		</tr>
		        	@endif
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<th colspan="5">Total</th>
                		<th style="text-align: right;">{{Str::currency($total)}}</th>
                	</tr>
	        	</tfoot>
	        </table>
	    @elseif($id==2)
	    	@if($data)
	    		@foreach($data as $k => $item)
	    			<h3>{{$judul ?? ''}}</h3>
        			<p>Tanggal : {{$tgl ?? ''}}</p>
        			<table>
			        	<thead>
			        		<tr>
			        			<th>No</th>
			        			<th>Tanggal Penerimaan</th>
			        			<th>NPWRD</th>
			        			<th>Nama WR</th>
			        			<th>Objek Retribusi</th>
			        			<th>Jenis Retribusi</th>
			        			<th>Jenis</th>
			        			<th>Bulan - Tahun</th>
			        			<th>Nomor Karcis</th>
			        			<th>Jumlah</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        		@php($id_user = 0)
			        		@php($ttd = [])
			        		@php($total = 0)
			        		@if(isset($item) && count($item) > 0)
				        		@foreach($item as $key => $value)
				        			@if($id_user != $value->id_user)
				                	<tr>
				                		<td colspan="10" style="text-align: left">Juru Pungut : <b>{{$value->user?->name}}</b></td>
				                	</tr>
				                	@php($ttd[] = [$value->user?->name, $value->wajib_retribusi?->wilayah_kerja?->koordinator?->name])
				                	@php($id_user = $value->id_user)
				                	@endif
				        			<tr>
				        				<td>{{ ($key +1) }}</td>  
				        				<td>{{ $value->tgl_bayar->format('d-m-Y') }}</td>  
				        				<td>{{ $value->npwrd }}</td>
				        				<td>{{ $value->wajib_retribusi->nama }}</td>
				        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->nama }}</td>
				        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama }}</td>
				        				<td>{{ $value->jenis_pembayaran?->nama }}</td>
				        				<td>{{ $value->bln }}-{{ $value->thn }}</td>
				        				<td>{{ $value->no_karcis }}</td>
				        				@php($total = $total + $value->jml)
				        				<td style="text-align: right;">{{ Str::currency($value->jml) }}</td>
				        			</tr>
				        		@endforeach
				        	@else
				        		<tr>
				        			<td colspan="9" height="100"><i>Tidak Ada Pembayaran</i></td>	
				        			<td style="text-align: right;">{{Str::currency($total)}}</td>
				        		</tr>
				        	@endif
			        	</tbody>
			        	<tfoot>
			        		<tr>
			        			<th colspan="9">Total</th>
		                		<th style="text-align: right;">{{Str::currency($total)}}</th>
		                	</tr>
			        	</tfoot>
			        </table>
			        <div style="width: 100%; display: table;">
			        	<div style="display: table-row;">
			        		<div style="display: table-cell;width: 40%">&nbsp;</div>
			        		<div style="display: table-cell;width: 20%">&nbsp;</div>
			        		<div style="display: table-cell;"><p style="text-align: center;">Tanjungpinang, {{Str::idDate(date('Y-m-d'))}}</p></div>
			        	</div>
			        	<div style="display: table-row;">
			        		<div style="display: table-cell;"><p>Koordinator</p></div>
			        		<div style="display: table-cell;width: 20%">&nbsp;</div>
			        		<div style="display: table-cell;"><p>Juru Pungut</p></div>
			        	</div>
			        	@if(isset($ttd))
					        @foreach($ttd as $value)
			        			<div style="display: table-row;">		
					        		<div style="display: table-cell; height: 10%; vertical-align: bottom;"><b>{{$value[1]}}</b></div>
					        		<div style="display: table-cell;width: 20%">&nbsp;</div>
					        		<div style="display: table-cell; height: 10%; vertical-align: bottom;"><b>{{$value[0]}}</b></div>
			        			</div>
			        		@endforeach
					    @endif
			        </div>
			        <div class="page-break"></div>
	    		@endforeach
	    	@endif
	    @elseif($id==3)
	    	@if($data)
	    		@foreach($data as $k => $item)
	    			<h3>{{$judul ?? ''}}</h3>
        			<p>Tanggal : {{$tgl ?? ''}}</p>
        			<table>
			        	<thead>
			        		<tr>
			        			<th>No</th>
			        			<th>Tanggal Penerimaan</th>
			        			<th>NPWRD</th>
			        			<th>Nama WR</th>
			        			<th>Objek Retribusi</th>
			        			<th>Jenis Retribusi</th>
			        			<th>Jenis</th>
			        			<th>Bulan - Tahun</th>
			        			<th>Nomor Karcis</th>
			        			<th>Jumlah</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        		@php($id_user = 0)
			        		@php($ttd = [])
			        		@php($total = 0)
			        		@if(isset($item) && count($item) > 0)
				        		@foreach($item as $key => $value)
				        			@if($id_user != $value->id_user)
				                	<tr>
				                		<td colspan="10" style="text-align: left">Juru Pungut : <b>{{$value->user?->name}}</b></td>
				                	</tr>
				                	@php($ttd = $value->wajib_retribusi?->wilayah_kerja?->koordinator?->name)
				                	@php($id_user = $value->id_user)
				                	@endif
				        			<tr>
				        				<td>{{ ($key +1) }}</td>  
				        				<td>{{ $value->tgl_bayar->format('d-m-Y') }}</td>  
				        				<td>{{ $value->npwrd }}</td>
				        				<td>{{ $value->wajib_retribusi->nama }}</td>
				        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->nama }}</td>
				        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama }}</td>
				        				<td>{{ $value->jenis_pembayaran?->nama }}</td>
				        				<td>{{ $value->bln }}-{{ $value->thn }}</td>
				        				<td>{{ $value->no_karcis }}</td>
				        				@php($total = $total + $value->jml)
				        				<td style="text-align: right;">{{ Str::currency($value->jml) }}</td>
				        			</tr>
				        		@endforeach
				        	@else
				        		<tr>
				        			<td colspan="9" height="100"><i>Tidak Ada Pembayaran</i></td>	
				        			<td style="text-align: right;">{{Str::currency($total)}}</td>
				        		</tr>
				        	@endif
			        	</tbody>
			        	<tfoot>
			        		<tr>
			        			<th colspan="9">Total</th>
		                		<th style="text-align: right;">{{Str::currency($total)}}</th>
		                	</tr>
			        	</tfoot>
			        </table>
			        <div style="width: 100%; display: table;">
			        	<div style="display: table-row;">
			        		<div style="display: table-cell;width: 60%">&nbsp;</div>
			        		<div style="display: table-cell;"><p style="text-align: center;">Tanjungpinang, {{Str::idDate(date('Y-m-d'))}}</p></div>
			        	</div>
			        	<div style="display: table-row;">
			        		<div style="display: table-cell;width: 60%">&nbsp;</div>
			        		<div style="display: table-cell;"><p>Koordinator</p></div>
			        	</div>
			        	<div style="display: table-row;">		
			        		<div style="display: table-cell;width: 20%">&nbsp;</div>
			        		<div style="display: table-cell; height: 10%; vertical-align: bottom;"><b>{{$ttd}}</b></div>
	        			</div>
			        </div>
			        <div class="page-break"></div>
	    		@endforeach
	    	@endif
	    @endif
    </div>
</body>
</html>