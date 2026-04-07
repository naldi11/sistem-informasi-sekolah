<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Tagihan;
use App\Models\Notifikasi;
use App\Models\LogAktivitas;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-generate tagihan setiap tanggal 1 pukul 00:00
Schedule::call(function () {
    $bulan = Carbon::now()->month;
    $tahun = Carbon::now()->year;
    $count = 0;

    $siswaAktif = Siswa::active()->with('kelas')->get();

    foreach ($siswaAktif as $siswa) {
        $existing = Tagihan::where('siswa_id', $siswa->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if (!$existing) {
            $spp = Spp::where('kelas_id', $siswa->kelas_id)
                ->where('berlaku_mulai', '<=', Carbon::create($tahun, $bulan, 1))
                ->orderBy('berlaku_mulai', 'desc')
                ->first();

            if ($spp) {
                Tagihan::create([
                    'siswa_id' => $siswa->id,
                    'spp_id' => $spp->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nominal' => $spp->nominal,
                    'status' => 'belum_bayar',
                ]);

                $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
                Notifikasi::create([
                    'user_id' => $siswa->user_id,
                    'pesan' => "Tagihan bulan {$namaBulan} {$tahun} telah tersedia. Nominal: Rp " . number_format($spp->nominal, 0, ',', '.'),
                ]);

                $count++;
            }
        }
    }

    LogAktivitas::log('generate_tagihan_otomatis', "Generate tagihan otomatis bulan {$bulan}/{$tahun}. Total: {$count} tagihan dibuat.", null);
})->monthlyOn(1, '00:00')->name('generate-tagihan-otomatis');
