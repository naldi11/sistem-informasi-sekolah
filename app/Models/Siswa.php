<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'kelas_id',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_deleted' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }

    public function getDefaultPassword()
    {
        return $this->tanggal_lahir->format('dmY');
    }
}
