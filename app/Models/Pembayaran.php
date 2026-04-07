<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'transaksi_sandbox_id',
        'file_bukti',
        'tanggal_upload',
        'tanggal_verifikasi',
        'verified_by',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_upload' => 'datetime',
            'tanggal_verifikasi' => 'datetime',
        ];
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function transaksiSandbox()
    {
        return $this->belongsTo(TransaksiSandbox::class, 'transaksi_sandbox_id');
    }
}
