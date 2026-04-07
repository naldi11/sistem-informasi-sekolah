<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'user'])->active();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $siswa = $query->orderBy('nama')->paginate(15)->appends($request->query());
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.siswa.index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:siswa,nis|max:20',
            'nama' => 'required|string|max:100',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
        ]);

        $tglLahir = Carbon::parse($request->tanggal_lahir);
        $defaultPassword = $tglLahir->format('dmY');

        $user = User::create([
            'username' => $request->nis,
            'password' => Hash::make($defaultPassword),
            'role' => 'siswa',
            'is_first_login' => true,
            'is_active' => true,
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        LogAktivitas::log('tambah_siswa', "Menambah siswa: {$siswa->nama} (NIS: {$siswa->nis})");

        return redirect()->route('admin.siswa.index')->with('success', "Siswa {$siswa->nama} berhasil ditambahkan! Password default: {$defaultPassword}");
    }

    public function show(Siswa $siswa)
    {
        $siswa->load([
            'kelas',
            'user',
            'tagihan' => function ($q) {
                $q->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');
            },
            'tagihan.pembayaran'
        ]);

        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
        ]);

        $oldData = "Kelas: {$siswa->kelas->nama_kelas}";
        $siswa->update($request->only('nama', 'kelas_id', 'tanggal_lahir', 'jenis_kelamin', 'alamat'));
        $siswa->load('kelas');
        $newData = "Kelas: {$siswa->kelas->nama_kelas}";

        LogAktivitas::log('edit_siswa', "Mengubah data siswa {$siswa->nama}: {$oldData} → {$newData}");

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->update(['is_deleted' => true]);
        $siswa->user->update(['is_active' => false]);

        LogAktivitas::log('hapus_siswa', "Menonaktifkan siswa: {$siswa->nama} (NIS: {$siswa->nis})");

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dinonaktifkan!');
    }

    public function resetPassword(Siswa $siswa)
    {
        $defaultPassword = $siswa->getDefaultPassword();
        $siswa->user->update([
            'password' => Hash::make($defaultPassword),
            'is_first_login' => true,
        ]);

        LogAktivitas::log('reset_password', "Mereset password siswa: {$siswa->nama} (NIS: {$siswa->nis})");

        return redirect()->back()->with('success', "Password siswa {$siswa->nama} berhasil direset ke default ({$defaultPassword})");
    }
}
