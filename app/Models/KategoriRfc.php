<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriRfc extends Model
{
    protected $table = 'kategori_rfc';

    public $timestamps = false;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function permohonanRfc(): HasMany
    {
        return $this->hasMany(PermohonanRfc::class, 'kategori_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
