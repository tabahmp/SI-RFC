<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peran extends Model
{
    protected $table = 'peran';

    public $timestamps = false;

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama_peran',
        'deskripsi',
    ];

    public function pengguna(): HasMany
    {
        return $this->hasMany(User::class, 'peran_id');
    }
}
