<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('Password@123');

        $users = [
            [
                'email' => 'pemohon@jabarprov.go.id',
                'name' => 'Ahmad Fauzi, S.Kom',
                'password' => $password,
                'nip' => '198904122014031002',
                'nomor_kontak' => '081234567891',
                'asal_opd' => 'Badan Perencanaan Pembangunan Daerah (Bappeda) Prov. Jawa Barat',
                'role' => 'pemohon',
                'status_registrasi' => 'terverifikasi',
                'diverifikasi_pada' => now(),
                'diverifikasi_oleh' => 'Raka Pratama',
            ],
            [
                'email' => 'verifikator@jabarprov.go.id',
                'name' => 'Raka Pratama, S.STP',
                'password' => $password,
                'nip' => '199203152016021004',
                'nomor_kontak' => '081234567892',
                'asal_opd' => 'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat',
                'role' => 'verifikator',
                'status_registrasi' => 'terverifikasi',
                'diverifikasi_pada' => now(),
                'diverifikasi_oleh' => 'Sistem Administrator',
            ],
            [
                'email' => 'pimpinan@jabarprov.go.id',
                'name' => 'Dr. H. Hendra Setiawan, M.Si',
                'password' => $password,
                'nip' => '197508211999031001',
                'nomor_kontak' => '081234567893',
                'asal_opd' => 'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat',
                'role' => 'pimpinan',
                'status_registrasi' => 'terverifikasi',
                'diverifikasi_pada' => now(),
                'diverifikasi_oleh' => 'Sistem Administrator',
            ],
            [
                'email' => 'teknisi@jabarprov.go.id',
                'name' => 'Teguh Wicaksono, S.T',
                'password' => $password,
                'nip' => '199506182019031003',
                'nomor_kontak' => '081234567894',
                'asal_opd' => 'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat',
                'role' => 'teknisi',
                'status_registrasi' => 'terverifikasi',
                'diverifikasi_pada' => now(),
                'diverifikasi_oleh' => 'Sistem Administrator',
            ],
            [
                'email' => 'dadan.perhubungan@jabarprov.go.id',
                'name' => 'Dadan Hermawan, S.STP',
                'password' => $password,
                'nip' => '198705122010011003',
                'nomor_kontak' => '082112345678',
                'asal_opd' => 'Dinas Perhubungan Prov. Jawa Barat',
                'role' => 'pemohon',
                'status_registrasi' => 'menunggu_verifikasi',
                'diverifikasi_pada' => null,
                'diverifikasi_oleh' => null,
            ],
            [
                'email' => 'siti.kesehatan@jabarprov.go.id',
                'name' => 'dr. Siti Rahmawati, M.Kes',
                'password' => $password,
                'nip' => '199011042015032001',
                'nomor_kontak' => '081398765432',
                'asal_opd' => 'Dinas Kesehatan Prov. Jawa Barat',
                'role' => 'pemohon',
                'status_registrasi' => 'menunggu_verifikasi',
                'diverifikasi_pada' => null,
                'diverifikasi_oleh' => null,
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

