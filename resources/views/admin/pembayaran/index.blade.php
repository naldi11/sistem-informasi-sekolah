@extends('layouts.admin')
@section('title', 'Pembayaran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Monitor Pembayaran</h5>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'semua' ? 'active' : '' }}"
                href="{{ route('admin.pembayaran.index', ['tab' => 'semua']) }}">Semua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'menunggu' ? 'active' : '' }}"
                href="{{ route('admin.pembayaran.index', ['tab' => 'menunggu']) }}">
                Menunggu Verifikasi
                @if($menungguCount > 0)
                    <span class="badge bg-danger">{{ $menungguCount }}</span>
                @endif
            </a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Bulan</th>
                        <th>Nominal</th>
                        <th>Tgl Upload</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $i => $t)
                        <tr>
                            <td>{{ $pembayaran->firstItem() + $i }}</td>
                            <td class="fw-medium">{{ $t->siswa->nama ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                            <td>{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                            <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                            <td>{{ $t->pembayaran?->tanggal_upload?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('admin.pembayaran.show', $t) }}" class="btn btn-sm btn-info"><i
                                        class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $pembayaran->links() }}</div>
@endsection