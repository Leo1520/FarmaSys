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
        Schema::table('users', function (Blueprint $table) {
            // Actualizar la columna rol para incluir 'invitado'
            // Primero, cambiar el tipo de enum
            $table->string('rol')->change(); // Convertir a string temporalmente
        });

        // Luego convertir de vuelta a enum con los nuevos valores
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'farmaceutica', 'invitado'])->default('invitado')->change();
            // Agregar campo para estado de aprobación
            $table->enum('estado', ['pendiente', 'activo', 'rechazado', 'inactivo'])->default('pendiente')->after('rol');
            // Campo para razon de rechazo
            $table->text('razon_rechazo')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['estado', 'razon_rechazo']);
            $table->enum('rol', ['admin', 'farmaceutica'])->default('farmaceutica')->change();
        });
    }
};
