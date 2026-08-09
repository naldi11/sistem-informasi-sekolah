<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSandbox;
use App\Services\PembayaranService;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class SimulatorController extends Controller
{
    public function __construct(
        private PembayaranService $pembayaranService
    ) {}

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
            return redirect()->route('sandbox.simulator', $orderId)->with('success', 'Pembayaran ini sudah berhasil dikonfirmasi sebelumnya.');
        }

        $pembayaranFirst = $transaksi->pembayaran->first();
        if ($transaksi->tipe === 'qris') {
            LogAktivitas::log('sandbox_webhook', "Simulasi klik bayar QRIS untuk Order ID {$orderId} sejumlah Rp {$transaksi->total_nominal}. Menunggu upload bukti user.");
            return redirect()->route('sandbox.simulator', $orderId)->with('success', 'Silakan tutup simulator ini dan upload bukti pembayaran Anda di halaman invoice (Sistem membutuhkan foto bukti transfer untuk memverifikasi pembayaran QRIS Anda).');
        } else {
            if ($pembayaranFirst && $pembayaranFirst->tagihan) {
                $this->pembayaranService->verifikasi($pembayaranFirst->tagihan);
            } else {
                $transaksi->update(['status' => 'sukses']);
            }
            LogAktivitas::log('sandbox_webhook', "Simulasi pengiriman transfer/QR untuk Order ID {$orderId} sejumlah Rp {$transaksi->total_nominal} berhasil dikonfirmasi.");
            return redirect()->route('sandbox.simulator', $orderId)->with('success', 'Pembayaran berhasil dikonfirmasi melalui simulator! Tagihan Anda telah Lunas.');
        }
    }
}
