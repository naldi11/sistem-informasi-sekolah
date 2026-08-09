@extends('layouts.admin')
@section('title', 'Laporan Per Siswa')

@section('content')
    <h5 class="fw-bold mb-3">Laporan Pembayaran Per Siswa</h5>

    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body">
            <form method="GET">
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-person me-1"></i>Pilih Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id }}" {{ request('siswa_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nis }} - {{ $s->nama }} ({{ $s->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-top pt-2 mt-3">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-medium">
                            <i class="bi bi-filter me-1"></i>Tampilkan Laporan
                        </button>
                        <a href="{{ route('admin.laporan.perSiswa') }}" class="btn btn-light btn-sm px-3 border text-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>
                    @if($siswa)
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">Export Laporan:</span>
                        <a href="{{ route('admin.laporan.exportPdf', ['type' => 'per-siswa', 'siswa_id' => $siswa->id]) }}" class="btn btn-danger btn-sm px-3 fw-medium">
                            <i class="bi bi-file-pdf-fill me-1"></i>Export PDF
                        </a>
                        <a href="{{ route('admin.laporan.exportExcel', ['type' => 'per-siswa', 'siswa_id' => $siswa->id]) }}" class="btn btn-success btn-sm px-3 fw-medium">
                            <i class="bi bi-file-excel-fill me-1"></i>Export Excel
                        </a>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($siswa)
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">{{ $siswa->nama }}</h6>
                <small class="text-muted">NIS: {{ $siswa->nis }} | Kelas: {{ $siswa->kelas->nama_kelas }}</small>
                <div class="row g-2 mt-2">
                    <div class="col-md-4">
                        <div class="p-2 bg-success bg-opacity-10 rounded text-center"><small class="text-muted">Total
                                Lunas</small>
                            <div class="fw-bold text-success">Rp
                                {{ number_format($tagihan->where('status', 'lunas')->sum('nominal'), 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 bg-danger bg-opacity-10 rounded text-center"><small class="text-muted">Total
                                Tunggakan</small>
                            <div class="fw-bold text-danger">Rp
                                {{ number_format($tagihan->whereIn('status', ['belum_bayar', 'ditolak'])->sum('nominal'), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 bg-info bg-opacity-10 rounded text-center"><small class="text-muted">Total
                                Tagihan</small>
                            <div class="fw-bold text-info">Rp {{ number_format($tagihan->sum('nominal'), 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tagihan as $t)
                            <tr>
                                <td>{{ $t->nama_bulan }}</td>
                                <td>{{ $t->tahun }}</td>
                                <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection