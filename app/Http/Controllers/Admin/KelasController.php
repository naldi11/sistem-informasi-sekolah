<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount(['siswa' => fn($q) => $q->where('is_deleted', false)])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|in:X,XI,XII',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $kelas = Kelas::create($request->only('nama_kelas', 'tingkat', 'tahun_ajaran'));

        LogAktivitas::log('tambah_kelas', "Menambah kelas: {$kelas->nama_kelas} ({$kelas->tingkat})");

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(Kelas $kela)
    {
        return view('admin.kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|in:X,XI,XII',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $old = $kela->nama_kelas;
        $kela->update($request->only('nama_kelas', 'tingkat', 'tahun_ajaran'));

        LogAktivitas::log('edit_kelas', "Mengubah kelas: {$old} → {$kela->nama_kelas}");

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy(Kelas $kela)
    {
        $activeSiswa = $kela->siswa()->where('is_deleted', false)->count();

        if ($activeSiswa > 0) {
            return redirect()->route('admin.kelas.index')
                ->with('error', "Tidak dapat menghapus kelas {$kela->nama_kelas}. Masih ada {$activeSiswa} siswa aktif.");
        }

        $nama = $kela->nama_kelas;
        $kela->delete();

        LogAktivitas::log('hapus_kelas', "Menghapus kelas: {$nama}");

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
