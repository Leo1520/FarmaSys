@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Aprobación de Usuarios Pendientes</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if ($usuarios_pendientes->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> No hay usuarios pendientes de aprobación.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol Solicitado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios_pendientes as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $user->email }}</code>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($user->rol) }}</span>
                            </td>
                            <td>
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <!-- Botón Aprobar -->
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#aprobarModal{{ $user->id }}" title="Aprobar usuario">
                                    <i class="bi bi-check-circle"></i> Aprobar
                                </button>

                                <!-- Botón Rechazar -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#rechazarModal{{ $user->id }}" title="Rechazar usuario">
                                    <i class="bi bi-x-circle"></i> Rechazar
                                </button>

                                <!-- Modal Aprobar -->
                                <div class="modal fade" id="aprobarModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Aprobar Usuario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('approval.aprobar', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <p>¿Deseas aprobar a <strong>{{ $user->name }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label for="rol" class="form-label">Asignar Rol</label>
                                                        <select class="form-select" id="rol" name="rol" required>
                                                            <option value="invitado" selected>Invitado (Solo lectura)</option>
                                                            <option value="farmaceutica">Farmacéutica (Lectura/Escritura)</option>
                                                            <option value="admin">Administrador (Control total)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-circle"></i> Aprobar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Rechazar -->
                                <div class="modal fade" id="rechazarModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rechazar Usuario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('approval.rechazar', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <p>¿Deseas rechazar a <strong>{{ $user->name }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label for="razon_rechazo" class="form-label">Razón del Rechazo</label>
                                                        <textarea class="form-control" id="razon_rechazo" name="razon_rechazo" rows="3"
                                                            placeholder="Especifica la razón del rechazo..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-x-circle"></i> Rechazar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información de Roles</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-success"><i class="bi bi-eye"></i> Invitado</h6>
                        <small class="text-muted">Solo lectura de medicamentos. Ideal para auditorías.</small>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-info"><i class="bi bi-pencil"></i> Farmacéutica</h6>
                        <small class="text-muted">Lectura y escritura en medicamentos y movimientos. No puede gestionar usuarios.</small>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-danger"><i class="bi bi-shield-lock"></i> Administrador</h6>
                        <small class="text-muted">Control total del sistema incluyendo gestión de usuarios y configuración.</small>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
</style>
@endsection
