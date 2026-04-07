<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aksi',
        'detail',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($aksi, $detail = null, $userId = null)
    {
        return static::create([
            'user_id' => $userId ?? auth()->id(),
            'aksi' => $aksi,
            'detail' => $detail,
            'ip_address' => request()->ip(),
        ]);
    }
}
