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

        $rawRiwayat = Tagihan::where('siswa_id', $siswa->id)
            ->whereIn('status', ['menunggu_verifikasi', 'lunas'])
            ->with('pembayaran.transaksiSandbox')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $riwayat = [];
        $processedIds = [];

        foreach ($rawRiwayat as $t) {
            if (in_array($t->id, $processedIds)) continue;

            $trx = $t->pembayaran?->transaksiSandbox;
            
            if ($trx) {
                $related = $rawRiwayat->filter(function($item) use ($trx) {
                    return $item->pembayaran && $item->pembayaran->transaksi_sandbox_id === $trx->id;
                });

                $isMulti = $related->count() > 1;
                $totalNominal = $related->sum('nominal');

                $sorted = $related->sortBy(fn($item) => $item->tahun * 12 + $item->bulan)->values();
                $firstTg = $sorted->first();
                $lastTg = $sorted->last();
                
                $bulanStr = $t->nama_bulan . ' ' . $t->tahun;
                if ($isMulti) {
                    $bulanStr = $firstTg->nama_bulan . ' ' . $firstTg->tahun . ' - ' . $lastTg->nama_bulan . ' ' . $lastTg->tahun;
                }

                $riwayat[] = (object) [
                    'id' => $t->id,
                    'isMulti' => $isMulti,
                    'count' => $related->count(),
                    'bulan_str' => $bulanStr,
                    'nominal_total' => $totalNominal,
                    'status_badge' => $t->status_badge,
                    'status_label' => $t->status_label,
                    'status' => $t->status,
                    'pembayaran' => $t->pembayaran
                ];

                $processedIds = array_merge($processedIds, $related->pluck('id')->toArray());
            } else {
                $riwayat[] = (object) [
                    'id' => $t->id,
                    'isMulti' => false,
                    'count' => 1,
                    'bulan_str' => $t->nama_bulan . ' ' . $t->tahun,
                    'nominal_total' => $t->nominal,
                    'status_badge' => $t->status_badge,
                    'status_label' => $t->status_label,
                    'status' => $t->status,
                    'pembayaran' => $t->pembayaran
                ];
                $processedIds[] = $t->id;
            }
        }

        $unreadNotif = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('siswa.dashboard', compact('siswa', 'tagihanBulanIni', 'tagihanAktif', 'riwayat', 'unreadNotif'));
    }
}
