<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filtrar por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->input('rol'));
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $users = $query->latest('created_at')->paginate(15);
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validar que no se edite a sí mismo el rol (protección extra)
        if (Auth::id() === $user->id && $request->input('rol') !== $user->rol) {
            return back()->withErrors(['rol' => 'No puedes cambiar tu propio rol.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'rol' => ['required', 'in:admin,farmaceutica,invitado'],
            'estado' => ['required', 'in:pendiente,activo,rechazado,inactivo'],
        ]);

        $oldRol = $user->rol;
        $oldEstado = $user->estado;
        
        $user->update($validated);

        // Registrar cambios en historial si algo cambió
        if ($oldRol !== $validated['rol'] || $oldEstado !== $validated['estado']) {
            \App\Models\HistorialAccion::create([
                'usuario_id' => Auth::id(),
                'accion' => 'actualizar_usuario',
                'descripcion' => "Usuario {$user->name} actualizado",
                'tabla' => 'users',
                'registro_id' => $user->id,
                'cambios' => json_encode([
                    'rol' => ['de' => $oldRol, 'a' => $validated['rol']],
                    'estado' => ['de' => $oldEstado, 'a' => $validated['estado']],
                ]),
            ]);
        }

        return redirect()->route('users.index')
                       ->with('success', "Usuario '{$user->name}' actualizado correctamente.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Proteger contra eliminación de la cuenta actual
        if (Auth::id() === $user->id) {
            return back()->withErrors('No puedes eliminar tu propia cuenta.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
                       ->with('success', "Usuario '{$name}' eliminado correctamente.");
    }

    /**
     * Display the specified user's profile.
     */
    public function show(User $user)
    {
        // Los usuarios solo pueden ver su propio perfil
        if (Auth::id() !== $user->id && !Auth::user()->esAdmin()) {
            abort(403, 'No autorizado');
        }

        return view('users.show', compact('user'));
    }
}
