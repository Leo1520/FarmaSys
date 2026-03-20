<?php

namespace App\Models;

use App\Traits\RegistraHistorial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicamento extends Model
{
    use HasFactory, RegistraHistorial;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medicamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'codigo',
        'precio',
        'stock',
        'stock_minimo',
        'fecha_vencimiento',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'fecha_vencimiento' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the validation rules for creating/updating a medicamento.
     * Usado para validación en el controlador.
     *
     * @return array<string, string>
     */
    public static function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:medicamentos',
            'precio' => 'required|numeric|min:0.01|max:999999.99',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'fecha_vencimiento' => 'nullable|date',
        ];
    }

    /**
     * Get the validation rules for updating a medicamento.
     *
     * @return array<string, string>
     */
    public static function rulesForUpdate($id): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:medicamentos,codigo,' . $id,
            'precio' => 'required|numeric|min:0.01|max:999999.99',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'fecha_vencimiento' => 'nullable|date',
        ];
    }

    /**
     * Scope para medicamentos con stock bajo.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock <= stock_minimo');
    }

    /**
     * Scope para medicamentos vencidos.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVencidos($query)
    {
        return $query->whereNotNull('fecha_vencimiento')
                     ->whereDate('fecha_vencimiento', '<', now());
    }

    /**
     * Scope para medicamentos próximos a vencer (30 días).
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProximosAVencer($query)
    {
        return $query->whereNotNull('fecha_vencimiento')
                     ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
                     ->whereDate('fecha_vencimiento', '>', now());
    }

    /**
     * Obtener nombre descriptivo para el historial
     */
    public function getNombreParaHistorial(): string
    {
        return "{$this->nombre} (Col: {$this->codigo})";
    }
}
