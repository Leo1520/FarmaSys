<?php

namespace App\Traits;

use App\Models\HistorialAccion;

trait RegistraHistorial
{
    /**
     * Boot del trait - configura los eventos del modelo
     */
    public static function bootRegistraHistorial()
    {
        // Cuando se crea un modelo
        static::created(function ($model) {
            HistorialAccion::registrar(
                entidad: static::class,
                accion: 'crear',
                descripcion: "Se creó un nuevo " . self::getNombreEntidad() . ": {$model->getNombreParaHistorial()}",
                entidad_id: $model->id,
                cambios_nuevos: $model->getAttributes()
            );
        });

        // Cuando se actualiza un modelo
        static::updated(function ($model) {
            $cambios = $model->getChanges();
            
            if (!empty($cambios)) {
                HistorialAccion::registrar(
                    entidad: static::class,
                    accion: 'actualizar',
                    descripcion: "Se actualizó " . self::getNombreEntidad() . ": {$model->getNombreParaHistorial()}",
                    entidad_id: $model->id,
                    cambios_anteriores: $model->getOriginal(),
                    cambios_nuevos: $model->getAttributes()
                );
            }
        });

        // Cuando se elimina un modelo
        static::deleted(function ($model) {
            HistorialAccion::registrar(
                entidad: static::class,
                accion: 'eliminar',
                descripcion: "Se eliminó " . self::getNombreEntidad() . ": {$model->getNombreParaHistorial()}",
                entidad_id: $model->id,
                cambios_anteriores: $model->getAttributes()
            );
        });
    }

    /**
     * Obtener nombre amigable de la entidad
     */
    protected static function getNombreEntidad(): string
    {
        return match(static::class) {
            'App\Models\Medicamento' => 'medicamento',
            'App\Models\ListaCompra' => 'lista de compra',
            'App\Models\DetalleListaCompra' => 'detalle de lista',
            default => strtolower(class_basename(static::class))
        };
    }

    /**
     * Obtener nombre para mostrar en historial
     * Esta función debe ser implementada en cada modelo
     */
    public function getNombreParaHistorial(): string
    {
        return "ID: {$this->id}";
    }
}
