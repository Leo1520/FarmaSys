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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['entrada', 'salida']); // Entrada o salida
            $table->integer('cantidad'); // Cantidad movida
            $table->string('razon'); // 'compra', 'devolución', 'ajuste', 'venta', 'pérdida', 'transferencia'
            $table->text('descripcion')->nullable(); // Descripción adicional
            $table->decimal('precio_unitario', 10, 2)->nullable(); // Para registrar costo
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('medicamento_id');
            $table->index('user_id');
            $table->index('tipo');
            $table->index('razon');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
