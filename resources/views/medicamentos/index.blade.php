@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <!-- Encabezado con título y botón de crear -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Gestión de Medicamentos</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('medicamentos.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Agregar Medicamento
            </a>
        </div>
    </div>

    <!-- Dashboard de Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Total de Medicamentos</h6>
                    <h3 class="text-primary fw-bold">{{ $totalMedicamentos }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Stock Bajo</h6>
                    <h3 class="text-danger fw-bold">{{ $stockBajo }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Vencidos</h6>
                    <h3 class="text-danger fw-bold">{{ $vencidos }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Próximos a Vencer</h6>
                    <h3 class="text-warning fw-bold">{{ $proximosAVencer }}</h3>
                </div>
            </div>
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

    <!-- Barra de Búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('medicamentos.index') }}" class="d-flex gap-2">
                        <div class="flex-grow-1">
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   name="search" 
                                   placeholder="🔍 Buscar por nombre o código..." 
                                   value="{{ $search }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        @if ($search)
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        @endif
                    </form>
                    @if ($search)
                        <small class="text-muted d-block mt-2">
                            Resultados de búsqueda para: <strong>"{{ $search }}"</strong>
                        </small>
                    @endif
                </div>
                <div class="col-md-4">
                    <a href="{{ route('medicamentos.exportar-pdf') }}?{{ request()->getQueryString() }}" class="btn btn-danger btn-lg w-100">
                        <i class="bi bi-file-pdf"></i> Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de medicamentos -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Stock Mínimo</th>
                    <th>Vencimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicamentos as $medicamento)
                    <tr>
                        <td>
                            <span class="badge bg-secondary">{{ $medicamento->id }}</span>
                        </td>
                        <td>
                            <strong>{{ $medicamento->nombre }}</strong>
                        </td>
                        <td>
                            {{ $medicamento->codigo ?? '-' }}
                        </td>
                        <td>
                            <span class="text-success fw-bold">${{ number_format($medicamento->precio, 2) }}</span>
                        </td>
                        <td>
                            @if ($medicamento->stock <= $medicamento->stock_minimo)
                                <span class="badge bg-danger">{{ $medicamento->stock }} - ¡Bajo stock!</span>
                            @else
                                <span class="badge bg-success">{{ $medicamento->stock }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $medicamento->stock_minimo }}</span>
                        </td>
                        <td>
                            @if ($medicamento->fecha_vencimiento)
                                @if ($medicamento->fecha_vencimiento < now()->format('Y-m-d'))
                                    <span class="badge bg-danger" title="Medicamento vencido">
                                        {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
                                    </span>
                                @elseif ($medicamento->fecha_vencimiento <= now()->addDays(30)->format('Y-m-d'))
                                    <span class="badge bg-warning" title="Próximo a vencer">
                                        {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ $medicamento->fecha_vencimiento->format('d/m/Y') }}</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <!-- Botón Ver -->
                            <a href="{{ route('medicamentos.show', $medicamento->id) }}" 
                               class="btn btn-sm btn-info" 
                               title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>

                            <!-- Botón Editar -->
                            <a href="{{ route('medicamentos.edit', $medicamento->id) }}" 
                               class="btn btn-sm btn-warning" 
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('medicamentos.destroy', $medicamento->id) }}" 
                                  method="POST" 
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este medicamento?')"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox"></i> No hay medicamentos registrados aún.
                            <br>
                            <a href="{{ route('medicamentos.create') }}" class="btn btn-sm btn-primary mt-2">
                                Crear el primero
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if ($medicamentos->count())
        <div class="d-flex justify-content-center mt-4">
            {{ $medicamentos->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
