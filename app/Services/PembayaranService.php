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
     */
    public function verifikasi(Tagihan $tagihan): void
    {
        $tagihan->update(['status' => 'lunas']);
        $tagihan->pembayaran->update([
            'tanggal_verifikasi' => Carbon::now(),
            'verified_by' => auth()->id(),
        ]);

        Notifikasi::create([
            'user_id' => $tagihan->siswa->user_id,
            'pesan' => "Pembayaran bulan {$tagihan->nama_bulan} {$tagihan->tahun} telah dikonfirmasi. Status: Lunas. ✅",
        ]);

        LogAktivitas::log(
            'verifikasi_pembayaran',
            "Memverifikasi pembayaran {$tagihan->siswa->nama} bulan {$tagihan->nama_bulan} {$tagihan->tahun} - Rp " . number_format((float) $tagihan->nominal, 0, ',', '.')
        );
    }

    /**
     * Tolak pembayaran siswa.
     */
    public function tolak(Tagihan $tagihan, ?string $catatan = null): void
    {
        $tagihan->update(['status' => 'ditolak']);

        if ($catatan) {
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

    /**
     * Upload bukti pembayaran siswa.
     */
    public function uploadBukti(Tagihan $tagihan, string $filePath): void
    {
        // Hapus pembayaran lama jika ada (untuk re-upload setelah ditolak)
        $tagihan->pembayaran()->delete();

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'file_bukti' => $filePath,
            'tanggal_upload' => Carbon::now(),
        ]);

        $tagihan->update(['status' => 'menunggu_verifikasi']);

        // Notifikasi ke semua admin
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
