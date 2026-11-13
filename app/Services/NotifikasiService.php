<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    /**
     * Kirim notifikasi ke satu pengguna.
     */
    public function kirim(int $penggunaId, string $judul, string $pesan, ?string $urlTujuan = null): Notifikasi
    {
        return Notifikasi::create([
            'pengguna_id'  => $penggunaId,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'sudah_dibaca' => false,
            'url_tujuan'   => $urlTujuan,
        ]);
    }

    /**
     * Kirim notifikasi ke semua pengguna dengan peran tertentu.
     */
    public function kirimKePeran(string $namaPeran, string $judul, string $pesan, ?string $urlTujuan = null): void
    {
        $pengguna = User::whereHas('peran', fn ($q) => $q->where('nama_peran', $namaPeran))
            ->where('status_aktivasi', 'aktif')
            ->get();

        foreach ($pengguna as $user) {
            $this->kirim($user->id, $judul, $pesan, $urlTujuan);
        }
    }

    /**
     * Hitung jumlah notifikasi belum dibaca untuk satu pengguna.
     */
    public function hitungBelumDibaca(int $penggunaId): int
    {
        return Notifikasi::where('pengguna_id', $penggunaId)
            ->where('sudah_dibaca', false)
            ->count();
    }
}
