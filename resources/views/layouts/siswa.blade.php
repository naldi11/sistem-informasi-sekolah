<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPP Sekolah') - Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f8fafc;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0c4a6e 0%, #075985 100%);
            color: #bae6fd;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0);
        }

        .sidebar.show {
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.3);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.15rem;
        }

        .sidebar-brand small {
            color: #7dd3fc;
            font-size: 0.75rem;
        }

        .sidebar-profile {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
        }

        .sidebar-profile .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            margin: 0 auto 0.5rem;
        }

        .sidebar-profile .nama {
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .sidebar-profile .info {
            color: #7dd3fc;
            font-size: 0.75rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1.25rem;
            color: #bae6fd;
            text-decoration: none;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            margin: 0.15rem 0.75rem;
            transition: all 0.2s;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .sidebar-nav a.active {
            background: #0ea5e9;
            color: #fff;
            font-weight: 500;
        }

        .sidebar-nav a i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .content-area {
            padding: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .sidebar-close {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        @media (max-width: 768px) {
            .sidebar-close {
                display: flex;
            }
        }

        /* Animated Hamburger */
        .hamburger-btn {
            width: 38px;
            height: 38px;
            border-radius: 0.5rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            padding: 0;
            transition: background 0.2s;
        }

        .hamburger-btn:hover {
            background: #e2e8f0;
        }

        .hamburger-btn span {
            display: block;
            width: 20px;
            height: 2px;
            background: #475569;
            border-radius: 2px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        @media (max-width: 768px) {
            .hamburger-btn {
                display: flex;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @php $siswa = auth()->user()->siswa; @endphp
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <nav class="sidebar" id="sidebar">
        <button class="sidebar-close" onclick="closeSidebar()"><i class="bi bi-x-lg"></i></button>
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill"></i> SPP Sekolah</h4>
            <small>Portal Siswa</small>
        </div>
        <div class="sidebar-profile">
            <div class="avatar"><i class="bi bi-person-fill"></i></div>
            <div class="nama">{{ $siswa->nama ?? '-' }}</div>
            <div class="info">{{ $siswa->kelas->nama_kelas ?? '-' }} | NIS: {{ $siswa->nis ?? '-' }}</div>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('siswa.dashboard') }}"
                class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('notifikasi.index') }}" class="{{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifikasi
                @php $unread = auth()->user()->unreadNotifikasi()->count(); @endphp
                @if($unread > 0)
                    <span class="badge bg-danger ms-auto">{{ $unread }}</span>
                @endif
            </a>
        </div>
    </nav>

    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()">
                    <span></span><span></span><span></span>
                </button>
                <span style="font-weight:600; font-size:1.1rem; color:#1e293b;">@yield('title', 'Dashboard')</span>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ $siswa->nama ?? '-' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('ganti-password') }}"><i
                                class="bi bi-key me-2"></i>Ganti Password</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger"><i
                                    class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show"><i
                        class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show"><i
                        class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button"
                        class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
            document.getElementById('hamburgerBtn').classList.toggle('active');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
            document.getElementById('hamburgerBtn').classList.remove('active');
        }
    </script>
    @stack('scripts')
</body>

</html>