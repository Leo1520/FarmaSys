<?php

use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\ListaCompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Ruta raíz - redirecciona a dashboard si está autenticado, a login si no
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
})->name('home');

// Rutas públicas de autenticación
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');
Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('medicamentos', MedicamentoController::class);
    Route::resource('lista-compra', ListaCompraController::class);

    // Rutas para exportar a PDF
    Route::get('medicamentos/exportar/pdf', [MedicamentoController::class, 'exportarPDF'])->name('medicamentos.exportar-pdf');
    Route::get('medicamentos/{medicamento}/exportar/pdf', [MedicamentoController::class, 'exportarMedicamentoPDF'])->name('medicamentos.exportar-medicamento-pdf');
    Route::get('lista-compra/{listaCompra}/exportar/pdf', [ListaCompraController::class, 'exportarPDF'])->name('lista-compra.exportar-pdf');

    // Rutas adicionales para lista de compra
    Route::post('lista-compra/{listaCompra}/agregar-medicamento', [ListaCompraController::class, 'agregarMedicamento'])->name('lista-compra.agregar');
    Route::delete('lista-compra/{listaCompra}/detalle/{detalle}', [ListaCompraController::class, 'removerMedicamento'])->name('lista-compra.remover');
});
