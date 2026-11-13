<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersetujuanRfc extends Model
{
    protected $table = 'persetujuan_rfc';

    public $timestamps = false;

    const CREATED_AT = 'diputuskan_pada';

    protected $fillable = [
        'rfc_id',
        'pimpinan_id',
        'keputusan',
        'catatan_keputusan',
        'diputuskan_pada',
    ];

    protected $casts = [
        'diputuskan_pada' => 'datetime',
    ];

    public function rfc(): BelongsTo
    {
        return $this->belongsTo(PermohonanRfc::class, 'rfc_id');
    }

    public function pimpinan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pimpinan_id');
    }
}
