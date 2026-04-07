@extends('layouts.admin')
@section('title', 'Log Aktivitas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Log Aktivitas Sistem</h5>
        <a href="{{ route('admin.log.exportPdf', request()->query()) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="aksi" class="form-control form-control-sm" placeholder="Filter aksi..."
                        value="{{ request('aksi') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Detail</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                            <td class="fw-medium">{{ $log->user->username ?? 'System' }}</td>
                            <td><span class="badge bg-light text-dark">{{ $log->aksi }}</span></td>
                            <td style="max-width:300px;"><small>{{ Str::limit($log->detail, 80) }}</small></td>
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada log</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $logs->links() }}</div>
@endsection