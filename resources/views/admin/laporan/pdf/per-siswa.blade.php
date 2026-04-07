<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan SPP - {{ $siswa->nama }}</title>
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

        .info {
            margin-bottom: 15px;
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
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN PEMBAYARAN SPP</h2>
        <p>Per Siswa</p>
    </div>
    <div class="info">
        <p><strong>Nama:</strong> {{ $siswa->nama }}</p>
        <p><strong>NIS:</strong> {{ $siswa->nis }}</p>
        <p><strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tagihan as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t->nama_bulan }}</td>
                    <td>{{ $t->tahun }}</td>
                    <td class="text-right">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                    <td>{{ $t->status_label }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total Lunas</strong></td>
                <td class="text-right"><strong>Rp
                        {{ number_format($tagihan->where('status', 'lunas')->sum('nominal'), 0, ',', '.') }}</strong>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Total Tunggakan</strong></td>
                <td class="text-right"><strong>Rp
                        {{ number_format($tagihan->whereIn('status', ['belum_bayar', 'ditolak'])->sum('nominal'), 0, ',', '.') }}</strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>