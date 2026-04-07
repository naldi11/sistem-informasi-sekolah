<?php

namespace App\Services;

use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Notifikasi;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class TagihanService
{
    /**
     * Generate tagihan untuk semua siswa aktif pada bulan/tahun tertentu.
     * Anti-duplikasi: skip jika sudah ada tagihan untuk siswa tersebut.
     *
     * @return int Jumlah tagihan yang berhasil dibuat
     */
    public function generateBulanan(int $bulan, int $tahun): int
    {
        return $this->generateFlexible([], $bulan, $bulan, $tahun);
    }

    /**
     * Generate tagihan secara fleksibel.
     * @param array $siswaIds - ID siswa (kosong = semua aktif)
     * @param int $dariBulan
     * @param int $sampaiBulan
     * @param int $tahun
     * @return int Jumlah tagihan yang berhasil dibuat
     */
    public function generateFlexible(array $siswaIds, int $dariBulan, int $sampaiBulan, int $tahun): int
    {
        $count = 0;

        $query = Siswa::active()->with('kelas');
        if (!empty($siswaIds)) {
            $query->whereIn('id', $siswaIds);
        }
        $siswaList = $query->get();

        for ($bulan = $dariBulan; $bulan <= $sampaiBulan; $bulan++) {
            foreach ($siswaList as $siswa) {
                if ($this->tagihanExists($siswa->id, $bulan, $tahun)) {
                    continue;
                }

                $spp = $this->getSppAktif($siswa->kelas_id, $bulan, $tahun);

                if ($spp) {
                    Tagihan::create([
                        'siswa_id' => $siswa->id,
                        'spp_id' => $spp->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'nominal' => $spp->nominal,
                        'status' => 'belum_bayar',
                    ]);

                    $this->kirimNotifikasiTagihan($siswa, $bulan, $tahun, $spp->nominal);
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Cek apakah tagihan sudah ada untuk siswa tertentu.
     */
    public function tagihanExists(int $siswaId, int $bulan, int $tahun): bool
    {
        return Tagihan::where('siswa_id', $siswaId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();
    }

    /**
     * Ambil SPP aktif berdasarkan kelas dan bulan/tahun.
     */
    public function getSppAktif(int $kelasId, int $bulan, int $tahun): ?Spp
    {
        return Spp::where('kelas_id', $kelasId)
            ->where('berlaku_mulai', '<=', Carbon::create($tahun, $bulan, 1))
            ->orderBy('berlaku_mulai', 'desc')
            ->first();
    }

    /**
     * Auto-detect siswa yang belum punya tagihan dan generate otomatis.
     * Cek dari berlaku_mulai SPP sampai bulan ini.
     *
     * @return array ['count' => int, 'details' => array]
     */
    public function autoGenerateMissing(): array
    {
        $count = 0;
        $details = [];
        $now = Carbon::now();

        $siswaAktif = Siswa::active()->with('kelas')->get();

        foreach ($siswaAktif as $siswa) {
            // Cari SPP terlama (berlaku_mulai paling awal) untuk kelas siswa
            $sppPertama = Spp::where('kelas_id', $siswa->kelas_id)
                ->orderBy('berlaku_mulai', 'asc')
                ->first();

            if (!$sppPertama)
                continue;

            $startDate = Carbon::parse($sppPertama->berlaku_mulai)->startOfMonth();
            $endDate = $now->copy()->startOfMonth();
            $siswaCount = 0;

            $current = $startDate->copy();
            while ($current <= $endDate) {
                $bulan = $current->month;
                $tahun = $current->year;

                if (!$this->tagihanExists($siswa->id, $bulan, $tahun)) {
                    $spp = $this->getSppAktif($siswa->kelas_id, $bulan, $tahun);
                    if ($spp) {
                        Tagihan::create([
                            'siswa_id' => $siswa->id,
                            'spp_id' => $spp->id,
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                            'nominal' => $spp->nominal,
                            'status' => 'belum_bayar',
                        ]);
                        $this->kirimNotifikasiTagihan($siswa, $bulan, $tahun, $spp->nominal);
                        $siswaCount++;
                        $count++;
                    }
                }

                $current->addMonth();
            }

            if ($siswaCount > 0) {
                $details[] = [
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas->nama_kelas ?? '-',
                    'jumlah' => $siswaCount,
                ];
            }
        }

        return ['count' => $count, 'details' => $details];
    }

    /**
     * Kirim notifikasi tagihan ke siswa.
     */
    private function kirimNotifikasiTagihan(Siswa $siswa, int $bulan, int $tahun, $nominal): void
    {
        $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
        Notifikasi::create([
            'user_id' => $siswa->user_id,
            'pesan' => "Tagihan bulan {$namaBulan} {$tahun} telah tersedia. Nominal: Rp " . number_format($nominal, 0, ',', '.'),
        ]);
    }

    /**
     * Ambil data statistik dashboard.
     */
    public function getDashboardStats(): array
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $today = Carbon::today();

        $tagihanBulanIni = Tagihan::where('bulan', $bulanIni)->where('tahun', $tahunIni);

        return [
            'totalSiswa' => Siswa::active()->count(),
            'pembayaranHariIni' => Tagihan::where('status', 'lunas')
                ->whereDate('updated_at', $today)->count(),
            'nominalHariIni' => (float) Tagihan::where('status', 'lunas')
                ->whereDate('updated_at', $today)->sum('nominal'),
            'menungguVerifikasi' => Tagihan::where('status', 'menunggu_verifikasi')->count(),
            'belumBayar' => Tagihan::where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->whereIn('status', ['belum_bayar', 'ditolak'])->count(),
            'totalTagihan' => Tagihan::where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)->count(),
            'totalLunasBulanIni' => Tagihan::where('bulan', $bulanIni)
                ->where('tahun', $tahunIni)
                ->where('status', 'lunas')->count(),
        ];
    }

    /**
     * Data chart tren pembayaran 6 bulan terakhir.
     */
    public function getTrenPembayaran(): array
    {
        $labels = [];
        $lunas = [];
        $belum = [];
        $pemasukan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $b = $date->month;
            $t = $date->year;

            $labels[] = $date->translatedFormat('M Y');
            $lunas[] = Tagihan::where('bulan', $b)->where('tahun', $t)->where('status', 'lunas')->count();
            $belum[] = Tagihan::where('bulan', $b)->where('tahun', $t)->whereIn('status', ['belum_bayar', 'ditolak'])->count();
            $pemasukan[] = (float) Tagihan::where('bulan', $b)->where('tahun', $t)->where('status', 'lunas')->sum('nominal');
        }

        return compact('labels', 'lunas', 'belum', 'pemasukan');
    }

    /**
     * Data chart distribusi status bulan ini.
     */
    public function getDistribusiStatus(): array
    {
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;

        return [
            'lunas' => Tagihan::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'lunas')->count(),
            'menunggu' => Tagihan::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'menunggu_verifikasi')->count(),
            'belum' => Tagihan::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'belum_bayar')->count(),
            'ditolak' => Tagihan::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'ditolak')->count(),
        ];
    }

    /**
     * Data pemasukan per kelas bulan ini.
     */
    public function getPemasukanPerKelas(): array
    {
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;

        $kelas = \App\Models\Kelas::with([
            'siswa' => function ($q) use ($bulan, $tahun) {
                $q->where('is_deleted', false)->with([
                    'tagihan' => function ($q2) use ($bulan, $tahun) {
                        $q2->where('bulan', $bulan)->where('tahun', $tahun);
                    }
                ]);
            }
        ])->orderBy('tingkat')->orderBy('nama_kelas')->get();

        $labels = [];
        $totalNominal = [];
        $lunasNominal = [];

        foreach ($kelas as $k) {
            $labels[] = $k->nama_kelas;
            $tagihanKelas = $k->siswa->flatMap->tagihan;
            $totalNominal[] = (float) $tagihanKelas->sum('nominal');
            $lunasNominal[] = (float) $tagihanKelas->where('status', 'lunas')->sum('nominal');
        }

        return compact('labels', 'totalNominal', 'lunasNominal');
    }
}
