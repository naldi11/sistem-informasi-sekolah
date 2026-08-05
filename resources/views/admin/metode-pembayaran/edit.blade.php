@extends('layouts.admin')
@section('title', 'Edit Metode Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Metode Pembayaran</h4>
        <p class="text-muted small mb-0">Ubah konfigurasi metode pembayaran {{ $metode->nama }}.</p>
    </div>
    <a href="{{ route('admin.metode-pembayaran.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.metode-pembayaran.update', $metode->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Metode <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $metode->nama) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kode Unik <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $metode->kode) }}" required>
                            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="transfer_manual" {{ old('kategori', $metode->kategori) == 'transfer_manual' ? 'selected' : '' }}>Transfer Bank Manual</option>
                                <option value="va" {{ old('kategori', $metode->kategori) == 'va' ? 'selected' : '' }}>Virtual Account (VA)</option>
                                <option value="qris" {{ old('kategori', $metode->kategori) == 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Rekening / Kode VA</label>
                            <input type="text" name="nomor_rekening" class="form-control @error('nomor_rekening') is-invalid @enderror" value="{{ old('nomor_rekening', $metode->nomor_rekening) }}">
                            @error('nomor_rekening') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Pemilik Rekening (Atas Nama)</label>
                            <input type="text" name="pemilik_rekening" class="form-control @error('pemilik_rekening') is-invalid @enderror" value="{{ old('pemilik_rekening', $metode->pemilik_rekening) }}">
                            @error('pemilik_rekening') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Instruksi Pembayaran</label>
                            <textarea name="instruksi" class="form-control @error('instruksi') is-invalid @enderror" rows="3">{{ old('instruksi', $metode->instruksi) }}</textarea>
                            @error('instruksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 border-top pt-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="butuh_bukti" id="butuh_bukti" value="1" {{ old('butuh_bukti', $metode->butuh_bukti) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="butuh_bukti">Wajibkan Upload Bukti Transfer oleh Siswa</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $metode->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Aktifkan Metode Pembayaran Ini</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Perbarui Metode
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
