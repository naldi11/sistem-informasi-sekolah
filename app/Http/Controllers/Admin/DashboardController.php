<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Services\TagihanService;

class DashboardController extends Controller
{
    public function __construct(
        private TagihanService $tagihanService
    ) {
    }

    public function index()
    {
        $stats = $this->tagihanService->getDashboardStats();
        $tren = $this->tagihanService->getTrenPembayaran();
        $distribusi = $this->tagihanService->getDistribusiStatus();
        $pemasukanPerKelas = $this->tagihanService->getPemasukanPerKelas();

        $siswaPerKelas = Kelas::withCount(['siswa' => fn($q) => $q->where('is_deleted', false)])->get();

        $recentActivity = LogAktivitas::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', array_merge(
            $stats,
            compact('siswaPerKelas', 'recentActivity', 'tren', 'distribusi', 'pemasukanPerKelas')
        ));
    }
}
