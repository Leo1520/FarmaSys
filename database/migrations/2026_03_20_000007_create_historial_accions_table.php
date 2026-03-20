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
        Schema::create('historial_accions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('entidad'); // 'medicamento', 'lista_compra', 'usuario', etc.
            $table->integer('entidad_id')->nullable();
            $table->string('accion'); // 'crear', 'actualizar', 'eliminar', 'ver'
            $table->string('descripcion');
            $table->json('cambios_anteriores')->nullable(); // Valores antes del cambio
            $table->json('cambios_nuevos')->nullable(); // Valores después del cambio
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('user_id');
            $table->index('entidad');
            $table->index('accion');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_accions');
    }
};
