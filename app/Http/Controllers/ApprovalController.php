<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Mostrar lista de usuarios pendientes de aprobación.
     */
    public function pendientes()
    {
        $usuarios_pendientes = User::where('estado', 'pendiente')->get();
        
        return view('approval.pendientes', compact('usuarios_pendientes'));
    }

    /**
     * Aprobar usuario: cambiar estado a 'activo' y asignar rol.
     */
    public function aprobar(Request $request, User $user)
    {
        $request->validate([
            'rol' => ['required', 'in:farmaceutica,admin'],
        ]);

        if ($user->estado !== 'pendiente') {
            return redirect()->route('approval.pendientes')
                ->with('error', 'Este usuario no está pendiente de aprobación.');
        }

        // Guardar rol anterior (será null para nuevos registros)
        $rol_anterior = $user->rol;

        // Actualizar estado y rol
        $user->update([
            'rol' => $request->rol,
            'estado' => 'activo',
            'email_verified_at' => now(), // Marcar como verificado
        ]);

        // Registrar en historial
        HistorialAccion::create([
            'usuario_id' => Auth::id(),
            'accion' => 'aprobar_usuario',
            'descripcion' => "Usuario {$user->name} aprobado como {$request->rol} (pendiente: {$rol_anterior} → activo: {$request->rol})",
            'tabla' => 'users',
            'registro_id' => $user->id,
            'cambios' => json_encode([
                'estado' => ['de' => 'pendiente', 'a' => 'activo'],
                'rol' => ['de' => $rol_anterior, 'a' => $request->rol],
            ]),
        ]);

        return redirect()->route('approval.pendientes')
            ->with('success', "Usuario {$user->name} aprobado exitosamente como {$request->rol}.");
    }

    /**
     * Rechazar usuario: cambiar estado a 'rechazado'.
     */
    public function rechazar(Request $request, User $user)
    {
        $request->validate([
            'razon_rechazo' => ['required', 'string', 'max:500'],
        ]);

        if ($user->estado !== 'pendiente') {
            return redirect()->route('approval.pendientes')
                ->with('error', 'Este usuario no está pendiente de aprobación.');
        }

        // Actualizar estado
        $user->update([
            'estado' => 'rechazado',
            'razon_rechazo' => $request->razon_rechazo,
        ]);

        // Registrar en historial
        HistorialAccion::create([
            'usuario_id' => Auth::id(),
            'accion' => 'rechazar_usuario',
            'descripcion' => "Usuario {$user->name} rechazado. Razón: {$request->razon_rechazo}",
            'tabla' => 'users',
            'registro_id' => $user->id,
            'cambios' => json_encode([
                'estado' => ['de' => 'pendiente', 'a' => 'rechazado'],
                'razon_rechazo' => $request->razon_rechazo,
            ]),
        ]);

        return redirect()->route('approval.pendientes')
            ->with('success', "Usuario {$user->name} rechazado exitosamente.");
    }

    /**
     * Cambiar rol de un usuario activo.
     */
    public function cambiarRol(Request $request, User $user)
    {
        $request->validate([
            'rol' => ['required', 'in:farmaceutica,admin'],
        ]);

        if ($user->estado !== 'activo') {
            return redirect()->route('users.index')
                ->with('error', 'Solo se puede cambiar rol a usuarios activos.');
        }

        $rol_anterior = $user->rol;

        $user->update(['rol' => $request->rol]);

        // Registrar en historial
        HistorialAccion::create([
            'usuario_id' => Auth::id(),
            'accion' => 'cambiar_rol',
            'descripcion' => "Rol de {$user->name} cambiado de {$rol_anterior} a {$request->rol}",
            'tabla' => 'users',
            'registro_id' => $user->id,
            'cambios' => json_encode([
                'rol' => ['de' => $rol_anterior, 'a' => $request->rol],
            ]),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Rol de {$user->name} cambiado a {$request->rol} exitosamente.");
    }

    /**
     * Desactivar usuario: cambiar estado a 'inactivo'.
     */
    public function desactivar(Request $request, User $user)
    {
        $request->validate([
            'razon' => ['nullable', 'string', 'max:500'],
        ]);

        if ($user->estado === 'inactivo') {
            return redirect()->route('users.index')
                ->with('error', 'Este usuario ya está inactivo.');
        }

        $estado_anterior = $user->estado;
        $user->update(['estado' => 'inactivo']);

        // Registrar en historial
        HistorialAccion::create([
            'usuario_id' => Auth::id(),
            'accion' => 'desactivar_usuario',
            'descripcion' => "Usuario {$user->name} desactivado (anterior: {$estado_anterior}). Razón: {$request->razon}",
            'tabla' => 'users',
            'registro_id' => $user->id,
            'cambios' => json_encode([
                'estado' => ['de' => $estado_anterior, 'a' => 'inactivo'],
            ]),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$user->name} desactivado exitosamente.");
    }

    /**
     * Reactivar usuario: cambiar estado de 'inactivo' a 'activo'.
     */
    public function reactivar(User $user)
    {
        if ($user->estado !== 'inactivo') {
            return redirect()->route('users.index')
                ->with('error', 'Este usuario no está inactivo.');
        }

        $user->update(['estado' => 'activo']);

        // Registrar en historial
        HistorialAccion::create([
            'usuario_id' => Auth::id(),
            'accion' => 'reactivar_usuario',
            'descripcion' => "Usuario {$user->name} reactivado (inactivo → activo)",
            'tabla' => 'users',
            'registro_id' => $user->id,
            'cambios' => json_encode([
                'estado' => ['de' => 'inactivo', 'a' => 'activo'],
            ]),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$user->name} reactivado exitosamente.");
    }
}
