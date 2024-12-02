<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="{{ public_path('css/pdf.css') }}" rel="stylesheet">
</head>
<body>
	 <div style="width: 100%; text-align: center;">
        <h3>{{$judul ?? ''}}</h3>
        @if($id == 0)
	        <table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>NPWRD</th>
	        			<th>Nama WR</th>
	        			<th>Alamat</th>
	        			<th>Objek Retribusi</th>
	        			<th>Jenis Retribusi</th>
	        			<th>Wilayah Kerja</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($id_kec = 0)
	        		@php($id_kel = 0)
	                @foreach($data as $key => $value)
	                	@if($id_kec != $value['id_kecamatan'])
	                	<tr>
	                		<td colspan="7" style="text-align: left">{{$value['nama_kecamatan']}}</td>
	                	</tr>
	                	@php($id_kec = $value['id_kecamatan'])
	                	@endif
	                	@if($id_kel != $value['id_kelurahan'])
	                	<tr>
	                		<td colspan="7" style="text-align: left">{{$value['nama_kelurahan']}}</td>
	                	</tr>
	                	@php($id_kel = $value['id_kelurahan'])
	                	@endif
	                    <tr>
	                        <td>{{ ($key+1) }}</td>                                    
	                        <td>{{$value['npwrd']}}</td>
	                        <td style="text-align: left;">{{$value['nama']}}</td>
	                        <td style="text-align: left;">{{$value['alamat']}}</td>
	                        <td>{{$value['nama_objek_retribusi']}}</td>
	                        <td>{{$value['nama_jenis_retribusi']}}</td>
	                        <td>{{$value['wilayah_kerja']}}</td>
	                    </tr>
	                @endforeach
	            </tbody>
	        </table>
	    @elseif($id==1)
	    	<table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>Objek Retribusi</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($id_jenis_retribusi = 0)
	        		@php($total = 0)
	        		@foreach($data as $key => $value)
	        			@if($id_jenis_retribusi != $value->objek_retribusi?->jenis_retribusi?->id)
	                	<tr>
	                		<td colspan="3" style="text-align: left">{{$value->objek_retribusi?->jenis_retribusi?->nama}}</td>
	                	</tr>
	                	@php($no = 1)
	                	@php($id_jenis_retribusi = $value->objek_retribusi?->jenis_retribusi?->id)
	                	@endif
	        			<tr>
		        			<td>{{ ($no) }}</td>                                    
	                        <td style="text-align: left;">{{$value->objek_retribusi?->nama}}</td>
	                        <td>{{$value->jml}}</td>
	                    </tr>
	                    @php($no = $no +1)
	                    @php($total = $total + $value->jml)
	        		@endforeach
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<th colspan="2">Total</th>
                		<th>{{$total}}</th>
                	</tr>
	        	</tfoot>
	        </table>
	    @elseif($id==2)
	    	<table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>Kecamatan</th>
	        			<th>Kelurahan</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($total = 0)
	        		@foreach($data as $key => $value)
	        			<tr>
		        			<td>{{ ($key +1) }}</td>                                    
	                        <td style="text-align: left;">{{$value->kecamatan?->nama}}</td>
	                        <td style="text-align: left;">{{$value->kelurahan?->nama}}</td>
	                        <td>{{$value->jml}}</td>
	                    </tr>
	                    @php($total = $total + $value->jml)
	        		@endforeach
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<th colspan="3">Total</th>
                		<th>{{$total}}</th>
                	</tr>
	        	</tfoot>
	        </table>
	    @elseif($id==3)
	    	<table>
	        	<thead>
	        		<tr>
	        			<th>No</th>
	        			<th>Wilayah Kerja</th>
	        			<th>Jumlah</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@php($total = 0)
	        		@foreach($data as $key => $value)
	        			<tr>
		        			<td>{{ ($key+1) }}</td>                                    
	                        <td style="text-align: left;">{{$value->wilayah_kerja?->nama}}</td>
	                        <td>{{$value->jml}}</td>
	                    </tr>
	                    @php($total = $total + $value->jml)
	        		@endforeach
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<th colspan="2">Total</th>
                		<th>{{$total}}</th>
                	</tr>
	        	</tfoot>
	        </table>
	    @endif
    </div>
</body>
</html>