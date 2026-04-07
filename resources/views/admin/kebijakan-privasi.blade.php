@extends('layouts.admin')
@section('title', 'Kebijakan Privasi')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header py-3 d-flex align-items-center">
                    <i class="bi bi-shield-check me-2 text-primary fs-5"></i>
                    <h5 class="fw-bold mb-0">Kebijakan Privasi & Perlindungan Data</h5>
                </div>
                <div class="card-body" style="font-size:0.9rem; line-height:1.8;">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Dokumen ini disusun berdasarkan <strong>Undang-Undang Nomor 27 Tahun 2022 tentang Perlindungan Data
                            Pribadi (UU PDP)</strong> Republik Indonesia.
                    </div>

                    <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-1-circle me-2 text-primary"></i>Data yang Dikumpulkan</h6>
                    <p>Sistem SPP Sekolah mengumpulkan dan menyimpan data pribadi siswa berikut:</p>
                    <ul>
                        <li><strong>Data Identitas:</strong> Nama lengkap, NIS (Nomor Induk Siswa), tanggal lahir, jenis
                            kelamin</li>
                        <li><strong>Data Kontak:</strong> Alamat tempat tinggal</li>
                        <li><strong>Data Akademik:</strong> Kelas, tingkat, tahun ajaran</li>
                        <li><strong>Data Transaksi:</strong> Riwayat pembayaran SPP, bukti transfer</li>
                        <li><strong>Data Akses:</strong> Log aktivitas login dan penggunaan sistem</li>
                    </ul>

                    <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-2-circle me-2 text-primary"></i>Tujuan Pengumpulan Data
                    </h6>
                    <ul>
                        <li>Administrasi pembayaran SPP dan pencatatan keuangan sekolah</li>
                        <li>Pembuatan tagihan dan pelaporan keuangan</li>
                        <li>Komunikasi terkait pembayaran melalui notifikasi dalam sistem</li>
                        <li>Audit internal dan kepatuhan regulasi</li>
                    </ul>

                    <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-3-circle me-2 text-primary"></i>Dasar Hukum Pemrosesan
                    </h6>
                    <p>Pemrosesan data pribadi dilakukan berdasarkan:</p>
                    <ul>
                        <li>Kepentingan sah institusi pendidikan (Pasal 20 ayat 2 UU PDP)</li>
                        <li>Pelaksanaan perjanjian pendidikan antara orang tua/wali siswa dengan sekolah</li>
                        <li>Kewajiban hukum penyelenggara pendidikan</li>
                    </ul>

                    <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-4-circle me-2 text-primary"></i>Kebijakan Retensi Data
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Data</th>
                                    <th>Masa Retensi</th>
                                    <th>Setelah Masa Retensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Data siswa aktif</td>
                                    <td>Selama siswa masih terdaftar</td>
                                    <td>Dipindahkan ke arsip</td>
                                </tr>
                                <tr>
                                    <td>Data siswa lulus/keluar</td>
                                    <td>2 tahun setelah kelulusan</td>
                                    <td>Dianonimisasi</td>
                                </tr>
                                <tr>
                                    <td>Bukti transfer pembayaran</td>
                                    <td>5 tahun</td>
                                    <td>Dihapus permanen</td>
                                </tr>
                                <tr>
                                    <td>Log aktivitas</td>
                                    <td>1 tahun</td>
                                    <td>Dihapus otomatis</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-5-circle me-2 text-primary"></i>Hak Subjek Data</h6>
                    <p>Sesuai UU PDP, orang tua/wali siswa memiliki hak:</p>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="border rounded p-2"><i class="bi bi-check-circle text-success me-2"></i>Hak
                                mengakses data pribadi</div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-2"><i class="bi bi-check-circle text-success me-2"></i>Hak
                                memperbaiki data yang tidak akurat</div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-2"><i class="bi bi-check-circle text-success me-2"></i>Hak
                                menghapus data (setelah masa retensi)</div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-2"><i class="bi bi-check-circle text-success me-2"></i>Hak mendapat
                                informasi penggunaan data</div>
                        </div>
                    </div>

                    <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-6-circle me-2 text-primary"></i>Keamanan Data</h6>
                    <ul>
                        <li>Password disimpan menggunakan enkripsi bcrypt (tidak bisa dibaca balik)</li>
                        <li>Seluruh akses ke sistem dicatat dalam log aktivitas</li>
                        <li>Session timeout otomatis setelah 30 menit tidak aktif</li>
                        <li>Akses berbasis peran (role-based access control)</li>
                    </ul>

                    <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-7-circle me-2 text-primary"></i>Kontak Pengelola Data</h6>
                    <div class="border rounded p-3 bg-light">
                        <p class="mb-1"><strong>Pengelola Data:</strong> Admin Sistem SPP Sekolah</p>
                        <p class="mb-1"><strong>Alamat:</strong> Jl. Pendidikan No. 1, Sumatera Utara</p>
                        <p class="mb-0"><strong>Terakhir diperbarui:</strong> {{ now()->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection