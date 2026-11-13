<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfc_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfc_id');
            $table->unsignedBigInteger('pengguna_id')->nullable();
            $table->string('nama_aktor');
            $table->string('peran_aktor', 50);
            $table->string('status_sebelumnya', 50)->nullable();
            $table->string('status_baru', 50);
            $table->text('keterangan');
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('rfc_id')->references('id')->on('permohonan_rfc')->cascadeOnDelete();
            $table->foreign('pengguna_id')->references('id')->on('users')->nullOnDelete();

            $table->index('rfc_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfc_histories');
    }
};
