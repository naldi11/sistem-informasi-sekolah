<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk QRIS - {{ $transaksi->order_id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981;
            --bg-color: #f3f4f6;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--text-main);
        }

        .receipt-card {
            background: #ffffff;
            width: 100%;
            max-width: 380px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
            position: relative;
        }

        /* Top Pattern / Header */
        .receipt-header {
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 30px 20px 40px;
            position: relative;
        }

        .success-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .success-icon svg {
            width: 32px;
            height: 32px;
            color: var(--primary);
        }

        .receipt-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .receipt-header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        /* Amount Section */
        .amount-section {
            text-align: center;
            padding: 25px 20px 20px;
            background: white;
            position: relative;
            z-index: 10;
            margin-top: -20px;
            border-radius: 20px 20px 0 0;
        }

        .amount-label {
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        /* Details Section */
        .details-section {
            padding: 0 25px 25px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px 0;
        }

        .detail-label {
            color: var(--text-muted);
            font-size: 14px;
            flex: 1;
        }

        .detail-value {
            font-weight: 500;
            font-size: 14px;
            text-align: right;
            flex: 1;
            word-break: break-word;
        }

        .divider {
            border-top: 1px dashed var(--border-color);
            margin: 5px 0;
        }

        /* Tagihan List */
        .tagihan-list {
            margin: 15px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 12px;
        }

        .tagihan-item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .tagihan-item:last-child {
            margin-bottom: 0;
        }
        .tagihan-item span:first-child {
            color: var(--text-muted);
        }
        .tagihan-item span:last-child {
            font-weight: 500;
        }

        /* Footer Info */
        .receipt-footer {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-top: 1px dashed var(--border-color);
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Print Button */
        .print-actions {
            margin-top: 25px;
            width: 100%;
            max-width: 380px;
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 14px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-print {
            background-color: #3b82f6;
            color: white;
        }

        .btn-print:hover { background-color: #2563eb; }

        .btn-close {
            background-color: white;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }
        .btn-close:hover { background-color: #f3f4f6; }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                border: 1px solid var(--border-color);
            }
            .print-actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="receipt-header">
            <div class="success-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h2>Pembayaran Berhasil</h2>
            <p>{{ config('app.name', 'Institusi Pendidikan') }}</p>
        </div>

        <div class="amount-section">
            <div class="amount-label">Total Pembayaran</div>
            <h1 class="amount-value">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</h1>
        </div>

        <div class="details-section">
            <div class="detail-row">
                <div class="detail-label">Tanggal</div>
                <div class="detail-value">{{ $transaksi->updated_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="divider"></div>
            
            <div class="detail-row">
                <div class="detail-label">Order ID</div>
                <div class="detail-value">{{ $transaksi->order_id }}</div>
            </div>
            <div class="divider"></div>

            <div class="detail-row">
                <div class="detail-label">Metode</div>
                <div class="detail-value">QRIS ({{ $transaksi->metode_pembayaran }})</div>
            </div>
            <div class="divider"></div>

            <div class="detail-row">
                <div class="detail-label">Siswa</div>
                <div class="detail-value">{{ $siswa->nama }}<br><span style="font-size: 12px; color: var(--text-muted);">NIS: {{ $siswa->nis }}</span></div>
            </div>

            <div class="tagihan-list">
                <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 10px; text-transform: uppercase;">Rincian Tagihan</div>
                @foreach($transaksi->pembayaran as $p)
                    @if($p->tagihan)
                    <div class="tagihan-item">
                        <span>{{ $p->tagihan->nama_bulan }} {{ $p->tagihan->tahun }}</span>
                        <span>Rp {{ number_format($p->tagihan->nominal, 0, ',', '.') }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="receipt-footer">
            Struk ini adalah bukti pembayaran yang sah.<br>
            Dicetak pada: {{ now()->format('d M Y, H:i') }}
        </div>
    </div>

    <div class="print-actions">
        <button onclick="closeTab()" class="btn-action btn-close">Tutup</button>
        <button onclick="window.print()" class="btn-action btn-print">Cetak Struk</button>
    </div>

    <script>
        function closeTab() {
            window.close();
            setTimeout(function() {
                window.location.href = "{{ route('siswa.bayar.invoice', $transaksi->order_id) }}";
            }, 300);
        }
    </script>

</body>
</html>
