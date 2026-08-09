<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\TransaksiSandbox;
use App\Models\MetodePembayaran;
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

        $metodeList = MetodePembayaran::where('is_active', true)->get();

        return view('siswa.pembayaran.checkout', compact('tagihanList', 'totalNominal', 'metodeList'));
    }

    public function processCheckout(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $metode = MetodePembayaran::where('kode', $request->metode_pembayaran)
            ->where('is_active', true)
            ->first();

        $rules = [
            'tagihan_ids' => 'required|array|min:1',
            'tagihan_ids.*' => 'exists:tagihan,id',
            'metode_pembayaran' => 'required|string',
            'file_bukti' => 'nullable|mimes:jpg,jpeg|max:5120',
        ];

        $request->validate($rules);

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
        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));
        
        $tipe = $metode ? $metode->kategori : ($request->metode_pembayaran === 'qris' ? 'qris' : 'va');
        
        // Generate Payment Code / Rekening sesuai setting Admin
        if ($tipe === 'qris') {
            $kodePembayaran = route('sandbox.simulator', ['order_id' => $orderId]);
        } else {
            $kodePembayaran = ($metode && !empty($metode->nomor_rekening)) ? $metode->nomor_rekening : ($siswa->nis ?? '8000');
        }

        // Upload bukti jika ada
        $filePath = '';
        if ($request->hasFile('file_bukti')) {
            $file = $request->file('file_bukti');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/bukti_pembayaran'), $fileName);
            $filePath = 'bukti_pembayaran/' . $fileName;
        }

        $transaksi = TransaksiSandbox::create([
            'order_id' => $orderId,
            'siswa_id' => $siswa->id,
            'total_nominal' => $totalNominal,
            'metode_pembayaran' => $metode ? $metode->nama : $request->metode_pembayaran,
            'tipe' => $tipe,
            'kode_pembayaran' => $kodePembayaran,
            'status' => 'pending',
            'expired_at' => now()->addDay(),
        ]);

        foreach ($tagihanList as $t) {
            if ($t->pembayaran && !$t->pembayaran->transaksi_sandbox_id) {
                $t->pembayaran->delete();
            }

            Pembayaran::create([
                'tagihan_id' => $t->id,
                'transaksi_sandbox_id' => $transaksi->id,
                'file_bukti' => $filePath,
                'tanggal_upload' => now(),
            ]);

            $t->update(['status' => 'menunggu_verifikasi']);
        }

        if ($filePath) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            $daftarBulan = [];
            foreach ($tagihanList as $t) {
                $daftarBulan[] = $t->nama_bulan . ' ' . $t->tahun;
            }
            $bulanStr = implode(', ', $daftarBulan);

            foreach ($admins as $admin) {
                \App\Models\Notifikasi::create([
                    'user_id' => $admin->id,
                    'pesan' => "Pembayaran baru dari {$siswa->nama} untuk tagihan ($bulanStr). Menunggu verifikasi.",
                ]);
            }
        }

        LogAktivitas::log('checkout_pembayaran', "Checkout pembayaran transaksi {$orderId} untuk " . count($tagihanList) . " tagihan.");

        return redirect()->route('siswa.bayar.invoice', ['order_id' => $orderId])
            ->with('success', 'Checkout berhasil! Selesaikan pembayaran Anda.');
    }

    public function invoice($orderId)
    {
        $siswa = auth()->user()->siswa;
        $transaksi = TransaksiSandbox::where('order_id', $orderId)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        if (in_array($transaksi->status, ['gagal', 'kadaluarsa'])) {
            return redirect()->route('siswa.dashboard')->with('info', 'Transaksi pembayaran ini telah dibatalkan atau kadaluarsa.');
        }

        $metode = MetodePembayaran::where('nama', $transaksi->metode_pembayaran)
            ->orWhere('kode', $transaksi->metode_pembayaran)
            ->first();

        return view('siswa.pembayaran.invoice', compact('transaksi', 'metode'));
    }

    public function uploadBuktiInvoice(Request $request, $orderId)
    {
        $siswa = auth()->user()->siswa;
        $transaksi = TransaksiSandbox::where('order_id', $orderId)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        $request->validate([
            'file_bukti' => 'required|mimes:jpg,jpeg|max:5120',
        ]);

        $file = $request->file('file_bukti');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('storage/bukti_pembayaran'), $fileName);
        $filePath = 'bukti_pembayaran/' . $fileName;

        $daftarBulan = [];
        foreach ($transaksi->pembayaran as $p) {
            $p->update([
                'file_bukti' => $filePath,
                'tanggal_upload' => now(),
            ]);
            $p->tagihan->update(['status' => 'menunggu_verifikasi']);
            $daftarBulan[] = $p->tagihan->nama_bulan . ' ' . $p->tagihan->tahun;
        }

        $transaksi->update(['status' => 'menunggu_verifikasi']);

        $admins = \App\Models\User::where('role', 'admin')->get();
        $bulanStr = implode(', ', $daftarBulan);
        foreach ($admins as $admin) {
            \App\Models\Notifikasi::create([
                'user_id' => $admin->id,
                'pesan' => "Pembayaran baru dari {$siswa->nama} untuk tagihan ($bulanStr). Menunggu verifikasi.",
            ]);
        }

        LogAktivitas::log('upload_bukti_invoice', "Upload/update foto bukti transfer untuk order {$orderId}.");

        return redirect()->route('siswa.bayar.invoice', $orderId)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi verifikasi admin.');
    }

    public function batalInvoice($orderId)
    {
        $siswa = auth()->user()->siswa;
        $transaksi = TransaksiSandbox::where('order_id', $orderId)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        if ($transaksi->status === 'sukses') {
            return redirect()->back()->with('error', 'Pembayaran yang sudah lunas tidak dapat dibatalkan.');
        }

        $transaksi->update(['status' => 'gagal']);

        foreach ($transaksi->pembayaran as $p) {
            if ($p->file_bukti && file_exists(public_path('storage/' . $p->file_bukti))) {
                @unlink(public_path('storage/' . $p->file_bukti));
            }
            if ($p->tagihan) {
                $p->tagihan->update(['status' => 'belum_bayar']);
            }
            $p->delete();
        }

        LogAktivitas::log('batal_pembayaran', "Siswa {$siswa->nama} membatalkan transaksi pembayaran {$orderId}.");

        return redirect()->route('siswa.dashboard')
            ->with('info', "Transaksi pembayaran {$orderId} telah dibatalkan.");
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

    public function printStruk($orderId)
    {
        $siswa = auth()->user()->siswa;
        $transaksi = TransaksiSandbox::where('order_id', $orderId)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        if ($transaksi->status !== 'sukses') {
            return redirect()->route('siswa.bayar.invoice', $orderId)->with('error', 'Hanya tagihan yang lunas yang dapat dicetak.');
        }

        if ($transaksi->tipe !== 'qris') {
            return redirect()->route('siswa.bayar.invoice', $orderId)->with('error', 'Cetak struk hanya tersedia untuk metode pembayaran QRIS.');
        }

        return view('siswa.pembayaran.print', compact('transaksi', 'siswa'));
    }
}
