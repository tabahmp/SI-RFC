<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisiRfc extends Model
{
    protected $table = 'revisi_rfc';

    public $timestamps = false;

    const CREATED_AT = 'dibuat_pada';

    protected $fillable = [
        'rfc_id',
        'verifikator_id',
        'catatan_revisi',
        'status_revisi',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    public function rfc(): BelongsTo
    {
        return $this->belongsTo(PermohonanRfc::class, 'rfc_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
