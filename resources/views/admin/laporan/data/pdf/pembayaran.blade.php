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
	 	<h3>{{$judul ?? ''}}</h3>
        <p>{{$tgl ?? ''}}</p>
        @if($id == 0)
	        <table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>NPWRD</th>
	        			<th>Nama WR</th>
	        			<th>Bulan - Tahun</th>
	        			<th>Objek Retribusi</th>
	        			<th>Jenis Retribusi</th>
	        			<th>Jenis</th>
	        			<th>Tanggal Pembayaran</th>
	        			<th>Nomor Karcis</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($total = 0)
	        		@if(isset($data) && count($data) > 0)
		        		@foreach($data as $key => $value)
		        			<tr>
		        				<td>{{ ($key +1) }}</td>  
		        				<td>{{ $value->npwrd }}</td>
		        				<td>{{ $value->wajib_retribusi->nama }}</td>
		        				<td>{{ $value->bln }}-{{ $value->thn }}</td>
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->nama }}</td>
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama }}</td>
		        				<td>{{ $value->jenis_pembayaran?->nama }}</td>
		        				<td>{{ $value->tgl_bayar->format('d-m-Y') }}</td>  
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
	    	<table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>Objek Retribusi</th>
	        			<th>NPWRD</th>
	        			<th>Nama WR</th>
	        			<th>Bulan - Tahun</th>
	        			<th>Jenis Retribusi</th>
	        			<th>Jenis</th>
	        			<th>Tanggal Pembayaran</th>
	        			<th>Nomor Karcis</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($objek_retribusi = '')
	        		@php($subtotal = 0)
	        		@php($total = 0)
	        		@if(isset($data) && count($data) > 0)
		        		@foreach($data as $key => $value)
		        			@if($objek_retribusi != $value->wajib_retribusi?->objek_retribusi?->nama)
		        				@if($subtotal != 0)
			        			<tr>
			        				<td colspan="8"></td>
	                				<td>Sub Total</td>
			                		<td style="text-align: right;">{{ Str::currency($subtotal)}}</td>
			                	</tr>
			                	@php($subtotal = 0)
			                	@endif
		                	<tr>
		                		<td colspan="10" style="text-align: left">Objek Retribusi : <b>{{$value->wajib_retribusi?->objek_retribusi?->nama}}</b></td>
		                	</tr>
		                	@php($objek_retribusi = $value->wajib_retribusi?->objek_retribusi?->nama)
		                	@php($no_urut = 1)
		                	@endif
		        			<tr>
		        				<td>{{ ($no_urut) }}</td>  
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->nama }}</td>
		        				<td>{{ $value->npwrd }}</td>
		        				<td>{{ $value->wajib_retribusi->nama }}</td>
		        				<td>{{ $value->bln }}-{{ $value->thn }}</td>
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama }}</td>
		        				<td>{{ $value->jenis_pembayaran?->nama }}</td>
		        				<td>{{ $value->tgl_bayar->format('d-m-Y') }}</td>  
		        				<td>{{ $value->no_karcis }}</td>
		        				@php($total = $total + $value->jml)
		        				<td style="text-align: right;">{{ Str::currency($value->jml) }}</td>
		        			</tr>
		        			@php($subtotal = $subtotal + $value->jml)
		        			@php($no_urut = $no_urut + 1)
		        		@endforeach
		        		<tr>
	                		<td colspan="8"></td>
	                		<td>Sub Total</td>
	                		<td style="text-align: right;">{{ Str::currency($subtotal)}}</td>
	                	</tr>
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
	    @elseif($id==2)
	    	<table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>Jenis Retribusi</th>
	        			<th>Objek Retribusi</th>
	        			<th>Tanggal Pembayaran</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($jenis_retribusi = '')
	        		@php($subtotal = 0)
	        		@php($total = 0)
	        		@if(isset($data) && count($data) > 0)
		        		@foreach($data as $key => $value)
		        			@if($jenis_retribusi != $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama)
		        				@if($subtotal != 0)
			        			<tr>
			        				<td colspan="3"></td>
	                				<td>Sub Total</td>
			                		<td style="text-align: right;">{{ Str::currency($subtotal)}}</td>
			                	</tr>
			                	@php($subtotal = 0)
			                	@endif
		                	<tr>
		                		<td colspan="5" style="text-align: left">Jenis Retribusi : <b>{{$value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama}}</b></td>
		                	</tr>
		                	@php($jenis_retribusi = $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama)
		                	@php($no_urut = 1)
		                	@endif
		        			<tr>
		        				<td>{{ ($no_urut) }}</td>  
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->jenis_retribusi?->nama }}</td>
		        				<td>{{ $value->wajib_retribusi?->objek_retribusi?->nama }}</td>
		        				<td>{{ $value->tgl_bayar->format('d-m-Y') }}</td>  
		        				@php($total = $total + $value->jml)
		        				<td style="text-align: right;">{{ Str::currency($value->jml) }}</td>
		        			</tr>
		        			@php($subtotal = $subtotal + $value->jml)
		        			@php($no_urut = $no_urut + 1)
		        		@endforeach
		        		<tr>
	                		<td colspan="3"></td>
	                		<td>Sub Total</td>
	                		<td style="text-align: right;">{{ Str::currency($subtotal)}}</td>
	                	</tr>
		        	@else
		        		<tr>
		        			<td colspan="4" height="100"><i>Tidak Ada Pembayaran</i></td>	
		        			<td style="text-align: right;">{{Str::currency($total)}}</td>
		        		</tr>
		        	@endif
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<th colspan="4">Total</th>
                		<th style="text-align: right;">{{Str::currency($total)}}</th>
                	</tr>
	        	</tfoot>
	        </table>
	    @elseif($id==3)
	    	
	    @endif
    </div>
</body>
</html>