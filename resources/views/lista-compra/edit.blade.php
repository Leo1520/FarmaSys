@extends('layouts.app')

@section('title', 'Editar Lista de Compra')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Editar Lista de Compra #{{ $listaCompra->id }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('lista-compra.show', $listaCompra->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Mensajes Flash -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Error!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Columna Principal -->
        <div class="col-md-8">
            <!-- Card: Información General -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Información General
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lista-compra.update', $listaCompra->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">Estado</label>
                            <select class="form-select form-select-lg @error('estado') is-invalid @enderror" 
                                    id="estado" 
                                    name="estado">
                                <option value="pendiente" {{ $listaCompra->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="comprada" {{ $listaCompra->estado === 'comprada' ? 'selected' : '' }}>Comprada</option>
                                <option value="cancelada" {{ $listaCompra->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label fw-bold">Notas</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" 
                                      id="notas" 
                                      name="notas" 
                                      rows="4"
                                      placeholder="Agregar observaciones...">{{ old('notas', $listaCompra->notas) }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card: Medicamentos -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam"></i> Medicamentos ({{ $detalles->count() }} items)
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Medicamento</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-right">Precio</th>
                                <th class="text-right">Subtotal</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detalles as $detalle)
                                <tr>
                                    <td>
                                        <strong>{{ $detalle->medicamento->nombre }}</strong><br>
                                        <small class="text-muted">{{ $detalle->medicamento->codigo ?? 'Sin código' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $detalle->cantidad_sugerida }}</span>
                                    </td>
                                    <td class="text-right">
                                        ${{ number_format($detalle->precio_unitario, 2) }}
                                    </td>
                                    <td class="text-right">
                                        <strong>${{ number_format($detalle->subtotal(), 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('lista-compra.remover', [$listaCompra->id, $detalle->id]) }}" 
                                              method="POST" 
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('¿Remover este medicamento?')"
                                                    title="Remover">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
                                    <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                    <td colspan="2" class="text-right fw-bold text-success h6" style="font-size: 1.2rem;">
                                        ${{ number_format($listaCompra->totalEstimado(), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Card: Agregar Medicamento -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Agregar Medicamento
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lista-compra.agregar', $listaCompra->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <label for="medicamento_id" class="form-label fw-bold">Medicamento</label>
                                <select class="form-select @error('medicamento_id') is-invalid @enderror" 
                                        id="medicamento_id" 
                                        name="medicamento_id" 
                                        required>
                                    <option value="">Seleccionar medicamento...</option>
                                    @foreach ($medicamentos as $medicamento)
                                        <option value="{{ $medicamento->id }}">
                                            {{ $medicamento->nombre }} (Stock: {{ $medicamento->stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('medicamento_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="cantidad_sugerida" class="form-label fw-bold">Cantidad</label>
                                <input type="number" 
                                       class="form-control @error('cantidad_sugerida') is-invalid @enderror" 
                                       id="cantidad_sugerida" 
                                       name="cantidad_sugerida" 
                                       min="1"
                                       value="1"
                                       required>
                                @error('cantidad_sugerida')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 mt-3">
                            <i class="bi bi-plus-circle"></i> Agregar a Lista
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card bg-light shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h6 class="card-title text-primary fw-bold mb-3">
                        <i class="bi bi-info-circle"></i> Resumen
                    </h6>

                    <p class="mb-2">
                        <strong>Total de Items:</strong><br>
                        <span class="h5">{{ $detalles->count() }}</span>
                    </p>

                    <p class="mb-2">
                        <strong>Costo Total:</strong><br>
                        <span class="h5 text-success">${{ number_format($listaCompra->totalEstimado(), 2) }}</span>
                    </p>

                    <p class="mb-2">
                        <strong>Estado Actual:</strong><br>
                        @if ($listaCompra->estado === 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @elseif ($listaCompra->estado === 'comprada')
                            <span class="badge bg-success">Comprada</span>
                        @else
                            <span class="badge bg-danger">Cancelada</span>
                        @endif
                    </p>

                    <hr>

                    <p class="mb-0 text-muted small">
                        <strong>Creada:</strong><br>
                        {{ $listaCompra->created_at->format('d/m/Y H:i:s') }}<br><br>
                        <strong>Última actualización:</strong><br>
                        {{ $listaCompra->updated_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
