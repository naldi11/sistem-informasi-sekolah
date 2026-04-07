@extends('layouts.admin')
@section('title', 'Data Siswa')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Data Siswa</h5>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah
            Siswa</a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari nama atau NIS..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="kelas_id" class="form-select form-select-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>JK</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $i => $s)
                        <tr>
                            <td>{{ $siswa->firstItem() + $i }}</td>
                            <td class="fw-medium">{{ $s->nis }}</td>
                            <td>{{ $s->nama }}</td>
                            <td><span class="badge bg-primary">{{ $s->kelas->nama_kelas }}</span></td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td>
                                @if($s->user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.siswa.show', $s) }}" class="btn btn-sm btn-info"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.siswa.edit', $s) }}" class="btn btn-sm btn-warning"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Nonaktifkan siswa ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data siswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $siswa->links() }}</div>
@endsection