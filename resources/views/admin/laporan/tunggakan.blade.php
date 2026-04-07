@extends('layouts.admin')
@section('title', 'Laporan Tunggakan')

@section('content')
    <h5 class="fw-bold mb-3">Laporan Tunggakan</h5>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <select name="kelas_id" class="form-select form-select-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">Filter</button></div>
                <div class="col-md-4 text-end ms-auto">
                    <a href="{{ route('admin.laporan.exportPdf', ['type' => 'tunggakan', 'kelas_id' => request('kelas_id')]) }}"
                        class="btn btn-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</a>
                    <a href="{{ route('admin.laporan.exportExcel', ['type' => 'tunggakan', 'kelas_id' => request('kelas_id')]) }}"
                        class="btn btn-success btn-sm"><i class="bi bi-file-excel me-1"></i>Excel</a>
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