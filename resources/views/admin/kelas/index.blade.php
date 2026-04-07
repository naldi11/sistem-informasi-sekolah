@extends('layouts.admin')
@section('title', 'Data Kelas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Data Kelas</h5>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah
            Kelas</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Tahun Ajaran</th>
                        <th class="text-center">Jumlah Siswa</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $i => $k)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-medium">{{ $k->nama_kelas }}</td>
                            <td><span class="badge bg-primary">{{ $k->tingkat }}</span></td>
                            <td>{{ $k->tahun_ajaran }}</td>
                            <td class="text-center"><span class="badge bg-secondary">{{ $k->siswa_count }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-sm btn-warning"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data kelas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection