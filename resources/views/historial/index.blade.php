@extends('layouts.app')

@section('title', 'Auditoría - Historial de Acciones')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-clock-history"></i> Auditoría - Historial de Acciones
            </h1>
            <p class="text-muted">Registro completo de todas las acciones del sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('historial.personal') }}" class="btn btn-info">
                <i class="bi bi-person"></i> Mi Historial
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('historial.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Descripción..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Entidad</label>
                    <select name="entidad" class="form-select">
                        <option value="">-- Todas --</option>
                        <option value="App\Models\Medicamento" @selected(request('entidad') === 'App\Models\Medicamento')>Medicamentos</option>
                        <option value="App\Models\ListaCompra" @selected(request('entidad') === 'App\Models\ListaCompra')>Listas de Compra</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Acción</label>
                    <select name="accion" class="form-select">
                        <option value="">-- Todas --</option>
                        <option value="crear" @selected(request('accion') === 'crear')>Crear</option>
                        <option value="actualizar" @selected(request('accion') === 'actualizar')>Actualizar</option>
                        <option value="eliminar" @selected(request('accion') === 'eliminar')>Eliminar</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ route('historial.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Historial -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>IP</th>
                        <th>Acciones</th>
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
                                <strong>{{ $registro->usuario->name }}</strong>
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
                                    @case('ver')
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-eye"></i> Ver
                                        </span>
                                    @break
                                    @default
                                        <span class="badge bg-warning">{{ $registro->accion }}</span>
                                @endswitch
                            </td>
                            <td>
                                {{ $registro->descripcion }}
                            </td>
                            <td>
                                <small class="text-muted">{{ $registro->ip_address ?? '-' }}</small>
                            </td>
                            <td>
                                <a href="{{ route('historial.show', $registro->id) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> No hay registros para mostrar
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
