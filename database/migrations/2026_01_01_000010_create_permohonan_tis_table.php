<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_tis', function (Blueprint $table) {
            $table->id();
            $table->string('no_rfc', 50)->unique();

            // BAGIAN 1: INFORMASI PEMOHON (FR-015)
            $table->string('pemohon');
            $table->string('unit_kerja');
            $table->string('perangkat_daerah')->nullable();
            $table->string('nomor_kontak');
            $table->string('email_dinas');
            $table->date('tanggal_permohonan');

            // BAGIAN 2: INFORMASI DATA PERUBAHAN
            $table->json('jenis_perubahan');
            $table->text('perubahan_diharapkan');
            $table->text('latar_belakang');
            $table->text('rincian_perubahan');
            $table->text('risiko_tidak_dilakukan')->nullable();
            $table->json('kriteria_risiko')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('solusi_diharapkan')->nullable();
            $table->text('risiko_perubahan')->nullable();
            $table->text('alternatif_perubahan')->nullable();
            $table->string('biaya_perubahan', 100)->nullable();
            $table->string('waktu_perubahan', 100)->nullable();

            // Identitas Aplikasi
            $table->string('nama_aplikasi')->nullable();
            $table->text('deskripsi_aplikasi')->nullable();
            $table->string('alamat_aplikasi')->nullable();
            $table->string('alamat_repository')->nullable();

            // Berkas & TTD Pemohon (Kotak 1)
            $table->json('dokumen_pendukung_file')->nullable();
            $table->string('tanda_tangan_file')->nullable();

            // STATUS & PRIORITAS
            $table->enum('status', [
                'menunggu_verifikasi',
                'perlu_revisi',
                'menunggu_keputusan_pimpinan',
                'disetujui',
                'dalam_pengerjaan',
                'menunggu_bast',
                'selesai',
                'ditolak',
                'ditunda',
            ])->default('menunggu_verifikasi');
            $table->enum('prioritas', [
                'P1 - Kritis',
                'P2 - Tinggi',
                'P3 - Sedang',
                'P4 - Rendah',
            ])->default('P3 - Sedang');
            $table->dateTime('target_sla')->nullable();
            $table->text('catatan_revisi')->nullable();

            // TAHAP 2: VERIFIKATOR (Kotak 2)
            $table->string('verifikator_nama')->nullable();
            $table->text('verifikator_catatan')->nullable();
            $table->enum('verifikator_rekomendasi', ['lanjut_pimpinan', 'perlu_revisi', 'tolak'])->nullable();
            $table->date('verifikator_tanggal')->nullable();
            $table->string('verifikator_ttd')->nullable();

            // TAHAP 3: PIMPINAN (Kotak 3)
            $table->string('pimpinan_nama')->nullable();
            $table->enum('pimpinan_keputusan', ['disetujui', 'ditolak', 'ditunda'])->nullable();
            $table->text('pimpinan_catatan')->nullable();
            $table->date('pimpinan_tanggal')->nullable();
            $table->string('pimpinan_ttd')->nullable();
            $table->string('assigned_to')->nullable();
            $table->date('target_deadline')->nullable();

            // TAHAP 4: TEKNISI
            $table->string('teknisi_nama')->nullable();
            $table->text('telaah_teknis')->nullable();
            $table->text('catatan_implementasi')->nullable();
            $table->string('tautan_perubahan')->nullable();
            $table->date('tgl_mulai_pengerjaan')->nullable();
            $table->date('tgl_selesai_pengerjaan')->nullable();

            // TAHAP 5: BAST (Kotak 4)
            $table->string('nomor_bast', 100)->nullable();
            $table->date('tanggal_bast')->nullable();
            $table->string('file_bast')->nullable();
            $table->text('catatan_bast')->nullable();
            $table->string('agen_nama')->nullable();
            $table->string('agen_ttd')->nullable();

            $table->timestamps();

            $table->index('no_rfc');
            $table->index('status');
            $table->index('prioritas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_tis');
    }
};

