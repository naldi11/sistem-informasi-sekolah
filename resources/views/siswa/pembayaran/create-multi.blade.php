@extends('layouts.siswa')
@section('title', 'Bayar SPP - ' . $tagihanList->count() . ' Bulan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header py-3"><i class="bi bi-receipt me-2"></i>Detail Tagihan ({{ $tagihanList->count() }}
                    Bulan)</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Bulan</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tagihanList as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                                    <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-primary fw-bold">
                                <td colspan="2">Total</td>
                                <td>Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
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
                        <p class="mb-0"><strong>Total Nominal:</strong> <span class="fs-5 fw-bold">Rp
                                {{ number_format($totalNominal, 0, ',', '.') }}</span></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header py-3"><i class="bi bi-upload me-2"></i>Upload Bukti Transfer</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger py-2">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('siswa.bayar.storeMulti') }}" enctype="multipart/form-data">
                        @csrf
                        @foreach($tagihanList as $t)
                            <input type="hidden" name="tagihan_ids[]" value="{{ $t->id }}">
                        @endforeach
                        <div class="mb-3">
                            <label class="form-label fw-medium">Upload Bukti Transfer</label>
                            <input type="file" name="file_bukti[]" class="form-control" accept=".jpg,.jpeg,.png" required
                                multiple>
                            <small class="text-muted">Format: JPG/PNG, Maks: 5MB per file. Bisa upload beberapa bukti
                                sekaligus.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Kirim Bukti ({{ $tagihanList->count() }} Bulan)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection