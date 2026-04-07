@extends('layouts.admin')
@section('title', 'Detail Siswa')

@section('content')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3"
                        style="width:80px;height:80px;">
                        <i class="bi bi-person-fill text-primary fs-2"></i>
                    </div>
                    <h5 class="fw-bold">{{ $siswa->nama }}</h5>
                    <p class="text-muted mb-2">NIS: {{ $siswa->nis }}</p>
                    <span class="badge bg-primary mb-3">{{ $siswa->kelas->nama_kelas }}</span>

                    <table class="table table-sm text-start mt-3">
                        <tr>
                            <td class="text-muted">Tanggal Lahir</td>
                            <td>{{ $siswa->tanggal_lahir->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Kelamin</td>
                            <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td>{{ $siswa->alamat ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Akun</td>
                            <td>{!! $siswa->user->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>' !!}
                            </td>
                        </tr>
                    </table>

                    <form action="{{ route('admin.siswa.resetPassword', $siswa) }}" method="POST"
                        onsubmit="return confirm('Reset password ke default?')">
                        @csrf
                        <button class="btn btn-outline-warning btn-sm w-100 mt-2"><i class="bi bi-key me-1"></i>Reset
                            Password</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header py-3"><i class="bi bi-receipt me-2"></i>Riwayat Pembayaran</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Tgl Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa->tagihan as $t)
                                <tr>
                                    <td>{{ $t->nama_bulan }}</td>
                                    <td>{{ $t->tahun }}</td>
                                    <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                                    <td>{{ $t->pembayaran ? $t->pembayaran->tanggal_verifikasi?->format('d/m/Y') ?? '-' : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada tagihan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection