<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiSandbox extends Model
{
    protected $table = 'transaksi_sandbox';

    protected $fillable = [
        'order_id',
        'siswa_id',
        'total_nominal',
        'metode_pembayaran',
        'tipe',
        'kode_pembayaran',
        'status',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'total_nominal' => 'decimal:2',
            'expired_at' => 'datetime',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'transaksi_sandbox_id');
    }
}
