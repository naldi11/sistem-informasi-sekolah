<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function perSiswa(Request $request)
    {
        $siswaList = Siswa::active()->with('kelas')->orderBy('nama')->get();
        $siswa = null;
        $tagihan = collect();

        if ($request->filled('siswa_id')) {
            $siswa = Siswa::with('kelas')->findOrFail($request->siswa_id);
            $query = Tagihan::where('siswa_id', $siswa->id);

            if ($request->filled('dari_bulan') && $request->filled('dari_tahun')) {
                $query->where(function ($q) use ($request) {
                    $q->where('tahun', '>', $request->dari_tahun)
                        ->orWhere(function ($q2) use ($request) {
                            $q2->where('tahun', $request->dari_tahun)
                                ->where('bulan', '>=', $request->dari_bulan);
                        });
                });
            }
            if ($request->filled('sampai_bulan') && $request->filled('sampai_tahun')) {
                $query->where(function ($q) use ($request) {
                    $q->where('tahun', '<', $request->sampai_tahun)
                        ->orWhere(function ($q2) use ($request) {
                            $q2->where('tahun', $request->sampai_tahun)
                                ->where('bulan', '<=', $request->sampai_bulan);
                        });
                });
            }

            $tagihan = $query->orderBy('tahun')->orderBy('bulan')->get();
        }

        return view('admin.laporan.per-siswa', compact('siswaList', 'siswa', 'tagihan'));
    }

    public function perBulan(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $tagihan = collect();
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $query = Tagihan::with(['siswa.kelas'])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun);

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }

        $tagihan = $query->orderBy('nominal', 'desc')->get();

        $totalLunas = $tagihan->where('status', 'lunas')->sum('nominal');
        $totalBelum = $tagihan->whereIn('status', ['belum_bayar', 'ditolak', 'menunggu_verifikasi'])->sum('nominal');

        return view('admin.laporan.per-bulan', compact('kelasList', 'tagihan', 'bulan', 'tahun', 'totalLunas', 'totalBelum'));
    }

    public function tunggakan(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        $query = Tagihan::with(['siswa.kelas'])
            ->whereIn('status', ['belum_bayar', 'ditolak']);

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->where('bulan', $request->bulan)->where('tahun', $request->tahun);
        }

        $tunggakan = $query->orderBy('tahun')->orderBy('bulan')->get();

        $grouped = $tunggakan->groupBy('siswa_id')->map(function ($items) {
            $siswa = $items->first()->siswa;
            return [
                'siswa' => $siswa,
                'tagihan' => $items,
                'total' => $items->sum('nominal'),
                'jumlah_bulan' => $items->count(),
            ];
        })->sortByDesc('total');

        return view('admin.laporan.tunggakan', compact('kelasList', 'grouped'));
    }

    public function rekap(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran', '2025/2026');

        // Parse tahun from tahun_ajaran
        $parts = explode('/', $tahunAjaran);
        $tahun1 = (int) ($parts[0] ?? 2025);
        $tahun2 = (int) ($parts[1] ?? 2026);

        $semester = $request->get('semester', '1');

        if ($semester == '1') {
            $months = [7, 8, 9, 10, 11, 12];
            $tahun = $tahun1;
        } else {
            $months = [1, 2, 3, 4, 5, 6];
            $tahun = $tahun2;
        }

        $rekap = [];
        foreach ($months as $m) {
            $total = Tagihan::where('bulan', $m)->where('tahun', $tahun)->count();
            $lunas = Tagihan::where('bulan', $m)->where('tahun', $tahun)->where('status', 'lunas')->count();
            $menunggu = Tagihan::where('bulan', $m)->where('tahun', $tahun)->where('status', 'menunggu_verifikasi')->count();
            $belum = $total - $lunas;
            $pemasukan = Tagihan::where('bulan', $m)->where('tahun', $tahun)->where('status', 'lunas')->sum('nominal');

            $namaBulan = Carbon::create($tahun, $m, 1)->translatedFormat('F');

            $rekap[] = [
                'bulan' => $m,
                'nama_bulan' => $namaBulan,
                'tahun' => $tahun,
                'total' => $total,
                'lunas' => $lunas,
                'belum' => $belum,
                'persentase' => $total > 0 ? round(($lunas / $total) * 100, 1) : 0,
                'pemasukan' => $pemasukan,
            ];
        }

        $totalPemasukan = collect($rekap)->sum('pemasukan');

        return view('admin.laporan.rekap', compact('rekap', 'tahunAjaran', 'semester', 'totalPemasukan'));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'per-bulan');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $query = Tagihan::with(['siswa.kelas']);

        if ($type === 'per-bulan') {
            $query->where('bulan', $bulan)->where('tahun', $tahun);
            if ($request->filled('kelas_id')) {
                $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
            }
            $tagihan = $query->get();
            $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
            $pdf = Pdf::loadView('admin.laporan.pdf.per-bulan', compact('tagihan', 'namaBulan', 'tahun'));
            return $pdf->download("laporan-spp-{$namaBulan}-{$tahun}.pdf");
        }

        if ($type === 'tunggakan') {
            $query->whereIn('status', ['belum_bayar', 'ditolak']);
            if ($request->filled('kelas_id')) {
                $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
            }
            $tunggakan = $query->orderBy('tahun')->orderBy('bulan')->get();
            $pdf = Pdf::loadView('admin.laporan.pdf.tunggakan', compact('tunggakan'));
            return $pdf->download("laporan-tunggakan.pdf");
        }

        if ($type === 'per-siswa' && $request->filled('siswa_id')) {
            $siswa = Siswa::with('kelas')->findOrFail($request->siswa_id);
            $tagihan = Tagihan::where('siswa_id', $siswa->id)->orderBy('tahun')->orderBy('bulan')->get();
            $pdf = Pdf::loadView('admin.laporan.pdf.per-siswa', compact('siswa', 'tagihan'));
            return $pdf->download("laporan-spp-{$siswa->nis}.pdf");
        }

        return redirect()->back()->with('error', 'Parameter laporan tidak valid.');
    }

    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'per-bulan');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');

        return Excel::download(new LaporanExport($type, $bulan, $tahun, $request->kelas_id, $request->siswa_id), "laporan-spp-{$type}-{$namaBulan}-{$tahun}.xlsx");
    }
}
