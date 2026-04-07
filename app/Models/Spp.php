<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    protected $table = 'spp';

    protected $fillable = [
        'kelas_id',
        'nominal',
        'tahun_ajaran',
        'berlaku_mulai',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'berlaku_mulai' => 'date',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }
}
