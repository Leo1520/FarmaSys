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
        Schema::create('detalle_lista_compra', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('lista_compra_id')->constrained('lista_compra')->onDelete('cascade');
            $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('cascade');
            
            // Cantidades
            $table->integer('cantidad_sugerida')->unsigned();
            $table->integer('cantidad_comprada')->unsigned()->nullable();
            
            // Información
            $table->decimal('precio_unitario', 10, 2)->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices
            $table->index('lista_compra_id');
            $table->index('medicamento_id');
            
            // Único: No duplicar el mismo medicamento en la misma lista
            $table->unique(['lista_compra_id', 'medicamento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_lista_compra');
    }
};
