@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-people-fill"></i> Gestión de Usuarios
            </h1>
            <p class="text-muted">Administra los usuarios del sistema</p>
        </div>
    </div>

    <!-- Mensajes de Éxito -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros y Búsqueda -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Buscar Usuario</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nombre o email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Rol</label>
                    <select name="rol" class="form-select">
                        <option value="">-- Todos los roles --</option>
                        <option value="admin" @selected(request('rol') === 'admin')>Administrador</option>
                        <option value="farmaceutica" @selected(request('rol') === 'farmaceutica')>Farmacéutica</option>
                        <option value="invitado" @selected(request('rol') === 'invitado')>Invitado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">-- Todos los estados --</option>
                        <option value="activo" @selected(request('estado') === 'activo')>Activo</option>
                        <option value="pendiente" @selected(request('estado') === 'pendiente')>Pendiente</option>
                        <option value="rechazado" @selected(request('estado') === 'rechazado')>Rechazado</option>
                        <option value="inactivo" @selected(request('estado') === 'inactivo')>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Registrado</th>
                        <th>Último Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if (Auth::id() === $user->id)
                                    <span class="badge bg-info ms-2">(Tú)</span>
                                @endif
                            </td>
                            <td><small>{{ $user->email }}</small></td>
                            <td>
                                @if ($user->esAdmin())
                                    <span class="badge bg-danger">
                                        <i class="bi bi-shield-check"></i> Admin
                                    </span>
                                @elseif ($user->esFarmaceutica())
                                    <span class="badge bg-primary">
                                        <i class="bi bi-person"></i> Farm.
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="bi bi-eye"></i> Invitado
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($user->estaActivo())
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Activo
                                    </span>
                                @elseif ($user->estaPendiente())
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass"></i> Pendiente
                                    </span>
                                @elseif ($user->estado === 'rechazado')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Rechazado
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-slash-circle"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                @if ($user->ultimo_acceso)
                                    <small class="text-muted">{{ $user->ultimo_acceso->diffForHumans() }}</small>
                                @else
                                    <small class="text-muted text-secondary">Sin acceso</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('users.edit', $user->id) }}" 
                                       class="btn btn-warning" title="Editar usuario">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($user->estaActivo() && Auth::user()->esAdmin())
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#desactivarModal{{ $user->id }}" 
                                                title="Desactivar usuario">
                                            <i class="bi bi-pause-circle"></i>
                                        </button>
                                    @elseif ($user->estado === 'inactivo' && Auth::user()->esAdmin())
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                data-bs-target="#reactivarModal{{ $user->id }}" 
                                                title="Reactivar usuario">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                    @endif
                                    @if (Auth::id() !== $user->id)
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('¿Estás seguro? No se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Eliminar usuario">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Desactivar Usuario -->
                        <div class="modal fade" id="desactivarModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Desactivar Usuario</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('users.desactivar', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <p>¿Deseas desactivar a <strong>{{ $user->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label for="razon" class="form-label">Razón (opcional)</label>
                                                <textarea class="form-control" name="razon" rows="2"
                                                    placeholder="Especifica la razón de la desactivación..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-pause-circle"></i> Desactivar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Reactivar Usuario -->
                        <div class="modal fade" id="reactivarModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reactivar Usuario</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('users.reactivar', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <p>¿Deseas reactivar a <strong>{{ $user->name }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-play-circle"></i> Reactivar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> No hay usuarios para mostrar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
