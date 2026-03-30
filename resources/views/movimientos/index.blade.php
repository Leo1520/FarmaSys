@extends('layouts.app')

@section('title', 'Movimientos de Inventario')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-arrow-left-right"></i> Movimientos de Inventario
            </h1>
            <p class="text-muted">Historial de entradas y salidas de medicamentos</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('movimientos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Movimiento
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('movimientos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Medicamento</label>
                    <select name="medicamento_id" class="form-select">
                        <option value="">-- Todos --</option>
                        @foreach ($medicamentos as $med)
                            <option value="{{ $med->id }}" @selected(request('medicamento_id') == $med->id)>
                                {{ $med->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="">-- Todos --</option>
                        <option value="entrada" @selected(request('tipo') === 'entrada')>📥 Entrada</option>
                        <option value="salida" @selected(request('tipo') === 'salida')>📤 Salida</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Razón</label>
                    <select name="razon" class="form-select">
                        <option value="">-- Todas --</option>
                        <option value="compra" @selected(request('razon') === 'compra')>Compra</option>
                        <option value="devolución" @selected(request('razon') === 'devolución')>Devolución</option>
                        <option value="ajuste" @selected(request('razon') === 'ajuste')>Ajuste</option>
                        <option value="venta" @selected(request('razon') === 'venta')>Venta</option>
                        <option value="pérdida" @selected(request('razon') === 'pérdida')>Pérdida</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ route('movimientos.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Medicamento</th>
                        <th>Tipo</th>
                        <th>Razón</th>
                        <th>Cantidad</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movimientos as $mov)
                        <tr>
                            <td>
                                <small class="text-muted">{{ $mov->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $mov->medicamento->nombre }}</strong>
                            </td>
                            <td>
                                @if ($mov->tipo === 'entrada')
                                    <span class="badge bg-success">
                                        <i class="bi bi-box-seam"></i> Entrada
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-box-seam"></i> Salida
                                    </span>
                                @endif
                            </td>
                            <td>
                                @switch($mov->razon)
                                    @case('compra')
                                        <span class="badge bg-info">🛒 Compra</span>
                                    @break
                                    @case('devolución')
                                        <span class="badge bg-warning">↩️ Devolución</span>
                                    @break
                                    @case('ajuste')
                                        <span class="badge bg-secondary">⚙️ Ajuste</span>
                                    @break
                                    @case('venta')
                                        <span class="badge bg-primary">💳 Venta</span>
                                    @break
                                    @case('pérdida')
                                        <span class="badge bg-danger">❌ Pérdida</span>
                                    @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ $mov->razon }}</span>
                                @endswitch
                            </td>
                            <td>
                                <strong>{{ $mov->cantidad }}</strong>
                                <small class="text-muted">unid.</small>
                            </td>
                            <td>
                                <small>{{ $mov->usuario->name }}</small>
                            </td>
                            <td>
                                <a href="{{ route('movimientos.show', $mov->id) }}" 
                                   class="btn btn-sm btn-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> No hay movimientos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $movimientos->links() }}
    </div>
</div>
@endsection
