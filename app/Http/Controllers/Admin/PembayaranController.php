<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Kelas;
use App\Services\PembayaranService;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function __construct(
        private PembayaranService $pembayaranService
    ) {
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'semua');

        $query = Tagihan::with(['siswa.kelas', 'pembayaran'])
            ->whereHas('pembayaran');

        if ($tab === 'menunggu') {
            $query->where('status', 'menunggu_verifikasi');
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }
        if ($request->filled('status') && $tab !== 'menunggu') {
            $query->where('status', $request->status);
        }

        $pembayaran = $query->orderBy('updated_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        $menungguCount = Tagihan::where('status', 'menunggu_verifikasi')->count();
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.pembayaran.index', compact('pembayaran', 'menungguCount', 'tab', 'kelas'));
    }

    public function show(Tagihan $tagihan)
    {
        $tagihan->load(['siswa.kelas', 'pembayaran', 'spp']);
        return view('admin.pembayaran.show', compact('tagihan'));
    }

    public function verifikasi(Tagihan $tagihan)
    {
        $this->pembayaranService->verifikasi($tagihan);
        return redirect()->route('admin.pembayaran.index', ['tab' => 'menunggu'])
            ->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function tolak(Request $request, Tagihan $tagihan)
    {
        $request->validate(['catatan' => 'nullable|string']);
        $this->pembayaranService->tolak($tagihan, $request->catatan);
        return redirect()->route('admin.pembayaran.index', ['tab' => 'menunggu'])
            ->with('success', 'Pembayaran telah ditolak.');
    }

    public function destroy(Tagihan $tagihan)
    {
        if (!$this->pembayaranService->hapusPembayaran($tagihan)) {
            return redirect()->back()->with('error', 'Hanya pembayaran berstatus Ditolak yang dapat dihapus.');
        }
        return redirect()->back()->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
