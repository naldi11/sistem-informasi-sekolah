<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spp;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class SppController extends Controller
{
    public function index()
    {
        $spp = Spp::with('kelas')
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.spp.index', compact('spp', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nominal' => 'required|numeric|min:0',
            'tahun_ajaran' => 'required|string|max:20',
            'berlaku_mulai' => 'required|date',
        ]);

        $spp = Spp::create($request->only('kelas_id', 'nominal', 'tahun_ajaran', 'berlaku_mulai'));
        $kelas = Kelas::find($request->kelas_id);

        LogAktivitas::log('tambah_spp', "Menambah nominal SPP: {$kelas->nama_kelas} - Rp " . number_format($spp->nominal, 0, ',', '.'));

        return redirect()->route('admin.spp.index')->with('success', 'Nominal SPP berhasil ditambahkan!');
    }

    public function edit(Spp $spp)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.spp.edit', compact('spp', 'kelas'));
    }

    public function update(Request $request, Spp $spp)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nominal' => 'required|numeric|min:0',
            'tahun_ajaran' => 'required|string|max:20',
            'berlaku_mulai' => 'required|date',
        ]);

        $oldNominal = $spp->nominal;
        $spp->update($request->only('kelas_id', 'nominal', 'tahun_ajaran', 'berlaku_mulai'));

        LogAktivitas::log('edit_spp', "Mengubah nominal SPP: Rp " . number_format($oldNominal, 0, ',', '.') . " → Rp " . number_format($spp->nominal, 0, ',', '.'));

        return redirect()->route('admin.spp.index')->with('success', 'Nominal SPP berhasil diperbarui!');
    }

    public function destroy(Spp $spp)
    {
        $spp->delete();
        LogAktivitas::log('hapus_spp', "Menghapus nominal SPP id: {$spp->id}");
        return redirect()->route('admin.spp.index')->with('success', 'Nominal SPP berhasil dihapus!');
    }
}
