<?php

namespace App\Services;

use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class PembayaranService
{
    /**
     * Verifikasi pembayaran dan update status tagihan ke lunas.
     * Jika pembayaran berupa transaksi multi-bulan, verifikasi seluruh tagihan dalam transaksi tersebut secara sekaligus.
     */
    public function verifikasi(Tagihan $tagihan): void
    {
        $pembayaran = $tagihan->pembayaran;
        $trxId = $pembayaran ? $pembayaran->transaksi_sandbox_id : null;

        if ($trxId) {
            $pembayaranList = Pembayaran::where('transaksi_sandbox_id', $trxId)->get();
            $bulanArr = [];
            foreach ($pembayaranList as $p) {
                $p->update([
                    'tanggal_verifikasi' => Carbon::now(),
                    'verified_by' => auth()->id(),
                ]);
                $p->tagihan->update(['status' => 'lunas']);
                $bulanArr[] = $p->tagihan->nama_bulan . ' ' . $p->tagihan->tahun;
            }

            if ($pembayaran && $pembayaran->transaksiSandbox) {
                $pembayaran->transaksiSandbox->update(['status' => 'sukses']);
            }

            $bulanStr = implode(', ', $bulanArr);
            Notifikasi::create([
                'user_id' => $tagihan->siswa->user_id,
                'pesan' => "Pembayaran tagihan ($bulanStr) telah diverifikasi & dikonfirmasi Lunas. ✅",
            ]);

            LogAktivitas::log(
                'verifikasi_pembayaran',
                "Memverifikasi pembayaran sekaligus {$tagihan->siswa->nama} untuk ($bulanStr)"
            );
        } else {
            $tagihan->update(['status' => 'lunas']);
            if ($tagihan->pembayaran) {
                $tagihan->pembayaran->update([
                    'tanggal_verifikasi' => Carbon::now(),
                    'verified_by' => auth()->id(),
                ]);
            }

            Notifikasi::create([
                'user_id' => $tagihan->siswa->user_id,
                'pesan' => "Pembayaran bulan {$tagihan->nama_bulan} {$tagihan->tahun} telah dikonfirmasi. Status: Lunas. ✅",
            ]);

            LogAktivitas::log(
                'verifikasi_pembayaran',
                "Memverifikasi pembayaran {$tagihan->siswa->nama} bulan {$tagihan->nama_bulan} {$tagihan->tahun} - Rp " . number_format((float) $tagihan->nominal, 0, ',', '.')
            );
        }
    }

    /**
     * Tolak pembayaran siswa.
     * Jika transaksi mencakup multi-bulan, tolak seluruh tagihan terkait sekaligus.
     */
    public function tolak(Tagihan $tagihan, ?string $catatan = null): void
    {
        $pembayaran = $tagihan->pembayaran;
        $trxId = $pembayaran ? $pembayaran->transaksi_sandbox_id : null;

        if ($trxId) {
            $pembayaranList = Pembayaran::where('transaksi_sandbox_id', $trxId)->get();
            $bulanArr = [];
            foreach ($pembayaranList as $p) {
                if ($catatan) {
                    $p->update(['catatan' => $catatan]);
                }
                $p->tagihan->update(['status' => 'ditolak']);
                $bulanArr[] = $p->tagihan->nama_bulan . ' ' . $p->tagihan->tahun;
            }

            if ($pembayaran && $pembayaran->transaksiSandbox) {
                $pembayaran->transaksiSandbox->update(['status' => 'gagal']);
            }

            $bulanStr = implode(', ', $bulanArr);
            Notifikasi::create([
                'user_id' => $tagihan->siswa->user_id,
                'pesan' => "Pembayaran tagihan ($bulanStr) ditolak. Silakan upload ulang bukti transfer. ❌",
            ]);

            LogAktivitas::log(
                'tolak_pembayaran',
                "Menolak pembayaran sekaligus {$tagihan->siswa->nama} untuk ($bulanStr)"
            );
        } else {
            $tagihan->update(['status' => 'ditolak']);
            if ($tagihan->pembayaran && $catatan) {
                $tagihan->pembayaran->update(['catatan' => $catatan]);
            }

            Notifikasi::create([
                'user_id' => $tagihan->siswa->user_id,
                'pesan' => "Pembayaran bulan {$tagihan->nama_bulan} {$tagihan->tahun} ditolak. Silakan upload ulang bukti transfer. ❌",
            ]);

            LogAktivitas::log(
                'tolak_pembayaran',
                "Menolak pembayaran {$tagihan->siswa->nama} bulan {$tagihan->nama_bulan} {$tagihan->tahun}"
            );
        }
    }

    /**
     * Upload bukti pembayaran siswa.
     */
    public function uploadBukti(Tagihan $tagihan, string $filePath): void
    {
        $tagihan->pembayaran()->delete();

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'file_bukti' => $filePath,
            'tanggal_upload' => Carbon::now(),
        ]);

        $tagihan->update(['status' => 'menunggu_verifikasi']);

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'pesan' => "Pembayaran baru dari {$tagihan->siswa->nama} untuk bulan {$tagihan->nama_bulan} {$tagihan->tahun}. Menunggu verifikasi.",
            ]);
        }

        LogAktivitas::log(
            'upload_bukti_pembayaran',
            "Upload bukti pembayaran bulan {$tagihan->nama_bulan} {$tagihan->tahun} - {$tagihan->siswa->nama}"
        );
    }

    /**
     * Hapus data pembayaran yang ditolak.
     */
    public function hapusPembayaran(Tagihan $tagihan): bool
    {
        if ($tagihan->status !== 'ditolak') {
            return false;
        }

        $tagihan->pembayaran()->delete();
        $tagihan->update(['status' => 'belum_bayar']);

        LogAktivitas::log('hapus_pembayaran', "Menghapus data pembayaran untuk tagihan id: {$tagihan->id}");

        return true;
    }
}
