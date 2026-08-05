<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'nama',
        'kode',
        'kategori',
        'nomor_rekening',
        'pemilik_rekening',
        'instruksi',
        'butuh_bukti',
        'is_active',
    ];

    protected $casts = [
        'butuh_bukti' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
