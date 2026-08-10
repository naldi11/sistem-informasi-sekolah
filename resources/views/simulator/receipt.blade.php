<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulator Receipt - {{ $transaksi->order_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Inter', sans-serif;
        }
        .mock-container {
            max-width: 400px;
            margin: 40px auto;
        }
        
        /* Receipt Card */
        .receipt-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            position: relative;
        }

        .receipt-header {
            background-color: #10b981; /* Success Green */
            color: white;
            text-align: center;
            padding: 30px 20px 40px;
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
            color: #10b981;
        }

        .receipt-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .receipt-header p {
            margin: 5px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .amount-section {
            text-align: center;
            padding: 25px 20px 10px;
            background: white;
            position: relative;
            z-index: 10;
            margin-top: -20px;
            border-radius: 20px 20px 0 0;
        }

        .amount-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            margin: 0;
        }

        .details-section {
            padding: 10px 25px 25px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            font-size: 13px;
        }

        .detail-label {
            color: #6b7280;
            flex: 1;
        }

        .detail-value {
            font-weight: 600;
            text-align: right;
            flex: 1;
            word-break: break-word;
            color: #374151;
        }

        .divider {
            border-top: 1px dashed #e5e7eb;
            margin: 5px 0;
        }
        
        .receipt-footer {
            text-align: center;
            padding: 15px;
            background: #f9fafb;
            border-top: 1px dashed #e5e7eb;
            font-size: 11px;
            color: #9ca3af;
        }

        /* Action Buttons outside the card */
        .actions-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 0 10px;
        }

        .btn-cta {
            padding: 14px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="mock-container">
        
        <div id="receiptCard" class="receipt-card">
            <div class="receipt-header">
                <div class="success-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h4>Pembayaran Berhasil</h4>
                <p>E-Wallet Simulator</p>
            </div>

            <div class="amount-section">
                <div class="amount-label">Total Pembayaran</div>
                <h1 class="amount-value">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</h1>
            </div>

            <div class="details-section">
                <div class="detail-row">
                    <div class="detail-label">Tanggal</div>
                    <div class="detail-value">{{ now()->format('d M Y, H:i') }}</div>
                </div>
                <div class="divider"></div>
                
                <div class="detail-row">
                    <div class="detail-label">Penerima</div>
                    <div class="detail-value">{{ config('app.name', 'Institusi Pendidikan') }}</div>
                </div>
                <div class="divider"></div>

                <div class="detail-row">
                    <div class="detail-label">Order ID</div>
                    <div class="detail-value">{{ $transaksi->order_id }}</div>
                </div>
                <div class="divider"></div>

                <div class="detail-row">
                    <div class="detail-label">Metode</div>
                    <div class="detail-value">QRIS (Simulator)</div>
                </div>
            </div>

            <div class="receipt-footer">
                Simpan struk ini sebagai bukti pembayaran Anda.<br>
                Ref: SIM-{{ strtoupper(Str::random(10)) }}
            </div>
        </div>

        <div class="actions-container">
            <button onclick="downloadReceipt()" id="btnDownload" class="btn btn-cta btn-dark border-0 shadow-sm">
                <i class="bi bi-download"></i> Simpan Struk (Image)
            </button>
            <a href="{{ route('siswa.bayar.invoice', $transaksi->order_id) }}" class="btn btn-cta btn-primary border-0 shadow-sm" style="background-color: #0d6efd;">
                <i class="bi bi-cloud-arrow-up-fill"></i> Lanjut Upload Bukti Bayar
            </a>
            <div class="text-center mt-2">
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Bukti ini wajib diupload ke sistem agar admin dapat mengonfirmasi pembayaran Anda.</small>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        function downloadReceipt() {
            const btn = document.getElementById('btnDownload');
            const card = document.getElementById('receiptCard');
            
            if(!card) return;
            
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
            btn.disabled = true;

            html2canvas(card, {
                scale: 3,
                backgroundColor: "#ffffff",
                logging: false,
                useCORS: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Bukti-QRIS-{{ $transaksi->order_id }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Berhasil Disimpan';
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 3000);
            }).catch(err => {
                console.error(err);
                alert("Gagal mengunduh struk.");
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>
