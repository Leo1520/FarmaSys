<?php

namespace App\Models;

use App\Traits\RegistraHistorial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    use RegistraHistorial;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'medicamento_id',
        'user_id',
        'tipo',
        'cantidad',
        'razon',
        'descripcion',
        'precio_unitario',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the medicamento that was moved.
     */
    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    /**
     * Get the user who performed the movement.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope para movimientos de entrada
     */
    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }

    /**
     * Scope para movimientos de salida
     */
    public function scopeSalidas($query)
    {
        return $query->where('tipo', 'salida');
    }

    /**
     * Scope para filtrar por razón
     */
    public function scopePorRazon($query, string $razon)
    {
        return $query->where('razon', $razon);
    }

    /**
     * Scope para filtrar por medicamento
     */
    public function scopePorMedicamento($query, int $medicamentoId)
    {
        return $query->where('medicamento_id', $medicamentoId);
    }

    /**
     * Obtener total de movimientos de entrada para un medicamento
     */
    public static function totalEntradas(int $medicamentoId): int
    {
        return self::where('medicamento_id', $medicamentoId)
                   ->entradas()
                   ->sum('cantidad');
    }

    /**
     * Obtener total de movimientos de salida para un medicamento
     */
    public static function totalSalidas(int $medicamentoId): int
    {
        return self::where('medicamento_id', $medicamentoId)
                   ->salidas()
                   ->sum('cantidad');
    }

    /**
     * Registrar un movimiento y actualizar stock automáticamente
     */
    public static function registrar(
        int $medicamentoId,
        string $tipo,
        int $cantidad,
        string $razon,
        ?string $descripcion = null,
        ?float $precioUnitario = null
    ): self {
        $medicamento = Medicamento::findOrFail($medicamentoId);

        // Validar que no se reste más stock del disponible (para salidas)
        if ($tipo === 'salida' && $medicamento->stock < $cantidad) {
            throw new \Exception("Stock insuficiente. Disponible: {$medicamento->stock}, Solicitado: {$cantidad}");
        }

        // Crear el movimiento
        $movimiento = self::create([
            'medicamento_id' => $medicamentoId,
            'user_id' => auth()->id(),
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'razon' => $razon,
            'descripcion' => $descripcion,
            'precio_unitario' => $precioUnitario,
        ]);

        // Actualizar stock del medicamento
        if ($tipo === 'entrada') {
            $medicamento->increment('stock', $cantidad);
        } else {
            $medicamento->decrement('stock', $cantidad);
        }

        return $movimiento;
    }

    /**
     * Obtener nombre descriptivo para el historial
     */
    public function getNombreParaHistorial(): string
    {
        $tipoLabel = $this->tipo === 'entrada' ? '📥' : '📤';
        return "{$tipoLabel} {$this->medicamento->nombre} - {$this->cantidad} unidades";
    }
}
