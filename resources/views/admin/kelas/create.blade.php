@extends('layouts.admin')
@section('title', 'Tambah Kelas')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header py-3"><i class="bi bi-plus-lg me-2"></i>Tambah Kelas Baru</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.kelas.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas') }}"
                                required placeholder="Contoh: X-A">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Tingkat</label>
                            <input type="text" name="tingkat" class="form-control" value="{{ old('tingkat') }}" required
                                placeholder="Contoh: X, XI, XII, TKJ, RPL">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control"
                                value="{{ old('tahun_ajaran', '2025/2026') }}" required placeholder="2025/2026">
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection