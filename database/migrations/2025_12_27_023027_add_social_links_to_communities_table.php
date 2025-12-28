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
        Schema::table('communities', function (Blueprint $table) {
            $table->string('instagram_handle')->nullable(); // ex: @communify
            $table->string('youtube_handle')->nullable();   // ex: /c/communify
            $table->string('whatsapp_group')->nullable();   // Link do grupo
            $table->string('accent_color')->default('#4F46E5'); // Cor de destaque (Bônus)
        });
    }

    public function down(): void
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->dropColumn(['instagram_handle', 'youtube_handle', 'whatsapp_group', 'accent_color']);
        });
    }
};
