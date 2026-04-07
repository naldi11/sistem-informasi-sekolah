<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSandbox;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class SimulatorController extends Controller
{
    public function index($orderId)
    {
        $transaksi = TransaksiSandbox::where('order_id', $orderId)->firstOrFail();
        $transaksi->load('siswa');
        
        return view('simulator.index', compact('transaksi'));
    }

    public function pay(Request $request, $orderId)
    {
        $transaksi = TransaksiSandbox::where('order_id', $orderId)->firstOrFail();

        if ($transaksi->status === 'sukses') {
            return redirect()->back()->with('error', 'Transaksi ini sudah pernah berhasil.');
        }

        // Simulasikan pembayaran sukses
        $transaksi->update([
            'status' => 'sukses'
        ]);

        $daftarBulan = [];

        foreach ($transaksi->pembayaran as $pembayaran) {
            $pembayaran->update([
                'tanggal_verifikasi' => now(),
                // 'verified_by' bisa dikosongi atau diisi ID sistem bot jika ada. Kita kosongi karena auto.
                'catatan' => 'Auto-verified by Sandbox Simulator'
            ]);

            $tagihan = $pembayaran->tagihan;
            $tagihan->update(['status' => 'lunas']);
            
            $daftarBulan[] = $tagihan->nama_bulan . ' ' . $tagihan->tahun;
        }

        $bulanStr = implode(', ', $daftarBulan);

        // Buat Notifikasi ke Siswa
        Notifikasi::create([
            'user_id' => $transaksi->siswa->user_id,
            'pesan' => "Pembayaran Anda sebesar Rp " . number_format($transaksi->total_nominal, 0, ',', '.') . " untuk tagihan ($bulanStr) berhasil diverifikasi Gateway Sandbox.",
            'is_read' => false,
        ]);

        LogAktivitas::log('sandbox_webhook', "Simulasi pembayaran sukses untuk Order ID {$orderId} sejumlah Rp {$transaksi->total_nominal}");

        return redirect()->route('sandbox.simulator', $orderId)->with('success', 'Pembayaran berhasil dikonfirmasi! Anda dapat menutup halaman simulator ini.');
    }
}
