<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanTiHistory extends Model
{
    protected $table = 'permohonan_ti_histories';

    protected $fillable = [
        'permohonan_ti_id', 'actor_name', 'actor_role',
        'status_sebelumnya', 'status_baru', 'keterangan', 'catatan',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(PermohonanTi::class, 'permohonan_ti_id');
    }

    public function getActorRoleLabelAttribute(): string
    {
        return match ($this->actor_role) {
            'pemohon' => 'Pemohon',
            'verifikator' => 'Verifikator',
            'approver' => 'Penanggung Jawab',
            'teknisi' => 'Teknisi Pelaksana',
            'agen' => 'Agen Pelaksana',
            'admin' => 'Administrator',
            default => $this->actor_role,
        };
    }
}

