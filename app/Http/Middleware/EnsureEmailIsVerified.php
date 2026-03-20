<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario no está autenticado, pasar
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Si el email ya está verificado, pasar
        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }

        // Rutas permitidas sin verificación de email
        $allowedRoutes = [
            'email.verify.show',
            'verification.send',
            'verification.resend',
            'email.verify',
            'logout',
        ];

        // Si está en ruta permitida, pasar
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // Si no está verificado y está en otra ruta, redirigir a verificación
        return redirect()->route('email.verify.show');
    }
}
