@extends('layouts.admin')
@section('title', 'Laporan Tunggakan')

@section('content')
    <h5 class="fw-bold mb-3">Laporan Tunggakan</h5>

    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body">
            <form method="GET">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-building me-1"></i>Filter Kelas</label>
                        <select name="kelas_id" class="form-select form-select-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-muted" style="font-size:0.8rem;"><i class="bi bi-calendar-event me-1"></i>Tahun</label>
                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ request('tahun') }}" placeholder="Semua Tahun">
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-top pt-2 mt-3">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-medium">
                            <i class="bi bi-filter me-1"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('admin.laporan.tunggakan') }}" class="btn btn-light btn-sm px-3 border text-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">Export Laporan:</span>
                        <a href="{{ route('admin.laporan.exportPdf', ['type' => 'tunggakan', 'kelas_id' => request('kelas_id'), 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-danger btn-sm px-3 fw-medium">
                            <i class="bi bi-file-pdf-fill me-1"></i>Export PDF
                        </a>
                        <a href="{{ route('admin.laporan.exportExcel', ['type' => 'tunggakan', 'kelas_id' => request('kelas_id'), 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-success btn-sm px-3 fw-medium">
                            <i class="bi bi-file-excel-fill me-1"></i>Export Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Bulan Menunggak</th>
                        <th>Total Tunggakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grouped as $i => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-medium">{{ $data['siswa']->nama ?? '-' }}<br><small class="text-muted">NIS:
                                    {{ $data['siswa']->nis ?? '-' }}</small></td>
                            <td><span class="badge bg-primary">{{ $data['siswa']->kelas->nama_kelas ?? '-' }}</span></td>
                            <td>
                                @foreach($data['tagihan'] as $t)
                                    <span class="badge bg-warning text-dark me-1">{{ $t->nama_bulan }} {{ $t->tahun }}</span>
                                @endforeach
                                <br><small class="text-muted">{{ $data['jumlah_bulan'] }} bulan</small>
                            </td>
                            <td class="fw-bold text-danger">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada tunggakan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection