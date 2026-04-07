@extends('layouts.admin')
@section('title', 'Laporan Per Bulan')

@section('content')
    <h5 class="fw-bold mb-3">Laporan Pembayaran Per Bulan</h5>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.8rem;">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.8rem;">Tahun</label>
                    <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.8rem;">Kelas</label>
                    <select name="kelas_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">Filter</button>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.laporan.exportPdf', ['type' => 'per-bulan', 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => request('kelas_id')]) }}"
                        class="btn btn-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</a>
                    <a href="{{ route('admin.laporan.exportExcel', ['type' => 'per-bulan', 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => request('kelas_id')]) }}"
                        class="btn btn-success btn-sm"><i class="bi bi-file-excel me-1"></i>Excel</a>
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