@extends('layouts.admin')
@section('title', 'Detail Pembayaran')

@section('content')
    @php
        $pembayaran = $tagihan->pembayaran;
        $trxId = $pembayaran ? $pembayaran->transaksi_sandbox_id : null;
        
        if ($trxId) {
            $tagihanGroup = \App\Models\Tagihan::whereHas('pembayaran', fn($q) => $q->where('transaksi_sandbox_id', $trxId))
                ->with(['siswa.kelas', 'pembayaran'])
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get();
        } else {
            $tagihanGroup = collect([$tagihan]);
        }

        $totalNominalGroup = $tagihanGroup->sum('nominal');
        $isMultiMonth = $tagihanGroup->count() > 1;

        // Ambil file bukti (bisa dari tagihan ini atau dari salah satu di group)
        $fileBukti = null;
        foreach ($tagihanGroup as $tg) {
            if ($tg->pembayaran && !empty($tg->pembayaran->file_bukti)) {
                $fileBukti = $tg->pembayaran->file_bukti;
                break;
            }
        }
    @endphp

    @if($isMultiMonth)
        <div class="alert alert-primary d-flex align-items-center shadow-sm mb-3">
            <i class="bi bi-layers-fill fs-3 me-3 text-primary"></i>
            <div>
                <strong>Transaksi Multi-Bulan ({{ $tagihanGroup->count() }} Bulan Tagihan)</strong><br>
                Siswa membayar tagihan sekaligus untuk kurun waktu <strong>{{ $tagihanGroup->first()->nama_bulan }} {{ $tagihanGroup->first()->tahun }} - {{ $tagihanGroup->last()->nama_bulan }} {{ $tagihanGroup->last()->tahun }}</strong>. Verifikasi akan memproses seluruh {{ $tagihanGroup->count() }} bulan ini secara sekaligus.
            </div>
        </div>
    @endif

    <div class="row g-3">
        <!-- Bukti Transfer Side -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header py-3 bg-white fw-bold"><i class="bi bi-image me-1"></i> Bukti Transfer / Resi</div>
                <div class="card-body text-center p-3">
                    @if($fileBukti)
                        @php
                            $imgUrl = str_starts_with($fileBukti, 'http') ? $fileBukti : asset('storage/' . $fileBukti);
                            $isPdf = Str::endsWith(strtolower($fileBukti), '.pdf');
                        @endphp
                        <div class="mb-2">
                            @if($isPdf)
                                <div class="alert alert-light border py-5 shadow-sm text-center">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger d-block mb-3" style="font-size: 4rem;"></i>
                                    <a href="{{ $imgUrl }}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka / Unduh Dokumen PDF
                                    </a>
                                </div>
                            @else
                                <a href="{{ $imgUrl }}" target="_blank" title="Klik untuk memperbesar">
                                    <img src="{{ $imgUrl }}" class="img-fluid rounded border shadow-sm" style="max-height: 420px; object-fit: contain;" alt="Bukti Transfer">
                                </a>
                                <div class="mt-2 text-muted small"><i class="bi bi-zoom-in me-1"></i> Klik gambar untuk melihat ukuran penuh</div>
                            @endif
                        </div>
                    @else
                        <div class="py-5 text-muted">
                            <i class="bi bi-card-image fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada foto bukti transfer yang diunggah.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Tagihan Side -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header py-3 bg-white fw-bold"><i class="bi bi-info-circle me-1"></i> Detail Pembayaran</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width:35%;">Nama Siswa</td>
                            <td class="fw-bold text-dark">{{ $tagihan->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIS</td>
                            <td><code>{{ $tagihan->siswa->nis }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas</td>
                            <td><span class="badge bg-primary">{{ $tagihan->siswa->kelas->nama_kelas }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Metode Pembayaran</td>
                            <td>
                                @if($pembayaran && $pembayaran->transaksiSandbox)
                                    <span class="badge bg-dark">{{ $pembayaran->transaksiSandbox->metode_pembayaran }}</span>
                                @else
                                    <span class="badge bg-secondary">Transfer Manual</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tgl Upload</td>
                            <td>{{ $tagihan->pembayaran?->tanggal_upload?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tgl Verifikasi</td>
                            <td>{{ $tagihan->pembayaran?->tanggal_verifikasi?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                        @if($tagihan->pembayaran?->catatan)
                            <tr>
                                <td class="text-muted">Catatan Admin</td>
                                <td class="text-danger fw-bold">{{ $tagihan->pembayaran->catatan }}</td>
                            </tr>
                        @endif
                    </table>

                    <hr class="my-3">

                    <h6 class="fw-bold mb-2 text-dark">Rincian Bulan Tagihan:</h6>
                    <div class="table-responsive mb-2">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan Tagihan</th>
                                    <th>Nominal</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihanGroup as $tg)
                                    <tr>
                                        <td>{{ $tg->nama_bulan }} {{ $tg->tahun }}</td>
                                        <td class="fw-bold">Rp {{ number_format($tg->nominal, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $tg->status_badge }}">{{ $tg->status_label }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td>Total Keseluruhan ({{ $tagihanGroup->count() }} Bulan)</td>
                                    <td colspan="2" class="text-primary fs-5">Rp {{ number_format($totalNominalGroup, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($tagihan->status === 'menunggu_verifikasi')
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3 bg-white fw-bold"><i class="bi bi-check2-square me-1"></i> Aksi Verifikasi Admin</div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            Verifikasi akan menandai <strong>{{ $isMultiMonth ? "seluruh {$tagihanGroup->count()} bulan tagihan" : "tagihan ini" }}</strong> sebagai <strong>LUNAS</strong> secara otomatis.
                        </p>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $tagihan) }}"
                                onsubmit="return confirm('Verifikasi pembayaran ini sebagai Lunas?')">
                                @csrf
                                <button class="btn btn-success px-4 fw-bold">
                                    <i class="bi bi-check-lg me-1"></i> {{ $isMultiMonth ? "Verifikasi Sekaligus ({$tagihanGroup->count()} Bulan)" : "Verifikasi (Lunas)" }}
                                </button>
                            </form>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">
                                <i class="bi bi-x-lg me-1"></i> Tolak Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tolak Modal -->
    <div class="modal fade" id="tolakModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.pembayaran.tolak', $tagihan) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tolak Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">
                            Penolakan akan memproses {{ $isMultiMonth ? "seluruh {$tagihanGroup->count()} bulan tagihan dalam transaksi ini" : "tagihan ini" }}.
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Alasan Penolakan (opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3"
                                placeholder="Contoh: Bukti transfer tidak jelas atau nominal tidak sesuai"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline-secondary mt-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pembayaran
    </a>
@endsection