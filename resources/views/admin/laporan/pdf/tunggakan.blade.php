<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tunggakan</title>
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
        <h2>LAPORAN TUNGGAKAN SPP</h2>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Bulan</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tunggakan as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t->siswa->nis }}</td>
                    <td>{{ $t->siswa->nama }}</td>
                    <td>{{ $t->siswa->kelas->nama_kelas }}</td>
                    <td>{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                    <td class="text-right">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                    <td>{{ $t->status_label }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><strong>Total Tunggakan</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($tunggakan->sum('nominal'), 0, ',', '.') }}</strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>