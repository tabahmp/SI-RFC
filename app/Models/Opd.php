<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opd extends Model
{
    protected $table = 'opd';

    public $timestamps = false;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'kode_opd',
        'nama_opd',
        'alamat',
        'telepon',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function pengguna(): HasMany
    {
        return $this->hasMany(User::class, 'opd_id');
    }

    public function permohonanRfc(): HasMany
    {
        return $this->hasMany(PermohonanRfc::class, 'opd_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
