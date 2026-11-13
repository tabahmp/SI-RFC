<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_rfc', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelacakan', 30)->unique();

            // ── Relasi Pemohon ──────────────────────────────────────────────────
            $table->unsignedBigInteger('pengguna_id')->nullable();
            $table->unsignedBigInteger('opd_id')->nullable();
            $table->unsignedBigInteger('kategori_id')->nullable();

            // ── Seksi 1: Informasi Pemohon (FR-015) ────────────────────────────
            $table->string('nama_pemohon');           // cache denormalisasi
            $table->string('nip', 50)->nullable();
            $table->string('unit_kerja');
            $table->string('nomor_surat', 100)->nullable();
            $table->string('nomor_kontak', 50);
            $table->string('email_dinas');
            $table->date('tanggal_permohonan');

            // ── Seksi 2: Jenis & Kategori Perubahan ───────────────────────────
            $table->json('jenis_perubahan')->nullable();  // checkbox: permintaan_baru, peningkatan_sistem, perbaikan_sistem

            // ── Seksi 3: Data Perubahan ────────────────────────────────────────
            $table->string('judul_perubahan')->nullable();
            $table->text('deskripsi_perubahan');
            $table->text('latar_belakang');
            $table->text('rincian_perubahan');

            // ── Seksi 4: Analisis Risiko SMKI ──────────────────────────────────
            $table->text('risiko_tidak_dilakukan')->nullable();
            $table->string('kriteria_risiko', 50)->nullable();   // malapetaka/sangat_berat/berat/agak_berat/tidak_berat
            $table->text('keterangan_risiko')->nullable();
            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang');
            $table->text('rencana_pemulihan')->nullable();       // rollback plan

            // ── Seksi 5: Teknis & Solusi ──────────────────────────────────────
            $table->text('solusi_diharapkan')->nullable();
            $table->text('risiko_perubahan')->nullable();
            $table->text('alternatif_perubahan')->nullable();
            $table->string('biaya_perubahan', 100)->nullable();
            $table->string('waktu_perubahan', 100)->nullable();

            // ── Seksi 6: Identitas Aplikasi ───────────────────────────────────
            $table->string('nama_aplikasi')->nullable();
            $table->text('deskripsi_aplikasi')->nullable();
            $table->string('alamat_aplikasi')->nullable();
            $table->string('alamat_repository')->nullable();

            // ── Seksi 7: Lampiran & Tanda Tangan ──────────────────────────────
            $table->json('path_lampiran')->nullable();
            $table->string('tanda_tangan_pemohon')->nullable();

            // ── Status & SLA ──────────────────────────────────────────────────
            $table->enum('status_terkini', [
                'draf',
                'diajukan',
                'perlu_revisi',
                'ditolak_verifikator',
                'menunggu_persetujuan',
                'ditolak_pimpinan',
                'ditunda',
                'disetujui',
                'sedang_dikerjakan',
                'selesai',
            ])->default('draf');
            $table->datetime('target_sla')->nullable();

            $table->timestamps();

            // ── Foreign Keys ──────────────────────────────────────────────────
            $table->foreign('pengguna_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('opd_id')->references('id')->on('opd')->nullOnDelete();
            $table->foreign('kategori_id')->references('id')->on('kategori_rfc')->nullOnDelete();

            // ── Indexes ───────────────────────────────────────────────────────
            $table->index('kode_pelacakan');
            $table->index('status_terkini');
            $table->index('pengguna_id');
            $table->index('opd_id');
            $table->index('kategori_id');
            $table->index('urgensi');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_rfc');
    }
};
