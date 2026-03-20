@extends('layouts.app')

@section('title', 'Lista de Compra #' . $listaCompra->id)

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Lista de Compra #{{ $listaCompra->id }}</h1>
            <p class="text-muted">
                Creada el {{ $listaCompra->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('lista-compra.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="{{ route('lista-compra.edit', $listaCompra->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
        </div>
    </div>

    <!-- Estado -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <strong>Estado:</strong> <br>
                    @if ($listaCompra->estado === 'pendiente')
                        <span class="badge bg-warning text-dark h5">Pendiente</span>
                    @elseif ($listaCompra->estado === 'comprada')
                        <span class="badge bg-success h5">Comprada</span>
                    @else
                        <span class="badge bg-danger h5">Cancelada</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <strong>Total Estimado:</strong> <br>
                    <span class="h5 text-success">${{ number_format($listaCompra->totalEstimado(), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de medicamentos -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-box-seam"></i> Medicamentos ({{ $listaCompra->detalles()->count() }} items)
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Medicamento</th>
                        <th>Código</th>
                        <th class="text-center">Cantidad Sugerida</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detalles as $detalle)
                        <tr>
                            <td>
                                <strong>{{ $detalle->medicamento->nombre }}</strong>
                            </td>
                            <td>
                                {{ $detalle->medicamento->codigo ?? '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $detalle->cantidad_sugerida }}</span>
                            </td>
                            <td class="text-right">
                                ${{ number_format($detalle->precio_unitario, 2) }}
                            </td>
                            <td class="text-right">
                                <strong class="text-success">
                                    ${{ number_format($detalle->subtotal(), 2) }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay medicamentos en esta lista
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($detalles->count() > 0)
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="4" class="text-end fw-bold">TOTAL ESTIMADO:</td>
                            <td class="text-right fw-bold text-success h5">
                                ${{ number_format($listaCompra->totalEstimado(), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Notas -->
    @if ($listaCompra->notas)
        <div class="card shadow-sm mb-4 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-sticky"></i> Notas
                </h6>
            </div>
            <div class="card-body">
                {{ $listaCompra->notas }}
            </div>
        </div>
    @endif

    <!-- Acciones -->
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('lista-compra.edit', $listaCompra->id) }}" class="btn btn-warning btn-lg w-100 mb-2">
                <i class="bi bi-pencil"></i> Editar Lista
            </a>
        </div>
        <div class="col-md-6">
            <form action="{{ route('lista-compra.destroy', $listaCompra->id) }}" 
                  method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn btn-danger btn-lg w-100"
                        onclick="return confirm('¿Estás seguro?')">
                    <i class="bi bi-trash"></i> Eliminar Lista
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
