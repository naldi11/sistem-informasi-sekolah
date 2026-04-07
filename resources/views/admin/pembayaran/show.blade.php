@extends('layouts.admin')
@section('title', 'Detail Pembayaran')

@section('content')
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header py-3">Bukti Transfer</div>
                <div class="card-body text-center">
                    @if($tagihan->pembayaran && $tagihan->pembayaran->file_bukti)
                        @php $buktiFiles = explode(',', $tagihan->pembayaran->file_bukti); @endphp
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            @foreach($buktiFiles as $bukti)
                                <img src="{{ asset('storage/bukti_transfer/' . trim($bukti)) }}" class="img-fluid rounded"
                                    style="max-height:400px; cursor:pointer;" alt="Bukti Transfer" onclick="window.open(this.src)">
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted py-5">Belum ada bukti transfer</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header py-3">Detail Tagihan</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted" style="width:40%;">Nama Siswa</td>
                            <td class="fw-medium">{{ $tagihan->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIS</td>
                            <td>{{ $tagihan->siswa->nis }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas</td>
                            <td>{{ $tagihan->siswa->kelas->nama_kelas }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bulan Tagihan</td>
                            <td>{{ $tagihan->nama_bulan }} {{ $tagihan->tahun }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nominal</td>
                            <td class="fw-bold text-success">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td><span
                                    class="badge bg-{{ $tagihan->status_badge }} badge-status">{{ $tagihan->status_label }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tgl Upload</td>
                            <td>{{ $tagihan->pembayaran?->tanggal_upload?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tgl Verifikasi</td>
                            <td>{{ $tagihan->pembayaran?->tanggal_verifikasi?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                        @if($tagihan->pembayaran?->catatan)
                            <tr>
                                <td class="text-muted">Catatan</td>
                                <td class="text-danger">{{ $tagihan->pembayaran->catatan }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($tagihan->status === 'menunggu_verifikasi')
                <div class="card">
                    <div class="card-header py-3">Tindakan</div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $tagihan) }}"
                                onsubmit="return confirm('Verifikasi pembayaran ini?')">
                                @csrf
                                <button class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Verifikasi (Lunas)</button>
                            </form>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal"><i
                                    class="bi bi-x-lg me-1"></i>Tolak</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tolak Modal -->
    <div class="modal fade" id="tolakModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.pembayaran.tolak', $tagihan) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Pembayaran</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Alasan Penolakan (opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3"
                                placeholder="Contoh: Bukti transfer tidak jelas"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary mt-3"><i
            class="bi bi-arrow-left me-1"></i>Kembali</a>
@endsection