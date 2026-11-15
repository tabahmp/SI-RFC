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
        Schema::table('permohonan_rfc', function (Blueprint $table) {
            $table->string('nama_penanggung_jawab')->nullable()->after('tanda_tangan_pemohon');
            $table->string('nip_penanggung_jawab', 50)->nullable()->after('nama_penanggung_jawab');
            $table->string('tanda_tangan_penanggung_jawab')->nullable()->after('nip_penanggung_jawab');
        });

        Schema::table('persetujuan_rfc', function (Blueprint $table) {
            $table->string('tanda_tangan_pimpinan')->nullable()->after('catatan_keputusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_rfc', function (Blueprint $table) {
            $table->dropColumn(['nama_penanggung_jawab', 'nip_penanggung_jawab', 'tanda_tangan_penanggung_jawab']);
        });

        Schema::table('persetujuan_rfc', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan_pimpinan');
        });
    }
};
