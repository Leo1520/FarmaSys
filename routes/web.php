<?php

use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\ListaCompraController;

Route::resource('medicamentos', MedicamentoController::class);
Route::resource('lista-compra', ListaCompraController::class);

// Rutas adicionales para lista de compra
Route::post('lista-compra/{listaCompra}/agregar-medicamento', [ListaCompraController::class, 'agregarMedicamento'])->name('lista-compra.agregar');
Route::delete('lista-compra/{listaCompra}/detalle/{detalle}', [ListaCompraController::class, 'removerMedicamento'])->name('lista-compra.remover');
