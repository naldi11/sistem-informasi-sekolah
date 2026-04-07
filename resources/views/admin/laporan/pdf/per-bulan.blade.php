<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan SPP Per Bulan - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN PEMBAYARAN SPP</h2>
        <p>Bulan {{ $namaBulan }} {{ $tahun }}</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tagihan as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t->siswa->nis }}</td>
                    <td>{{ $t->siswa->nama }}</td>
                    <td>{{ $t->siswa->kelas->nama_kelas }}</td>
                    <td class="text-right">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                    <td>{{ $t->status_label }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><strong>Total Lunas</strong></td>
                <td class="text-right"><strong>Rp
                        {{ number_format($tagihan->where('status', 'lunas')->sum('nominal'), 0, ',', '.') }}</strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">SPP Sekolah - Sistem Informasi Pembayaran</div>
</body>

</html>