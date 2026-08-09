<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\LogAktivitas;
use App\Services\TagihanService;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function __construct(
        private TagihanService $tagihanService
    ) {
    }

    public function index(Request $request)
    {
        $query = Tagihan::with(['siswa.kelas', 'spp']);

        if ($request->filled('search')) {
            $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', '%' . $request->search . '%')->orWhere('nis', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('semester')) {
            if ($request->semester == '1') {
                // Semester 1 (Ganjil): Juli (7) s/d Desember (12)
                $query->whereBetween('bulan', [7, 12]);
            } elseif ($request->semester == '2') {
                // Semester 2 (Genap): Januari (1) s/d Juni (6)
                $query->whereBetween('bulan', [1, 6]);
            }
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }

        $tagihan = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(20)
            ->appends($request->query());

        $kelas = \App\Models\Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $siswaList = \App\Models\Siswa::active()->with('kelas')->orderBy('nama')->get();

        return view('admin.tagihan.index', compact('tagihan', 'kelas', 'siswaList'));
    }

    public function generateManual(Request $request)
    {
        $request->validate([
            'dari_bulan' => 'required|integer|min:1|max:12',
            'sampai_bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        $dariBulan = (int) $request->dari_bulan;
        $sampaiBulan = (int) $request->sampai_bulan;

        if ($sampaiBulan < $dariBulan) {
            return redirect()->back()->with('error', 'Bulan akhir tidak boleh lebih kecil dari bulan awal.');
        }

        $siswaIds = $request->siswa_ids ?? [];
        $count = $this->tagihanService->generateFlexible($siswaIds, $dariBulan, $sampaiBulan, (int) $request->tahun);

        $namaDari = \Carbon\Carbon::create(null, $dariBulan)->translatedFormat('F');
        $namaSampai = \Carbon\Carbon::create(null, $sampaiBulan)->translatedFormat('F');
        $rangeLabel = $dariBulan === $sampaiBulan ? $namaDari : "{$namaDari} - {$namaSampai}";
        $targetLabel = empty($siswaIds) ? 'semua siswa' : count($siswaIds) . ' siswa terpilih';

        LogAktivitas::log('generate_tagihan_manual', "Generate tagihan {$rangeLabel} {$request->tahun} untuk {$targetLabel}. Total: {$count} tagihan dibuat.");

        return redirect()->route('admin.tagihan.index')
            ->with('success', "Berhasil generate {$count} tagihan ({$rangeLabel} {$request->tahun}) untuk {$targetLabel}.");
    }

    public function autoGenerate()
    {
        $result = $this->tagihanService->autoGenerateMissing();

        if ($result['count'] === 0) {
            return redirect()->route('admin.tagihan.index')
                ->with('info', 'Semua tagihan sudah lengkap, tidak ada yang perlu di-generate.');
        }

        $detailMsg = collect($result['details'])->map(fn($d) => "{$d['nama']} ({$d['kelas']}): {$d['jumlah']} tagihan")->implode(', ');

        LogAktivitas::log('auto_generate_tagihan', "Auto-generate tunggakan: {$result['count']} tagihan dibuat. Detail: {$detailMsg}");

        return redirect()->route('admin.tagihan.index')
            ->with('success', "Berhasil auto-generate {$result['count']} tagihan tunggakan. Detail: {$detailMsg}");
    }

    public function autoLunasPrior(Request $request)
    {
        $request->validate([
            'mulai_bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        $mulaiBulan = (int) $request->mulai_bulan;
        $tahun = (int) $request->tahun;
        $siswaIds = $request->siswa_ids ?? [];

        $count = $this->tagihanService->autoLunasPriorMonths($mulaiBulan, $tahun, $siswaIds);

        $namaMulai = \Carbon\Carbon::create(null, $mulaiBulan)->translatedFormat('F');
        $targetLabel = empty($siswaIds) ? 'semua siswa' : count($siswaIds) . ' siswa terpilih';

        LogAktivitas::log('auto_lunas_prior', "Pelunasan otomatis bulan sebelum {$namaMulai} {$tahun} untuk {$targetLabel}. Total: {$count} tagihan dilunaskan.");

        return redirect()->route('admin.tagihan.index')
            ->with('success', "Berhasil melunaskan {$count} tagihan bulan-bulan sebelum {$namaMulai} {$tahun} untuk {$targetLabel}. Siswa kini mulai pembayaran di bulan {$namaMulai}.");
    }
}
