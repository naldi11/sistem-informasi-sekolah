<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandbox Mock Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .mock-container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .mock-header {
            background: #111827;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .mock-body {
            padding: 30px;
        }
    </style>
</head>
<body>

    <div class="mock-container">
        <div class="mock-header">
            @if($transaksi->tipe === 'qris')
                <h4 class="mb-0 fw-bold"><i class="bi bi-wallet2 text-success me-2"></i>E-Wallet Simulator</h4>
            @else
                <h4 class="mb-0 fw-bold"><i class="bi bi-bank2 text-info me-2"></i>M-Banking Simulator</h4>
            @endif
        </div>
        
        <div class="mock-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <div class="mt-2 text-center text-sm">
                        <i>Silakan tutup tab ini.</i>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($transaksi->status === 'pending')
                <div class="text-center mb-4">
                    <h6 class="text-muted text-uppercase mb-1">Pembayaran Kepada</h6>
                    <h5 class="fw-bold">{{ config('app.name', 'Institusi Pendidikan') }}</h5>
                </div>
                
                <div class="bg-light p-3 rounded mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order ID</span>
                        <span class="fw-bold">{{ $transaksi->order_id }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Metode</span>
                        <span class="fw-bold text-uppercase">{{ str_replace('_', ' ', $transaksi->metode_pembayaran) }}</span>
                    </div>
                    @if($transaksi->tipe === 'va')
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Nomor VA</span>
                        <span class="fw-bold">{{ $transaksi->kode_pembayaran }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <span class="text-muted">Total Bayar</span>
                        <span class="fw-bold fs-4 text-primary">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('sandbox.simulator.pay', $transaksi->order_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5 shadow-sm rounded-pill">
                        Konfirmasi Pembayaran
                    </button>
                    <div class="text-center mt-3">
                        <small class="text-muted"><i class="bi bi-shield-lock-fill me-1"></i>Secure Sandbox Environment</small>
                    </div>
                </form>
            @elseif($transaksi->status === 'sukses' && !session('success'))
                <div class="text-center py-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    <h4 class="mt-3 text-success fw-bold">Transaksi Sukses!</h4>
                    <p class="text-muted">Tagihan ini sudah diselesaikan.</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>
