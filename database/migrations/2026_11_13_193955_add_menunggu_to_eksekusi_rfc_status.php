<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eksekusi_rfc', function (Blueprint $table) {
            // Tambah nilai 'menunggu' ke ENUM agar teknisi bisa di-assign sebelum mulai
            \DB::statement("ALTER TABLE eksekusi_rfc MODIFY status_eksekusi ENUM('menunggu','proses_pengerjaan','selesai_dikerjakan') NOT NULL DEFAULT 'menunggu'");
        });
    }

    public function down(): void
    {
        Schema::table('eksekusi_rfc', function (Blueprint $table) {
            \DB::statement("ALTER TABLE eksekusi_rfc MODIFY status_eksekusi ENUM('proses_pengerjaan','selesai_dikerjakan') NOT NULL DEFAULT 'proses_pengerjaan'");
        });
    }
};
