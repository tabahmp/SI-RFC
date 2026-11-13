<?php

namespace Database\Seeders;

use App\Models\KategoriRfc;
use Illuminate\Database\Seeder;

class KategoriRfcSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = [
            ['nama_kategori' => 'Jaringan',              'deskripsi' => 'Perubahan pada infrastruktur jaringan, router, switch, firewall, VPN, dan konektivitas'],
            ['nama_kategori' => 'Server',                'deskripsi' => 'Perubahan pada server fisik maupun virtual, OS, konfigurasi hardware dan kapasitas'],
            ['nama_kategori' => 'Aplikasi',              'deskripsi' => 'Perubahan pada aplikasi web, mobile, atau desktop yang dikelola Diskominfo'],
            ['nama_kategori' => 'Database',              'deskripsi' => 'Perubahan pada struktur database, migrasi data, optimasi query, atau backup'],
            ['nama_kategori' => 'Keamanan Informasi',    'deskripsi' => 'Perubahan terkait SMKI, sertifikat SSL/TLS, kebijakan keamanan, dan audit'],
            ['nama_kategori' => 'Infrastruktur',         'deskripsi' => 'Perubahan pada data center, storage, power, pendingin, dan fasilitas fisik TI'],
        ];

        foreach ($kategoriList as $data) {
            KategoriRfc::updateOrCreate(
                ['nama_kategori' => $data['nama_kategori']],
                ['deskripsi' => $data['deskripsi'], 'aktif' => true]
            );
        }
    }
}
