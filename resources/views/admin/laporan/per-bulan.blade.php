@extends('layouts.admin')
@section('title', 'Laporan Per Bulan')

@section('content')
    <h5 class="fw-bold mb-3">Laporan Pembayaran Per Bulan</h5>

    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body">
            <form method="GET">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-calendar-month me-1"></i>Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-calendar-event me-1"></i>Tahun</label>
                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-building me-1"></i>Kelas</label>
                        <select name="kelas_id" class="form-select form-select-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-top pt-2 mt-3">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-medium">
                            <i class="bi bi-filter me-1"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('admin.laporan.perBulan') }}" class="btn btn-light btn-sm px-3 border text-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">Export Laporan:</span>
                        <a href="{{ route('admin.laporan.exportPdf', ['type' => 'per-bulan', 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => request('kelas_id')]) }}" class="btn btn-danger btn-sm px-3 fw-medium">
                            <i class="bi bi-file-pdf-fill me-1"></i>Export PDF
                        </a>
                        <a href="{{ route('admin.laporan.exportExcel', ['type' => 'per-bulan', 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => request('kelas_id')]) }}" class="btn btn-success btn-sm px-3 fw-medium">
                            <i class="bi bi-file-excel-fill me-1"></i>Export Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="stat-card" style="background:linear-gradient(135deg,#059669,#10b981);">
                <div class="stat-label">Total Lunas</div>
                <div class="stat-value">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card" style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                <div class="stat-label">Total Belum Bayar</div>
                <div class="stat-value">Rp {{ number_format($totalBelum, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihan as $i => $t)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $t->siswa->nis }}</td>
                            <td>{{ $t->siswa->nama }}</td>
                            <td><span class="badge bg-primary">{{ $t->siswa->kelas->nama_kelas }}</span></td>
                            <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection