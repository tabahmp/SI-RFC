<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_ti_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_ti_id')->constrained('permohonan_tis')->onDelete('cascade');
            $table->string('actor_name');
            $table->string('actor_role', 50); // pemohon, verifikator, approver, teknisi, agen, admin
            $table->string('status_sebelumnya', 50)->nullable();
            $table->string('status_baru', 50);
            $table->string('keterangan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('permohonan_ti_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_ti_histories');
    }
};

