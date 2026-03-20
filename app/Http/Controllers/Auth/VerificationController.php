<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    /**
     * Mostrar vista para solicitar email de verificación
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Enviar email de verificación
     */
    public function send(Request $request)
    {
        $user = Auth::user();

        // Si ya está verificado
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Tu correo electrónico ya ha sido verificado.');
        }

        // Generar token de verificación
        $token = hash('sha256', $user->id . $user->email . time());

        // Guardar token en sesión
        $request->session()->put('verification_token', [
            'token' => $token,
            'user_id' => $user->id,
            'expires_at' => now()->addHours(24),
        ]);

        // Construir URL de verificación
        $verificationUrl = route('email.verify', ['token' => $token]);

        // Enviar email
        try {
            Mail::to($user->email)->send(new VerifyEmailMail($user, $verificationUrl));

            return back()->with('success', 'Se ha enviado un enlace de verificación a tu correo electrónico.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error al enviar el email. Intenta más tarde.']);
        }
    }

    /**
     * Verificar email usando token
     */
    public function verify(Request $request)
    {
        $token = $request->query('token');

        // Buscar el token en la sesión de todos los usuarios
        // Esta es una aproximación simplificada; en producción usar base de datos
        $verificationToken = $request->session()->get('verification_token');

        if (!$verificationToken) {
            return redirect()->route('email.verify.show')
                ->withErrors(['token' => 'El token de verificación ha expirado.']);
        }

        // Validar token
        if ($verificationToken['token'] !== $token) {
            return redirect()->route('email.verify.show')
                ->withErrors(['token' => 'El token de verificación es inválido.']);
        }

        // Validar que no haya expirado
        if (now() > $verificationToken['expires_at']) {
            $request->session()->forget('verification_token');
            return redirect()->route('email.verify.show')
                ->withErrors(['token' => 'El token de verificación ha expirado.']);
        }

        // Obtener usuario
        $user = User::find($verificationToken['user_id']);

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Usuario no encontrado.']);
        }

        // Marcar email como verificado
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Limpiar token de sesión
        $request->session()->forget('verification_token');

        return redirect()->route('dashboard')
            ->with('success', '¡Correo electrónico verificado exitosamente!');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Tu correo electrónico ya ha sido verificado.');
        }

        // Enviar email nuevamente
        try {
            $token = hash('sha256', $user->id . $user->email . time());
            $request->session()->put('verification_token', [
                'token' => $token,
                'user_id' => $user->id,
                'expires_at' => now()->addHours(24),
            ]);

            $verificationUrl = route('email.verify', ['token' => $token]);
            Mail::to($user->email)->send(new VerifyEmailMail($user, $verificationUrl));

            return back()->with('success', 'Se ha reenviado el enlace de verificación.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error al enviar el email: ' . $e->getMessage()]);
        }
    }
}
