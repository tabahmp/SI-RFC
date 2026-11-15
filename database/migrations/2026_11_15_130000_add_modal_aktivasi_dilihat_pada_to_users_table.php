<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'modal_aktivasi_dilihat_pada')) {
                $table->timestamp('modal_aktivasi_dilihat_pada')->nullable()->after('diverifikasi_pada');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'modal_aktivasi_dilihat_pada')) {
                $table->dropColumn('modal_aktivasi_dilihat_pada');
            }
        });
    }
};

