<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaksi->order_id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        .mt-4 { margin-top: 20px; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .border-top { border-top: 1px dashed #000; padding-top: 10px; }
        .d-flex { display: flex; }
        .justify-between { justify-content: space-between; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 4px 0;
        }
        .text-right {
            text-align: right;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .receipt-container {
                border: none;
                max-width: 100%;
            }
            .no-print {
                display: none;
            }
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            text-align: center;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt-container">
        <button class="no-print btn-print" onclick="window.print()">Cetak Struk (PDF / Printer)</button>

        <div class="text-center mb-4 border-bottom">
            <h2 class="font-bold" style="margin: 0 0 5px 0;">{{ config('app.name', 'Institusi Pendidikan') }}</h2>
            <p style="margin: 0;">Bukti Pembayaran Resmi</p>
        </div>

        <div class="mb-4">
            <div class="d-flex justify-between">
                <span>Order ID:</span>
                <span class="font-bold">{{ $transaksi->order_id }}</span>
            </div>
            <div class="d-flex justify-between">
                <span>Tanggal:</span>
                <span>{{ $transaksi->updated_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="d-flex justify-between">
                <span>Siswa:</span>
                <span class="font-bold">{{ $siswa->nama }} ({{ $siswa->nis }})</span>
            </div>
            <div class="d-flex justify-between">
                <span>Metode:</span>
                <span>{{ $transaksi->metode_pembayaran }}</span>
            </div>
            <div class="d-flex justify-between">
                <span>Status:</span>
                <span class="font-bold text-center" style="text-transform: uppercase;">{{ $transaksi->status }}</span>
            </div>
        </div>

        <div class="border-top border-bottom mb-4">
            <table>
                <thead>
                    <tr>
                        <th>Rincian Tagihan</th>
                        <th class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->pembayaran as $p)
                        @if($p->tagihan)
                        <tr>
                            <td>{{ $p->tagihan->nama_bulan }} {{ $p->tagihan->tahun }}</td>
                            <td class="text-right">Rp {{ number_format($p->tagihan->nominal, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-between font-bold" style="font-size: 1.2em;">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</span>
        </div>

        <div class="text-center mt-4 border-top pt-3">
            <p style="margin: 0; font-size: 0.9em;">Terima kasih atas pembayaran Anda.</p>
            <p style="margin: 5px 0 0 0; font-size: 0.8em;">Simpan struk ini sebagai bukti pembayaran yang sah.</p>
        </div>
    </div>

</body>
</html>
