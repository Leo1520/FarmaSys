@extends('layouts.app')

@section('title', $medicamento->nombre)

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">{{ $medicamento->nombre }}</h1>
            @if ($medicamento->codigo)
                <p class="text-muted mb-0">
                    <i class="bi bi-barcode"></i> Código: <strong>{{ $medicamento->codigo }}</strong>
                </p>
            @endif
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="{{ route('medicamentos.edit', $medicamento->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
        </div>
    </div>

    <!-- Alertas de estado -->
    @if ($medicamento->stock <= $medicamento->stock_minimo)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <strong>¡Stock Bajo!</strong> Solo quedan {{ $medicamento->stock }} unidades (mínimo: {{ $medicamento->stock_minimo }})
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($medicamento->fecha_vencimiento && $medicamento->fecha_vencimiento < now())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle-fill"></i>
            <strong>¡Medicamento Vencido!</strong> Vencimiento: {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif ($medicamento->fecha_vencimiento && $medicamento->fecha_vencimiento <= now()->addDays(30))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <strong>¡Próximo a Vencer!</strong> Vencimiento: {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <!-- Card Info Básica -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-capsule"></i> Información Básica
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong>Nombre:</strong> <br>
                                <span class="h5 text-primary">{{ $medicamento->nombre }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong>Código:</strong> <br>
                                <span class="text-muted">
                                    {{ $medicamento->codigo ?? 'No especificado' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <p class="mb-0 text-muted small">
                        <i class="bi bi-calendar"></i> Registrado el {{ $medicamento->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Card Info Financiera -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-currency-dollar"></i> Información Financiera
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-0">
                                <strong>Precio Unitario:</strong> <br>
                                <span class="h4 text-success">${{ number_format($medicamento->precio, 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Info Inventario -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam"></i> Información de Inventario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-0">
                                <strong>Stock Actual:</strong> <br>
                                @if ($medicamento->stock <= $medicamento->stock_minimo)
                                    <span class="h5 text-danger fw-bold">{{ $medicamento->stock }} unidades</span>
                                    <span class="badge bg-danger ms-2">Bajo</span>
                                @else
                                    <span class="h5 text-success fw-bold">{{ $medicamento->stock }} unidades</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">
                                <strong>Stock Mínimo:</strong> <br>
                                <span class="h5 text-warning fw-bold">{{ $medicamento->stock_minimo }} unidades</span>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-light mb-0">
                        <small class="text-muted">
                            Se genera una alerta cuando el stock llega o desciende por debajo del stock mínimo.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Card Info Vencimiento -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i> Información de Vencimiento
                    </h5>
                </div>
                <div class="card-body">
                    @if ($medicamento->fecha_vencimiento)
                        <p class="mb-0">
                            <strong>Fecha de Vencimiento:</strong> <br>
                            @if ($medicamento->fecha_vencimiento < now())
                                <span class="h5 text-danger fw-bold">
                                    {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
                                    <span class="badge bg-danger">Vencido</span>
                                </span>
                            @elseif ($medicamento->fecha_vencimiento <= now()->addDays(30))
                                <span class="h5 text-warning fw-bold">
                                    {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
                                    <span class="badge bg-warning">Próximo a vencer</span>
                                </span>
                            @else
                                <span class="h5 text-success fw-bold">
                                    {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
                                    <span class="badge bg-success">OK</span>
                                </span>
                            @endif
                        </p>
                    @else
                        <p class="mb-0 text-muted">
                            <i class="bi bi-info-circle"></i> No especificado
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Acciones -->
        <div class="col-md-4">
            <!-- Card Acciones -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Acciones
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('medicamentos.edit', $medicamento->id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('medicamentos.exportar-medicamento-pdf', $medicamento->id) }}" class="btn btn-info w-100 mb-2">
                        <i class="bi bi-file-pdf"></i> Descargar PDF
                    </a>
                    <form action="{{ route('medicamentos.destroy', $medicamento->id) }}" 
                          method="POST" 
                          style="display: inline-block; width: 100%;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger w-100"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este medicamento?')">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Estado -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle"></i> Estado
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Stock:</strong> <br>
                        @if ($medicamento->stock <= $medicamento->stock_minimo)
                            <span class="badge bg-danger">Bajo</span>
                        @elseif ($medicamento->stock <= $medicamento->stock_minimo * 1.5)
                            <span class="badge bg-warning">Normal</span>
                        @else
                            <span class="badge bg-success">Óptimo</span>
                        @endif
                    </p>
                    <p class="mb-0">
                        <strong>Vencimiento:</strong> <br>
                        @if (!$medicamento->fecha_vencimiento)
                            <span class="badge bg-secondary">No especificado</span>
                        @elseif ($medicamento->fecha_vencimiento < now())
                            <span class="badge bg-danger">Vencido</span>
                        @elseif ($medicamento->fecha_vencimiento <= now()->addDays(30))
                            <span class="badge bg-warning">Próximo a vencer</span>
                        @else
                            <span class="badge bg-success">Válido</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Card Timestamps -->
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        <strong>Creado:</strong> <br>
                        {{ $medicamento->created_at->format('d/m/Y H:i:s') }}
                    </small>
                    <small class="text-muted d-block">
                        <strong>Última actualización:</strong> <br>
                        {{ $medicamento->updated_at->format('d/m/Y H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
