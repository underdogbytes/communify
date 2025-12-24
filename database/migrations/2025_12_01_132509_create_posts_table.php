<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cria a tabela POSTS
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            
            // Conteúdo
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('content');
            $table->string('image')->nullable();
            
            // Classificação
            $table->string('type')->default('short');
            $table->string('status')->default('published');
            $table->string('visibility')->default('public');
            
            // Organização
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->json('settings')->nullable();

            $table->timestamps();
        });

        // 2. Cria a tabela COMMENTS (Adicionado agora)
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem comentou
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Em qual post
            $table->text('content'); // O texto do comentário
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        // A ordem aqui importa: apaga filhos (comments) antes dos pais (posts)
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
    }
};