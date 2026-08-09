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

        LogAktivitas::log('sandbox_webhook', "Simulasi pengiriman transfer/QR untuk Order ID {$orderId} sejumlah Rp {$transaksi->total_nominal}");

        return redirect()->route('sandbox.simulator', $orderId)->with('info', 'Pembayaran QR/Transfer telah dikonfirmasi di simulator. Harap upload foto bukti transfer (.jpg) di halaman Invoice untuk diverifikasi secara manual oleh Admin.');
    }
}
