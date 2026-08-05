<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            [
                '1004',
                'I Putu Gede Sanjaya',
                'X IPA 1',
                '2008-04-15',
                'L',
                'Jl. Melati No. 10 Tabanan',
            ],
            [
                '1005',
                'Ni Made Kadek Lestari',
                'XI IPS 1',
                '2007-10-20',
                'P',
                'Jl. Flamboyan No. 5 Kerambitan',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Nama Kelas',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Jenis Kelamin (L/P)',
            'Alamat',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 28,
            'C' => 18,
            'D' => 25,
            'E' => 22,
            'F' => 35,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ]
            ],
        ];
    }
}
