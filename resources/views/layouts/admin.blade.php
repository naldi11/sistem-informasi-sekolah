<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPP Sekolah') - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Select2 & Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --sidebar-bg: #1e1b4b;
            --sidebar-text: #c7d2fe;
            --sidebar-active: #6366f1;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f1f5f9;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            color: var(--sidebar-text);
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
            color: #a5b4fc;
            font-size: 0.75rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-label {
            padding: 0.5rem 1.25rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #818cf8;
            font-weight: 600;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            color: var(--sidebar-text);
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
            background: var(--sidebar-active);
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

        .topbar .page-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #1e293b;
        }

        .content-area {
            padding: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
        }

        .stat-card {
            border-radius: 0.75rem;
            padding: 1.25rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -15px;
            bottom: -15px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            opacity: 0.85;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .table th {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
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
    <!-- Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <nav class="sidebar" id="sidebar">
        <button class="sidebar-close" onclick="closeSidebar()"><i class="bi bi-x-lg"></i></button>
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill"></i> SPP Sekolah</h4>
            <small>Sistem Pembayaran SPP</small>
        </div>
        <div class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-label mt-2">Master Data</div>
            <a href="{{ route('admin.kelas.index') }}"
                class="{{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Data Kelas
            </a>
            <a href="{{ route('admin.spp.index') }}" class="{{ request()->routeIs('admin.spp.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Nominal SPP
            </a>
            <a href="{{ route('admin.siswa.index') }}"
                class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Data Siswa
            </a>

            <div class="nav-label mt-2">Transaksi</div>
            <a href="{{ route('admin.tagihan.index') }}"
                class="{{ request()->routeIs('admin.tagihan.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Tagihan
            </a>
            <a href="{{ route('admin.pembayaran.index') }}"
                class="{{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i> Pembayaran
                @php $pendingCount = \App\Models\Tagihan::where('status', 'menunggu_verifikasi')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $pendingCount }}</span>
                @endif
            </a>

            <div class="nav-label mt-2">Laporan</div>
            <a href="{{ route('admin.laporan.perSiswa') }}"
                class="{{ request()->routeIs('admin.laporan.perSiswa') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> Per Siswa
            </a>
            <a href="{{ route('admin.laporan.perBulan') }}"
                class="{{ request()->routeIs('admin.laporan.perBulan') ? 'active' : '' }}">
                <i class="bi bi-calendar-month"></i> Per Bulan
            </a>
            <a href="{{ route('admin.laporan.tunggakan') }}"
                class="{{ request()->routeIs('admin.laporan.tunggakan') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i> Tunggakan
            </a>
            <a href="{{ route('admin.laporan.rekap') }}"
                class="{{ request()->routeIs('admin.laporan.rekap') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Rekap
            </a>

            <div class="nav-label mt-2">Sistem</div>
            <a href="{{ route('admin.log.index') }}" class="{{ request()->routeIs('admin.log.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Log Aktivitas
            </a>
            <a href="{{ route('admin.kebijakanPrivasi') }}"
                class="{{ request()->routeIs('admin.kebijakanPrivasi') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i> Kebijakan Privasi
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()">
                    <span></span><span></span><span></span>
                </button>
                <span class="page-title">@yield('title', 'Dashboard')</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('notifikasi.index') }}" class="btn btn-sm btn-light position-relative">
                    <i class="bi bi-bell"></i>
                    @php $unread = auth()->user()->unreadNotifikasi()->count(); @endphp
                    @if($unread > 0)
                        <span class="notif-badge">{{ $unread }}</span>
                    @endif
                </a>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->username }}
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
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        
        // Auto-Initialize Select2 on all form-select
        $(document).ready(function() {
            $('select.form-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih salah satu...'
            });
            // If they are inside smaller contexts like form-select-sm
            $('select.form-select-sm').each(function() {
                $(this).next('.select2-container').addClass('select2-container--sm');
            });
        });
    </script>
    @stack('scripts')
</body>

</html>