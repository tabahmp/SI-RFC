<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenBast extends Model
{
    protected $table = 'dokumen_bast';

    public $timestamps = false;

    const CREATED_AT = 'dibuat_pada';

    protected $fillable = [
        'rfc_id',
        'nomor_dokumen',
        'path_file',
        'tanda_tangan_teknisi',
        'catatan',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    public function rfc(): BelongsTo
    {
        return $this->belongsTo(PermohonanRfc::class, 'rfc_id');
    }
}
