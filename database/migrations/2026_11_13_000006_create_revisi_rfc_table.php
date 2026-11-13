<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi_rfc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfc_id');
            $table->unsignedBigInteger('verifikator_id')->nullable();
            $table->text('catatan_revisi');
            $table->enum('status_revisi', ['menunggu_perbaikan', 'selesai_diperbaiki'])
                  ->default('menunggu_perbaikan');
            $table->timestamp('dibuat_pada')->useCurrent();

            $table->foreign('rfc_id')->references('id')->on('permohonan_rfc')->cascadeOnDelete();
            $table->foreign('verifikator_id')->references('id')->on('users')->nullOnDelete();

            $table->index('rfc_id');
            $table->index('verifikator_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_rfc');
    }
};
