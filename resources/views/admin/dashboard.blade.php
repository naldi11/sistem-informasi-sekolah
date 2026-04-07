@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                <div class="stat-label"><i class="bi bi-people me-1"></i> Total Siswa Aktif</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #059669, #10b981);">
                <div class="stat-label"><i class="bi bi-cash me-1"></i> Pembayaran Hari Ini</div>
                <div class="stat-value">{{ $pembayaranHariIni }}</div>
                <div style="font-size:0.75rem; opacity:0.8;">Rp {{ number_format($nominalHariIni, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.pembayaran.index', ['tab' => 'menunggu']) }}" class="text-decoration-none">
                <div class="stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                    <div class="stat-label"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</div>
                    <div class="stat-value">{{ $menungguVerifikasi }}</div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
                <div class="stat-label"><i class="bi bi-exclamation-triangle me-1"></i> Belum Bayar Bulan Ini</div>
                <div class="stat-value">{{ $belumBayar }}</div>
                <div style="font-size:0.75rem; opacity:0.8;">dari {{ $totalTagihan }} tagihan</div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">
        {{-- Tren Pembayaran 6 Bulan --}}
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-graph-up me-2"></i>Tren Pembayaran 6 Bulan Terakhir</span>
                    <small class="text-muted">Jumlah tagihan lunas vs belum bayar</small>
                </div>
                <div class="card-body">
                    <canvas id="chartTren" height="220"></canvas>
                </div>
            </div>
        </div>
        {{-- Distribusi Status Bulan Ini --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header py-3">
                    <i class="bi bi-pie-chart me-2"></i>Status Bulan Ini
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartDistribusi" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Pemasukan Per Kelas + Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header py-3">
                    <i class="bi bi-bar-chart me-2"></i>Pemasukan Per Kelas (Bulan Ini)
                </div>
                <div class="card-body">
                    <canvas id="chartPerKelas" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header py-3">
                    <i class="bi bi-cash-coin me-2"></i>Ringkasan Pemasukan
                </div>
                <div class="card-body">
                    <canvas id="chartPemasukan" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Table + Activity --}}
    <div class="row g-3">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header py-3">
                    <i class="bi bi-building me-2"></i>Siswa Per Kelas
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kelas</th>
                                <th>Tingkat</th>
                                <th>Tahun Ajaran</th>
                                <th class="text-center">Jumlah Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswaPerKelas as $k)
                                <tr>
                                    <td class="fw-medium">{{ $k->nama_kelas }}</td>
                                    <td><span class="badge bg-primary">{{ $k->tingkat }}</span></td>
                                    <td>{{ $k->tahun_ajaran }}</td>
                                    <td class="text-center"><span class="badge bg-secondary">{{ $k->siswa_count }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header py-3">
                    <i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivity as $log)
                            <div class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-light text-dark" style="font-size:0.7rem;">{{ $log->aksi }}</span>
                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                                <small class="text-muted d-block mt-1">{{ Str::limit($log->detail, 60) }}</small>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">Belum ada aktivitas</div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white border-top text-center py-2">
                    <a href="{{ route('admin.log.index') }}" class="text-decoration-none text-primary" style="font-size: 0.85rem; font-weight: 500;">
                        Lihat Semua Detail Aktivitas <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const colors = {
            primary: '#4f46e5', primaryLight: 'rgba(79,70,229,0.15)',
            success: '#10b981', successLight: 'rgba(16,185,129,0.15)',
            danger: '#ef4444', dangerLight: 'rgba(239,68,68,0.15)',
            warning: '#f59e0b', warningLight: 'rgba(245,158,11,0.15)',
            info: '#0ea5e9', infoLight: 'rgba(14,165,233,0.15)',
            gray: '#64748b',
        };

        Chart.defaults.font.family = 'Inter';
        Chart.defaults.font.size = 12;

        // 1. Tren Pembayaran Bar Chart
        new Chart(document.getElementById('chartTren'), {
            type: 'bar',
            data: {
                labels: @json($tren['labels']),
                datasets: [
                    {
                        label: 'Lunas',
                        data: @json($tren['lunas']),
                        backgroundColor: colors.success,
                        borderRadius: 4,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Belum Bayar',
                        data: @json($tren['belum']),
                        backgroundColor: colors.danger,
                        borderRadius: 4,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
            }
        });

        // 2. Distribusi Status Doughnut Chart
        new Chart(document.getElementById('chartDistribusi'), {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Menunggu Verifikasi', 'Belum Bayar', 'Ditolak'],
                datasets: [{
                    data: [@json($distribusi['lunas']), @json($distribusi['menunggu']), @json($distribusi['belum']), @json($distribusi['ditolak'])],
                    backgroundColor: [colors.success, colors.warning, colors.danger, colors.gray],
                    borderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }
                }
            }
        });

        // 3. Pemasukan Per Kelas Horizontal Bar
        new Chart(document.getElementById('chartPerKelas'), {
            type: 'bar',
            data: {
                labels: @json($pemasukanPerKelas['labels']),
                datasets: [
                    {
                        label: 'Total Tagihan',
                        data: @json($pemasukanPerKelas['totalNominal']),
                        backgroundColor: colors.primaryLight,
                        borderColor: colors.primary,
                        borderWidth: 1.5,
                        borderRadius: 4,
                    },
                    {
                        label: 'Sudah Lunas',
                        data: @json($pemasukanPerKelas['lunasNominal']),
                        backgroundColor: colors.successLight,
                        borderColor: colors.success,
                        borderWidth: 1.5,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } } },
                scales: {
                    x: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v / 1000) + 'K' } },
                    y: { grid: { display: false } }
                }
            }
        });

        // 4. Pemasukan Line Chart
        new Chart(document.getElementById('chartPemasukan'), {
            type: 'line',
            data: {
                labels: @json($tren['labels']),
                datasets: [{
                    label: 'Pemasukan (Rp)',
                    data: @json($tren['pemasukan']),
                    borderColor: colors.primary,
                    backgroundColor: colors.primaryLight,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'Jt' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
@endpush