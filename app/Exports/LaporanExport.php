<?php

namespace App\Exports;

use App\Models\Tagihan;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $type;
    protected $bulan;
    protected $tahun;
    protected $kelasId;
    protected $siswaId;
    protected $status;

    public function __construct($type, $bulan = null, $tahun = null, $kelasId = null, $siswaId = null, $status = null)
    {
        $this->type = $type;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kelasId = $kelasId;
        $this->siswaId = $siswaId;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Tagihan::with(['siswa.kelas']);

        if ($this->type === 'per-bulan') {
            if ($this->bulan) $query->where('bulan', $this->bulan);
            if ($this->tahun) $query->where('tahun', $this->tahun);
        } elseif ($this->type === 'per-kelas') {
            if ($this->kelasId) {
                $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $this->kelasId));
            }
            if ($this->bulan) $query->where('bulan', $this->bulan);
            if ($this->tahun) $query->where('tahun', $this->tahun);
        } elseif ($this->type === 'tunggakan') {
            $query->whereIn('status', ['belum_bayar', 'ditolak']);
        } elseif ($this->type === 'per-siswa' && $this->siswaId) {
            $query->where('siswa_id', $this->siswaId);
        } elseif ($this->type === 'keseluruhan') {
            if ($this->bulan) $query->where('bulan', $this->bulan);
            if ($this->tahun) $query->where('tahun', $this->tahun);
        }

        if ($this->kelasId && $this->type !== 'per-kelas') {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $this->kelasId));
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
    }

    public function headings(): array
    {
        return ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Bulan', 'Tahun', 'Nominal', 'Status'];
    }

    public function map($tagihan): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $tagihan->siswa->nis ?? '-',
            $tagihan->siswa->nama ?? '-',
            $tagihan->siswa->kelas->nama_kelas ?? '-',
            $tagihan->nama_bulan,
            $tagihan->tahun,
            $tagihan->nominal,
            $tagihan->status_label,
        ];
    }

    public function title(): string
    {
        return 'Laporan SPP';
    }
}
