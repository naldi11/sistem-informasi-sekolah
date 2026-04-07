<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Spp;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'keuangan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_first_login' => false,
            'is_active' => true,
        ]);
    }
}
