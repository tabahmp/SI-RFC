<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id');
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('sudah_dibaca')->default(false);
            $table->string('url_tujuan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();

            $table->foreign('pengguna_id')->references('id')->on('users')->cascadeOnDelete();

            $table->index('pengguna_id');
            $table->index('sudah_dibaca');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
