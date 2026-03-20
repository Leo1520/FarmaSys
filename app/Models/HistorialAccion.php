<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialAccion extends Model
{
    protected $table = 'historial_accions';

    protected $fillable = [
        'user_id',
        'entidad',
        'entidad_id',
        'accion',
        'descripcion',
        'cambios_anteriores',
        'cambios_nuevos',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'cambios_anteriores' => 'array',
        'cambios_nuevos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Registrar una acción en el historial
     */
    public static function registrar(
        string $entidad,
        string $accion,
        string $descripcion,
        ?int $entidad_id = null,
        ?array $cambios_anteriores = null,
        ?array $cambios_nuevos = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'entidad' => $entidad,
            'entidad_id' => $entidad_id,
            'accion' => $accion,
            'descripcion' => $descripcion,
            'cambios_anteriores' => $cambios_anteriores,
            'cambios_nuevos' => $cambios_nuevos,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Obtener acciones recientes
     */
    public static function recientes(int $limite = 10)
    {
        return self::with('usuario')
                   ->latest('created_at')
                   ->limit($limite)
                   ->get();
    }

    /**
     * Obtener acciones por entidad
     */
    public function scopePorEntidad($query, string $entidad)
    {
        return $query->where('entidad', $entidad);
    }

    /**
     * Obtener acciones por usuario
     */
    public function scopePorUsuario($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Obtener acciones de un día específico
     */
    public function scopeDelDia($query, $fecha = null)
    {
        $fecha = $fecha ?? now()->toDateString();
        return $query->whereDate('created_at', $fecha);
    }
}
