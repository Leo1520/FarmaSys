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
        // Crear usuario administrador de prueba
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@farmasys.com',
            'password' => bcrypt('password'),
            'rol' => 'admin',
        ]);

        // Crear usuario farmacéutica de prueba
        User::factory()->create([
            'name' => 'Farmacéutica Test',
            'email' => 'farmaceutica@farmasys.com',
            'password' => bcrypt('password'),
            'rol' => 'farmaceutica',
        ]);
    }
}
