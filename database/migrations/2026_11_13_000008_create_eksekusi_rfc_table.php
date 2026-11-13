<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eksekusi_rfc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfc_id');
            $table->unsignedBigInteger('teknisi_id')->nullable();
            $table->text('catatan_eksekusi')->nullable();
            $table->enum('status_eksekusi', ['proses_pengerjaan', 'selesai_dikerjakan'])
                  ->default('proses_pengerjaan');
            $table->timestamp('dimulai_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('rfc_id')->references('id')->on('permohonan_rfc')->cascadeOnDelete();
            $table->foreign('teknisi_id')->references('id')->on('users')->nullOnDelete();

            $table->index('rfc_id');
            $table->index('teknisi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eksekusi_rfc');
    }
};
