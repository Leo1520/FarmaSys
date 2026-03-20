<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleListaCompra extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detalle_lista_compra';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lista_compra_id',
        'medicamento_id',
        'cantidad_sugerida',
        'cantidad_comprada',
        'precio_unitario',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relación: Detalle pertenece a una lista
     */
    public function listaCompra(): BelongsTo
    {
        return $this->belongsTo(ListaCompra::class, 'lista_compra_id');
    }

    /**
     * Relación: Detalle pertenece a un medicamento
     */
    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class, 'medicamento_id');
    }

    /**
     * Calcular costo total de este detalle
     */
    public function subtotal(): float
    {
        return ($this->cantidad_sugerida ?? 0) * ($this->precio_unitario ?? 0);
    }
}
