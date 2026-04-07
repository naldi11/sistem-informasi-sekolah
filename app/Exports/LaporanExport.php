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

    public function __construct($type, $bulan, $tahun, $kelasId = null, $siswaId = null)
    {
        $this->type = $type;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kelasId = $kelasId;
        $this->siswaId = $siswaId;
    }

    public function collection()
    {
        $query = Tagihan::with(['siswa.kelas']);

        if ($this->type === 'per-bulan') {
            $query->where('bulan', $this->bulan)->where('tahun', $this->tahun);
        } elseif ($this->type === 'tunggakan') {
            $query->whereIn('status', ['belum_bayar', 'ditolak']);
        } elseif ($this->type === 'per-siswa' && $this->siswaId) {
            $query->where('siswa_id', $this->siswaId);
        }

        if ($this->kelasId) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $this->kelasId));
        }

        return $query->orderBy('tahun')->orderBy('bulan')->get();
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
