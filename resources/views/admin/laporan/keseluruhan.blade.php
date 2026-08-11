@extends('layouts.admin')
@section('title', 'Laporan Pembayaran Keseluruhan')

@section('content')
    <h5 class="fw-bold mb-3"><i class="bi bi-collection-fill me-2 text-primary"></i>Laporan Pembayaran Keseluruhan</h5>

    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body">
            <form method="GET">
                <div class="row g-2">
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-search me-1"></i>Cari Siswa / NIS</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama / NIS..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4 col-lg-2">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-building me-1"></i>Tingkat</label>
                        <select name="tingkat" class="form-select form-select-sm">
                            <option value="">Semua Tingkat</option>
                            @foreach($tingkatList as $t)
                                <option value="{{ $t }}" {{ request('tingkat') == $t ? 'selected' : '' }}>
                                    Kelas {{ $t }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-2">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-calendar-month me-1"></i>Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-calendar-event me-1"></i>Tahun</label>
                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ request('tahun') }}" placeholder="Semua Tahun">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-funnel me-1"></i>Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-top pt-2 mt-3">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-medium">
                            <i class="bi bi-filter me-1"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('admin.laporan.keseluruhan') }}" class="btn btn-light btn-sm px-3 border text-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">Export Laporan:</span>
                        <a href="{{ route('admin.laporan.exportPdf', array_merge(['type' => 'keseluruhan'], request()->all())) }}" class="btn btn-danger btn-sm px-3 fw-medium">
                            <i class="bi bi-file-pdf-fill me-1"></i>Export PDF
                        </a>
                        <a href="{{ route('admin.laporan.exportExcel', array_merge(['type' => 'keseluruhan'], request()->all())) }}" class="btn btn-success btn-sm px-3 fw-medium">
                            <i class="bi bi-file-excel-fill me-1"></i>Export Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">
                <div class="stat-label">Total Tagihan (Filter)</div>
                <div class="stat-value">Rp {{ number_format($stats['totalNominal'], 0, ',', '.') }}</div>
                <small class="text-white-50">{{ $stats['totalCount'] }} Tagihan</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#059669,#10b981);">
                <div class="stat-label">Total Lunas</div>
                <div class="stat-value">Rp {{ number_format($stats['lunasNominal'], 0, ',', '.') }}</div>
                <small class="text-white-50">{{ $stats['lunasCount'] }} Lunas</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                <div class="stat-label">Menunggu Verifikasi</div>
                <div class="stat-value">Rp {{ number_format($stats['menungguNominal'], 0, ',', '.') }}</div>
                <small class="text-white-50">{{ $stats['menungguCount'] }} Menunggu</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                <div class="stat-label">Belum Bayar / Tunggakan</div>
                <div class="stat-value">Rp {{ number_format($stats['belumNominal'], 0, ',', '.') }}</div>
                <small class="text-white-50">{{ $stats['belumCount'] }} Tagihan</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Data Pembayaran SPP Keseluruhan</h6>
            <span class="badge bg-secondary">Total {{ $tagihan->total() }} Data</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Bulan & Tahun</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihan as $i => $t)
                        <tr>
                            <td>{{ $tagihan->firstItem() + $i }}</td>
                            <td>{{ $t->siswa->nis ?? '-' }}</td>
                            <td class="fw-medium">{{ $t->siswa->nama ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                            <td>{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                            <td class="fw-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data tagihan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $tagihan->links() }}</div>
@endsection
