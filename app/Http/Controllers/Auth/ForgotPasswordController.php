<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class ForgotPasswordController extends Controller
{
    /**
     * Mostrar formulario de solicitud de recuperación
     */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar email con enlace de recuperación
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No encontramos un usuario con ese correo electrónico.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }

        // Generar token de reset
        $token = hash('sha256', $user->id . $user->email . time() . config('app.key'));

        // Guardar en base de datos
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Construir URL de reset
        $resetUrl = route('password.reset.form', ['token' => $token, 'email' => $user->email]);

        // Enviar email
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));

            return back()->with('success', 'Se ha enviado un enlace de recuperación a tu correo electrónico. Válido por 2 horas.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error al enviar el email. Intenta más tarde.']);
        }
    }
}
