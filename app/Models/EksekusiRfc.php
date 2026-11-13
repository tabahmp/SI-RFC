<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EksekusiRfc extends Model
{
    protected $table = 'eksekusi_rfc';

    public $timestamps = false;

    const CREATED_AT  = 'dibuat_pada';
    const UPDATED_AT  = 'diperbarui_pada';

    protected $fillable = [
        'rfc_id',
        'teknisi_id',
        'catatan_eksekusi',
        'uraian_pekerjaan',
        'hasil_pengerjaan',
        'path_lampiran_hasil',
        'status_eksekusi',
        'dimulai_pada',
        'selesai_pada',
    ];

    protected $casts = [
        'dimulai_pada'    => 'datetime',
        'selesai_pada'    => 'datetime',
        'dibuat_pada'     => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    public function rfc(): BelongsTo
    {
        return $this->belongsTo(PermohonanRfc::class, 'rfc_id');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
