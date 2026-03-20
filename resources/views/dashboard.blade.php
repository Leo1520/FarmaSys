@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid mt-5">
    <!-- Encabezado Bienvenida -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                👋 Bienvenido, <span class="text-primary">{{ Auth::user()->name }}</span>
            </h1>
            <p class="text-muted">
                <i class="bi bi-calendar3"></i> {{ now()->format('l, d \\de F \\de Y') }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-primary">
                Rol: 
                @if (Auth::user()->esAdmin())
                    <i class="bi bi-shield-check"></i> Administrador
                @else
                    <i class="bi bi-person"></i> Farmacéutica
                @endif
            </span>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total de Medicamentos</h6>
                    <h3 class="text-primary fw-bold">{{ $totalMedicamentos }}</h3>
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                        Ver Lista
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Stock Bajo</h6>
                    <h3 class="text-danger fw-bold">{{ $stockBajo }}</h3>
                    @if ($stockBajo > 0)
                        <a href="{{ route('medicamentos.index') }}?search=" class="btn btn-sm btn-outline-danger mt-2">
                            Revisar
                        </a>
                    @else
                        <span class="badge bg-success mt-2">Todo OK</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Medicamentos Vencidos</h6>
                    <h3 class="text-danger fw-bold">{{ $vencidos }}</h3>
                    @if ($vencidos > 0)
                        <span class="badge bg-danger mt-2">⚠️ Atención</span>
                    @else
                        <span class="badge bg-success mt-2">Ninguno</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Próximos a Vencer</h6>
                    <h3 class="text-warning fw-bold">{{ $proximosAVencer }}</h3>
                    @if ($proximosAVencer > 0)
                        <span class="badge bg-warning text-dark mt-2">30 días</span>
                    @else
                        <span class="badge bg-success mt-2">Ninguno</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">⚡ Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('medicamentos.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle"></i> Agregar Medicamento
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('lista-compra.create') }}" class="btn btn-success w-100">
                                <i class="bi bi-cart-check"></i> Nueva Lista Compra
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-info w-100">
                                <i class="bi bi-search"></i> Buscar Medicamento
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('lista-compra.index') }}" class="btn btn-warning w-100">
                                <i class="bi bi-list-check"></i> Mis Listas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medicamentos Registrados Recientemente -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📋 Últimos Medicamentos Registrados</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Código</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Registrado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($medicamentosRegistrados as $medicamento)
                                <tr>
                                    <td><strong>{{ $medicamento->nombre }}</strong></td>
                                    <td>{{ $medicamento->codigo ?? '-' }}</td>
                                    <td>${{ number_format($medicamento->precio, 2) }}</td>
                                    <td>
                                        @if ($medicamento->stock <= $medicamento->stock_minimo)
                                            <span class="badge bg-danger">{{ $medicamento->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $medicamento->stock }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $medicamento->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('medicamentos.show', $medicamento->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No hay medicamentos registrados aún
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Listas de Compra Pendientes -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">🛒 Listas de Compra Pendientes</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <strong>Total de listas pendientes:</strong>
                        <span class="badge bg-warning text-dark fs-6">{{ $listasCompra }}</span>
                    </p>
                    <a href="{{ route('lista-compra.index') }}" class="btn btn-warning w-100">
                        Ver Todas las Listas
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">ℹ️ Información de Cuenta</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Rol:</strong></td>
                            <td>
                                @if (Auth::user()->esAdmin())
                                    <span class="badge bg-danger">Administrador</span>
                                @else
                                    <span class="badge bg-primary">Farmacéutica</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Último Acceso:</strong></td>
                            <td>
                                @if (Auth::user()->ultimo_acceso)
                                    {{ Auth::user()->ultimo_acceso->format('d/m/Y H:i') }}
                                @else
                                    Primer acceso
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial Reciente -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Historial Reciente
                    </h5>
                    <a href="{{ route('historial.personal') }}" class="btn btn-sm btn-outline-primary">
                        Ver más
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Acción</th>
                                <th>Descripción</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $historialReciente = \App\Models\HistorialAccion::with('usuario')->latest('created_at')->limit(5)->get();
                            @endphp
                            @forelse ($historialReciente as $registro)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $registro->created_at->format('d/m/Y H:i') }}</small>
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
                                        <small>{{ $registro->usuario->name }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> Sin registros aún
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
