<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $medicamentos = Medicamento::query()
            ->when($search, function ($query, $search) {
                return $query->where('nombre', 'like', '%' . $search . '%')
                             ->orWhere('codigo', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre')
            ->paginate(15);

        // Contadores para el dashboard
        $totalMedicamentos = Medicamento::count();
        $stockBajo = Medicamento::stockBajo()->count();
        $vencidos = Medicamento::vencidos()->count();
        $proximosAVencer = Medicamento::proximosAVencer()->count();

        return view('medicamentos.index', compact('medicamentos', 'search', 'totalMedicamentos', 'stockBajo', 'vencidos', 'proximosAVencer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('medicamentos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar datos
        $validated = $request->validate(Medicamento::rules());

        // Crear el medicamento
        Medicamento::create($validated);

        return redirect()
            ->route('medicamentos.index')
            ->with('success', 'Medicamento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicamento $medicamento): View
    {
        return view('medicamentos.show', compact('medicamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medicamento $medicamento): View
    {
        return view('medicamentos.edit', compact('medicamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medicamento $medicamento): RedirectResponse
    {
        // Validar datos con reglas específicas para update
        $validated = $request->validate(Medicamento::rulesForUpdate($medicamento->id));

        // Actualizar el medicamento
        $medicamento->update($validated);

        return redirect()
            ->route('medicamentos.index')
            ->with('success', 'Medicamento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicamento $medicamento): RedirectResponse
    {
        $nombre = $medicamento->nombre;
        $medicamento->delete();

        return redirect()
            ->route('medicamentos.index')
            ->with('success', "Medicamento '{$nombre}' eliminado exitosamente.");
    }
}
