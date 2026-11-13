<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    public function run(): void
    {
        $opdList = [
            ['kode_opd' => 'DISKOMINFO',  'nama_opd' => 'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat'],
            ['kode_opd' => 'BAPPEDA',     'nama_opd' => 'Badan Perencanaan Pembangunan Daerah (Bappeda) Prov. Jawa Barat'],
            ['kode_opd' => 'DISDIK',      'nama_opd' => 'Dinas Pendidikan Prov. Jawa Barat'],
            ['kode_opd' => 'DINKES',      'nama_opd' => 'Dinas Kesehatan Prov. Jawa Barat'],
            ['kode_opd' => 'DPUPR',       'nama_opd' => 'Dinas Pekerjaan Umum dan Penataan Ruang (DPUPR) Prov. Jawa Barat'],
            ['kode_opd' => 'DISHUB',      'nama_opd' => 'Dinas Perhubungan Prov. Jawa Barat'],
            ['kode_opd' => 'DINSOS',      'nama_opd' => 'Dinas Sosial Prov. Jawa Barat'],
            ['kode_opd' => 'BKD',         'nama_opd' => 'Badan Kepegawaian Daerah (BKD) Prov. Jawa Barat'],
            ['kode_opd' => 'BPKAD',       'nama_opd' => 'Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD) Prov. Jawa Barat'],
            ['kode_opd' => 'DPMPTSP',     'nama_opd' => 'Dinas Penanaman Modal dan PTSP Prov. Jawa Barat'],
            ['kode_opd' => 'DLH',         'nama_opd' => 'Dinas Lingkungan Hidup Prov. Jawa Barat'],
            ['kode_opd' => 'DISPARBUD',   'nama_opd' => 'Dinas Pariwisata dan Kebudayaan Prov. Jawa Barat'],
            ['kode_opd' => 'DISTANPANG',  'nama_opd' => 'Dinas Pertanian dan Ketahanan Pangan Prov. Jawa Barat'],
            ['kode_opd' => 'DISPERINDAG', 'nama_opd' => 'Dinas Perindustrian dan Perdagangan Prov. Jawa Barat'],
            ['kode_opd' => 'DISKOPUKM',   'nama_opd' => 'Dinas Koperasi dan Usaha Kecil Prov. Jawa Barat'],
            ['kode_opd' => 'DISNAKERTRANS','nama_opd' => 'Dinas Ketenagakerjaan dan Transmigrasi Prov. Jawa Barat'],
            ['kode_opd' => 'DP3A',        'nama_opd' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak Prov. Jawa Barat'],
            ['kode_opd' => 'DISPUSIPDA',  'nama_opd' => 'Dinas Perpustakaan dan Kearsipan Prov. Jawa Barat'],
            ['kode_opd' => 'BAKESBANGPOL','nama_opd' => 'Badan Kesatuan Bangsa dan Politik (Bakesbangpol) Prov. Jawa Barat'],
            ['kode_opd' => 'SETDA',       'nama_opd' => 'Sekretariat Daerah Prov. Jawa Barat'],
            ['kode_opd' => 'LAINNYA',     'nama_opd' => 'Lainnya'],
        ];

        foreach ($opdList as $data) {
            Opd::updateOrCreate(
                ['kode_opd' => $data['kode_opd']],
                ['nama_opd' => $data['nama_opd'], 'aktif' => true]
            );
        }
    }
}
