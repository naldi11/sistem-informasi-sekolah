@extends('layouts.admin')
@section('title', 'Tambah Metode Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Metode Pembayaran</h4>
        <p class="text-muted small mb-0">Tambahkan metode pembayaran baru untuk siswa.</p>
    </div>
    <a href="{{ route('admin.metode-pembayaran.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.metode-pembayaran.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Metode <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Transfer Bank Mandiri" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kode Unik <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="Contoh: transfer_mandiri" required>
                            <small class="text-muted">Huruf kecil, tanpa spasi (gunakan underscore).</small>
                            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="transfer_manual" {{ old('kategori') == 'transfer_manual' ? 'selected' : '' }}>Transfer Bank Manual</option>
                                <option value="va" {{ old('kategori') == 'va' ? 'selected' : '' }}>Virtual Account (VA)</option>
                                <option value="qris" {{ old('kategori') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Rekening / Kode VA</label>
                            <input type="text" name="nomor_rekening" class="form-control @error('nomor_rekening') is-invalid @enderror" value="{{ old('nomor_rekening') }}" placeholder="Contoh: 1234567890">
                            @error('nomor_rekening') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Pemilik Rekening (Atas Nama)</label>
                            <input type="text" name="pemilik_rekening" class="form-control @error('pemilik_rekening') is-invalid @enderror" value="{{ old('pemilik_rekening') }}" placeholder="Contoh: SMANKER OFFICIAL">
                            @error('pemilik_rekening') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Instruksi Pembayaran</label>
                            <textarea name="instruksi" class="form-control @error('instruksi') is-invalid @enderror" rows="3" placeholder="Tuliskan petunjuk transfer bagi siswa...">{{ old('instruksi') }}</textarea>
                            @error('instruksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 border-top pt-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="butuh_bukti" id="butuh_bukti" value="1" {{ old('butuh_bukti', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="butuh_bukti">Wajibkan Upload Bukti Transfer oleh Siswa</label>
                            </div>
                            <small class="text-muted d-block mb-3">Jika dicentang, siswa wajib mengunggah foto/bukti resi transfer saat checkout atau di halaman invoice.</small>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Aktifkan Metode Pembayaran Ini</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Metode
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
