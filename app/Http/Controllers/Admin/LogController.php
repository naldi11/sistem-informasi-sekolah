<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('aksi')) {
            $query->where('aksi', 'like', "%{$request->aksi}%");
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs = $query->paginate(25)->appends($request->query());

        return view('admin.log.index', compact('logs'));
    }

    public function exportPdf(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('aksi')) {
            $query->where('aksi', 'like', "%{$request->aksi}%");
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs = $query->take(500)->get();

        $pdf = Pdf::loadView('admin.log.pdf', compact('logs'));
        return $pdf->download('log-aktivitas-' . now()->format('Y-m-d') . '.pdf');
    }

    public function kebijakanPrivasi()
    {
        return view('admin.kebijakan-privasi');
    }
}
