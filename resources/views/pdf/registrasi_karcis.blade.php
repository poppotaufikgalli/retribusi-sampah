<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Formulir Serah Terima</title>
    <style type="text/css">
        body{
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        table {
            border-collapse: collapse;
        }
        td{
            text-align: center;
        }
    </style>
</head>
<body>
    <div style="width: 100%; text-align: center;">
        <h2 style="text-decoration: underline;">TANDA TERIMA</h2>
        <p>Nomor : {{$data->no_serah_terima}}</p>
        @if(isset($data) && count($data->karcis) > 0)
            <table style="width: 100%;">
                <thead>
                    <tr style="background-color: black; color: white;">
                        <th width="5%">No</th>
                        <th width="20%">Tahun</th>
                        <th width="25%">Nomor Karcis</th>
                        <th width="25%">Jumlah</th>
                        <th width="25%">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->karcis as $key => $value)
                        <tr>
                            <td>{{ ($key+1) }}</td>                                    
                            <td>{{$value->tahun}}</td>
                            <td>{{$value->no_karcis_awal}} s/d {{$value->no_karcis_akhir}}</td>
                            <td>{{$value->no_karcis_akhir - $value->no_karcis_awal}}</td>
                            <td>{{ Str::currency($value->harga)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if(isset($data) && count($data->tagihan) > 0)
            <table style="width: 100%;">
                <thead>
                    <tr style="background-color: black; color: white;">
                        <th width="5%">No</th>
                        <th width="20%">Bulan - Tahun</th>
                        <th width="25%">NPWRD</th>
                        <th width="25%">SKRD</th>
                        <th width="25%">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->tagihan as $key => $value)
                        <tr>
                            <td>{{ ($key+1) }}</td>                                    
                            <td>{{$value->bln}} - {{$value->thn}}</td>
                            <td>{{$value->npwrd}}<br>{{$value->wajib_retribusi?->nama}}</td>
                            <td>{{$value->no_skrd}}<br>tgl.{{$value->tgl_skrd->format('d-m-Y')}}</td>
                            <td>{{ Str::currency($value->jml)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div style="text-align: left; padding: 5px; margin:20px 5px; border: .5px solid black; width: 40%;">
            <p><u>Deskripsi : </u></p>
            {{$data->deskripsi}}        
        </div>
        <p>Tanjungpinang, {{$data->tgl_penyerahan->format('d-m-Y')}}</p>
        <div style="display: table; width: 100%; margin-top: 20px;">
            <div style="display: table-row;">
                <div style="display: table-cell;">
                    <p style="min-height: 60px">Koordinator</p>
                    <b>{{$data->koordinator?->name}}</b>
                </div>
                <div style="display: table-cell;">
                    <p style="min-height: 60px">Juru Pungut</p>
                    <b>{{$data->juru_pungut?->name}}</b>
                </div>
            </div>
        </div>
    </div>

</body>
</html>