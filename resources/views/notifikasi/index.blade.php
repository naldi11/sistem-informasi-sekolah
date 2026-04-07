@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.siswa')
@section('title', 'Notifikasi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Notifikasi</h5>
        <form method="POST" action="{{ route('notifikasi.readAll') }}">
            @csrf
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifikasi as $n)
                    <div class="list-group-item {{ !$n->is_read ? 'bg-primary bg-opacity-10' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                @if(!$n->is_read)
                                    <span class="badge bg-primary me-1" style="font-size:0.6rem;">BARU</span>
                                @endif
                                <span style="font-size:0.9rem;">{{ $n->pesan }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-muted text-nowrap">{{ $n->created_at->diffForHumans() }}</small>
                                @if(!$n->is_read)
                                    <form method="POST" action="{{ route('notifikasi.read', $n) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-secondary" style="font-size:0.7rem;"><i
                                                class="bi bi-check"></i></button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center py-5 text-muted">
                        <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>Belum ada notifikasi
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $notifikasi->links() }}</div>
@endsection