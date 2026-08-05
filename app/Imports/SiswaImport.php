<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;
    public array $skippedRows = [];

    public function collection(Collection $rows)
    {
        $kelasList = Kelas::all()->keyBy(function ($k) {
            return strtolower(trim($k->nama_kelas));
        });

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // Accounting for 1-based index + heading row

            $nis = trim((string) ($row['nis'] ?? ''));
            $nama = trim((string) ($row['nama_siswa'] ?? $row['nama'] ?? ''));
            $namaKelas = trim((string) ($row['nama_kelas'] ?? $row['kelas'] ?? ''));
            $tglLahirRaw = trim((string) ($row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? ''));
            $jkRaw = strtoupper(trim((string) ($row['jenis_kelamin_lp'] ?? $row['jenis_kelamin'] ?? '')));
            $alamat = trim((string) ($row['alamat'] ?? ''));

            if (empty($nis) || empty($nama) || empty($namaKelas)) {
                $this->skippedRows[] = "Baris {$rowNum}: NIS, Nama, atau Nama Kelas kosong.";
                continue;
            }

            // Cek apakah NIS sudah terdaftar
            if (User::where('username', $nis)->exists() || Siswa::where('nis', $nis)->exists()) {
                $this->skippedRows[] = "Baris {$rowNum}: NIS {$nis} sudah terdaftar di sistem.";
                continue;
            }

            // Cek pencocokan Kelas
            $kelasKey = strtolower($namaKelas);
            if (!isset($kelasList[$kelasKey])) {
                $this->skippedRows[] = "Baris {$rowNum}: Kelas '{$namaKelas}' tidak ditemukan di Master Kelas.";
                continue;
            }
            $kelas = $kelasList[$kelasKey] ?? $kelasList->first();
            $kelasId = $kelas ? $kelas->id : null;

            if (!$kelasId) {
                $this->skippedRows[] = "Baris {$rowNum}: Kelas '{$namaKelas}' tidak valid.";
                continue;
            }

            // Parse Tanggal Lahir
            try {
                if (is_numeric($tglLahirRaw)) {
                    // Excel timestamp number
                    $tglLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tglLahirRaw);
                    $tglLahirCarbon = Carbon::instance($tglLahir);
                } else {
                    $tglLahirCarbon = Carbon::parse($tglLahirRaw);
                }
            } catch (\Exception $e) {
                $tglLahirCarbon = Carbon::create(2008, 1, 1);
            }

            $jk = in_array($jkRaw, ['L', 'P']) ? $jkRaw : 'L';
            $defaultPassword = $tglLahirCarbon->format('dmY');

            // Buat User
            $user = User::create([
                'username' => $nis,
                'password' => Hash::make($defaultPassword),
                'role' => 'siswa',
                'is_first_login' => true,
                'is_active' => true,
            ]);

            // Buat Siswa
            Siswa::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'nama' => $nama,
                'kelas_id' => $kelasId,
                'tanggal_lahir' => $tglLahirCarbon->format('Y-m-d'),
                'jenis_kelamin' => $jk,
                'alamat' => $alamat,
                'is_deleted' => false,
            ]);

            $this->importedCount++;
        }

        if ($this->importedCount > 0) {
            LogAktivitas::log('import_siswa', "Berhasil meng-import {$this->importedCount} data siswa baru.");
        }
    }
}
