<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador de prueba con email real
        User::factory()->create([
            'name' => 'Leonardo Peña',
            'email' => 'leonardopenaanez@gmail.com',
            'password' => bcrypt('password'),
            'rol' => 'admin',
            'estado' => 'activo',
            'email_verified_at' => now(),
        ]);

        // Crear usuario farmacéutica de prueba
        User::factory()->create([
            'name' => 'Farmacéutica Test',
            'email' => 'farmaceutica@farmasys.com',
            'password' => bcrypt('password'),
            'rol' => 'farmaceutica',
            'estado' => 'activo',
            'email_verified_at' => now(),
        ]);
    }
}
