<?php

namespace App\Http\Controllers;

use App\Models\ListaCompra;
use App\Models\DetalleListaCompra;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ListaCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $listas = ListaCompra::orderBy('created_at', 'desc')->paginate(10);

        return view('lista-compra.index', compact('listas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $medicamentosStockBajo = Medicamento::stockBajo()->get();

        return view('lista-compra.create', compact('medicamentosStockBajo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'notas' => 'nullable|string|max:1000',
        ]);

        // Crear lista sugerida automáticamente
        $lista = ListaCompra::crearSugerida();

        return redirect()
            ->route('lista-compra.show', $lista->id)
            ->with('success', 'Lista de compra creada automáticamente con medicamentos de stock bajo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ListaCompra $listaCompra): View
    {
        $detalles = $listaCompra->detalles()->with('medicamento')->get();

        return view('lista-compra.show', compact('listaCompra', 'detalles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListaCompra $listaCompra): View
    {
        $detalles = $listaCompra->detalles()->with('medicamento')->get();
        $medicamentos = Medicamento::all();

        return view('lista-compra.edit', compact('listaCompra', 'detalles', 'medicamentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListaCompra $listaCompra): RedirectResponse
    {
        $request->validate([
            'estado' => 'required|in:pendiente,comprada,cancelada',
            'notas' => 'nullable|string|max:1000',
        ]);

        $listaCompra->update($request->only('estado', 'notas'));

        return redirect()
            ->route('lista-compra.show', $listaCompra->id)
            ->with('success', 'Lista de compra actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListaCompra $listaCompra): RedirectResponse
    {
        $listaCompra->delete();

        return redirect()
            ->route('lista-compra.index')
            ->with('success', 'Lista de compra eliminada.');
    }

    /**
     * Agregar medicamento a lista
     */
    public function agregarMedicamento(Request $request, ListaCompra $listaCompra): RedirectResponse
    {
        $request->validate([
            'medicamento_id' => 'required|exists:medicamentos,id',
            'cantidad_sugerida' => 'required|integer|min:1',
        ]);

        // Verificar si el medicamento ya está en la lista
        $existe = DetalleListaCompra::where('lista_compra_id', $listaCompra->id)
            ->where('medicamento_id', $request->medicamento_id)
            ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Este medicamento ya está en la lista.');
        }

        $medicamento = Medicamento::find($request->medicamento_id);

        DetalleListaCompra::create([
            'lista_compra_id' => $listaCompra->id,
            'medicamento_id' => $request->medicamento_id,
            'cantidad_sugerida' => $request->cantidad_sugerida,
            'precio_unitario' => $medicamento->precio,
        ]);

        return redirect()->back()->with('success', 'Medicamento agregado a la lista.');
    }

    /**
     * Remover medicamento de lista
     */
    public function removerMedicamento(ListaCompra $listaCompra, DetalleListaCompra $detalle): RedirectResponse
    {
        if ($detalle->lista_compra_id !== $listaCompra->id) {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $detalle->delete();

        return redirect()->back()->with('success', 'Medicamento removido de la lista.');
    }

    /**
     * Exportar lista de compra a PDF
     */
    public function exportarPDF(ListaCompra $listaCompra)
    {
        $detalles = $listaCompra->detalles()->with('medicamento')->get();

        $pdf = Pdf::loadView('lista-compra.pdf-show', compact('listaCompra', 'detalles'));
        
        return $pdf->download('lista-compra-' . $listaCompra->id . '-' . date('Y-m-d') . '.pdf');
    }
}
