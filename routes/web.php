<?php

use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\ListaCompraController;

Route::resource('medicamentos', MedicamentoController::class);
Route::resource('lista-compra', ListaCompraController::class);

// Rutas para exportar a PDF
Route::get('medicamentos/exportar/pdf', [MedicamentoController::class, 'exportarPDF'])->name('medicamentos.exportar-pdf');
Route::get('medicamentos/{medicamento}/exportar/pdf', [MedicamentoController::class, 'exportarMedicamentoPDF'])->name('medicamentos.exportar-medicamento-pdf');
Route::get('lista-compra/{listaCompra}/exportar/pdf', [ListaCompraController::class, 'exportarPDF'])->name('lista-compra.exportar-pdf');

// Rutas adicionales para lista de compra
Route::post('lista-compra/{listaCompra}/agregar-medicamento', [ListaCompraController::class, 'agregarMedicamento'])->name('lista-compra.agregar');
Route::delete('lista-compra/{listaCompra}/detalle/{detalle}', [ListaCompraController::class, 'removerMedicamento'])->name('lista-compra.remover');
