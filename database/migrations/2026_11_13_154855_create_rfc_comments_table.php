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
        Schema::create('rfc_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rfc_id')->constrained('permohonan_rfc')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('comment');
            $table->boolean('is_internal')->default(false)->comment('Internal notes only visible to staff');
            $table->foreignId('parent_id')->nullable()->constrained('rfc_comments')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index(['rfc_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfc_comments');
    }
};
