@extends('layouts.admin')
@section('title', 'Tagihan')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-1"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Manajemen Tagihan</h5>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('admin.tagihan.autoGenerate') }}">
                @csrf
                <button class="btn btn-warning btn-sm"><i class="bi bi-magic me-1"></i>Auto-Detect Tunggakan</button>
            </form>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#generateModal"><i
                    class="bi bi-lightning me-1"></i>Generate Tagihan</button>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">Semua Bulan</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="tahun" class="form-control form-control-sm" placeholder="Tahun"
                        value="{{ request('tahun') }}">
                </div>
                <div class="col-md-2">
                    <select name="kelas_id" class="form-select form-select-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar
                        </option>
                        <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Bulan</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihan as $i => $t)
                        <tr>
                            <td>{{ $tagihan->firstItem() + $i }}</td>
                            <td class="fw-medium">{{ $t->siswa->nama ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                            <td>{{ $t->nama_bulan }} {{ $t->tahun }}</td>
                            <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $t->status_badge }}">{{ $t->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada tagihan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $tagihan->links() }}</div>

    <!-- Generate Modal -->
    <div class="modal fade" id="generateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('admin.tagihan.generate') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-lightning me-2"></i>Generate Tagihan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Target Siswa --}}
                        <div class="mb-3">
                            <label class="form-label fw-medium">Target Siswa</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="target" id="targetSemua" value="semua"
                                    checked onchange="toggleSiswaList()">
                                <label class="form-check-label" for="targetSemua">Semua Siswa Aktif</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="target" id="targetPilih" value="pilih"
                                    onchange="toggleSiswaList()">
                                <label class="form-check-label" for="targetPilih">Pilih Siswa Tertentu</label>
                            </div>
                        </div>

                        {{-- Siswa List (hidden by default) --}}
                        <div id="siswaListContainer" class="mb-3" style="display:none;">
                            <input type="text" id="searchSiswa" class="form-control form-control-sm mb-2"
                                placeholder="Cari nama siswa...">
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="selectAllSiswa()">Pilih Semua</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="deselectAllSiswa()">Hapus Semua</button>
                            </div>
                            <div
                                style="max-height:200px; overflow-y:auto; border:1px solid #dee2e6; border-radius:0.375rem; padding:0.5rem;">
                                @foreach($siswaList as $s)
                                    <div class="form-check siswa-item" data-nama="{{ strtolower($s->nama) }}">
                                        <input class="form-check-input siswa-check" type="checkbox" name="siswa_ids[]"
                                            value="{{ $s->id }}" id="siswa{{ $s->id }}">
                                        <label class="form-check-label" for="siswa{{ $s->id }}" style="font-size:0.85rem;">
                                            {{ $s->nama }} <span class="text-muted">({{ $s->kelas->nama_kelas ?? '-' }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        {{-- Periode --}}
                        <label class="form-label fw-medium">Periode Tagihan</label>
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="setRange(now_month, now_month)">1 Bulan</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setRange(7, 12)">Semester
                                1 (Jul-Des)</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setRange(1, 6)">Semester 2
                                (Jan-Jun)</button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="setRange(1, 12)">1 Tahun
                                Penuh</button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.8rem;">Dari Bulan</label>
                                <select name="dari_bulan" id="dariBulan" class="form-select form-select-sm" required>
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.8rem;">Sampai Bulan</label>
                                <select name="sampai_bulan" id="sampaiBulan" class="form-select form-select-sm" required>
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.8rem;">Tahun</label>
                                <input type="number" name="tahun" class="form-control form-control-sm"
                                    value="{{ now()->year }}" required>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Tagihan yang sudah ada tidak akan ditimpa
                            (anti-duplikasi).</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-lightning me-1"></i>Generate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const now_month = {{ now()->month }};

        function toggleSiswaList() {
            const show = document.getElementById('targetPilih').checked;
            document.getElementById('siswaListContainer').style.display = show ? 'block' : 'none';
            if (!show) deselectAllSiswa();
        }

        function selectAllSiswa() {
            document.querySelectorAll('.siswa-check').forEach(c => { if (c.closest('.siswa-item').style.display !== 'none') c.checked = true; });
        }

        function deselectAllSiswa() {
            document.querySelectorAll('.siswa-check').forEach(c => c.checked = false);
        }

        function setRange(dari, sampai) {
            document.getElementById('dariBulan').value = dari;
            document.getElementById('sampaiBulan').value = sampai;
        }

        document.getElementById('searchSiswa')?.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.siswa-item').forEach(item => {
                item.style.display = item.dataset.nama.includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush