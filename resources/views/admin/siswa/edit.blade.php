@extends('layouts.admin')
@section('title', 'Edit Siswa')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header py-3">Edit Data Siswa — {{ $siswa->nama }}</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}">
                        @csrf @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">NIS</label>
                                <input type="text" class="form-control" value="{{ $siswa->nis }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Kelas</label>
                                <select name="kelas_id" class="form-select" required>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ old('tanggal_lahir', $siswa->tanggal_lahir->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-medium">Alamat</label>
                                <textarea name="alamat" class="form-control"
                                    rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection