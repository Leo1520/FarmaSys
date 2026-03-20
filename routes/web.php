<?php

use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\ListaCompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Ruta raíz - redirecciona a dashboard si está autenticado, a login si no
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
})->name('home');

// Rutas públicas de autenticación
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');
Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');

// Rutas de recuperación de contraseña
Route::get('forgot-password', [ForgotPasswordController::class, 'show'])->name('password.forgot');
Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.forgot.store');
Route::get('reset-password', [ResetPasswordController::class, 'show'])->name('password.reset.form');
Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.reset.store');

// Rutas de verificación de email (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('email/verify', [VerificationController::class, 'show'])->name('email.verify.show');
    Route::post('email/verify/send', [VerificationController::class, 'send'])->name('verification.send');
    Route::post('email/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('verify-email/{token}', [VerificationController::class, 'verify'])->name('email.verify');
});

// Rutas protegidas (requieren autenticación Y verificación de email)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ruta de perfil personal (accesible para todos)
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    
    // Rutas de Gestión de Usuarios (solo admin)
    Route::middleware('admin')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::put('users/{user}/cambiar-rol', [ApprovalController::class, 'cambiarRol'])->name('users.cambiar-rol');
        Route::put('users/{user}/desactivar', [ApprovalController::class, 'desactivar'])->name('users.desactivar');
        Route::put('users/{user}/reactivar', [ApprovalController::class, 'reactivar'])->name('users.reactivar');
    });
    
    // Rutas de Aprobación de Usuarios (solo admin)
    Route::middleware('admin')->group(function () {
        Route::get('approval/pendientes', [ApprovalController::class, 'pendientes'])->name('approval.pendientes');
        Route::put('approval/{user}/aprobar', [ApprovalController::class, 'aprobar'])->name('approval.aprobar');
        Route::put('approval/{user}/rechazar', [ApprovalController::class, 'rechazar'])->name('approval.rechazar');
    });
    
    Route::resource('medicamentos', MedicamentoController::class);
    Route::resource('lista-compra', ListaCompraController::class);
    
    // Rutas de Movimientos de Inventario
    Route::resource('movimientos', MovimientoInventarioController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('movimientos/{movimiento}/enviar-comprobante', [MovimientoInventarioController::class, 'mostrarFormularioEnvioComprobante'])->name('movimientos.mostrar-envio-comprobante');
    Route::post('movimientos/{movimiento}/enviar-comprobante', [MovimientoInventarioController::class, 'enviarComprobante'])->name('movimientos.enviar-comprobante');
    Route::get('movimientos/{movimiento}/exportar/pdf', [MovimientoInventarioController::class, 'exportarPDF'])->name('movimientos.exportar-pdf');

    // Rutas para exportar a PDF
    Route::get('medicamentos/exportar/pdf', [MedicamentoController::class, 'exportarPDF'])->name('medicamentos.exportar-pdf');
    Route::get('medicamentos/{medicamento}/exportar/pdf', [MedicamentoController::class, 'exportarMedicamentoPDF'])->name('medicamentos.exportar-medicamento-pdf');
    Route::get('lista-compra/{listaCompra}/exportar/pdf', [ListaCompraController::class, 'exportarPDF'])->name('lista-compra.exportar-pdf');

    // Rutas adicionales para lista de compra
    Route::post('lista-compra/{listaCompra}/agregar-medicamento', [ListaCompraController::class, 'agregarMedicamento'])->name('lista-compra.agregar');
    Route::delete('lista-compra/{listaCompra}/detalle/{detalle}', [ListaCompraController::class, 'removerMedicamento'])->name('lista-compra.remover');
    
    // Rutas de Historial/Auditoría
    Route::get('historial/personal', [HistorialController::class, 'personal'])->name('historial.personal');
    Route::get('historial/{historialAccion}', [HistorialController::class, 'show'])->name('historial.show');
    
    // Rutas de Auditoría (solo admin)
    Route::middleware('admin')->group(function () {
        Route::get('historial', [HistorialController::class, 'index'])->name('historial.index');
    });
});
