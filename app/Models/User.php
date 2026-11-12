<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'nomor_kontak',
        'asal_opd',
        'role',
        'status_registrasi',
        'catatan_verifikasi',
        'diverifikasi_pada',
        'diverifikasi_oleh',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'diverifikasi_pada' => 'datetime',
    ];

    public function permohonanTis(): HasMany
    {
        return $this->hasMany(PermohonanTi::class, 'user_id');
    }

    public function verifikasiTis(): HasMany
    {
        return $this->hasMany(PermohonanTi::class, 'verifikator_id');
    }

    public function keputusanTis(): HasMany
    {
        return $this->hasMany(PermohonanTi::class, 'pimpinan_id');
    }

    public function tugasTeknisi(): HasMany
    {
        return $this->hasMany(PermohonanTi::class, 'teknisi_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(PermohonanTiHistory::class, 'user_id');
    }

    public function isPemohon(): bool
    {
        return $this->role === 'pemohon';
    }

    public function isVerifikator(): bool
    {
        return $this->role === 'verifikator';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function isVerified(): bool
    {
        return $this->status_registrasi === 'terverifikasi';
    }
}
