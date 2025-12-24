<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabela Molde (Seeder)
        Schema::create('base_products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2); 
            $table->json('options_json')->nullable(); 
            $table->timestamps();
        });

        // 2. Tabela de Vendas (Híbrida)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            
            // Nullable para permitir produtos digitais
            $table->foreignId('base_product_id')->nullable()->constrained()->onDelete('restrict');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Apenas o lucro é salvo no banco
            $table->decimal('profit', 10, 2); 
            
            // Arquivos
            $table->string('image_path')->nullable(); // Padronizado
            $table->string('file_artwork')->nullable(); // Só para físicos (POD)
            
            // Gatekeeper (Digital)
            $table->string('type')->default('physical'); // 'physical' ou 'digital'
            $table->text('delivery_url')->nullable();    // Link secreto
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('base_products');
    }
};