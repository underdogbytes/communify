<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de Avaliações
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned(); // 1 a 5
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Impede que o usuário avalie o mesmo produto 2x
            $table->unique(['user_id', 'product_id']);
        });

        // Tabela de Perguntas e Respostas
        Schema::create('product_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem perguntou
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->text('answer')->nullable(); // Resposta do criador
            $table->dateTime('answered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_questions');
        Schema::dropIfExists('reviews');
    }
};
