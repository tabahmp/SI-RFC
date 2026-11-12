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
        Schema::table('permohonan_tis', function (Blueprint $table) {
            // Relasi 3NF ke tabel users
            $table->foreignId('user_id')->nullable()->after('no_rfc')->constrained('users')->nullOnDelete();
            $table->foreignId('verifikator_id')->nullable()->after('verifikator_nama')->constrained('users')->nullOnDelete();
            $table->foreignId('pimpinan_id')->nullable()->after('pimpinan_nama')->constrained('users')->nullOnDelete();
            $table->foreignId('teknisi_id')->nullable()->after('teknisi_nama')->constrained('users')->nullOnDelete();
            $table->foreignId('agen_id')->nullable()->after('agen_nama')->constrained('users')->nullOnDelete();
        });

        Schema::table('permohonan_ti_histories', function (Blueprint $table) {
            // Relasi 3NF actor ke tabel users
            $table->foreignId('user_id')->nullable()->after('permohonan_ti_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_ti_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('permohonan_tis', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['verifikator_id']);
            $table->dropForeign(['pimpinan_id']);
            $table->dropForeign(['teknisi_id']);
            $table->dropForeign(['agen_id']);
            $table->dropColumn(['user_id', 'verifikator_id', 'pimpinan_id', 'teknisi_id', 'agen_id']);
        });
    }
};
