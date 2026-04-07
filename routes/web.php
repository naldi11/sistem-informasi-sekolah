<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SppController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PembayaranController as SiswaPembayaranController;

// Auth Routes
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ganti Password (first login & umum)
Route::middleware('auth')->group(function () {
    Route::get('/ganti-password', [AuthController::class, 'showGantiPassword'])->name('ganti-password');
    Route::post('/ganti-password', [AuthController::class, 'gantiPassword']);
});

// Notifikasi (all authenticated users)
Route::middleware(['auth', 'first_login'])->group(function () {
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::patch('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.readAll');
});

// Admin Routes
Route::middleware(['auth', 'first_login', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelas
    Route::resource('kelas', KelasController::class)->except('show');

    // SPP
    Route::resource('spp', SppController::class)->except(['show', 'create']);

    // Siswa
    Route::resource('siswa', SiswaController::class);
    Route::post('/siswa/{siswa}/reset-password', [SiswaController::class, 'resetPassword'])->name('siswa.resetPassword');

    // Tagihan
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan/generate', [TagihanController::class, 'generateManual'])->name('tagihan.generate');
    Route::post('/tagihan/auto-generate', [TagihanController::class, 'autoGenerate'])->name('tagihan.autoGenerate');

    // Pembayaran
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{tagihan}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{tagihan}/verifikasi', [AdminPembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    Route::post('/pembayaran/{tagihan}/tolak', [AdminPembayaranController::class, 'tolak'])->name('pembayaran.tolak');
    Route::delete('/pembayaran/{tagihan}', [AdminPembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    // Laporan
    Route::get('/laporan/per-siswa', [LaporanController::class, 'perSiswa'])->name('laporan.perSiswa');
    Route::get('/laporan/per-bulan', [LaporanController::class, 'perBulan'])->name('laporan.perBulan');
    Route::get('/laporan/tunggakan', [LaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
    Route::get('/laporan/rekap', [LaporanController::class, 'rekap'])->name('laporan.rekap');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.exportExcel');

    // Log
    Route::get('/log', [LogController::class, 'index'])->name('log.index');
    Route::get('/log/export-pdf', [LogController::class, 'exportPdf'])->name('log.exportPdf');

    // Kebijakan Privasi
    Route::get('/kebijakan-privasi', [LogController::class, 'kebijakanPrivasi'])->name('kebijakanPrivasi');
});

// Siswa Routes
Route::middleware(['auth', 'first_login', 'siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::match(['get', 'post'], '/checkout', [SiswaPembayaranController::class, 'checkout'])->name('bayar.checkout');
    Route::post('/checkout/process', [SiswaPembayaranController::class, 'processCheckout'])->name('bayar.process');
    Route::get('/invoice/{order_id}', [SiswaPembayaranController::class, 'invoice'])->name('bayar.invoice');
    Route::get('/invoice/{order_id}/status', [SiswaPembayaranController::class, 'checkStatus'])->name('bayar.status');
});

// Simulator Routes (Public / Sandbox)
Route::prefix('sandbox')->name('sandbox.')->group(function () {
    Route::get('/simulator/{order_id}', [\App\Http\Controllers\SimulatorController::class, 'index'])->name('simulator');
    Route::post('/simulator/{order_id}/pay', [\App\Http\Controllers\SimulatorController::class, 'pay'])->name('simulator.pay');
});
