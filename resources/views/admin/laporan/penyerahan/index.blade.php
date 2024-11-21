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
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @if($data)
        @foreach($data as $i => $item)
            <div style="height: 120px">&nbsp;</div>
            <div style="width: 100%; text-align: center;">
                <h2 style="text-decoration: underline;">TANDA TERIMA</h2>
                <p>Nomor : </p>
                <table style="width: 100%;">
                    <thead>
                        <tr style="background-color: black; color: white;">
                            <th width="5%">No</th>
                            <th width="10%">Jenis</th>
                            <th width="10%">Tahun</th>
                            <th width="15%">Ket</th>
                            <th width="10%">Jumlah</th>
                            <th width="15%">Harga</th>
                            <th width="15%">Total</th>
                            <th width="20%">Tanggal Penyerahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($no = 1)
                        @php($koord = [])
                        @php($jungut = [])
                        @if($item)
                            @foreach($item as $key => $value)
                                <tr>
                                    <td>{{ $no }}</td>                             
                                    <td>{{$value['route']}}</td>       
                                    <td>{{$value['thn']}}</td>
                                    <td>No.{!!$value['ket']!!}</td>
                                    <td>{{$value['jml']}}</td>
                                    <td>{{ Str::rupiah($value['harga'])}}</td>
                                    <td>{{ Str::rupiah($value['jml'] * $value['harga'])}}</td>
                                    <td>{{$value['tgl_penyerahan']->format('d-m-Y')}}</td>
                                </tr>
                                @php($no++)
                                @php($koord[] = $value['koordinator']['name'])
                                @php($jungut[] = $value['juru_pungut']['name'])
                            @endforeach
                        @endif
                    </tbody>
                    @php($koord = array_unique($koord))
                    @php($jungut = array_unique($jungut))
                </table>
                
                <p>Tanjungpinang, </p>
                <div style="display: table; width: 100%; margin-top: 20px;">
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            <p>Koordinator</p>
                            @for($i=0; $i < count($koord); $i++)
                                <p style="min-height: 60px"></p>
                                <b>{{$koord[$i]}}</b>
                            @endfor
                            
                        </div>
                        <div style="display: table-cell;">
                            <p>Juru Pungut</p>
                            @for($i=0; $i < count($jungut); $i++)
                                <p style="min-height: 60px"></p>
                                <b>{{$jungut[$i]}}</b>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
        @endforeach
    @endif
</body>
</html>