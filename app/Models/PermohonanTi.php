<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermohonanTi extends Model
{
    use HasFactory;

    protected $table = 'permohonan_tis';

    protected $fillable = [
        'no_rfc', 'user_id', 'pemohon', 'unit_kerja', 'perangkat_daerah',
        'nomor_kontak', 'email_dinas', 'tanggal_permohonan',
        'jenis_perubahan', 'perubahan_diharapkan', 'latar_belakang', 'rincian_perubahan',
        'risiko_tidak_dilakukan', 'kriteria_risiko', 'keterangan',
        'solusi_diharapkan', 'risiko_perubahan', 'alternatif_perubahan',
        'biaya_perubahan', 'waktu_perubahan',
        'nama_aplikasi', 'deskripsi_aplikasi', 'alamat_aplikasi', 'alamat_repository',
        'dokumen_pendukung_file', 'tanda_tangan_file',
        'status', 'prioritas', 'target_sla', 'catatan_revisi',
        'verifikator_id', 'verifikator_nama', 'verifikator_catatan', 'verifikator_rekomendasi',
        'verifikator_tanggal', 'verifikator_ttd',
        'pimpinan_id', 'pimpinan_nama', 'pimpinan_keputusan', 'pimpinan_catatan',
        'pimpinan_tanggal', 'pimpinan_ttd', 'assigned_to', 'target_deadline',
        'teknisi_id', 'teknisi_nama', 'telaah_teknis', 'catatan_implementasi',
        'tautan_perubahan', 'tgl_mulai_pengerjaan', 'tgl_selesai_pengerjaan',
        'nomor_bast', 'tanggal_bast', 'file_bast', 'catatan_bast',
        'agen_id', 'agen_nama', 'agen_ttd',
    ];

    protected $casts = [
        'jenis_perubahan' => 'array',
        'kriteria_risiko' => 'array',
        'dokumen_pendukung_file' => 'array',
        'tanggal_permohonan' => 'date',
        'verifikator_tanggal' => 'date',
        'pimpinan_tanggal' => 'date',
        'target_deadline' => 'date',
        'tgl_mulai_pengerjaan' => 'date',
        'tgl_selesai_pengerjaan' => 'date',
        'tanggal_bast' => 'date',
        'target_sla' => 'datetime',
    ];

    public function histories(): HasMany
    {
        return $this->hasMany(PermohonanTiHistory::class, 'permohonan_ti_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pemohonUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifikatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    public function pimpinanUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pimpinan_id');
    }

    public function teknisiUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function agenUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agen_id');
    }

    public static function generateNoRfc(): string
    {
        $year = now()->format('Y');
        $last = static::whereYear('created_at', $year)->count();
        return sprintf('RFC-%s-%04d', $year, $last + 1);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'perlu_revisi' => 'Perlu Revisi',
            'menunggu_keputusan_pimpinan' => 'Menunggu Keputusan Pimpinan',
            'disetujui' => 'Disetujui',
            'dalam_pengerjaan' => 'Dalam Pengerjaan',
            'menunggu_bast' => 'Menunggu BAST',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            'ditunda' => 'Ditunda',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'blue',
            'perlu_revisi' => 'orange',
            'menunggu_keputusan_pimpinan' => 'purple',
            'disetujui' => 'green',
            'dalam_pengerjaan' => 'cyan',
            'menunggu_bast' => 'indigo',
            'selesai' => 'emerald',
            'ditolak' => 'red',
            'ditunda' => 'yellow',
            default => 'slate',
        };
    }

    public function getPrioritasColorAttribute(): string
    {
        return match ($this->prioritas) {
            'P1 - Kritis' => 'red',
            'P2 - Tinggi' => 'orange',
            'P3 - Sedang' => 'yellow',
            'P4 - Rendah' => 'green',
            default => 'slate',
        };
    }

    public function addHistory(string $actorName, string $actorRole, string $statusBaru, string $keterangan, ?string $catatan = null, ?int $userId = null): void
    {
        $this->histories()->create([
            'user_id' => $userId ?? auth()->id(),
            'actor_name' => $actorName,
            'actor_role' => $actorRole,
            'status_sebelumnya' => $this->status,
            'status_baru' => $statusBaru,
            'keterangan' => $keterangan,
            'catatan' => $catatan,
        ]);
    }
}

