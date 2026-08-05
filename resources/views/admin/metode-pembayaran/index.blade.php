@extends('layouts.admin')
@section('title', 'Kelola Metode Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-wallet2 me-2 text-primary"></i>Metode Pembayaran</h4>
        <p class="text-muted small mb-0">Kelola daftar metode pembayaran yang dapat digunakan oleh siswa.</p>
    </div>
    <a href="{{ route('admin.metode-pembayaran.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Metode
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Nama Metode</th>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>No. Rekening / Kode</th>
                        <th class="text-center">Upload Bukti</th>
                        <th class="text-center">Status</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($metodeList as $index => $m)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $m->nama }}</div>
                                @if($m->pemilik_rekening)
                                    <small class="text-muted">a/n {{ $m->pemilik_rekening }}</small>
                                @endif
                            </td>
                            <td><code>{{ $m->kode }}</code></td>
                            <td>
                                @if($m->kategori === 'qris')
                                    <span class="badge px-2 py-1" style="background-color: rgba(111, 66, 193, 0.15); color: #6f42c1; border: 1px solid rgba(111, 66, 193, 0.4);"><i class="bi bi-qr-code-scan me-1"></i>QRIS</span>
                                @elseif($m->kategori === 'va')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1"><i class="bi bi-bank me-1"></i>Virtual Account</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1"><i class="bi bi-cash-coin me-1"></i>Transfer Manual</span>
                                @endif
                            </td>
                            <td>{{ $m->nomor_rekening ?? '-' }}</td>
                            <td class="text-center">
                                @if($m->butuh_bukti)
                                    <span class="badge bg-primary rounded-pill"><i class="bi bi-paperclip me-1"></i>Wajib Upload</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Otomatis / Tanpa Bukti</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($m->is_active)
                                    <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Aktif</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i>Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.metode-pembayaran.edit', $m->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.metode-pembayaran.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus metode pembayaran ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada metode pembayaran yang dikonfigurasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
