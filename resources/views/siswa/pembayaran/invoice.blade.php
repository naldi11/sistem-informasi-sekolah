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
            <div class="card-body p-5 text-center">
                @php
                    $pembayaranFirst = $transaksi->pembayaran->first();
                    $hasBukti = $pembayaranFirst && !empty($pembayaranFirst->file_bukti);
                @endphp
                
                @if($transaksi->status === 'sukses')
                    <div class="my-5">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 text-success fw-bold">Pembayaran Diterima!</h4>
                        <p class="text-muted">Terima kasih, pembayaran telah berhasil diverifikasi.</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
                            @if($transaksi->tipe === 'qris')
                                <a href="{{ route('siswa.bayar.print', $transaksi->order_id) }}" target="_blank" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Cetak Struk</a>
                            @endif
                        </div>
                    </div>
                @elseif($transaksi->status === 'menunggu_verifikasi' || $hasBukti)
                    <div class="my-5">
                        <i class="bi bi-hourglass-split text-info" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 text-info fw-bold">Menunggu Verifikasi Admin</h4>
                        <p class="text-muted">Bukti transfer Anda telah diterima dan sedang menunggu pengecekan oleh Admin.</p>
                        
                        @if($hasBukti)
                            <div class="mt-4">
                                <a href="{{ asset('storage/' . $pembayaranFirst->file_bukti) }}" target="_blank" class="btn btn-outline-info rounded-pill px-4">
                                    <i class="bi bi-eye me-1"></i> Lihat Foto Bukti
                                </a>
                            </div>
                        @endif

                        <div class="mt-5 pt-4 border-top d-flex justify-content-center">
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    
                    @if($transaksi->tipe === 'qris')
                        @if(request('action') === 'upload')
                            <!-- Hide QR Code if action=upload (Came from Simulator) -->
                            <div class="alert alert-info text-start mb-4 shadow-sm border-0 border-start border-info border-4">
                                <strong><i class="bi bi-info-circle-fill me-2"></i> Pembayaran Disimulasikan</strong><br>
                                Silakan upload bukti transfer (struk e-wallet yang baru saja Anda unduh/simpan) di bawah ini untuk diverifikasi oleh admin.
                            </div>
                        @else
                            <!-- E-Wallet Style Struk -->
                        <div class="mx-auto border rounded-4 shadow-sm overflow-hidden mb-4" style="max-width: 350px;">
                            <div class="bg-primary text-white p-3 text-center">
                                <h6 class="mb-0 fw-bold">Pindai QR Code Berikut</h6>
                            </div>
                            <div class="p-4 bg-white" id="qrDownloadCard">
                                <div class="text-center mb-3">
                                    <h5 class="fw-bold mb-1 text-primary"><i class="bi bi-qr-code"></i> QRIS</h5>
                                    <small class="text-muted">{{ config('app.name', 'Institusi Pendidikan') }}</small>
                                </div>
                                
                                <div class="text-center mb-4">
                                    <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($transaksi->kode_pembayaran)) }}" alt="QRIS" class="img-fluid border p-2 rounded-3 shadow-sm" id="qrisImage">
                                </div>
                                
                                <div class="text-center pt-3 border-top" style="border-top-style: dashed !important;">
                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Tagihan</small>
                                    <h3 class="fw-bold text-dark mb-0 mt-1">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</h3>
                                    <small class="text-muted d-block mt-2" style="font-size: 11px;">Order ID: {{ $transaksi->order_id }}</small>
                                </div>
                            </div>
                            <div class="bg-light p-2 text-center d-flex justify-content-center gap-2">
                                <button onclick="downloadQR()" class="btn btn-outline-dark btn-sm w-50" id="btnDownload">
                                    <i class="bi bi-download"></i> Unduh
                                </button>
                                <a href="{{ route('sandbox.simulator', $transaksi->order_id) }}" target="_blank" class="btn btn-primary btn-sm w-50">
                                    <i class="bi bi-box-arrow-up-right"></i> Simulator
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- CTA Upload Bukti below the Struk -->
                        <div class="mt-4 pt-4 border-top text-center">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-earmark-image me-1 text-primary"></i> Upload Bukti Transfer</h6>
                            <p class="text-muted small mb-3">Setelah melakukan pembayaran via e-wallet / m-banking, wajib upload screenshot bukti transfer agar admin dapat memverifikasinya.</p>
                            
                            <form action="{{ route('siswa.bayar.uploadBukti', $transaksi->order_id) }}" method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 400px;">
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
                                <div class="input-group input-group-lg shadow-sm">
                                    <input type="file" name="file_bukti" class="form-control fs-6" accept="image/*" required>
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        <i class="bi bi-upload me-1"></i> Upload
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2 text-start"><i class="bi bi-info-circle me-1"></i> Format: Semua Gambar (Maks. 5MB, Otomatis dikompres)</small>
                            </form>
                        </div>

                    @elseif($transaksi->tipe === 'va')
                        <h6 class="text-muted mb-1">Total Tagihan</h6>
                        <h2 class="fw-bold text-dark mb-4">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</h2>
                        <hr>

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
                        <h6 class="text-muted mb-1">Total Tagihan</h6>
                        <h2 class="fw-bold text-dark mb-4">Rp {{ number_format($transaksi->total_nominal, 0, ',', '.') }}</h2>
                        <hr>

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

                    @if($transaksi->tipe !== 'qris')
                        <!-- Upload Bukti Transfer Form untuk Non-QRIS -->
                        <div class="mt-4 pt-3 border-top text-start">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-earmark-image me-1"></i> Bukti Transfer</h6>
                            
                            @php
                                $pembayaranFirst = $transaksi->pembayaran->first();
                                $hasBukti = $pembayaranFirst && !empty($pembayaranFirst->file_bukti);
                            @endphp
                            
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
                                <label class="form-label small fw-bold">Upload Bukti Transfer (Gambar):</label>
                                <div class="input-group">
                                    <input type="file" name="file_bukti" class="form-control form-control-sm" accept="image/*" required>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-upload me-1"></i> Upload
                                    </button>
                                </div>
                                <small class="text-muted">Format: Semua Gambar (Maks. 5MB, otomatis dikompres sebelum dikirim).</small>
                            </form>
                        </div>
                    @endif

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

    // Script Kompresi Gambar Otomatis untuk mencegah "Entity Too Large" dari foto Kamera HP
    function compressImage(file, callback) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = event => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const MAX_WIDTH = 1200;
                let width = img.width;
                let height = img.height;

                if (width > MAX_WIDTH) {
                    height = Math.round((height * MAX_WIDTH) / width);
                    width = MAX_WIDTH;
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(blob => {
                    const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + "_compressed.jpg", {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    callback(newFile);
                }, 'image/jpeg', 0.8);
            };
        };
    }

    document.querySelectorAll('form').forEach(form => {
        const fileInput = form.querySelector('input[type="file"]');
        if (fileInput) {
            form.addEventListener('submit', function(e) {
                const file = fileInput.files[0];
                if (file && file.type.startsWith('image/') && file.size > 1024 * 1024) { // Kompres jika > 1MB
                    e.preventDefault();
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalHtml = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengompres...';
                    submitBtn.disabled = true;

                    compressImage(file, function(compressedFile) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        fileInput.files = dataTransfer.files;
                        form.submit();
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection
