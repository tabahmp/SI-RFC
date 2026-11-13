<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        // Lama (backward-compatible)
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
        // Baru (3NF)
        'nama_lengkap',
        'peran_id',
        'opd_id',
        'status_aktivasi',
        'email_diverifikasi_pada',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'       => 'datetime',
        'password'                => 'hashed',
        'diverifikasi_pada'       => 'datetime',
        'email_diverifikasi_pada' => 'datetime',
    ];

    // ─── Relasi 3NF ──────────────────────────────────────────────────────────

    public function peran(): BelongsTo
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }

    // ─── Relasi RFC ──────────────────────────────────────────────────────────

    public function permohonanRfc(): HasMany
    {
        return $this->hasMany(PermohonanRfc::class, 'pengguna_id');
    }

    public function revisiRfc(): HasMany
    {
        return $this->hasMany(RevisiRfc::class, 'verifikator_id');
    }

    public function persetujuanRfc(): HasMany
    {
        return $this->hasMany(PersetujuanRfc::class, 'pimpinan_id');
    }

    public function eksekusiRfc(): HasMany
    {
        return $this->hasMany(EksekusiRfc::class, 'teknisi_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'pengguna_id')->orderByDesc('dibuat_pada');
    }

    public function rfcHistories(): HasMany
    {
        return $this->hasMany(RfcHistory::class, 'pengguna_id');
    }

    // ─── Helper Methods ──────────────────────────────────────────────────────

    public function getNamaLengkapAttribute($value): string
    {
        // Fallback ke kolom 'name' lama jika nama_lengkap kosong
        return $value ?: ($this->attributes['name'] ?? '');
    }

    public function getNamaPeranAttribute(): string
    {
        return $this->peran?->nama_peran ?? $this->role ?? '';
    }

    public function getInisialsAttribute(): string
    {
        $nama = $this->nama_lengkap ?: $this->name;
        $parts = preg_split('/\s+/', trim($nama));
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }
        return strtoupper(substr($nama, 0, 2));
    }

    public function isAdmin(): bool
    {
        return ($this->peran?->nama_peran ?? $this->role) === 'admin';
    }

    public function isPemohon(): bool
    {
        return ($this->peran?->nama_peran ?? $this->role) === 'pemohon';
    }

    public function isVerifikator(): bool
    {
        return ($this->peran?->nama_peran ?? $this->role) === 'verifikator';
    }

    public function isPimpinan(): bool
    {
        return ($this->peran?->nama_peran ?? $this->role) === 'pimpinan';
    }

    public function isTeknisi(): bool
    {
        return ($this->peran?->nama_peran ?? $this->role) === 'teknisi';
    }

    public function isAktif(): bool
    {
        // Cek status_aktivasi baru, fallback ke status_registrasi lama
        if ($this->status_aktivasi) {
            return $this->status_aktivasi === 'aktif';
        }
        return $this->status_registrasi === 'terverifikasi';
    }

    public function getStatusAktivasiEfektifAttribute(): string
    {
        if ($this->status_aktivasi) {
            return $this->status_aktivasi;
        }
        return match ($this->status_registrasi ?? '') {
            'terverifikasi'       => 'aktif',
            'menunggu_verifikasi' => 'tertunda',
            'ditolak'             => 'nonaktif',
            default               => 'tertunda',
        };
    }
}
