@extends('layouts.admin')
@section('title', 'Nominal SPP')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Nominal SPP Per Kelas</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="bi bi-plus-lg me-1"></i>Tambah Nominal</button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Nominal</th>
                        <th>Tahun Ajaran</th>
                        <th>Berlaku Mulai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spp as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-medium">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                            <td>{{ $s->tahun_ajaran }}</td>
                            <td>{{ $s->berlaku_mulai->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.spp.edit', $s) }}" class="btn btn-sm btn-warning"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.spp.destroy', $s) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada nominal SPP</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.spp.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Nominal SPP</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Kelas</label>
                            <select name="kelas_id" class="form-select" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control" required min="0" placeholder="250000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" value="2025/2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Berlaku Mulai</label>
                            <input type="date" name="berlaku_mulai" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection