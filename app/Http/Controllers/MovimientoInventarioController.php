<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoInventarioController extends Controller
{
    /**
     * Display a listing of movimientos.
     */
    public function index(Request $request)
    {
        $query = MovimientoInventario::with(['medicamento', 'usuario']);

        // Filtrar por medicamento
        if ($request->filled('medicamento_id')) {
            $query->where('medicamento_id', $request->input('medicamento_id'));
        }

        // Filtrar por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        // Filtrar por razón
        if ($request->filled('razon')) {
            $query->where('razon', $request->input('razon'));
        }

        // Búsqueda en descripción
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('descripcion', 'like', "%{$search}%")
                  ->orWhereHas('medicamento', function ($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%");
                  });
        }

        $movimientos = $query->latest('created_at')->paginate(20);
        $medicamentos = Medicamento::orderBy('nombre')->get();

        return view('movimientos.index', compact('movimientos', 'medicamentos'));
    }

    /**
     * Show the form for creating a new movimiento.
     */
    public function create()
    {
        $medicamentos = Medicamento::orderBy('nombre')->get();
        
        return view('movimientos.create', compact('medicamentos'));
    }

    /**
     * Store a newly created movimiento in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicamento_id' => 'required|exists:medicamentos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'razon' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'precio_unitario' => 'nullable|numeric|min:0',
        ]);

        try {
            $movimiento = MovimientoInventario::registrar(
                medicamentoId: $validated['medicamento_id'],
                tipo: $validated['tipo'],
                cantidad: $validated['cantidad'],
                razon: $validated['razon'],
                descripcion: $validated['descripcion'],
                precioUnitario: $validated['precio_unitario'] ?? null
            );

            return redirect()->route('movimientos.show', $movimiento->id)
                           ->with('success', 'Movimiento de inventario registrado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['cantidad' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified movimiento.
     */
    public function show(MovimientoInventario $movimiento)
    {
        $movimiento->load(['medicamento', 'usuario']);
        
        return view('movimientos.show', compact('movimiento'));
    }

    /**
     * Get razones disponibles
     */
    public static function getRazones()
    {
        return [
            'compra' => 'Compra',
            'devolución' => 'Devolución',
            'ajuste' => 'Ajuste',
            'venta' => 'Venta',
            'pérdida' => 'Pérdida',
            'transferencia' => 'Transferencia',
            'otro' => 'Otro',
        ];
    }
}
