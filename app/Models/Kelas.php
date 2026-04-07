<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'tahun_ajaran',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function activeSiswa()
    {
        return $this->hasMany(Siswa::class)->where('is_deleted', false);
    }

    public function spp()
    {
        return $this->hasMany(Spp::class);
    }

    public function latestSpp()
    {
        return $this->hasOne(Spp::class)->latestOfMany();
    }
}
