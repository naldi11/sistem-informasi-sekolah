@extends('layouts.siswa')
@section('title', 'Instruksi Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="alert alert-info border-info d-flex align-items-center shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill fs-3 me-3 text-info"></i>
            <div>
                <strong>Instruksi Pembayaran</strong><br>
                Selesaikan pembayaran Anda menggunakan metode <strong>{{ $transaksi->metode_pembayaran }}</strong>.
            </div>
        </div>

        <div class="card shadow-sm border-0 text-center mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold">Selesaikan Pembayaran Anda</h5>
            </div>
            <div class="card-body py-4">
                
                @if($transaksi->status === 'sukses')
                    <div class="my-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 text-success fw-bold">Pembayaran Diterima!</h4>
                        <p class="text-muted">Terima kasih, pembayaran telah berhasil diverifikasi.</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
                            <a href="{{ route('siswa.bayar.print', $transaksi->order_id) }}" target="_blank" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Cetak Struk</a>
                        </div>
                    </div>
                @else
                    <h6 class="text-muted mb-1">Total Tagihan</h6>
                    <h2 class="fw-bold text-dark mb-4">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</h2>
                    <hr>

                    @if($transaksi->tipe === 'qris')
                        <h6 class="fw-bold text-dark mb-3">Scan QR Code Berikut:</h6>
                        
                        <div id="qrDownloadCard" class="mb-4 d-inline-block p-4 border rounded bg-white shadow-sm" style="min-width: 300px;">
                            <div class="mb-3">
                                <h6 class="mb-0 text-primary fw-bold"><i class="bi bi-qr-code"></i> QRIS</h6>
                                <small class="text-muted">{{ config('app.name', 'Institusi Pendidikan') }}</small>
                            </div>
                            
                            <div class="bg-white p-2 d-inline-block rounded">
                                <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(220)->generate($transaksi->kode_pembayaran)) }}" alt="QRIS" class="img-fluid" id="qrisImage">
                            </div>
                            
                            <div class="text-center mt-3 pt-2 border-top">
                                <h5 class="fw-bold text-dark mb-1">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</h5>
                                <small class="text-muted">Order: {{ $transaksi->order_id }}</small>
                            </div>
                        </div>

                        <div>
                            <button onclick="downloadQR()" class="btn btn-outline-dark btn-sm" id="btnDownload">
                                <i class="bi bi-download me-1"></i> Unduh QR Code
                            </button>
                            <a href="{{ route('sandbox.simulator', $transaksi->order_id) }}" target="_blank" class="btn btn-primary btn-sm ms-2">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Simulator E-Wallet
                            </a>
                        </div>
                    @elseif($transaksi->tipe === 'va')
                        <h6 class="fw-bold text-dark mb-2">Transfer ke Nomor Virtual Account:</h6>
                        <div class="d-flex align-items-center justify-content-center mt-3 mb-4">
                            <span class="fs-4 badge bg-light text-dark border p-3 font-monospace shadow-sm tracking-wide">
                                {{ $transaksi->kode_pembayaran }}
                            </span>
                        </div>
                        <p class="text-muted small">Anda bisa memasukkan nomor di atas pada Sandbox Simulator ATM.</p>
                        <a href="{{ route('sandbox.simulator', $transaksi->order_id) }}" target="_blank" class="btn btn-primary btn-sm mt-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Simulator M-Banking
                        </a>
                    @else
                        <h6 class="fw-bold text-dark mb-2">Nomor Rekening Tujuan:</h6>
                        <div class="d-flex align-items-center justify-content-center mt-3 mb-3">
                            <span class="fs-4 badge bg-light text-dark border p-3 font-monospace shadow-sm tracking-wide">
                                {{ $transaksi->kode_pembayaran }}
                            </span>
                        </div>
                        @if($metode && $metode->instruksi)
                            <div class="alert alert-warning text-start small mb-4">
                                <strong>Petunjuk:</strong><br>
                                {{ $metode->instruksi }}
                            </div>
                        @endif
                    @endif

                    <!-- Upload Bukti Transfer Form -->
                    <div class="mt-4 pt-3 border-top text-start">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-earmark-image me-1"></i> Bukti Transfer</h6>
                        
                        @php
                            $pembayaranFirst = $transaksi->pembayaran->first();
                            $hasBukti = $pembayaranFirst && !empty($pembayaranFirst->file_bukti);
                        @endphp

                        @if($hasBukti)
                            <div class="mb-3 p-3 border rounded bg-light">
                                <span class="badge bg-success mb-2"><i class="bi bi-check-circle me-1"></i> Bukti Transfer Telah Diunggah</span>
                                <div>
                                    <a href="{{ asset('storage/' . $pembayaranFirst->file_bukti) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Lihat Foto Bukti
                                    </a>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('siswa.bayar.uploadBukti', $transaksi->order_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @if($errors->any())
                                <div class="alert alert-danger py-2 px-3 small text-start">
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <label class="form-label small fw-bold">{{ $hasBukti ? 'Ubah / Upload Ulang Bukti Transfer (.jpg):' : 'Upload Bukti Transfer (.jpg):' }}</label>
                            <div class="input-group">
                                <input type="file" name="file_bukti" class="form-control form-control-sm" accept="image/jpeg,image/jpg,.jpg,.jpeg" required>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-upload me-1"></i> Upload
                                </button>
                            </div>
                            <small class="text-muted">Format: File JPG / JPEG sahaja (Maksimal 5MB). Memerlukan verifikasi admin.</small>
                        </form>
                    </div>

                    <!-- Navigation & Action Buttons -->
                    <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm text-nowrap w-100 w-sm-auto">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                        <form action="{{ route('siswa.bayar.batal', $transaksi->order_id) }}" method="POST" class="w-100 w-sm-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi pembayaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm text-nowrap w-100">
                                <i class="bi bi-x-circle me-1"></i> Batalkan Pembayaran Ini
                            </button>
                        </form>
                    </div>

                @endif
                
            </div>
            <div class="card-footer text-muted small py-3">
                Waktu expired: {{ $transaksi->expired_at ? $transaksi->expired_at->format('d M Y H:i') : '-' }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    function downloadQR() {
        const btn = document.getElementById('btnDownload');
        const card = document.getElementById('qrDownloadCard');
        
        if(!card) return;
        
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Memproses...';
        btn.disabled = true;

        html2canvas(card, {
            scale: 2,
            backgroundColor: "#ffffff",
            logging: false
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'QR-Pembayaran-{{ $transaksi->order_id }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            btn.innerHTML = '<i class="bi bi-download me-1"></i> Unduh QR Code';
            btn.disabled = false;
        }).catch(err => {
            console.error(err);
            alert("Gagal mengunduh QR code.");
            btn.innerHTML = '<i class="bi bi-download me-1"></i> Unduh QR Code';
            btn.disabled = false;
        });
    }

    @if($transaksi->status === 'pending')
    setInterval(() => {
        fetch("{{ route('siswa.bayar.status', $transaksi->order_id) }}")
            .then(res => res.json())
            .then(data => {
                if(data.status === 'sukses') {
                    window.location.reload();
                }
            })
            .catch(err => console.error('Gagal mengecek status', err));
    }, 3000);
    @endif
</script>
@endpush
@endsection
