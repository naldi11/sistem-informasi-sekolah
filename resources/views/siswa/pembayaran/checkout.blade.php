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

                    <h6 class="text-muted fw-bold mb-3"><i class="bi bi-wallet2 me-1"></i>Pilih Metode Pembayaran</h6>
                    <div class="row g-3 mb-4">
                        @forelse($metodeList as $m)
                        <div class="col-md-6">
                            <label class="card bg-light border p-3 rounded h-100 position-relative me-metode-card" style="cursor: pointer;">
                                <div class="form-check">
                                    <input class="form-check-input metode-radio" type="radio" name="metode_pembayaran" value="{{ $m->kode }}" 
                                        data-nama="{{ $m->nama }}"
                                        data-kategori="{{ $m->kategori }}"
                                        data-rekening="{{ $m->nomor_rekening ?? '-' }}"
                                        data-pemilik="{{ $m->pemilik_rekening ?? '-' }}"
                                        data-instruksi="{{ $m->instruksi ?? '' }}"
                                        data-butuh-bukti="{{ $m->butuh_bukti ? '1' : '0' }}" required>
                                    <label class="form-check-label ms-2 fw-bold d-block">
                                        @if($m->kategori === 'qris')
                                            <i class="bi bi-qr-code-scan fs-4 text-dark me-2"></i> {{ $m->nama }}
                                        @elseif($m->kategori === 'va')
                                            <i class="bi bi-bank fs-4 text-primary me-2"></i> {{ $m->nama }}
                                        @else
                                            <i class="bi bi-cash-coin fs-4 text-warning me-2"></i> {{ $m->nama }}
                                        @endif
                                        @if($m->butuh_bukti || $m->kategori === 'qris')
                                            <span class="badge bg-primary rounded-pill ms-1 text-white" style="font-size:0.65rem;">Perlu Bukti Transfer (JPG)</span>
                                        @else
                                            <span class="badge bg-info rounded-pill ms-1 text-white" style="font-size:0.65rem;">Virtual Account</span>
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

                    <!-- Information Container payment target -->
                    <div class="card border-info bg-info bg-opacity-10 mb-4" id="targetPembayaranContainer" style="display: none;">
                        <div class="card-body">
                            <h6 class="fw-bold text-info-emphasis mb-2 d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-2 fs-5"></i> Informasi Tujuan Pembayaran
                            </h6>
                            <div class="p-3 bg-white rounded border mb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <small class="text-muted d-block mb-1">Metode Terpilih:</small>
                                        <div class="fw-bold fs-6 text-dark" id="targetMetodeNama">-</div>
                                        <div class="mt-2" id="targetDetailRekening">
                                            <small class="text-muted d-block">Nomor Rekening / VA / Kode:</small>
                                            <span class="fs-5 fw-bold text-primary font-monospace" id="targetNoRek">-</span>
                                            <small class="text-muted d-block" id="targetPemilikWrap">a.n <span id="targetPemilik" class="fw-semibold">-</span></small>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-md-end mt-3 mt-md-0 border-start ps-md-3">
                                        <small class="text-muted d-block">Total Tagihan:</small>
                                        <span class="fs-4 fw-bold text-success">Rp {{ number_format($totalNominal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted small" id="targetInstruksiWrap" style="display: none;">
                                <i class="bi bi-lightbulb text-warning me-1"></i> <strong>Keterangan / Panduan:</strong>
                                <span id="targetInstruksiText"></span>
                            </div>
                            <div class="alert alert-warning text-dark py-2 px-3 mb-0 mt-2 small" id="infoNextStep">
                                <i class="bi bi-arrow-right-circle me-1"></i> Klik <strong>Lanjut Pembayaran</strong> untuk menuju halaman berikutnya.
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic File Upload Container -->
                    <div class="card border-primary bg-primary bg-opacity-10 mb-3" id="buktiUploadContainer" style="display: none;">
                        <div class="card-body">
                            <label class="form-label fw-bold text-primary mb-1">
                                <i class="bi bi-upload me-1"></i> Upload Bukti Transfer (.jpg) <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="file_bukti" id="inputFileBukti" class="form-control" accept="image/jpeg,image/jpg,.jpg,.jpeg">
                            <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>Format: File JPG / JPEG sahaja (Maksimal 5MB). Bukti wajib diunggah dan diverifikasi admin.</small>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center px-4" id="btnSubmitCheckout">
                        <i class="bi bi-arrow-right-circle-fill me-2"></i> Lanjut Pembayaran
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
        
        const targetContainer = document.getElementById('targetPembayaranContainer');
        const targetNama = document.getElementById('targetMetodeNama');
        const targetNoRek = document.getElementById('targetNoRek');
        const targetPemilik = document.getElementById('targetPemilik');
        const targetPemilikWrap = document.getElementById('targetPemilikWrap');
        const targetInstruksiWrap = document.getElementById('targetInstruksiWrap');
        const targetInstruksiText = document.getElementById('targetInstruksiText');
        const infoNextStep = document.getElementById('infoNextStep');
        const btnSubmit = document.getElementById('btnSubmitCheckout');

        function updatePaymentSelection() {
            let selectedRadio = null;
            radios.forEach(r => {
                if (r.checked) selectedRadio = r;
            });

            if (selectedRadio) {
                targetContainer.style.display = 'block';
                targetNama.innerText = selectedRadio.dataset.nama;
                
                const kat = selectedRadio.dataset.kategori;
                const rek = selectedRadio.dataset.rekening;
                const pem = selectedRadio.dataset.pemilik;
                const ins = selectedRadio.dataset.instruksi;
                const needsProof = selectedRadio.dataset.butuhBukti === '1' || kat === 'qris';

                if (kat === 'qris') {
                    targetNoRek.innerText = rek && rek !== '-' ? rek : 'QRIS Code Dinamis';
                    targetPemilikWrap.style.display = 'none';
                    infoNextStep.innerHTML = '<i class="bi bi-qr-code me-1"></i> Scan QRIS dan upload foto bukti transfer (.jpg) untuk diverifikasi oleh Admin.';
                } else {
                    targetNoRek.innerText = rek && rek !== '-' ? rek : '-';
                    const hasPemilik = pem && pem.trim() !== '' && pem.trim() !== '-';
                    if (hasPemilik) {
                        targetPemilik.innerText = pem;
                        targetPemilikWrap.style.display = 'block';
                    } else {
                        targetPemilik.innerText = '';
                        targetPemilikWrap.style.display = 'none';
                    }
                    if (kat === 'va') {
                        infoNextStep.innerHTML = '<i class="bi bi-bank me-1"></i> Silakan transfer ke Nomor Virtual Account di atas.';
                    } else {
                        infoNextStep.innerHTML = '<i class="bi bi-upload me-1"></i> Setelah transfer, silakan upload bukti transfer (.jpg) di bawah ini.';
                    }
                }

                if (ins && ins.trim() !== '') {
                    targetInstruksiText.innerText = ins;
                    targetInstruksiWrap.style.display = 'block';
                } else {
                    targetInstruksiWrap.style.display = 'none';
                }

                if (needsProof) {
                    uploadBox.style.display = 'block';
                    fileInput.required = true;
                    btnSubmit.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Konfirmasi & Lanjut';
                } else {
                    uploadBox.style.display = 'none';
                    fileInput.required = false;
                    btnSubmit.innerHTML = '<i class="bi bi-arrow-right-circle-fill me-2"></i> Lanjut Pembayaran';
                }
            } else {
                targetContainer.style.display = 'none';
                uploadBox.style.display = 'none';
                fileInput.required = false;
            }
        }

        radios.forEach(r => {
            r.addEventListener('change', updatePaymentSelection);
        });

        updatePaymentSelection();
    });
</script>
@endpush
@endsection
