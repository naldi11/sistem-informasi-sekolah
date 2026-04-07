@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.siswa')
@section('title', 'Ganti Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle"
                            style="width:60px;height:60px;">
                            <i class="bi bi-key-fill text-warning fs-4"></i>
                        </div>
                        @if(auth()->user()->is_first_login)
                            <h5 class="fw-bold mt-3">Buat Password Baru</h5>
                            <p class="text-muted" style="font-size:0.85rem;">Ini login pertama Anda. Silakan buat password baru
                                untuk keamanan akun.</p>
                        @else
                            <h5 class="fw-bold mt-3">Ganti Password</h5>
                            <p class="text-muted" style="font-size:0.85rem;">Masukkan password lama dan password baru Anda.</p>
                        @endif
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger py-2" style="font-size:0.85rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ganti-password') }}">
                        @csrf
                        @if(!auth()->user()->is_first_login)
                            <div class="mb-3">
                                <label class="form-label fw-medium">Password Lama</label>
                                <div class="input-group">
                                    <input type="password" name="password_lama" id="password_lama" class="form-control"
                                        required>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePassword('password_lama', this)" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-medium">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_baru" id="password_baru" class="form-control" required
                                    minlength="6">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePassword('password_baru', this)" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_baru_confirmation" id="password_baru_conf"
                                    class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePassword('password_baru_conf', this)" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg me-1"></i> Simpan Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
@endpush