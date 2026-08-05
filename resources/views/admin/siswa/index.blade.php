@extends('layouts.admin')
@section('title', 'Data Siswa')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-people me-2 text-primary"></i>Data Siswa</h4>
            <p class="text-muted small mb-0">Kelola data siswa aktif, reset password, dan import data dari Excel.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importSiswaModal">
                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
            </button>
            <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari nama atau NIS..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="kelas_id" class="form-select form-select-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Cari Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th class="text-center">JK</th>
                            <th class="text-center">Status</th>
                            <th style="width: 150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $i => $s)
                            <tr>
                                <td class="fw-bold text-muted">{{ $siswa->firstItem() + $i }}</td>
                                <td class="fw-medium"><code>{{ $s->nis }}</code></td>
                                <td class="fw-bold text-dark">{{ $s->nama }}</td>
                                <td><span class="badge bg-primary">{{ $s->kelas->nama_kelas }}</span></td>
                                <td class="text-center"><span class="badge bg-light text-dark border">{{ $s->jenis_kelamin }}</span></td>
                                <td class="text-center">
                                    @if($s->user && $s->user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.siswa.show', $s) }}" class="btn btn-outline-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.siswa.edit', $s) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Nonaktifkan siswa ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Nonaktifkan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    Tidak ada data siswa ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $siswa->links() }}</div>

    <!-- Modal Import Siswa & Panduan -->
    <div class="modal fade" id="importSiswaModal" tabindex="-1" aria-labelledby="importSiswaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold" id="importSiswaModalLabel">
                            <i class="bi bi-file-earmark-excel me-2"></i>Import Data Siswa dari Excel
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        
                        <!-- Unduh Template Action -->
                        <div class="alert alert-success border-success d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="fw-bold mb-1"><i class="bi bi-download me-1"></i> 1. Unduh Format Template Excel</h6>
                                <p class="small text-muted mb-0">Gunakan file template ini agar struktur kolom sesuai dengan sistem.</p>
                            </div>
                            <a href="{{ route('admin.siswa.downloadTemplate') }}" class="btn btn-sm btn-success fw-bold px-3">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Download Template (.xlsx)
                            </a>
                        </div>

                        <!-- Panduan Pengisian Kolom -->
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle me-1 text-primary"></i> 2. Panduan & Aturan Pengisian Kolom Excel:</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered small align-middle mb-0">
                                <thead class="table-light fw-bold">
                                    <tr>
                                        <th>Nama Kolom</th>
                                        <th>Status</th>
                                        <th>Aturan / Keterangan Format</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>NIS</code></td>
                                        <td><span class="badge bg-danger">Wajib</span></td>
                                        <td>Angka unik NIS (Digunakan sebagai <strong>Username</strong> login siswa). Harus belum terdaftar.</td>
                                    </tr>
                                    <tr>
                                        <td><code>Nama Siswa</code></td>
                                        <td><span class="badge bg-danger">Wajib</span></td>
                                        <td>Nama lengkap siswa.</td>
                                    </tr>
                                    <tr>
                                        <td><code>Nama Kelas</code></td>
                                        <td><span class="badge bg-danger">Wajib</span></td>
                                        <td>Nama kelas harus cocok persis dengan Master Kelas di sistem (contoh: <strong>X IPA 1</strong>).</td>
                                    </tr>
                                    <tr>
                                        <td><code>Tanggal Lahir</code></td>
                                        <td><span class="badge bg-danger">Wajib</span></td>
                                        <td>Format: <code>YYYY-MM-DD</code> (contoh: <code>2008-05-12</code>). Digunakan untuk <strong>Password Default</strong> <code>12052008</code> (DDMMYYYY).</td>
                                    </tr>
                                    <tr>
                                        <td><code>Jenis Kelamin</code></td>
                                        <td><span class="badge bg-danger">Wajib</span></td>
                                        <td>Diisi huruf <code>L</code> (Laki-laki) atau <code>P</code> (Perempuan).</td>
                                    </tr>
                                    <tr>
                                        <td><code>Alamat</code></td>
                                        <td><span class="badge bg-secondary">Opsional</span></td>
                                        <td>Alamat domisili/tempat tinggal siswa.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info py-2 px-3 small mb-4">
                            <i class="bi bi-key-fill me-1"></i> <strong>Informasi Akun Login:</strong><br>
                            Saat di-import, sistem akan otomatis membuatkan akun login dengan <strong>Username = NIS</strong> dan <strong>Password Default = Tanggal Lahir (DDMMYYYY)</strong>. Siswa dipaksa ganti password saat login pertama kali.
                        </div>

                        <!-- Form Input File -->
                        <div class="mb-2">
                            <label class="form-label fw-bold"><i class="bi bi-upload me-1"></i> 3. Pilih File Excel / CSV yang Sudah Diisi:</label>
                            <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                            <small class="text-muted">Format file yang didukung: <code>.xlsx</code>, <code>.xls</code>, <code>.csv</code> (Maksimal 10MB).</small>
                        </div>

                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="bi bi-check-circle me-1"></i> Proses Import Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection