<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persetujuan_rfc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfc_id');
            $table->unsignedBigInteger('pimpinan_id')->nullable();
            $table->enum('keputusan', ['disetujui', 'ditolak', 'ditunda']);
            $table->text('catatan_keputusan')->nullable();
            $table->timestamp('diputuskan_pada')->useCurrent();

            $table->foreign('rfc_id')->references('id')->on('permohonan_rfc')->cascadeOnDelete();
            $table->foreign('pimpinan_id')->references('id')->on('users')->nullOnDelete();

            $table->index('rfc_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persetujuan_rfc');
    }
};
