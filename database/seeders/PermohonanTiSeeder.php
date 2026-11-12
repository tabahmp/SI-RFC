<?php

namespace Database\Seeders;

use App\Models\PermohonanTi;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermohonanTiSeeder extends Seeder
{
    public function run(): void
    {
        $pemohon = User::where('email', 'pemohon@jabarprov.go.id')->first();
        $verifikator = User::where('email', 'verifikator@jabarprov.go.id')->first();
        $pimpinan = User::where('email', 'pimpinan@jabarprov.go.id')->first();
        $teknisi = User::where('email', 'teknisi@jabarprov.go.id')->first();

        // 1. RFC-202506-0142: Satu Data Jabar (P1 - Menunggu Verifikasi)
        $t1 = PermohonanTi::updateOrCreate(
            ['no_rfc' => 'RFC-202506-0142'],
            [
                'user_id' => $pemohon?->id,
                'pemohon' => 'Ahmad Fauzi, S.Kom (NIP. 198904122014031002)',
                'unit_kerja' => 'Seksi Integrasi Layanan Publik',
                'perangkat_daerah' => 'Badan Perencanaan Pembangunan Daerah (Bappeda) Prov. Jawa Barat',
                'nomor_kontak' => '0812-3456-7891 / ND-042/BAPPEDA/2025',
                'email_dinas' => 'pemohon@jabarprov.go.id',
                'tanggal_permohonan' => '2025-06-18',
                'jenis_perubahan' => ['Peningkatan Sistem', 'Perbaikan Bug'],
                'perubahan_diharapkan' => 'Optimasi sinkronisasi data lintas OPD untuk memastikan konsistensi dataset Satu Data Jabar.',
                'latar_belakang' => 'Lonjakan transaksi data lintas instansi menyebabkan latensi sinkronisasi pada jam sibuk.',
                'rincian_perubahan' => "1. Refactoring worker queue Redis\n2. Optimasi pipeline ETL PostgreSQL\n3. Rate limiting API per OPD",
                'risiko_tidak_dilakukan' => 'Ketidaksinkronan data geospasial dan statistik daerah untuk pengambilan keputusan pimpinan.',
                'kriteria_risiko' => ['Sangat Berat'],
                'keterangan' => 'SLA Kritis P1 (Sisa tanggap ≤ 4 jam).',
                'solusi_diharapkan' => 'Pemisahan thread sinkronisasi realtime dan batching terjadwal malam hari.',
                'risiko_perubahan' => 'Maintenance window 20 menit.',
                'alternatif_perubahan' => 'Scale-out instance background worker.',
                'biaya_perubahan' => 'Rp 0 (Fasilitasi internal)',
                'waktu_perubahan' => '2 Hari Kerja',
                'nama_aplikasi' => 'Satu Data Jabar',
                'alamat_aplikasi' => 'https://satudata.jabarprov.go.id',
                'alamat_repository' => 'https://gitlab.jabarprov.go.id/data/satudata-sync',
                'status' => 'menunggu_verifikasi',
                'prioritas' => 'P1 - Kritis',
                'target_sla' => '2025-06-18 16:00:00',
            ]
        );
        $t1->histories()->delete();
        $t1->addHistory('Ahmad Fauzi, S.Kom', 'pemohon', 'menunggu_verifikasi', 'Permohonan RFC diajukan dengan prioritas P1', null, $pemohon?->id);

        // 2. RFC-202506-0138: Sistem Perizinan Terpadu (P2 - Dalam Pengerjaan)
        $t2 = PermohonanTi::updateOrCreate(
            ['no_rfc' => 'RFC-202506-0138'],
            [
                'user_id' => $pemohon?->id,
                'verifikator_id' => $verifikator?->id,
                'pimpinan_id' => $pimpinan?->id,
                'teknisi_id' => $teknisi?->id,
                'pemohon' => 'Budi Santoso, S.STP',
                'unit_kerja' => 'Bidang Pelayanan Perizinan',
                'perangkat_daerah' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Provinsi Jawa Barat',
                'nomor_kontak' => '0813-8888-9999 / 503/412/DPMPTSP',
                'email_dinas' => 'budi.santoso@dpmptsp.jabarprov.go.id',
                'tanggal_permohonan' => '2025-06-17',
                'jenis_perubahan' => ['Peningkatan Sistem'],
                'perubahan_diharapkan' => 'Integrasi API tanda tangan elektronik (TTE BSrE) pada modul penerbitan izin usaha.',
                'latar_belakang' => 'Mandat regulasi digitalisasi perizinan terintegrasi dengan Balai Sertifikasi Elektronik BSSN.',
                'rincian_perubahan' => "1. Integrasi client API BSrE v2\n2. Modul validasi sertifikat digital pemohon\n3. Stamping barcode visual pada dokumen PDF izin",
                'risiko_tidak_dilakukan' => 'Penerbitan surat izin tertunda dan proses legalisasi manual memakan waktu.',
                'kriteria_risiko' => ['Berat'],
                'keterangan' => 'SLA 18 jam tersisa.',
                'solusi_diharapkan' => 'Pengujian koneksi sandbox BSrE sebelum rilis produksi.',
                'risiko_perubahan' => 'Ketergantungan pada availability server CA BSSN.',
                'alternatif_perubahan' => 'Mekanisme antrean TTE offline berulang.',
                'biaya_perubahan' => 'Rp 0',
                'waktu_perubahan' => '5 Hari Kerja',
                'nama_aplikasi' => 'Sistem Perizinan Terpadu',
                'alamat_aplikasi' => 'https://perizinan.jabarprov.go.id',
                'alamat_repository' => 'https://gitlab.jabarprov.go.id/dpmptsp/perizinan-core',
                'status' => 'dalam_pengerjaan',
                'prioritas' => 'P2 - Tinggi',
                'target_sla' => '2025-06-18 18:00:00',
                'verifikator_nama' => 'Raka Pratama, S.STP',
                'verifikator_catatan' => 'Arsitektur integrasi memenuhi standar keamanan. Tim meminta implementasi di staging.',
                'verifikator_rekomendasi' => 'lanjut_pimpinan',
                'verifikator_tanggal' => '2025-06-17',
                'pimpinan_nama' => 'Dr. H. Hendra Setiawan, M.Si',
                'pimpinan_keputusan' => 'disetujui',
                'pimpinan_catatan' => 'Disetujui. Lakukan uji beban API sebelum deployment produksi.',
                'pimpinan_tanggal' => '2025-06-17',
                'assigned_to' => 'Teguh Wicaksono, S.T',
                'target_deadline' => '2025-06-27',
                'teknisi_nama' => 'Teguh Wicaksono, S.T',
                'telaah_teknis' => 'Konfigurasi endpoint sandbox BSrE telah disiapkan dan webhook staging aktif.',
                'catatan_implementasi' => 'Gunakan endpoint sandbox BSrE, validasi webhook, dan siapkan rollback ke versi 2.4.1.',
                'tgl_mulai_pengerjaan' => '2025-06-18',
            ]
        );
        $t2->histories()->delete();
        $t2->addHistory('Budi Santoso', 'pemohon', 'menunggu_verifikasi', 'Permohonan diajukan', null, $pemohon?->id);
        $t2->addHistory('Raka Pratama, S.STP', 'verifikator', 'menunggu_keputusan_pimpinan', 'Verifikasi berkas disetujui, diteruskan ke Pimpinan', 'Arsitektur integrasi memenuhi standar keamanan.', $verifikator?->id);
        $t2->addHistory('Dr. H. Hendra Setiawan, M.Si', 'approver', 'disetujui', 'Disetujui Pimpinan, disposisi ke Teguh Wicaksono, S.T', 'Disetujui dengan catatan uji staging.', $pimpinan?->id);
        $t2->addHistory('Teguh Wicaksono, S.T', 'teknisi', 'dalam_pengerjaan', 'Pengerjaan modul TTE dimulai di lingkungan staging', null, $teknisi?->id);

        // 3. RFC-202506-0129: Portal Kepegawaian (P3 - Perlu Revisi)
        $t3 = PermohonanTi::updateOrCreate(
            ['no_rfc' => 'RFC-202506-0129'],
            [
                'pemohon' => 'Siti Nurhaliza, S.Psi',
                'unit_kerja' => 'Bidang Pengembangan Karir',
                'perangkat_daerah' => 'Badan Kepegawaian Daerah Provinsi Jawa Barat',
                'nomor_kontak' => '0857-1111-2222 / 800/102/BKD',
                'email_dinas' => 'siti.nurhaliza@bkd.jabarprov.go.id',
                'tanggal_permohonan' => '2025-06-15',
                'jenis_perubahan' => ['Peningkatan Sistem'],
                'perubahan_diharapkan' => 'Penambahan modul riwayat pelatihan dan sertifikasi kompetensi ASN.',
                'latar_belakang' => 'Pemenuhan jam pelajaran wajib ASN 20 JP per tahun sesuai standar BKN.',
                'rincian_perubahan' => "1. Formulir unggah sertifikat digital\n2. Verifikasi admin BKD per OPD\n3. Kalkulasi otomatis jam pelajaran",
                'risiko_tidak_dilakukan' => 'Data pengembangan kompetensi ASN tidak terpetakan.',
                'kriteria_risiko' => ['Sedang'],
                'keterangan' => 'Lampiran format data belum lengkap.',
                'solusi_diharapkan' => 'Penyesuaian format kamus data sertifikasi.',
                'biaya_perubahan' => 'Rp 0',
                'waktu_perubahan' => '7 Hari Kerja',
                'nama_aplikasi' => 'Portal Kepegawaian Jabar (SIMPEG)',
                'alamat_aplikasi' => 'https://simpeg.jabarprov.go.id',
                'status' => 'perlu_revisi',
                'prioritas' => 'P3 - Sedang',
                'target_sla' => '2025-06-20 12:00:00',
                'verifikator_nama' => 'Hendro Susilo, M.T.',
                'verifikator_catatan' => 'Mohon lampirkan spesifikasi format data sertifikasi BKN dan surat pengantar kepala dinas.',
                'verifikator_rekomendasi' => 'perlu_revisi',
                'verifikator_tanggal' => '2025-06-16',
            ]
        );
        $t3->histories()->delete();
        $t3->addHistory('Siti Nurhaliza', 'pemohon', 'menunggu_verifikasi', 'Permohonan diajukan');
        $t3->addHistory('Hendro Susilo', 'verifikator', 'perlu_revisi', 'Permohonan dikembalikan untuk dilengkapi lampiran format BKN');

        // 4. RFC-202506-0117: Dashboard Pimpinan (P4 - Selesai / Ditutup)
        $t4 = PermohonanTi::updateOrCreate(
            ['no_rfc' => 'RFC-202506-0117'],
            [
                'pemohon' => 'Raka Pratama, S.Kom',
                'unit_kerja' => 'Bidang Perencanaan dan Evaluasi',
                'perangkat_daerah' => 'Badan Perencanaan Pembangunan Daerah Provinsi Jawa Barat',
                'nomor_kontak' => 'Surat permohonan pada lampiran • raka@instansi.go.id',
                'email_dinas' => 'raka@instansi.go.id',
                'tanggal_permohonan' => '2025-06-12',
                'jenis_perubahan' => ['Peningkatan Sistem'],
                'perubahan_diharapkan' => 'Penyempurnaan visualisasi indikator IKU untuk pemantauan pimpinan.',
                'latar_belakang' => 'Kebutuhan pimpinan daerah untuk pemantauan real-time capaian indikator kinerja utama lintas sektor.',
                'rincian_perubahan' => "1. Modifikasi komponen chart agregat\n2. Optimasi API query rekapitulasi capaian triwulanan\n3. Penambahan filter dinamis berdasarkan perangkat daerah",
                'risiko_tidak_dilakukan' => 'Penyajian data visual indikator IKU tidak mutakhir.',
                'kriteria_risiko' => ['Rendah'],
                'keterangan' => 'Selesai tepat waktu sesuai SLA.',
                'solusi_diharapkan' => 'Penerapan caching data 15 menit dan mekanisme fallback database replica.',
                'biaya_perubahan' => 'Rp 0',
                'waktu_perubahan' => '3 Hari Kerja',
                'nama_aplikasi' => 'Dashboard Pimpinan',
                'alamat_aplikasi' => 'https://dashboard.jabarprov.go.id',
                'status' => 'selesai',
                'prioritas' => 'P4 - Rendah',
                'target_sla' => '2025-06-16 17:00:00',
                'verifikator_nama' => 'Hendro Susilo, M.T.',
                'verifikator_catatan' => 'Berkas lengkap dan sesuai kriteria.',
                'verifikator_rekomendasi' => 'lanjut_pimpinan',
                'verifikator_tanggal' => '2025-06-12',
                'pimpinan_nama' => 'Drs. H. Iwan Gunawan, M.Si',
                'pimpinan_keputusan' => 'disetujui',
                'pimpinan_catatan' => 'Disetujui untuk dilaksanakan segera.',
                'pimpinan_tanggal' => '2025-06-13',
                'assigned_to' => 'Tim Frontend APTIKA',
                'target_deadline' => '2025-06-16',
                'teknisi_nama' => 'Tim Frontend APTIKA',
                'telaah_teknis' => 'Komponen visual chart selesai diperbarui dan telah diuji.',
                'catatan_implementasi' => 'Deployment ke server production pada tanggal 15 Juni 2025.',
                'tautan_perubahan' => 'https://gitlab.jabarprov.go.id/dashboard/iku/-/commit/4c2a110',
                'tgl_mulai_pengerjaan' => '2025-06-13',
                'tgl_selesai_pengerjaan' => '2025-06-15',
                'nomor_bast' => 'BAST-202506-0117/APTIKA',
                'tanggal_bast' => '2025-06-16',
                'catatan_bast' => 'Perubahan telah diserahterimakan dan disetujui pemohon tanpa catatan.',
            ]
        );
        $t4->histories()->delete();
        $t4->addHistory('Raka Pratama', 'pemohon', 'menunggu_verifikasi', 'Permohonan diajukan');
        $t4->addHistory('Hendro Susilo', 'verifikator', 'menunggu_keputusan_pimpinan', 'Verifikasi selesai');
        $t4->addHistory('Drs. H. Iwan Gunawan, M.Si', 'approver', 'disetujui', 'Disetujui Pimpinan');
        $t4->addHistory('Tim Frontend APTIKA', 'teknisi', 'dalam_pengerjaan', 'Implementasi fitur chart visualisasi IKU');
        $t4->addHistory('Tim Frontend APTIKA', 'teknisi', 'selesai', 'Pengujian selesai dan BAST ditandatangani');
    }
}
