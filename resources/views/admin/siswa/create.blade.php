@extends('layouts.admin')
@section('title', 'Tambah Siswa')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header py-3"><i class="bi bi-person-plus me-2"></i>Tambah Siswa Baru</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.siswa.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">NIS <span class="text-danger">*</span></label>
                                <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Kelas <span class="text-danger">*</span></label>
                                <select name="kelas_id" class="form-select" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ old('tanggal_lahir') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-medium">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                        <div class="alert alert-info py-2" style="font-size:0.85rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            Akun login akan dibuat otomatis. <b>Username = NIS</b>, <b>Password = tanggal lahir
                                (DDMMYYYY)</b>
                        </div>
                        <div class="alert alert-warning py-2" style="font-size:0.8rem;">
                            <i class="bi bi-shield-check me-1"></i>
                            <strong>Persetujuan Data Pribadi (UU PDP):</strong> Dengan menyimpan data ini, Anda menyatakan
                            bahwa data pribadi siswa telah diperoleh dengan persetujuan orang tua/wali siswa dan akan
                            diproses sesuai dengan <a href="{{ route('admin.kebijakanPrivasi') }}" target="_blank">Kebijakan
                                Privasi</a> sistem.
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection