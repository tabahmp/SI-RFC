<?php

namespace Database\Seeders;

use App\Models\Peran;
use Illuminate\Database\Seeder;

class PeranSeeder extends Seeder
{
    public function run(): void
    {
        $peranList = [
            ['nama_peran' => 'admin',       'deskripsi' => 'Administrator sistem — mengelola master data, akun, dan pengaturan sistem'],
            ['nama_peran' => 'pemohon',      'deskripsi' => 'Pemohon dari OPD — mengajukan Request for Change dan memantau progres'],
            ['nama_peran' => 'verifikator',  'deskripsi' => 'Verifikator APTIKA — melakukan telaah awal RFC dan evaluasi dampak teknis'],
            ['nama_peran' => 'pimpinan',     'deskripsi' => 'Pimpinan — memberikan keputusan akhir atas RFC yang telah diverifikasi'],
            ['nama_peran' => 'teknisi',      'deskripsi' => 'Tim Teknisi — mengeksekusi perubahan TI dan menerbitkan dokumen BAST'],
        ];

        foreach ($peranList as $data) {
            Peran::updateOrCreate(
                ['nama_peran' => $data['nama_peran']],
                ['deskripsi' => $data['deskripsi']]
            );
        }
    }
}
