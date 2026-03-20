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
        Schema::create('lista_compra', function (Blueprint $table) {
            $table->id();
            
            // Estado de la lista
            $table->enum('estado', ['pendiente', 'comprada', 'cancelada'])->default('pendiente')->index();
            
            // Información
            $table->text('notas')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_compra');
    }
};
