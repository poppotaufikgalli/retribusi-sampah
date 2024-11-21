<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PAKTA INTEGRITAS</title>
	<style>
          
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Bold.ttf") }}) format("truetype");
                font-weight: 700;
                font-style: normal;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-BoldItalic.ttf") }}) format("truetype");
                font-weight: 700;
                font-style: italic;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-ExtraBold.ttf") }}) format("truetype");
                font-weight: 800;
                font-style: normal;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-ExtraBoldItalic.ttf") }}) format("truetype");
                font-weight: 800;
                font-style: italic;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Light.ttf") }}) format("truetype");
                font-weight: 300;
                font-style: normal;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-LightItalic.ttf") }}) format("truetype");
                font-weight: 300;
                font-style: italic;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Medium.ttf") }}) format("truetype");
                font-weight: 500;
                font-style: normal;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-MediumItalic.ttf") }}) format("truetype");
                font-weight: 500;
                font-style: italic;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Regular.ttf") }}) format("truetype");
                font-weight: 400;
                font-style: normal;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-SemiBold.ttf") }}) format("truetype");
                font-weight: 600;
                font-style: normal;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-SemiBoldItalic.ttf") }}) format("truetype");
                font-weight: 600;
                font-style: italic;
            }
    
            @font-face {
                font-family: 'Open Sans';
                src: url({{ storage_path("fonts/static/OpenSans/OpenSans-Italic.ttf") }}) format("truetype");
                font-weight: 400;
                font-style: italic;
            }
    
            body {
                font-family: 'Open Sans', sans-serif;
            }
	</style>
</head>
<body>
	<h1 style="text-align: center;">PAKTA INTEGRITAS</h1>
	<table style="width:100%">
		<tr>
			<td colspan="4">Kami yang bertanda tangan dibawah ini :</td>
		</tr>
		<tr>
			<td width="2%">1.</td>
			<td width="20%">Nama</td>
			<td>:</td>
			<td width="75%">{{$data->pic}}</td>
		</tr>
		<tr>
			<td></td>
			<td>NIK</td>
			<td>:</td>
			<td>..............................</td>
		</tr>
		<tr>
			<td></td>
			<td>Jabatan dalam regu</td>
			<td>:</td>
			<td>Penanggung Jawab</td>
		</tr>
		<tr>
			<td>2.</td>
			<td>Nama</td>
			<td>:</td>
			<td>{{$data->ketua}}</td>
		</tr>
		<tr>
			<td></td>
			<td>NIK</td>
			<td>:</td>
			<td>..............................</td>
		</tr>
		<tr>
			<td></td>
			<td>Komandan Regu</td>
			<td>:</td>
			<td>{{$data->no_peserta}}</td>
		</tr>
	</table>
	<div>
		<p>Dengan ini kami menyatakan bahwa :</p>
		<ol>
			<li>Mematuhi semua peraturan dan ketentuan yang telah di tetapkan oleh Panitia.</li>
			<li>Menerima apapun keputusan yang diterapkan oleh Dewan Juri terkait hasil perlombaan Gerak Jalan ini.</li>
			<li>Bertanggung jawab untuk mencegah terjadinya kegaduhan dalam pelaksanaan perlombaan Gerak Jalan ini.</li>
			<li>Bertanggung jawab sepenuhnya apabila terjadi tindakan anarkis yang disebabkan oleh regu kami yang bertentangan dengan hukum.</li>
		</ol>
		<p>Demikian Pakta Integritas ini kami buat untuk dapat digunakan sebagaimana mestinya.</p>
	</div>
	<table>
		<tr style="vertical-align: text-top;">
			<td>Penanggung Jawab</td>
			<td>
				<div style="height: 80px;width: 120px;border: 1px solid gray; text-align: center; vertical-align: middle;">
					<p>Materai<br>Rp. 10.000,-</p>
				</div>
				<p>Nama : {{$data->pic}}</p>
			</td>
		</tr>
		<tr style="vertical-align: text-top;">
			<td>Komandan Regu</td>
			<td>
				<div style="height: 80px;width: 120px;border: 1px transparent black; text-align: center; vertical-align: middle;">
					
				</div>
				<p>Nama : {{$data->ketua}}</p>
			</td>
		</tr>
	</table>
</body>
</html>