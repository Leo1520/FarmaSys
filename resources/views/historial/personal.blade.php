@extends('layouts.app')

@section('title', 'Mi Historial')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-clock-history"></i> Mi Historial de Acciones
            </h1>
            <p class="text-muted">Registro de tus actividades en el sistema</p>
        </div>
        <div class="col-md-4 text-end">
            @if (Auth::user()->esAdmin())
                <a href="{{ route('historial.index') }}" class="btn btn-warning">
                    <i class="bi bi-shield-check"></i> Auditoría Completa
                </a>
            @endif
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('historial.personal') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Filtrar por Entidad</label>
                    <select name="entidad" class="form-select">
                        <option value="">-- Todas --</option>
                        <option value="App\Models\Medicamento" @selected(request('entidad') === 'App\Models\Medicamento')>Medicamentos</option>
                        <option value="App\Models\ListaCompra" @selected(request('entidad') === 'App\Models\ListaCompra')>Listas de Compra</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filtrar por Acción</label>
                    <select name="accion" class="form-select">
                        <option value="">-- Todas --</option>
                        <option value="crear" @selected(request('accion') === 'crear')>Crear</option>
                        <option value="actualizar" @selected(request('accion') === 'actualizar')>Actualizar</option>
                        <option value="eliminar" @selected(request('accion') === 'eliminar')>Eliminar</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                    <a href="{{ route('historial.personal') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Acciones Realizadas</h6>
                    <h3 class="text-primary fw-bold">
                        {{ \App\Models\HistorialAccion::where('user_id', Auth::id())->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Hoy</h6>
                    <h3 class="text-info fw-bold">
                        {{ \App\Models\HistorialAccion::where('user_id', Auth::id())->delDia(now()->toDateString())->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Medicamentos</h6>
                    <h3 class="text-success fw-bold">
                        {{ \App\Models\HistorialAccion::where('user_id', Auth::id())->where('entidad', 'App\Models\Medicamento')->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Últimas 7 Días</h6>
                    <h3 class="text-warning fw-bold">
                        {{ \App\Models\HistorialAccion::where('user_id', Auth::id())->where('created_at', '>=', now()->subDays(7))->count() }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Historial -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Entidad</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($historial as $registro)
                        <tr>
                            <td>
                                <small class="text-muted">
                                    {{ $registro->created_at->format('d/m/Y H:i:s') }}
                                </small>
                            </td>
                            <td>
                                @switch($registro->accion)
                                    @case('crear')
                                        <span class="badge bg-success">
                                            <i class="bi bi-plus-circle"></i> Crear
                                        </span>
                                    @break
                                    @case('actualizar')
                                        <span class="badge bg-info">
                                            <i class="bi bi-pencil"></i> Actualizar
                                        </span>
                                    @break
                                    @case('eliminar')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </span>
                                    @break
                                    @default
                                        <span class="badge bg-secondary">{{ $registro->accion }}</span>
                                @endswitch
                            </td>
                            <td>
                                {{ $registro->descripcion }}
                            </td>
                            <td>
                                <small>
                                    @switch($registro->entidad)
                                        @case('App\Models\Medicamento')
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-capsule"></i> Medicamento
                                            </span>
                                        @break
                                        @case('App\Models\ListaCompra')
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-list-check"></i> Lista
                                            </span>
                                        @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ class_basename($registro->entidad) }}</span>
                                    @endswitch
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('historial.show', $registro->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> No hay registros en tu historial
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $historial->links() }}
    </div>
</div>
@endsection
