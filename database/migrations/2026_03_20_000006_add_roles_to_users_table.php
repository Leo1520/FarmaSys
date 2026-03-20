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
            // Agregar rol si no existe
            if (!Schema::hasColumn('users', 'rol')) {
                $table->enum('rol', ['admin', 'farmaceutica'])->default('farmaceutica')->after('email');
            }
            
            // Agregar fecha de último acceso
            if (!Schema::hasColumn('users', 'ultimo_acceso')) {
                $table->timestamp('ultimo_acceso')->nullable()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rol', 'ultimo_acceso']);
        });
    }
};
