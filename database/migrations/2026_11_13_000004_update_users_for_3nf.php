<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom baru 3NF
            $table->string('nama_lengkap')->nullable()->after('name');
            $table->unsignedBigInteger('peran_id')->nullable()->after('nama_lengkap');
            $table->unsignedBigInteger('opd_id')->nullable()->after('peran_id');
            $table->enum('status_aktivasi', ['tertunda', 'aktif', 'nonaktif'])
                  ->default('tertunda')
                  ->after('password');
            $table->timestamp('email_diverifikasi_pada')->nullable()->after('status_aktivasi');

            // Foreign keys
            $table->foreign('peran_id')->references('id')->on('peran')->nullOnDelete();
            $table->foreign('opd_id')->references('id')->on('opd')->nullOnDelete();

            $table->index('peran_id');
            $table->index('opd_id');
            $table->index('status_aktivasi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['peran_id']);
            $table->dropForeign(['opd_id']);
            $table->dropIndex(['peran_id']);
            $table->dropIndex(['opd_id']);
            $table->dropIndex(['status_aktivasi']);
            $table->dropColumn([
                'nama_lengkap',
                'peran_id',
                'opd_id',
                'status_aktivasi',
                'email_diverifikasi_pada',
            ]);
        });
    }
};
