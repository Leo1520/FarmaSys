@extends('layouts.app')

@section('title', 'Detalle del Historial')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-clock-history"></i> Detalle del Registro
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('historial.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <!-- Información General -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información del Registro</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Fecha y Hora</label>
                            <p class="h6">{{ $historial->created_at->format('d \\de F \\de Y \\ a \\l\\a\\s H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Usuario</label>
                            <p class="h6">
                                <i class="bi bi-person"></i> {{ $historial->usuario->name }}
                                <small class="text-muted">({{ $historial->usuario->email }})</small>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Acción</label>
                            <p>
                                @switch($historial->accion)
                                    @case('crear')
                                        <span class="badge bg-success fs-6">
                                            <i class="bi bi-plus-circle"></i> Crear
                                        </span>
                                    @break
                                    @case('actualizar')
                                        <span class="badge bg-info fs-6">
                                            <i class="bi bi-pencil"></i> Actualizar
                                        </span>
                                    @break
                                    @case('eliminar')
                                        <span class="badge bg-danger fs-6">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </span>
                                    @break
                                    @default
                                        <span class="badge bg-warning fs-6">{{ $historial->accion }}</span>
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Entidad</label>
                            <p class="h6">
                                @switch($historial->entidad)
                                    @case('App\Models\Medicamento')
                                        <i class="bi bi-capsule"></i> Medicamento
                                    @break
                                    @case('App\Models\ListaCompra')
                                        <i class="bi bi-list-check"></i> Lista de Compra
                                    @break
                                    @default
                                        {{ $historial->entidad }}
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Descripción</label>
                        <p class="h6">{{ $historial->descripcion }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Dirección IP</label>
                            <p><code>{{ $historial->ip_address ?? 'No registrada' }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">User Agent</label>
                            <p>
                                <small class="text-muted">
                                    {{ $historial->user_agent ? Str::limit($historial->user_agent, 50) : 'No registrado' }}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cambios Realizados -->
            @if ($historial->cambios_nuevos || $historial->cambios_anteriores)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Cambios Realizados</h5>
                    </div>
                    <div class="card-body">
                        @if ($historial->accion === 'crear')
                            <h6>Valores Creados:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Campo</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($historial->cambios_nuevos as $campo => $valor)
                                            @if (!in_array($campo, ['created_at', 'updated_at', 'remember_token']))
                                                <tr>
                                                    <td><strong>{{ $campo }}</strong></td>
                                                    <td><code>{{ $valor }}</code></td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-muted">Sin cambios registrados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @elseif ($historial->accion === 'actualizar')
                            <h6>Comparación de Cambios:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Campo</th>
                                            <th class="bg-danger-light">Anterior</th>
                                            <th class="bg-success-light">Nuevo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $campos_anteriores = $historial->cambios_anteriores ?? [];
                                            $campos_nuevos = $historial->cambios_nuevos ?? [];
                                            $todos_campos = array_unique(array_merge(array_keys($campos_anteriores), array_keys($campos_nuevos)));
                                        @endphp
                                        @forelse ($todos_campos as $campo)
                                            @if (!in_array($campo, ['created_at', 'updated_at', 'remember_token']))
                                                <tr>
                                                    <td><strong>{{ $campo }}</strong></td>
                                                    <td class="text-danger">
                                                        <code>{{ $campos_anteriores[$campo] ?? '-' }}</code>
                                                    </td>
                                                    <td class="text-success">
                                                        <code>{{ $campos_nuevos[$campo] ?? '-' }}</code>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-muted">Sin cambios registrados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @elseif ($historial->accion === 'eliminar')
                            <h6>Valores Eliminados:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Campo</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($historial->cambios_anteriores as $campo => $valor)
                                            @if (!in_array($campo, ['created_at', 'updated_at', 'remember_token']))
                                                <tr>
                                                    <td><strong>{{ $campo }}</strong></td>
                                                    <td><code>{{ $valor }}</code></td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-muted">Sin cambios registrados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Información del Usuario (Sidebar) -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Usuario Responsable</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar" style="width: 60px; height: 60px; border-radius: 50%; background-color: #2c5aa0; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </div>
                    <p class="text-center">
                        <strong>{{ $historial->usuario->name }}</strong>
                    </p>
                    <p class="text-center text-muted">
                        <small>{{ $historial->usuario->email }}</small>
                    </p>
                    <hr>
                    <p>
                        <strong>Rol:</strong><br>
                        @if ($historial->usuario->esAdmin())
                            <span class="badge bg-danger">Administrador</span>
                        @else
                            <span class="badge bg-primary">Farmacéutica</span>
                        @endif
                    </p>
                    <p>
                        <strong>Registrado:</strong><br>
                        <small class="text-muted">{{ $historial->usuario->created_at->format('d/m/Y') }}</small>
                    </p>
                </div>
            </div>

            <!-- Información de Red -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información de Red</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Dirección IP:</strong><br>
                        <code>{{ $historial->ip_address ?? 'No registrada' }}</code>
                    </p>
                    <p>
                        <strong>Navegador:</strong><br>
                        <small class="text-muted">{{ $historial->user_agent ? Str::limit($historial->user_agent, 60) : 'No registrado' }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
