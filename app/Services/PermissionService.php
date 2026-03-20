<?php

namespace App\Services;

class PermissionService
{
    /**
     * Definir permisos por rol
     */
    public static function getPermissions(string $rol): array
    {
        return match ($rol) {
            'admin' => self::getAdminPermissions(),
            'farmaceutica' => self::getFarmaceuticaPermissions(),
            'invitado' => self::getGuestPermissions(),
            default => [],
        };
    }

    /**
     * Permisos de Administrador (acceso completo)
     */
    private static function getAdminPermissions(): array
    {
        return [
            // Medicamentos
            'medicamentos.ver' => true,
            'medicamentos.crear' => true,
            'medicamentos.editar' => true,
            'medicamentos.eliminar' => true,
            'medicamentos.exportar' => true,

            // Movimientos
            'movimientos.ver' => true,
            'movimientos.crear' => true,
            'movimientos.editar' => true,
            'movimientos.eliminar' => true,

            // Listas de Compra
            'lista-compra.ver' => true,
            'lista-compra.crear' => true,
            'lista-compra.editar' => true,
            'lista-compra.eliminar' => true,
            'lista-compra.exportar' => true,

            // Usuarios
            'usuarios.ver' => true,
            'usuarios.crear' => true,
            'usuarios.editar' => true,
            'usuarios.eliminar' => true,
            'usuarios.aprobar' => true,
            'usuarios.gestionar-roles' => true,

            // Historial
            'historial.ver-completo' => true,
            'historial.ver-personal' => true,

            // Dashboard
            'dashboard.ver' => true,
            'dashboard.estadisticas-avanzadas' => true,

            // Configuración
            'configuracion.acceder' => true,
        ];
    }

    /**
     * Permisos de Farmacéutica (acceso limitado)
     */
    private static function getFarmaceuticaPermissions(): array
    {
        return [
            // Medicamentos
            'medicamentos.ver' => true,
            'medicamentos.crear' => true,
            'medicamentos.editar' => true,
            'medicamentos.eliminar' => false, // No puede eliminar
            'medicamentos.exportar' => true,

            // Movimientos
            'movimientos.ver' => true,
            'movimientos.crear' => true,
            'movimientos.editar' => false, // No puede editar movimientos registrados
            'movimientos.eliminar' => false, // No puede eliminar

            // Listas de Compra
            'lista-compra.ver' => true,
            'lista-compra.crear' => true,
            'lista-compra.editar' => true,
            'lista-compra.eliminar' => false, // No puede eliminar
            'lista-compra.exportar' => true,

            // Usuarios (solo ver su perfil)
            'usuarios.ver' => false,
            'usuarios.crear' => false,
            'usuarios.editar' => false, // Solo su propio perfil
            'usuarios.eliminar' => false,
            'usuarios.aprobar' => false,
            'usuarios.gestionar-roles' => false,

            // Historial
            'historial.ver-completo' => false,
            'historial.ver-personal' => true,

            // Dashboard
            'dashboard.ver' => true,
            'dashboard.estadisticas-avanzadas' => false,

            // Configuración
            'configuracion.acceder' => false,
        ];
    }

    /**
     * Permisos de Invitado (muy limitado)
     */
    private static function getGuestPermissions(): array
    {
        return [
            // Medicamentos (solo lectura)
            'medicamentos.ver' => true,
            'medicamentos.crear' => false,
            'medicamentos.editar' => false,
            'medicamentos.eliminar' => false,
            'medicamentos.exportar' => false,

            // Movimientos
            'movimientos.ver' => false,
            'movimientos.crear' => false,
            'movimientos.editar' => false,
            'movimientos.eliminar' => false,

            // Listas de Compra
            'lista-compra.ver' => false,
            'lista-compra.crear' => false,
            'lista-compra.editar' => false,
            'lista-compra.eliminar' => false,
            'lista-compra.exportar' => false,

            // Usuarios
            'usuarios.ver' => false,
            'usuarios.crear' => false,
            'usuarios.editar' => false,
            'usuarios.eliminar' => false,
            'usuarios.aprobar' => false,
            'usuarios.gestionar-roles' => false,

            // Historial
            'historial.ver-completo' => false,
            'historial.ver-personal' => false,

            // Dashboard
            'dashboard.ver' => false,
            'dashboard.estadisticas-avanzadas' => false,

            // Configuración
            'configuracion.acceder' => false,
        ];
    }

    /**
     * Verificar si un usuario tiene un permiso específico
     */
    public static function can(string $permiso, ?string $rol = null): bool
    {
        $rol = $rol ?? auth()->user()->rol ?? 'invitado';
        $permisos = self::getPermissions($rol);
        return $permisos[$permiso] ?? false;
    }

    /**
     * Verificar si un usuario tiene un permiso (usado en Blade)
     */
    public static function userCan(string $permiso): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return self::can($permiso, auth()->user()->rol);
    }
}
