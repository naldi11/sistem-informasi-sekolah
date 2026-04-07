@extends('layouts.siswa')
@section('title', 'Dashboard')

@push('styles')
<style>
    .responsive-heading {
        font-size: clamp(0.85rem, 3.5vw, 1.15rem);
    }
    .responsive-text {
        font-size: clamp(0.75rem, 3vw, 0.95rem);
    }
    .table-responsive-text td, .table-responsive-text th {
        font-size: clamp(0.75rem, 2.5vw, 0.95rem);
        vertical-align: middle;
    }
    .nav-pills.custom-tabs {
        display: flex;
        flex-wrap: nowrap;
        width: 100%;
        gap: 0.5rem;
    }
    .nav-pills.custom-tabs .nav-item {
        flex: 1;
        min-width: 0; /* needed for flexbox text truncation */
    }
    .nav-pills.custom-tabs .nav-link {
        width: 100%;
        text-align: center;
        padding: 0.5rem 0.25rem !important;
        font-size: clamp(0.7rem, 2.8vw, 0.9rem);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@section('content')
    <!-- Nav Tabs -->
    <ul class="nav nav-pills custom-tabs mb-4 mt-2" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold rounded-pill shadow-sm border" id="tagihan-tab" data-bs-toggle="pill" data-bs-target="#tagihan" type="button" role="tab" aria-controls="tagihan" aria-selected="true">
                <i class="bi bi-wallet2 d-none d-sm-inline"></i> Tagihan Belum Dibayar
                @if($tagihanAktif->count() > 0)
                <span class="badge bg-danger rounded-circle ms-1 p-1 align-middle" style="font-size:0.6rem;">{{ $tagihanAktif->count() }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold rounded-pill shadow-sm border" id="riwayat-tab" data-bs-toggle="pill" data-bs-target="#riwayat" type="button" role="tab" aria-controls="riwayat" aria-selected="false">
                <i class="bi bi-clock-history d-none d-sm-inline"></i> Riwayat Pembayaran
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="dashboardTabsContent">
        
        <!-- TAB 1: TAGIHAN -->
        <div class="tab-pane fade show active" id="tagihan" role="tabpanel" aria-labelledby="tagihan-tab">
            
            @if($tagihanBulanIni)
                <div class="card shadow-sm mb-4 border-start border-4 border-{{ $tagihanBulanIni->status_badge }}">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <h6 class="fw-bold mb-1">Tagihan {{ $tagihanBulanIni->nama_bulan }} {{ $tagihanBulanIni->tahun }}</h6>
                                <span class="badge bg-{{ $tagihanBulanIni->status_badge }}">{{ $tagihanBulanIni->status_label }}</span>
                            </div>
                            <div class="text-md-end text-start">
                                <div class="fs-4 fw-bold text-primary">Rp {{ number_format($tagihanBulanIni->nominal, 0, ',', '.') }}</div>
                                @if(in_array($tagihanBulanIni->status, ['belum_bayar', 'ditolak']))
                                    <a href="{{ route('siswa.bayar.checkout', ['tagihan_ids' => [$tagihanBulanIni->id]]) }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-upload me-1"></i>Bayar Bulan Ini
                                    </a>
                                @elseif($tagihanBulanIni->status === 'menunggu_verifikasi' && $tagihanBulanIni->pembayaran && $tagihanBulanIni->pembayaran->transaksiSandbox)
                                    <a href="{{ route('siswa.bayar.invoice', $tagihanBulanIni->pembayaran->transaksiSandbox->order_id) }}" class="btn btn-info text-white mt-2">
                                        <i class="bi bi-receipt me-1"></i>Selesaikan Pembayaran
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($tagihanAktif->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header py-3 bg-danger bg-opacity-10 d-flex justify-content-between align-items-center border-0">
                        <div>
                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                            <span class="fw-bold text-danger">Tunggakan / Tagihan Lainnya</span>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('siswa.bayar.checkout') }}" id="formMultiBayar">
                        <div class="card-body p-0 table-responsive" style="max-height: 440px; overflow-y: auto;">
                            <table class="table table-hover mb-0 align-middle table-responsive-text" id="tabelTagihan">
                                <thead class="table-light position-sticky top-0 z-1">
                                    <tr>
                                        <th style="width:30px;" class="text-center px-1 px-sm-2">
                                            <input type="checkbox" class="form-check-input" id="checkAll" title="Pilih Semua">
                                        </th>
                                        <th class="text-nowrap px-1 px-sm-2">Bulan</th>
                                        <th class="text-nowrap px-1 px-sm-2">Nominal</th>
                                        <th class="text-nowrap px-1 px-sm-2 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tagihanAktif as $i => $t)
                                        <tr>
                                            <td class="text-center px-1 px-sm-2">
                                                <input class="form-check-input tagihan-check" type="checkbox"
                                                    name="tagihan_ids[]" value="{{ $t->id }}"
                                                    data-index="{{ $i }}" data-nominal="{{ $t->nominal }}">
                                            </td>
                                            <td class="text-nowrap px-1 px-sm-2 responsive-text">{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                                            <td class="text-nowrap fw-bold px-1 px-sm-2 responsive-text">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                            <td class="px-1 px-sm-2 text-center"><span class="badge bg-{{ $t->status_badge }} responsive-text" style="font-weight: 500;">{{ $t->status_label }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Sticky Footer for Checking out -->
                        <div class="card-footer position-sticky bottom-0 bg-white border-top shadow-sm d-flex justify-content-between align-items-center responsive-text" id="bayarFooter" style="display:none !important; z-index: 10;">
                            <div>
                                <span class="fw-bold d-none d-sm-inline">Total: </span>
                                <span class="fw-bold text-primary responsive-heading" id="totalBayar">Rp 0</span>
                                <span class="text-muted ms-1 small" style="font-size: clamp(0.6rem, 2vw, 0.8rem);">(<span id="jumlahBulan">0</span> bln)</span>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill px-2 px-sm-4 responsive-text d-flex align-items-center">
                                <i class="bi bi-cart-check-fill me-1"></i> <span class="d-none d-md-inline me-1">Checkout</span>(<span id="btnBulanCount">0</span>)
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Pagination container for Tagihan -->
                <div id="pgTagihan" class="d-flex justify-content-center mt-2 mb-4"></div>

            @else
                @if(!$tagihanBulanIni)
                <div class="text-center py-5">
                    <i class="bi bi-check2-circle text-success" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">Hore! Semua tagihan Anda sudah lunas.</h5>
                </div>
                @endif
            @endif
        </div>

        <!-- TAB 2: RIWAYAT -->
        <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat-tab">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom"><i class="bi bi-clock-history me-2 text-primary responsive-heading"></i><span class="responsive-heading">Riwayat Pembayaran</span></div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0 align-middle table-responsive-text" id="tabelRiwayat">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap px-2 px-sm-3">Bulan</th>
                                <th class="text-nowrap px-2 px-sm-3">Nominal</th>
                                <th class="text-nowrap px-2 px-sm-3 text-center">Status</th>
                                <th class="text-nowrap px-2 px-sm-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $t)
                                <tr>
                                    <td class="text-nowrap px-2 px-sm-3 responsive-text">{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                                    <td class="text-nowrap fw-bold px-2 px-sm-3 responsive-text">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                    <td class="px-2 px-sm-3 text-center"><span class="badge bg-{{ $t->status_badge }} responsive-text" style="font-weight: 500;">{{ $t->status_label }}</span></td>
                                    <td style="min-width: 120px;" class="px-2 px-sm-3">
                                        @if($t->pembayaran && $t->pembayaran->tanggal_verifikasi)
                                            <small class="text-muted d-block responsive-text"><i class="bi bi-check-circle-fill text-success me-1"></i>Selesai: {{ $t->pembayaran->tanggal_verifikasi->format('d/m/Y') }}</small>
                                        @elseif($t->status === 'menunggu_verifikasi' && $t->pembayaran && $t->pembayaran->transaksiSandbox)
                                            <a href="{{ route('siswa.bayar.invoice', $t->pembayaran->transaksiSandbox->order_id) }}" class="btn btn-sm btn-info text-white py-1 px-2 rounded-pill mt-1" style="font-size: 0.70rem;">
                                                Lanjut Bayar <i class="bi bi-arrow-right"></i>
                                            </a>
                                        @elseif($t->pembayaran && $t->pembayaran->catatan)
                                            <small class="text-danger d-block responsive-text"><i class="bi bi-info-circle-fill me-1"></i>{{ $t->pembayaran->catatan }}</small>
                                        @else
                                            <span class="text-muted responsive-text">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                        Belum ada riwayat pembayaran yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="pgRiwayat" class="d-flex justify-content-center mt-2 mb-4"></div>
        </div>
        
    </div>

    @push('scripts')
    <script>
        const checks = document.querySelectorAll('.tagihan-check');
        const footer = document.getElementById('bayarFooter');
        const totalEl = document.getElementById('totalBayar');
        const jumlahEl = document.getElementById('jumlahBulan');
        const btnCount = document.getElementById('btnBulanCount');
        const checkAll = document.getElementById('checkAll');

        function enforceSequential(changedIdx, isChecked) {
            if (isChecked) {
                checks.forEach(c => {
                    if (parseInt(c.dataset.index) <= changedIdx) c.checked = true;
                });
            } else {
                checks.forEach(c => {
                    if (parseInt(c.dataset.index) >= changedIdx) c.checked = false;
                });
            }
            updateTotal();
        }

        function updateTotal() {
            if(!totalEl) return;
            let total = 0, count = 0;
            checks.forEach(c => {
                if (c.checked) { total += parseInt(c.dataset.nominal); count++; }
            });
            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            jumlahEl.textContent = count;
            btnCount.textContent = count;
            if(footer) {
                footer.style.display = count > 0 ? 'flex' : 'none';
                footer.style.setProperty('display', count > 0 ? 'flex' : 'none', 'important');
            }
            if(checkAll) checkAll.checked = (count > 0 && count === checks.length);
        }

        checks.forEach(c => {
            c.addEventListener('change', () => enforceSequential(parseInt(c.dataset.index), c.checked));
        });

        if(checkAll) {
            checkAll.addEventListener('change', () => {
                checks.forEach(c => {
                    c.checked = checkAll.checked;
                });
                updateTotal();
            });
        }

        // Client-side Pagination Logic
        function initPagination(tableId, pgId, rowsPerPage) {
            const table = document.getElementById(tableId);
            if (!table) return;
            const tbody = table.querySelector('tbody');
            if(!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Ignore empty placeholders
            if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td') && rows[0].querySelector('td').colSpan > 1)) return;
            if (rows.length <= rowsPerPage) return; // No need for pagination
            
            let currentPage = 1;
            const totalPages = Math.ceil(rows.length / rowsPerPage);
            const pgContainer = document.getElementById(pgId);
            if(!pgContainer) return;

            function renderTable() {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                rows.forEach((row, i) => {
                    row.style.setProperty('display', (i >= start && i < end) ? '' : 'none', 'important');
                });
            }

            function renderPagination() {
                let html = '<ul class="pagination pagination-sm m-0 shadow-sm">';
                html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a></li>`;
                for (let i = 1; i <= totalPages; i++) {
                    html += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a></li>`;
                html += '</ul>';
                pgContainer.innerHTML = html;

                pgContainer.querySelectorAll('.page-link').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.preventDefault();
                        let p = parseInt(e.target.dataset.page);
                        if (!isNaN(p) && p >= 1 && p <= totalPages) {
                            currentPage = p;
                            renderTable();
                            renderPagination();
                        }
                    });
                });
            }

            renderTable();
            renderPagination();
        }

        document.addEventListener('DOMContentLoaded', () => {
            initPagination('tabelTagihan', 'pgTagihan', 10);
            initPagination('tabelRiwayat', 'pgRiwayat', 10);
        });
    </script>
    @endpush
@endsection