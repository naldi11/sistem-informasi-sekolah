@extends('layouts.siswa')
@section('title', 'Checkout Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cart-check me-2 text-primary"></i>Checkout Tagihan</h5>
            </div>
            
            <form action="{{ route('siswa.bayar.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h6 class="text-muted fw-bold mb-3">Tagihan Yang Akan Dibayar</h6>
                    
                    <ul class="list-group mb-4">
                        @foreach($tagihanList as $t)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <input type="hidden" name="tagihan_ids[]" value="{{ $t->id }}">
                                <strong>{{ $t->nama_bulan }} {{ $t->tahun }}</strong>
                            </div>
                            <span class="fs-5">Rp {{ number_format($t->nominal, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <strong class="fs-5">Total Bayar</strong>
                            <strong class="fs-4 text-primary">Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong>
                        </li>
                    </ul>

                    <h6 class="text-muted fw-bold mb-3">Pilih Metode Pembayaran</h6>
                    <div class="row g-3 mb-4">
                        @forelse($metodeList as $m)
                        <div class="col-md-6">
                            <label class="card bg-light border p-3 rounded h-100 position-relative" style="cursor: pointer;">
                                <div class="form-check">
                                    <input class="form-check-input metode-radio" type="radio" name="metode_pembayaran" value="{{ $m->kode }}" data-butuh-bukti="{{ $m->butuh_bukti ? '1' : '0' }}" required>
                                    <label class="form-check-label ms-2 fw-bold d-block">
                                        @if($m->kategori === 'qris')
                                            <i class="bi bi-qr-code-scan fs-4 text-dark me-2"></i> {{ $m->nama }}
                                        @elseif($m->kategori === 'va')
                                            <i class="bi bi-bank fs-4 text-primary me-2"></i> {{ $m->nama }}
                                        @else
                                            <i class="bi bi-cash-coin fs-4 text-warning me-2"></i> {{ $m->nama }}
                                        @endif
                                        @if($m->butuh_bukti)
                                            <span class="badge bg-primary rounded-pill ms-1 text-white" style="font-size:0.65rem;">Perlu Bukti Transfer</span>
                                        @endif
                                    </label>
                                </div>
                            </label>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-warning mb-0">Belum ada metode pembayaran yang aktif.</div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Dynamic File Upload Container -->
                    <div class="card border-primary bg-primary bg-opacity-10 mb-3" id="buktiUploadContainer" style="display: none;">
                        <div class="card-body">
                            <label class="form-label fw-bold text-primary mb-1">
                                <i class="bi bi-upload me-1"></i> Upload Bukti Transfer <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="file_bukti" id="inputFileBukti" class="form-control" accept="image/jpeg,image/png,image/jpg,application/pdf">
                            <small class="text-muted d-block mt-1">Format: JPG, PNG, atau PDF (Maksimal 5MB).</small>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center px-4">
                        <i class="bi bi-lock-fill me-2"></i> Bayar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.metode-radio');
        const uploadBox = document.getElementById('buktiUploadContainer');
        const fileInput = document.getElementById('inputFileBukti');

        function toggleUploadBox() {
            let needsProof = false;
            radios.forEach(r => {
                if (r.checked && r.dataset.butuhBukti === '1') {
                    needsProof = true;
                }
            });

            if (needsProof) {
                uploadBox.style.display = 'block';
                fileInput.required = true;
            } else {
                uploadBox.style.display = 'none';
                fileInput.required = false;
            }
        }

        radios.forEach(r => {
            r.addEventListener('change', toggleUploadBox);
        });

        toggleUploadBox();
    });
</script>
@endpush
@endsection
