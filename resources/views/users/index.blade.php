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
                <div class="col-md-6">
                    <label class="form-label">Buscar Usuario</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nombre o email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filtrar por Rol</label>
                    <select name="rol" class="form-select">
                        <option value="">-- Todos los roles --</option>
                        <option value="admin" @selected(request('rol') === 'admin')>Administrador</option>
                        <option value="farmaceutica" @selected(request('rol') === 'farmaceutica')>Farmacéutica</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Buscar
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
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->esAdmin())
                                    <span class="badge bg-danger">
                                        <i class="bi bi-shield-check"></i> Administrador
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="bi bi-person"></i> Farmacéutica
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
                                       class="btn btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if (Auth::id() !== $user->id)
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar a {{ $user->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
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
