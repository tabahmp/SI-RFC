<?php

namespace Database\Seeders;

use App\Models\Opd;
use App\Models\Peran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID peran & OPD dari database
        $peranAdmin      = Peran::where('nama_peran', 'admin')->first();
        $peranPemohon    = Peran::where('nama_peran', 'pemohon')->first();
        $peranVerif      = Peran::where('nama_peran', 'verifikator')->first();
        $peranPimpinan   = Peran::where('nama_peran', 'pimpinan')->first();
        $peranTeknisi    = Peran::where('nama_peran', 'teknisi')->first();

        $opdDiskominfo   = Opd::where('kode_opd', 'DISKOMINFO')->first();
        $opdBappeda      = Opd::where('kode_opd', 'BAPPEDA')->first();
        $opdDishub       = Opd::where('kode_opd', 'DISHUB')->first();
        $opdDinkes       = Opd::where('kode_opd', 'DINKES')->first();

        $password = Hash::make('Password@123');
        $now      = now();

        $users = [
            // ── Admin ───────────────────────────────────────────────────────
            [
                'email'                 => 'admin@diskominfo.jabarprov.go.id',
                'name'                  => 'Administrator Sistem',
                'nama_lengkap'          => 'Administrator Sistem',
                'password'              => $password,
                'peran_id'              => $peranAdmin?->id,
                'opd_id'                => $opdDiskominfo?->id,
                'role'                  => 'admin',
                'status_aktivasi'       => 'aktif',
                'status_registrasi'     => 'terverifikasi',
                'email_diverifikasi_pada'=> $now,
            ],
            // ── Pemohon (Bappeda) ────────────────────────────────────────────
            [
                'email'                 => 'pemohon@jabarprov.go.id',
                'name'                  => 'Ahmad Fauzi, S.Kom',
                'nama_lengkap'          => 'Ahmad Fauzi, S.Kom',
                'password'              => $password,
                'nip'                   => '198904122014031002',
                'nomor_kontak'          => '081234567891',
                'asal_opd'              => 'Badan Perencanaan Pembangunan Daerah (Bappeda) Prov. Jawa Barat',
                'peran_id'              => $peranPemohon?->id,
                'opd_id'                => $opdBappeda?->id,
                'role'                  => 'pemohon',
                'status_aktivasi'       => 'aktif',
                'status_registrasi'     => 'terverifikasi',
                'email_diverifikasi_pada'=> $now,
                'diverifikasi_pada'     => $now,
                'diverifikasi_oleh'     => 'Administrator Sistem',
            ],
            // ── Verifikator ──────────────────────────────────────────────────
            [
                'email'                 => 'verifikator@jabarprov.go.id',
                'name'                  => 'Raka Pratama, S.STP',
                'nama_lengkap'          => 'Raka Pratama, S.STP',
                'password'              => $password,
                'nip'                   => '199203152016021004',
                'nomor_kontak'          => '081234567892',
                'asal_opd'              => 'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat',
                'peran_id'              => $peranVerif?->id,
                'opd_id'                => $opdDiskominfo?->id,
                'role'                  => 'verifikator',
                'status_aktivasi'       => 'aktif',
                'status_registrasi'     => 'terverifikasi',
                'email_diverifikasi_pada'=> $now,
                'diverifikasi_pada'     => $now,
                'diverifikasi_oleh'     => 'Administrator Sistem',
            ],
            // ── Pimpinan ─────────────────────────────────────────────────────
            [
                'email'                 => 'pimpinan@jabarprov.go.id',
                'name'                  => 'Dr. H. Hendra Setiawan, M.Si',
                'nama_lengkap'          => 'Dr. H. Hendra Setiawan, M.Si',
                'password'              => $password,
                'nip'                   => '197508211999031001',
                'nomor_kontak'          => '081234567893',
                'asal_opd'              => 'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat',
                'peran_id'              => $peranPimpinan?->id,
                'opd_id'                => $opdDiskominfo?->id,
                'role'                  => 'pimpinan',
                'status_aktivasi'       => 'aktif',
                'status_registrasi'     => 'terverifikasi',
                'email_diverifikasi_pada'=> $now,
                'diverifikasi_pada'     => $now,
                'diverifikasi_oleh'     => 'Administrator Sistem',
            ],
            // ── Teknisi ──────────────────────────────────────────────────────
            [
                'email'                 => 'teknisi@jabarprov.go.id',
                'name'                  => 'Teguh Wicaksono, S.T',
                'nama_lengkap'          => 'Teguh Wicaksono, S.T',
                'password'              => $password,
                'nip'                   => '199506182019031003',
                'nomor_kontak'          => '081234567894',
                'asal_opd'              => 'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat',
                'peran_id'              => $peranTeknisi?->id,
                'opd_id'                => $opdDiskominfo?->id,
                'role'                  => 'teknisi',
                'status_aktivasi'       => 'aktif',
                'status_registrasi'     => 'terverifikasi',
                'email_diverifikasi_pada'=> $now,
                'diverifikasi_pada'     => $now,
                'diverifikasi_oleh'     => 'Administrator Sistem',
            ],
            // ── Pemohon menunggu aktivasi (untuk testing) ────────────────────
            [
                'email'                 => 'dadan.perhubungan@jabarprov.go.id',
                'name'                  => 'Dadan Hermawan, S.STP',
                'nama_lengkap'          => 'Dadan Hermawan, S.STP',
                'password'              => $password,
                'nip'                   => '198705122010011003',
                'nomor_kontak'          => '082112345678',
                'asal_opd'              => 'Dinas Perhubungan Prov. Jawa Barat',
                'peran_id'              => $peranPemohon?->id,
                'opd_id'                => $opdDishub?->id,
                'role'                  => 'pemohon',
                'status_aktivasi'       => 'tertunda',
                'status_registrasi'     => 'menunggu_verifikasi',
            ],
            [
                'email'                 => 'siti.kesehatan@jabarprov.go.id',
                'name'                  => 'dr. Siti Rahmawati, M.Kes',
                'nama_lengkap'          => 'dr. Siti Rahmawati, M.Kes',
                'password'              => $password,
                'nip'                   => '199011042015032001',
                'nomor_kontak'          => '081398765432',
                'asal_opd'              => 'Dinas Kesehatan Prov. Jawa Barat',
                'peran_id'              => $peranPemohon?->id,
                'opd_id'                => $opdDinkes?->id,
                'role'                  => 'pemohon',
                'status_aktivasi'       => 'tertunda',
                'status_registrasi'     => 'menunggu_verifikasi',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
