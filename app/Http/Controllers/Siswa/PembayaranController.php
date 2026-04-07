<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\TransaksiSandbox;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function checkout(Request $request)
    {
        $siswa = auth()->user()->siswa;
        
        $ids = $request->input('tagihan_ids', []);

        if (empty($ids)) {
            return redirect()->route('siswa.dashboard')->with('error', 'Pilih minimal 1 tagihan untuk dibayar.');
        }

        $tagihanList = Tagihan::where('siswa_id', $siswa->id)
            ->whereIn('id', $ids)
            ->whereIn('status', ['belum_bayar', 'ditolak'])
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        if ($tagihanList->isEmpty()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Tidak ada tagihan yang valid untuk dibayar.');
        }

        // Sequential validation: check no gaps
        $allUnpaid = Tagihan::where('siswa_id', $siswa->id)
            ->whereIn('status', ['belum_bayar', 'ditolak'])
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->pluck('id')
            ->toArray();

        foreach ($tagihanList as $i => $t) {
            $expectedId = $allUnpaid[$i] ?? null;
            if ($t->id !== $expectedId) {
                return redirect()->route('siswa.dashboard')
                    ->with('error', 'Tidak bisa melewati bulan. Bayar sesuai urutan dari yang paling lama.');
            }
        }

        $totalNominal = $tagihanList->sum('nominal');
        $tagihanList->load('siswa.kelas');

        return view('siswa.pembayaran.checkout', compact('tagihanList', 'totalNominal'));
    }

    public function processCheckout(Request $request)
    {
        $siswa = auth()->user()->siswa;
        $request->validate([
            'tagihan_ids' => 'required|array|min:1',
            'tagihan_ids.*' => 'exists:tagihan,id',
            'metode_pembayaran' => 'required|string',
        ]);

        $tagihanList = Tagihan::where('siswa_id', $siswa->id)
            ->whereIn('id', $request->tagihan_ids)
            ->whereIn('status', ['belum_bayar', 'ditolak'])
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        if ($tagihanList->isEmpty()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Tidak ada tagihan yang valid.');
        }

        $totalNominal = $tagihanList->sum('nominal');
        // Generate Unique Order ID
        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));
        
        $tipe = $request->metode_pembayaran === 'qris' ? 'qris' : 'va';
        
        // Generate Mock Payment Code
        if ($tipe === 'qris') {
            // For QRIS, the url will point to the simulator page to simulate scan
            $kodePembayaran = route('sandbox.simulator', ['order_id' => $orderId]);
        } else {
            // Virtual Account Number (e.g., Bank Code + NIS)
            $bankCodes = [
                'va_bca' => '3901',
                'va_mandiri' => '89508',
                'va_bri' => '22000'
            ];
            $kodePembayaran = ($bankCodes[$request->metode_pembayaran] ?? '8000') . $siswa->nis;
        }

        $transaksi = TransaksiSandbox::create([
            'order_id' => $orderId,
            'siswa_id' => $siswa->id,
            'total_nominal' => $totalNominal,
            'metode_pembayaran' => $request->metode_pembayaran,
            'tipe' => $tipe,
            'kode_pembayaran' => $kodePembayaran,
            'status' => 'pending',
            'expired_at' => now()->addDay(), // 24 hours expiry
        ]);

        foreach ($tagihanList as $t) {
            // Delete old manual upload if it was rejected previously to clean up
            if ($t->pembayaran && !$t->pembayaran->transaksi_sandbox_id) {
                $t->pembayaran->delete();
            }

            Pembayaran::create([
                'tagihan_id' => $t->id,
                'transaksi_sandbox_id' => $transaksi->id,
                'tanggal_upload' => now(), // basically checkout time
            ]);

            $t->update(['status' => 'menunggu_verifikasi']);
        }

        return redirect()->route('siswa.bayar.invoice', ['order_id' => $orderId])
            ->with('success', 'Checkout berhasil! Selesaikan pembayaran Anda.');
    }

    public function invoice($orderId)
    {
        $siswa = auth()->user()->siswa;
        $transaksi = TransaksiSandbox::where('order_id', $orderId)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        return view('siswa.pembayaran.invoice', compact('transaksi'));
    }

    public function checkStatus($orderId)
    {
        $siswa = auth()->user()->siswa;
        $transaksi = TransaksiSandbox::where('order_id', $orderId)
            ->where('siswa_id', $siswa->id)
            ->first();

        if (!$transaksi) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json(['status' => $transaksi->status]);
    }
}
