<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListaCompra extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lista_compra';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'estado',
        'notas',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relación: Una lista de compra tiene muchos detalles
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleListaCompra::class, 'lista_compra_id');
    }

    /**
     * Obtener medicamentos con stock bajo para crear lista de compra sugerida
     */
    public static function crearSugerida(): self
    {
        $lista = self::create([
            'estado' => 'pendiente',
            'notas' => 'Lista generada automáticamente por medicamentos con stock bajo',
        ]);

        // Obtener medicamentos con stock bajo
        $medicamentosStockBajo = Medicamento::stockBajo()->get();

        foreach ($medicamentosStockBajo as $medicamento) {
            // Calcular cantidad sugerida (stock_minimo - stock_actual)
            $cantidadSugerida = $medicamento->stock_minimo - $medicamento->stock;

            DetalleListaCompra::create([
                'lista_compra_id' => $lista->id,
                'medicamento_id' => $medicamento->id,
                'cantidad_sugerida' => $cantidadSugerida,
                'precio_unitario' => $medicamento->precio,
            ]);
        }

        return $lista;
    }

    /**
     * Calcular total de la lista
     */
    public function totalEstimado(): float
    {
        return $this->detalles()
            ->whereNotNull('precio_unitario')
            ->get()
            ->sum(function ($detalle) {
                return $detalle->cantidad_sugerida * $detalle->precio_unitario;
            });
    }

    /**
     * Scope para listas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para listas compradas
     */
    public function scopeCompradas($query)
    {
        return $query->where('estado', 'comprada');
    }
}
