@extends('layouts.siswa')
@section('title', 'Checkout Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cart-check me-2 text-primary"></i>Checkout Tagihan</h5>
            </div>
            
            <form action="{{ route('siswa.bayar.process') }}" method="POST">
                @csrf
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-3">Tagihan Yang Akan Dibayar</h6>
                    
                    <ul class="list-group mb-4">
                        @foreach($tagihanList as $t)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <input type="hidden" name="tagihan_ids[]" value="{{ $t->id }}">
                                <strong>{{ $t->nama_bulan }} {{ $t->tahun }}</strong>
                            </div>
                            <span class="fs-5">Rp {{ number_format($t->nominal, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <strong class="fs-5">Total Bayar</strong>
                            <strong class="fs-4 text-primary">Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong>
                        </li>
                    </ul>

                    <h6 class="text-muted fw-bold mb-3">Pilih Metode Pembayaran</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="card bg-light border p-3 rounded" style="cursor: pointer;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" value="qris" required>
                                    <label class="form-check-label ms-2 d-flex align-items-center fw-bold">
                                        <i class="bi bi-qr-code-scan fs-4 text-dark me-2"></i> QRIS Dinamis
                                    </label>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="card bg-light border p-3 rounded" style="cursor: pointer;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" value="va_bca" required>
                                    <label class="form-check-label ms-2 d-flex align-items-center fw-bold">
                                        <i class="bi bi-bank fs-4 text-primary me-2"></i> BCA Virtual Account
                                    </label>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="card bg-light border p-3 rounded" style="cursor: pointer;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" value="va_mandiri" required>
                                    <label class="form-check-label ms-2 d-flex align-items-center fw-bold">
                                        <i class="bi bi-bank fs-4 text-warning me-2"></i> Mandiri VA
                                    </label>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="card bg-light border p-3 rounded" style="cursor: pointer;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" value="va_bri" required>
                                    <label class="form-check-label ms-2 d-flex align-items-center fw-bold">
                                        <i class="bi bi-bank fs-4 text-info me-2"></i> BRI Briva
                                    </label>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center px-4">
                        <i class="bi bi-lock-fill me-2"></i> Bayar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
