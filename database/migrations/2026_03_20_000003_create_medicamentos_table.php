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
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();
            
            // Información básica del medicamento
            $table->string('nombre')->index();
            $table->string('codigo')->nullable()->unique('codigo_unique');
            
            // Información financiera y de inventario
            $table->decimal('precio', 10, 2)->unsigned();
            $table->integer('stock')->unsigned()->default(0);
            $table->integer('stock_minimo')->unsigned()->default(10);
            
            // Información de caducidad
            $table->date('fecha_vencimiento')->nullable();
            
            // Timestamps para auditoría
            $table->timestamps();
            
            // Índices para optimización de consultas
            $table->index('nombre');
            $table->index('fecha_vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
