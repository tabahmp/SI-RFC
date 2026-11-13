<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    public $timestamps = false;

    const CREATED_AT = 'dibuat_pada';

    protected $fillable = [
        'pengguna_id',
        'judul',
        'pesan',
        'sudah_dibaca',
        'url_tujuan',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'dibuat_pada'  => 'datetime',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function scopeBelumDibaca($query)
    {
        return $query->where('sudah_dibaca', false);
    }
}
