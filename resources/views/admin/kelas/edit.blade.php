@extends('layouts.admin')
@section('title', 'Edit Kelas')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header py-3"><i class="bi bi-pencil me-2"></i>Edit Kelas</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control"
                                value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Tingkat</label>
                            <input type="text" name="tingkat" class="form-control"
                                value="{{ old('tingkat', $kelas->tingkat) }}" required
                                placeholder="Contoh: X, XI, XII, TKJ, RPL">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control"
                                value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" required>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection