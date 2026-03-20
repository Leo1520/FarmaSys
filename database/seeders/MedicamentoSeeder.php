<?php

namespace Database\Seeders;

use App\Models\Medicamento;
use Illuminate\Database\Seeder;

class MedicamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicamentos = [
            [
                'nombre' => 'Paracetamol 500mg',
                'codigo' => 'PAR-001',
                'precio' => 2.50,
                'stock' => 5,
                'stock_minimo' => 10,
                'fecha_vencimiento' => '2026-12-31',
            ],
            [
                'nombre' => 'Ibuprofeno 400mg',
                'codigo' => 'IBU-002',
                'precio' => 3.75,
                'stock' => 20,
                'stock_minimo' => 15,
                'fecha_vencimiento' => '2025-06-30',
            ],
            [
                'nombre' => 'Amoxicilina 500mg',
                'codigo' => 'AMO-003',
                'precio' => 5.00,
                'stock' => 12,
                'stock_minimo' => 8,
                'fecha_vencimiento' => '2027-03-15',
            ],
            [
                'nombre' => 'Dipirona 500mg',
                'codigo' => 'DIP-004',
                'precio' => 1.50,
                'stock' => 3,
                'stock_minimo' => 5,
                'fecha_vencimiento' => '2026-08-20',
            ],
            [
                'nombre' => 'Loratadina 10mg',
                'codigo' => 'LOR-005',
                'precio' => 4.25,
                'stock' => 30,
                'stock_minimo' => 10,
                'fecha_vencimiento' => '2027-01-10',
            ],
            [
                'nombre' => 'Omeprazol 20mg',
                'codigo' => 'OMP-006',
                'precio' => 6.50,
                'stock' => 8,
                'stock_minimo' => 10,
                'fecha_vencimiento' => '2026-11-05',
            ],
            [
                'nombre' => 'Métformina 850mg',
                'codigo' => 'MET-007',
                'precio' => 7.99,
                'stock' => 25,
                'stock_minimo' => 20,
                'fecha_vencimiento' => '2027-05-22',
            ],
            [
                'nombre' => 'Lisinopril 10mg',
                'codigo' => 'LIS-008',
                'precio' => 8.50,
                'stock' => 15,
                'stock_minimo' => 12,
                'fecha_vencimiento' => '2026-09-30',
            ],
        ];

        foreach ($medicamentos as $medicamento) {
            Medicamento::create($medicamento);
        }
    }
}
