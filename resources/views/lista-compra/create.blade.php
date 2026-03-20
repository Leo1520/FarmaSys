@extends('layouts.app')

@section('title', 'Nueva Lista de Compra')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Crear Nueva Lista de Compra</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('lista-compra.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Crear Lista Automáticamente
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        FarmaSys generará automáticamente una nueva lista de compra con todos los medicamentos 
                        que tengan stock por debajo del mínimo establecido.
                    </p>

                    @if ($medicamentosStockBajo->count() > 0)
                        <div class="alert alert-info">
                            <strong>{{ $medicamentosStockBajo->count() }} medicamento(s)</strong> con stock bajo
                        </div>

                        <form action="{{ route('lista-compra.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="notas" class="form-label">Notas (Opcional)</label>
                                <textarea class="form-control" 
                                          id="notas" 
                                          name="notas" 
                                          rows="3"
                                          placeholder="Agregar observaciones sobre esta lista..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-check-circle"></i> Crear Lista Automáticamente
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <strong>¡Excelente!</strong> No hay medicamentos con stock bajo en este momento.
                        </div>
                        <a href="{{ route('lista-compra.index') }}" class="btn btn-secondary btn-lg w-100">
                            <i class="bi bi-arrow-left"></i> Volver a Listas
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle"></i> Cómo Funciona
                    </h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2">
                            <strong>1.</strong> Se detectan automáticamente todos los medicamentos con stock bajo
                        </li>
                        <li class="mb-2">
                            <strong>2.</strong> Se crea una lista con cantidades sugeridas
                        </li>
                        <li class="mb-2">
                            <strong>3.</strong> Se calcula automáticamente el costo estimado
                        </li>
                        <li>
                            <strong>4.</strong> Puedes editar y exportar a PDF
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
