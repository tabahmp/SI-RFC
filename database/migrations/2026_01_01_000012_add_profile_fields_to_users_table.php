<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('name');
            $table->string('nomor_kontak')->nullable()->after('nip');
            $table->string('asal_opd')->nullable()->after('nomor_kontak');
            $table->string('role')->default('pemohon')->after('email');
            $table->enum('status_registrasi', ['menunggu_verifikasi', 'terverifikasi', 'ditolak'])
                  ->default('menunggu_verifikasi')
                  ->after('role');
            $table->text('catatan_verifikasi')->nullable()->after('status_registrasi');
            $table->timestamp('diverifikasi_pada')->nullable()->after('catatan_verifikasi');
            $table->string('diverifikasi_oleh')->nullable()->after('diverifikasi_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nip',
                'nomor_kontak',
                'asal_opd',
                'role',
                'status_registrasi',
                'catatan_verifikasi',
                'diverifikasi_pada',
                'diverifikasi_oleh',
            ]);
        });
    }
};

