@extends('layouts.admin')
@section('title', 'Monitor Pembayaran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-credit-card me-2 text-primary"></i>Monitor Pembayaran</h4>
            <p class="text-muted small mb-0">Kelola dan konfirmasi bukti pembayaran tagihan siswa.</p>
        </div>
    </div>

    <ul class="nav nav-pills custom-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link rounded-pill px-3 {{ $tab == 'semua' ? 'active fw-bold' : 'bg-light text-dark' }}"
                href="{{ route('admin.pembayaran.index', ['tab' => 'semua']) }}">Semua Pembayaran</a>
        </li>
        <li class="nav-item ms-2">
            <a class="nav-link rounded-pill px-3 {{ $tab == 'menunggu' ? 'active fw-bold bg-danger' : 'bg-light text-dark' }}"
                href="{{ route('admin.pembayaran.index', ['tab' => 'menunggu']) }}">
                Menunggu Verifikasi
                @if($menungguCount > 0)
                    <span class="badge bg-white text-danger ms-1">{{ $menungguCount }}</span>
                @endif
            </a>
        </li>
    </ul>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Tagihan / Transaksi</th>
                            <th>Metode</th>
                            <th>Nominal</th>
                            <th>Tgl Upload</th>
                            <th class="text-center">Status</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $i => $t)
                            @php
                                $trx = $t->pembayaran?->transaksiSandbox;
                                $isMulti = false;
                                $trxBulanStr = $t->nama_bulan . ' ' . $t->tahun;
                                
                                if ($trx && $trx->pembayaran->count() > 1) {
                                    $isMulti = true;
                                    $firstTg = $trx->pembayaran->first()->tagihan;
                                    $lastTg = $trx->pembayaran->last()->tagihan;
                                    if ($firstTg && $lastTg) {
                                        $trxBulanStr = $firstTg->nama_bulan . ' ' . $firstTg->tahun . ' - ' . $lastTg->nama_bulan . ' ' . $lastTg->tahun;
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="fw-bold text-muted">{{ $pembayaran->firstItem() + $i }}</td>
                                <td class="fw-medium">
                                    {{ $t->siswa->nama ?? '-' }}
                                    <small class="d-block text-muted">NIS: {{ $t->siswa->nis ?? '-' }}</small>
                                </td>
                                <td><span class="badge bg-primary">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $trxBulanStr }}</div>
                                    @if($isMulti)
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info" style="font-size:0.7rem;">
                                            <i class="bi bi-layers me-1"></i>Multi-Bulan ({{ $trx->pembayaran->count() }} Bln)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($trx)
                                        <span class="badge bg-dark">{{ $trx->metode_pembayaran }}</span>
                                    @else
                                        <span class="badge bg-secondary">Transfer Manual</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td class="small">{{ $t->pembayaran?->tanggal_upload?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="text-center"><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pembayaran.show', $t) }}" class="btn btn-sm btn-outline-primary fw-bold" title="Detail Verifikasi">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    Tidak ada data pembayaran yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $pembayaran->links() }}</div>
@endsection