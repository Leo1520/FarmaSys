<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    /**
     * Display a listing of historial acciones.
     */
    public function index(Request $request)
    {
        $query = HistorialAccion::with('usuario');

        // Búsqueda por descripción
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('descripcion', 'like', "%{$search}%");
        }

        // Filtrar por entidad
        if ($request->filled('entidad')) {
            $query->where('entidad', $request->input('entidad'));
        }

        // Filtrar por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->input('accion'));
        }

        // Filtrar por usuario
        if ($request->filled('usuario_id')) {
            $query->where('user_id', $request->input('usuario_id'));
        }

        $historial = $query->latest('created_at')->paginate(20);

        return view('historial.index', compact('historial'));
    }

    /**
     * Display the specified historial record.
     */
    public function show(HistorialAccion $historialAccion)
    {
        return view('historial.show', ['historial' => $historialAccion]);
    }

    /**
     * Display user's personal historial.
     */
    public function personal(Request $request)
    {
        $query = HistorialAccion::where('user_id', Auth::id())
                               ->with('usuario');

        // Filtrar por entidad
        if ($request->filled('entidad')) {
            $query->where('entidad', $request->input('entidad'));
        }

        // Filtrar por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->input('accion'));
        }

        $historial = $query->latest('created_at')->paginate(20);

        return view('historial.personal', compact('historial'));
    }

    /**
     * Get entidades available
     */
    public static function getEntidades()
    {
        return [
            'App\Models\Medicamento' => 'Medicamentos',
            'App\Models\ListaCompra' => 'Listas de Compra',
            'App\Models\DetalleListaCompra' => 'Detalles',
        ];
    }

    /**
     * Get acciones available
     */
    public static function getAcciones()
    {
        return [
            'crear' => 'Crear',
            'actualizar' => 'Actualizar',
            'eliminar' => 'Eliminar',
            'ver' => 'Ver',
        ];
    }
}
