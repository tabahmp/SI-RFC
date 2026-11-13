<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opd', function (Blueprint $table) {
            $table->id();
            $table->string('kode_opd', 30)->unique();
            $table->string('nama_opd');
            $table->text('alamat')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->index('aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opd');
    }
};
