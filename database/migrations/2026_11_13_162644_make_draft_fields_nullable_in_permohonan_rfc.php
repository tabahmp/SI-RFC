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
            // Make fields nullable untuk support draft RFC
            $table->text('deskripsi_perubahan')->nullable()->change();
            $table->text('latar_belakang')->nullable()->change();
            $table->text('rincian_perubahan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_rfc', function (Blueprint $table) {
            // Revert to NOT NULL (jika rollback diperlukan)
            $table->text('deskripsi_perubahan')->nullable(false)->change();
            $table->text('latar_belakang')->nullable(false)->change();
            $table->text('rincian_perubahan')->nullable(false)->change();
        });
    }
};
