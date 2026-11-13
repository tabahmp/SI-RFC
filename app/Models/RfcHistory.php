<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RfcHistory extends Model
{
    protected $table = 'rfc_histories';

    public $timestamps = false;

    const CREATED_AT  = 'dibuat_pada';
    const UPDATED_AT  = 'diperbarui_pada';

    protected $fillable = [
        'rfc_id',
        'pengguna_id',
        'nama_aktor',
        'peran_aktor',
        'status_sebelumnya',
        'status_baru',
        'keterangan',
        'catatan',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    public function rfc(): BelongsTo
    {
        return $this->belongsTo(PermohonanRfc::class, 'rfc_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
