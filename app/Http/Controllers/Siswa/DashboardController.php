<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $tagihanBulanIni = Tagihan::where('siswa_id', $siswa->id)
            ->where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', '!=', 'lunas')
            ->with('pembayaran.transaksiSandbox')
            ->first();

        $tagihanAktif = Tagihan::where('siswa_id', $siswa->id)
            ->whereIn('status', ['belum_bayar', 'ditolak'])
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        $riwayat = Tagihan::where('siswa_id', $siswa->id)
            ->whereIn('status', ['menunggu_verifikasi', 'lunas'])
            ->with('pembayaran.transaksiSandbox')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $unreadNotif = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('siswa.dashboard', compact('siswa', 'tagihanBulanIni', 'tagihanAktif', 'riwayat', 'unreadNotif'));
    }
}
