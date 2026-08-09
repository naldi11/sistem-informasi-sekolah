@extends('layouts.admin')
@section('title', 'Laporan Per Kelas')

@section('content')
    <h5 class="fw-bold mb-3"><i class="bi bi-door-open me-2 text-primary"></i>Laporan Pembayaran Per Kelas</h5>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" style="font-size:0.8rem;">Pilih Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.8rem;">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">Semua Bulan</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.8rem;">Tahun</label>
                    <input type="number" name="tahun" class="form-control form-control-sm" value="{{ request('tahun', $tahun) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.8rem;">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-primary btn-sm me-1"><i class="bi bi-search me-1"></i>Filter</button>
                    @if(request('kelas_id'))
                        <a href="{{ route('admin.laporan.exportPdf', array_merge(['type' => 'per-kelas'], request()->all())) }}" class="btn btn-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</a>
                        <a href="{{ route('admin.laporan.exportExcel', array_merge(['type' => 'per-kelas'], request()->all())) }}" class="btn btn-success btn-sm"><i class="bi bi-file-excel me-1"></i>Excel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($kelas)
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#059669,#10b981);">
                    <div class="stat-label">Total Pembayaran Lunas ({{ $kelas->nama_kelas }})</div>
                    <div class="stat-value">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card" style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                    <div class="stat-label">Total Belum Lunas / Tunggakan</div>
                    <div class="stat-value">Rp {{ number_format($totalBelum, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Daftar Tagihan Siswa - {{ $kelas->nama_kelas }}</h6>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Bulan & Tahun</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihan as $i => $t)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $t->siswa->nis }}</td>
                                <td class="fw-medium">{{ $t->siswa->nama }}</td>
                                <td>{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                                <td class="fw-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data tagihan untuk kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-5 shadow-sm">
            <i class="bi bi-info-circle fs-1 text-info d-block mb-2"></i>
            Silakan pilih kelas terlebih dahulu pada filter di atas untuk melihat laporan pembayaran per kelas.
        </div>
    @endif
@endsection
