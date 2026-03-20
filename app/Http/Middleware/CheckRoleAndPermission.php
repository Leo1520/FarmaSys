<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAndPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = '', string $estado = 'activo'): Response
    {
        if (!auth()->check()) {
            return abort(401, 'No autenticado');
        }

        $user = auth()->user();

        // Verificar estado de la cuenta
        if ($estado !== '*' && $user->estado !== $estado) {
            if ($user->estado === 'pendiente') {
                return abort(403, 'Tu cuenta está pendiente de aprobación. Por favor, aguarda la confirmación del administrador.');
            } elseif ($user->estado === 'rechazado') {
                return abort(403, "Tu cuenta ha sido rechazada. Razón: {$user->razon_rechazo}");
            } elseif ($user->estado === 'inactivo') {
                return abort(403, 'Tu cuenta está inactiva.');
            }
        }

        // Verificar rol
        if ($role && !$this->hasRole($user, $role)) {
            return abort(403, 'No tienes permisos para acceder a este recurso.');
        }

        return $next($request);
    }

    /**
     * Verificar si el usuario tiene el rol requerido
     */
    private function hasRole($user, string $role): bool
    {
        $roles = explode('|', $role);
        return in_array($user->rol, $roles);
    }
}
