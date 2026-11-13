<?php

namespace App\Http\Middleware;

use App\Models\Notifikasi;
use App\Models\PermohonanRfc;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),

            'auth' => [
                'user' => $user ? $this->formatUser($user) : null,
            ],

            'notifikasi_belum_dibaca' => $user
                ? Notifikasi::where('pengguna_id', $user->id)
                            ->where('sudah_dibaca', false)
                            ->count()
                : 0,

            'sidebar_counts' => $user ? $this->hitungSidebarCounts($user) : [
                'draf'               => 0,
                'antrean'            => 0,
                'menunggu_keputusan' => 0,
                'tugas_saya'         => 0,
                'akun_tertunda'      => 0,
            ],

            'flash' => [
                'sukses' => $request->session()->get('sukses') ?? $request->session()->get('success'),
                'error'  => $request->session()->get('error'),
                'info'   => $request->session()->get('info'),
            ],
        ];
    }

    private function formatUser(User $user): array
    {
        // Eager load peran & opd jika belum di-load
        if (! $user->relationLoaded('peran')) {
            $user->load('peran', 'opd');
        }

        return [
            'id'              => $user->id,
            'nama_lengkap'    => $user->nama_lengkap ?: $user->name,
            'email'           => $user->email,
            'nip'             => $user->nip,
            'nomor_kontak'    => $user->nomor_kontak,
            'peran'           => $user->peran?->nama_peran ?? $user->role ?? 'pemohon',
            'opd'             => $user->opd?->nama_opd ?? $user->asal_opd ?? '',
            'opd_id'          => $user->opd_id,
            'status_aktivasi' => $user->status_aktivasi_efektif,
            'inisial'         => $user->inisials,
        ];
    }

    private function hitungSidebarCounts(User $user): array
    {
        $namaPeran = $user->peran?->nama_peran ?? $user->role ?? '';

        $counts = [
            'draf'               => 0,
            'antrean'            => 0,
            'menunggu_keputusan' => 0,
            'tugas_saya'         => 0,
            'akun_tertunda'      => 0,
        ];

        try {
            switch ($namaPeran) {
                case 'pemohon':
                    $counts['draf']   = PermohonanRfc::where('pengguna_id', $user->id)
                                                      ->where('status_terkini', 'draf')
                                                      ->count();
                    break;

                case 'verifikator':
                    $counts['antrean'] = PermohonanRfc::where('status_terkini', 'diajukan')->count();
                    break;

                case 'pimpinan':
                    $counts['menunggu_keputusan'] = PermohonanRfc::where('status_terkini', 'menunggu_persetujuan')->count();
                    break;

                case 'teknisi':
                    $counts['tugas_saya'] = \App\Models\EksekusiRfc::where('teknisi_id', $user->id)
                                                                     ->where('status_eksekusi', 'proses_pengerjaan')
                                                                     ->count();
                    break;

                case 'admin':
                    $counts['akun_tertunda'] = User::where('status_aktivasi', 'tertunda')->count();
                    break;
            }
        } catch (\Exception $e) {
            // Jika tabel belum ada (saat migrate), kembalikan 0 semua
        }

        return $counts;
    }
}
