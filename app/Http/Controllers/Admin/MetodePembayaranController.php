<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $metodeList = MetodePembayaran::orderBy('created_at', 'asc')->get();
        return view('admin.metode-pembayaran.index', compact('metodeList'));
    }

    public function create()
    {
        return view('admin.metode-pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:metode_pembayaran,kode',
            'kategori' => 'required|in:qris,va,transfer_manual',
            'nomor_rekening' => 'nullable|string|max:100',
            'pemilik_rekening' => 'nullable|string|max:255',
            'instruksi' => 'nullable|string',
            'butuh_bukti' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $metode = MetodePembayaran::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'kategori' => $request->kategori,
            'nomor_rekening' => $request->nomor_rekening,
            'pemilik_rekening' => $request->pemilik_rekening,
            'instruksi' => $request->instruksi,
            'butuh_bukti' => $request->has('butuh_bukti'),
            'is_active' => $request->has('is_active'),
        ]);

        LogAktivitas::log('tambah_metode_pembayaran', "Menambahkan metode pembayaran {$metode->nama} ({$metode->kode})");

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', "Metode pembayaran {$metode->nama} berhasil ditambahkan!");
    }

    public function edit(MetodePembayaran $metodePembayaran)
    {
        return view('admin.metode-pembayaran.edit', ['metode' => $metodePembayaran]);
    }

    public function update(Request $request, MetodePembayaran $metodePembayaran)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:metode_pembayaran,kode,' . $metodePembayaran->id,
            'kategori' => 'required|in:qris,va,transfer_manual',
            'nomor_rekening' => 'nullable|string|max:100',
            'pemilik_rekening' => 'nullable|string|max:255',
            'instruksi' => 'nullable|string',
        ]);

        $metodePembayaran->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'kategori' => $request->kategori,
            'nomor_rekening' => $request->nomor_rekening,
            'pemilik_rekening' => $request->pemilik_rekening,
            'instruksi' => $request->instruksi,
            'butuh_bukti' => $request->has('butuh_bukti'),
            'is_active' => $request->has('is_active'),
        ]);

        LogAktivitas::log('update_metode_pembayaran', "Memperbarui metode pembayaran {$metodePembayaran->nama}");

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', "Metode pembayaran {$metodePembayaran->nama} berhasil diperbarui!");
    }

    public function destroy(MetodePembayaran $metodePembayaran)
    {
        $nama = $metodePembayaran->nama;
        $metodePembayaran->delete();

        LogAktivitas::log('hapus_metode_pembayaran', "Menghapus metode pembayaran {$nama}");

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', "Metode pembayaran {$nama} berhasil dihapus.");
    }
}
