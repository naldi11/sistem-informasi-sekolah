@extends('layouts.siswa')
@section('title', 'Bayar SPP')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header py-3"><i class="bi bi-receipt me-2"></i>Detail Tagihan</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width:40%;">Nama</td>
                            <td class="fw-medium">{{ $tagihan->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas</td>
                            <td>{{ $tagihan->siswa->kelas->nama_kelas }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bulan</td>
                            <td>{{ $tagihan->nama_bulan }} {{ $tagihan->tahun }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nominal</td>
                            <td class="fs-5 fw-bold text-primary">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header py-3"><i class="bi bi-bank me-2"></i>Informasi Transfer</div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <p class="mb-1"><strong>Bank:</strong> Bank BRI</p>
                        <p class="mb-1"><strong>No. Rekening:</strong> 1234-5678-9012-3456</p>
                        <p class="mb-1"><strong>Atas Nama:</strong> Bendahara Sekolah</p>
                        <p class="mb-0"><strong>Nominal:</strong> Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header py-3"><i class="bi bi-upload me-2"></i>Upload Bukti Transfer</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('siswa.bayar.store', $tagihan) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Upload Bukti Transfer</label>
                            <input type="file" name="file_bukti" class="form-control" accept=".jpg,.jpeg,.png" required>
                            <small class="text-muted">Format: JPG/PNG, Maks: 5MB</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Kirim Bukti Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection