<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_bast', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfc_id');
            $table->string('nomor_dokumen', 100);
            $table->string('path_file');
            $table->string('tanda_tangan_teknisi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();

            $table->foreign('rfc_id')->references('id')->on('permohonan_rfc')->cascadeOnDelete();

            $table->index('rfc_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_bast');
    }
};
