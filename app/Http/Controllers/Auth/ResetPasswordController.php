<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{
    /**
     * Mostrar formulario de reset de contraseña
     */
    public function show(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('login')
                ->withErrors(['password' => 'Token o email inválido.']);
        }

        // Obtener el usuario
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['password' => 'Usuario no encontrado.']);
        }

        // Verificar que el token exista en la base de datos
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetToken) {
            return redirect()->route('login')
                ->withErrors(['password' => 'Token inválido o expirado.']);
        }

        // Verificar que no haya expirado (2 horas)
        if (now()->diffInHours($resetToken->created_at) > 2) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['token' => 'El enlace ha expirado. Solicita uno nuevo.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Procesar el reset de contraseña
     */
    public function store(Request $request)
    {
        $email = $request->email;
        $token = $request->query('token');

        // Si no hay token en query, intentar desde el formulario
        if (!$token) {
            return redirect()->route('login')
                ->withErrors(['password' => 'Token inválido.']);
        }

        // Obtener usuario
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Usuario no encontrado.']);
        }

        // Obtener token de base de datos
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetToken) {
            return redirect()->route('login')
                ->withErrors(['password' => 'Token inválido o expirado.']);
        }

        // Validar que el token coincida
        if ($resetToken->token !== $token) {
            return back()->withErrors(['token' => 'Token inválido.']);
        }

        // Validar que no haya expirado
        if (now()->diffInHours($resetToken->created_at) > 2) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['token' => 'El enlace ha expirado. Solicita uno nuevo.']);
        }

        // Validar contraseña
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Registrar en historial
        \App\Models\HistorialAccion::create([
            'usuario_id' => $user->id,
            'accion' => 'reset_password',
            'descripcion' => "Usuario {$user->name} recuperó su contraseña",
            'tabla' => 'users',
            'registro_id' => $user->id,
            'cambios' => json_encode([
                'password' => 'actualizada',
            ]),
        ]);

        // Eliminar token de base de datos
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')
            ->with('success', 'Tu contraseña ha sido recuperada exitosamente. Por favor inicia sesión.');
    }
}
