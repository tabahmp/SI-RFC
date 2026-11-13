<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eksekusi_rfc', function (Blueprint $table) {
            $table->text('uraian_pekerjaan')->nullable()->after('catatan_eksekusi');
            $table->text('hasil_pengerjaan')->nullable()->after('uraian_pekerjaan');
            $table->string('path_lampiran_hasil')->nullable()->after('hasil_pengerjaan');
        });
    }

    public function down(): void
    {
        Schema::table('eksekusi_rfc', function (Blueprint $table) {
            $table->dropColumn(['uraian_pekerjaan', 'hasil_pengerjaan', 'path_lampiran_hasil']);
        });
    }
};
