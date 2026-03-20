<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use App\Models\ListaCompra;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $totalMedicamentos = Medicamento::count();
        $stockBajo = Medicamento::stockBajo()->count();
        $vencidos = Medicamento::vencidos()->count();
        $proximosAVencer = Medicamento::proximosAVencer()->count();
        
        $listasCompra = ListaCompra::pendientes()->count();
        $medicamentosRegistrados = Medicamento::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalMedicamentos',
            'stockBajo',
            'vencidos',
            'proximosAVencer',
            'listasCompra',
            'medicamentosRegistrados'
        ));
    }
}
