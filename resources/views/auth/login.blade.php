<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPP Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            border-radius: 1rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .login-logo {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.65rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
        }

        .input-group-text {
            background: #f8fafc;
            border-right: none;
        }

        .form-control {
            border-left: none;
        }

        .btn-toggle-password {
            background: #f8fafc;
            border: 1px solid #dee2e6;
            border-left: none;
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s;
        }

        .btn-toggle-password:hover {
            color: #4f46e5;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <h4 class="text-center fw-bold mb-1">SPP Sekolah</h4>
        <p class="text-center text-muted mb-4" style="font-size:0.85rem;">Masuk ke akun Anda</p>

        @if($errors->any())
            <div class="alert alert-danger py-2" style="font-size:0.85rem;">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-medium" style="font-size:0.85rem;">Username / NIS</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                        value="{{ old('username') }}" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium" style="font-size:0.85rem;">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Masukkan password" required>
                    <button type="button" class="btn-toggle-password" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
            </button>
        </form>

        <p class="text-center text-muted mt-4 mb-0" style="font-size:0.75rem;">
            &copy; {{ date('Y') }} SPP Sekolah. All rights reserved.
        </p>
    </div>
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
</body>

</html>