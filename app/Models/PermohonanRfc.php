<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PermohonanRfc extends Model
{
    use HasFactory;

    protected $table = 'permohonan_rfc';

    protected $fillable = [
        'kode_pelacakan',
        'pengguna_id',
        'opd_id',
        'kategori_id',
        // Seksi 1
        'nama_pemohon',
        'nip',
        'unit_kerja',
        'nomor_surat',
        'nomor_kontak',
        'email_dinas',
        'tanggal_permohonan',
        // Seksi 2
        'jenis_perubahan',
        // Seksi 3
        'judul_perubahan',
        'deskripsi_perubahan',
        'latar_belakang',
        'rincian_perubahan',
        // Seksi 4
        'risiko_tidak_dilakukan',
        'kriteria_risiko',
        'keterangan_risiko',
        'urgensi',
        'rencana_pemulihan',
        // Seksi 5
        'solusi_diharapkan',
        'risiko_perubahan',
        'alternatif_perubahan',
        'biaya_perubahan',
        'waktu_perubahan',
        // Seksi 6
        'nama_aplikasi',
        'deskripsi_aplikasi',
        'alamat_aplikasi',
        'alamat_repository',
        // Seksi 7
        'path_lampiran',
        'tanda_tangan_pemohon',
        // Status
        'status_terkini',
        'target_sla',
    ];

    protected $casts = [
        'jenis_perubahan'    => 'array',
        'path_lampiran'      => 'array',
        'tanggal_permohonan' => 'date',
        'target_sla'         => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────────────────────

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriRfc::class, 'kategori_id');
    }

    public function revisi(): HasMany
    {
        return $this->hasMany(RevisiRfc::class, 'rfc_id')->orderByDesc('dibuat_pada');
    }

    public function persetujuan(): HasOne
    {
        return $this->hasOne(PersetujuanRfc::class, 'rfc_id')->latestOfMany('diputuskan_pada');
    }

    public function eksekusi(): HasMany
    {
        return $this->hasMany(EksekusiRfc::class, 'rfc_id')->orderByDesc('dimulai_pada');
    }

    public function eksekusiAktif(): HasOne
    {
        return $this->hasOne(EksekusiRfc::class, 'rfc_id')->latestOfMany('dimulai_pada');
    }

    public function dokumenBast(): HasOne
    {
        return $this->hasOne(DokumenBast::class, 'rfc_id')->latestOfMany('dibuat_pada');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(RfcHistory::class, 'rfc_id')->orderByDesc('dibuat_pada');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(RfcComment::class, 'rfc_id')->orderBy('created_at');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getStatusTerkiniLabelAttribute(): string
    {
        return match ($this->status_terkini) {
            'draf'                   => 'Draf',
            'diajukan'               => 'Diajukan',
            'perlu_revisi'           => 'Perlu Revisi',
            'ditolak_verifikator'    => 'Ditolak Verifikator',
            'menunggu_persetujuan'   => 'Menunggu Persetujuan',
            'ditolak_pimpinan'       => 'Ditolak Pimpinan',
            'ditunda'                => 'Ditunda',
            'disetujui'              => 'Disetujui',
            'sedang_dikerjakan'      => 'Sedang Dikerjakan',
            'selesai'                => 'Selesai',
            default                  => ucfirst(str_replace('_', ' ', $this->status_terkini)),
        };
    }

    public function getWarnaBadgeAttribute(): string
    {
        return match ($this->status_terkini) {
            'draf'                 => 'gray',
            'diajukan'             => 'blue',
            'perlu_revisi'         => 'orange',
            'ditolak_verifikator'  => 'red',
            'menunggu_persetujuan' => 'purple',
            'ditolak_pimpinan'     => 'red',
            'ditunda'              => 'yellow',
            'disetujui'            => 'green',
            'sedang_dikerjakan'    => 'cyan',
            'selesai'              => 'emerald',
            default                => 'slate',
        };
    }

    public function getUrgensiLabelAttribute(): string
    {
        return match ($this->urgensi) {
            'rendah'  => 'Rendah',
            'sedang'  => 'Sedang',
            'tinggi'  => 'Tinggi',
            'kritis'  => 'Kritis',
            default   => ucfirst($this->urgensi ?? ''),
        };
    }

    public function getUrgensiWarnaAttribute(): string
    {
        return match ($this->urgensi) {
            'rendah' => 'green',
            'sedang' => 'yellow',
            'tinggi' => 'orange',
            'kritis' => 'red',
            default  => 'slate',
        };
    }

    // ─── Static Helpers ──────────────────────────────────────────────────────

    public static function generateKodePelacakan(): string
    {
        return \DB::transaction(function () {
            $tahun = now()->format('Y');
            $bulan = now()->format('m');
            
            // Lock table untuk prevent race condition
            $urutan = static::lockForUpdate()
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->count() + 1;
            
            return sprintf('RFC-%s-%s-%05d', $tahun, $bulan, $urutan);
        });
    }

    // ─── Method Helper ───────────────────────────────────────────────────────

    public function tambahHistory(
        ?User $pengguna,
        string $namaAktor,
        string $peranAktor,
        string $statusBaru,
        string $keterangan,
        ?string $catatan = null,
        ?string $statusSebelumnya = null
    ): RfcHistory {
        return $this->histories()->create([
            'pengguna_id'      => $pengguna?->id,
            'nama_aktor'       => $namaAktor,
            'peran_aktor'      => $peranAktor,
            'status_sebelumnya'=> $statusSebelumnya ?? $this->status_terkini,
            'status_baru'      => $statusBaru,
            'keterangan'       => $keterangan,
            'catatan'          => $catatan,
        ]);
    }

    public function hitungSlaDalamJam(): int
    {
        return match ($this->urgensi) {
            'kritis' => 4,
            'tinggi' => 24,
            'sedang' => 72,
            'rendah' => 120,
            default  => 72,
        };
    }

    public function validasiTransisi(string $ke): void
    {
        $transisiValid = [
            'draf'                 => ['diajukan'],
            'diajukan'             => ['perlu_revisi', 'ditolak_verifikator', 'menunggu_persetujuan'],
            'perlu_revisi'         => ['diajukan'],
            'menunggu_persetujuan' => ['ditolak_pimpinan', 'ditunda', 'disetujui'],
            'ditunda'              => ['ditolak_pimpinan', 'disetujui', 'menunggu_persetujuan'],
            'disetujui'            => ['sedang_dikerjakan', 'selesai'],
            'sedang_dikerjakan'    => ['selesai'],
        ];

        $dari = $this->status_terkini;
        if (! in_array($ke, $transisiValid[$dari] ?? [])) {
            throw new \InvalidArgumentException(
                "Transisi status dari '{$dari}' ke '{$ke}' tidak diizinkan."
            );
        }
    }
}
