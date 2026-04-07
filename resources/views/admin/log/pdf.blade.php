<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Log Aktivitas Sistem</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LOG AKTIVITAS SISTEM</h2>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }} | Total: {{ $logs->count() }} entri</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>User</th>
                <th>Aksi</th>
                <th>Detail</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $i => $log)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->user->username ?? 'System' }}</td>
                    <td>{{ $log->aksi }}</td>
                    <td>{{ Str::limit($log->detail, 60) }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">SPP Sekolah - Log Aktivitas Sistem</div>
</body>

</html>