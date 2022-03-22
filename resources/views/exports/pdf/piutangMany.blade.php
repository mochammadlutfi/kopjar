<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SLIP TAGIHAN RUTIN KOPERASI</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/print.css') }}">

    <style type="text/css" media="all">
        @page {
            size: 210mm 165mm;
            margin: 0px;
        }

        body {
            /* width: 210mm; */
            margin: 0px;
            background-color: white;
            font-size: 11pt;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        h2 {
            font-size: 14pt;
        }

        a {
            color: #fff;
            text-decoration: none;
        }
        .content {
            padding: 15px;
        }

        .t-head {
            background-color: #f6f7f9;
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed; //new line
        }
        .t-head td {
            vertical-align : middle;
            padding : 10px;
        }

        
        .t-info {
            border-collapse: collapse;
            width: 100%;
        }
        .t-info td {
            vertical-align : middle;
            padding : 2px 0 2px 0;
        }

        .t-items {
            width: 100%;
            text-align: center;
            vertical-align: middle;
            border-collapse: collapse;
        }

        .t-items td, .t-items th {
            border: 1px solid black;
            padding: 8px;
        }
        .page-break {
            page-break-after: always;
        }

    </style>
</head>

<body>
    @foreach ($items as $i)
    <table class="t-head">
        <tr>
            <td>
                <h2>SLIP POTONGAN RUTIN</h2>
            </td>
            <td>
                <img src="{{ asset('media/logo/logo.png') }}" style="height: 60px; margin-right:10px">
                <img src="{{ asset('media/logo/logo_koperasi.png') }}" style="height: 60px">
            </td>
        </tr>
    </table>
    <br>
    <div class="content">
        <table class="t-info" style="width: 50%">
            <tr>
                <td>No Anggota</td>
                <td>
                    : <b>{{ $i["anggota_id"] }}</b>
                </td>
            </tr>
            <tr>
                <td>Nama Anggota</td>
                <td>
                    : <b>{{ $i["nama"] }}</b>
                </td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>
                    : <b>{{ $i["nip"] }}</b>
                </td>
            </tr>
            <tr>
                <td>Golongan</td>
                <td>
                    : <b>{{ $i["golongan"] }}</b>
                </td>
            </tr>
        </table>
        <hr style="border-width: 2px;"/>
        <table class="t-items">
            <tr>
                <th>No Transaksi</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach ($i["list"] as $t)
                <tr>
                    <td>{{ $t->nomor }}</td>
                    <td>
                        @if($t->jenis == 'setoran sukarela')
                            Simpanan Sukarela
                        @elseif($t->jenis == 'pendaftaran')
                            Pendaftaran
                        @else
                            Simpanan Wajib
                        @endif
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($t->tgl)->format('d m Y') }}
                    </td>
                    <td>
                        Rp {{ number_format($t->total,0,',','.') }}
                    </td>
                </tr>
                @php
                    $total += $t->total;
                @endphp
            @endforeach
            <tfoot>
                <tr>
                    <td colspan="3">Total Tagihan</td>
                    <td>Rp {{ number_format($total,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
    @endforeach
</body>

</html>
