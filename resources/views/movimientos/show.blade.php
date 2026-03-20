@extends('layouts.app')

@section('title', 'Detalle del Movimiento')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-arrow-left-right"></i> Detalle del Movimiento
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('movimientos.exportar-pdf', $movimiento->id) }}" class="btn btn-outline-info" title="Descargar PDF">
                    <i class="bi bi-download"></i> PDF
                </a>
                <a href="{{ route('movimientos.mostrar-envio-comprobante', $movimiento->id) }}" class="btn btn-outline-primary" title="Enviar por email">
                    <i class="bi bi-envelope-at"></i> Enviar
                </a>
                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <!-- Resumen del Movimiento -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Medicamento</label>
                            <p class="h5">
                                <i class="bi bi-capsule"></i> {{ $movimiento->medicamento->nombre }}
                            </p>
                            <small class="text-muted">
                                Código: {{ $movimiento->medicamento->codigo ?? 'Sin código' }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Fecha y Hora</label>
                            <p class="h5">{{ $movimiento->created_at->format('d \\de F \\de Y') }}</p>
                            <small class="text-muted">{{ $movimiento->created_at->format('H:i:s') }}</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label text-muted">Tipo de Movimiento</label>
                            <p>
                                @if ($movimiento->tipo === 'entrada')
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-box-seam"></i> Entrada
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6">
                                        <i class="bi bi-box-seam"></i> Salida
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Cantidad</label>
                            <p class="h5 fw-bold text-primary">{{ $movimiento->cantidad }} unid.</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Razón</label>
                            <p>
                                @switch($movimiento->razon)
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
                                        <span class="badge bg-light text-dark">{{ $movimiento->razon }}</span>
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Precio Unitario</label>
                            <p class="h5">
                                @if ($movimiento->precio_unitario)
                                    ${{ number_format($movimiento->precio_unitario, 2) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            @if ($movimiento->descripcion)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Descripción Adicional</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $movimiento->descripcion }}</p>
                    </div>
                </div>
            @endif

            <!-- Impacto en el Stock -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up"></i> Impacto en el Stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Stock Actual</label>
                            <p class="h3 fw-bold text-primary">{{ $movimiento->medicamento->stock }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Movimiento</label>
                            <p class="h3 fw-bold">
                                @if ($movimiento->tipo === 'entrada')
                                    <span class="text-success">+{{ $movimiento->cantidad }}</span>
                                @else
                                    <span class="text-danger">-{{ $movimiento->cantidad }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Stock Mínimo</label>
                            <p class="h3 fw-bold {{ $movimiento->medicamento->stock <= $movimiento->medicamento->stock_minimo ? 'text-danger' : 'text-success' }}">
                                {{ $movimiento->medicamento->stock_minimo }}
                            </p>
                        </div>
                    </div>

                    @if ($movimiento->medicamento->stock <= $movimiento->medicamento->stock_minimo)
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Atención:</strong> El stock está por debajo del mínimo establecido.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información del Usuario (Sidebar) -->
        <div class="col-md-4">
            <!-- Usuario Responsable -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i> Usuario Responsable
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div style="width: 70px; height: 70px; border-radius: 50%; background-color: #2c5aa0; color: white; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto mb-3;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <p class="h6">{{ $movimiento->usuario->name }}</p>
                    <p class="text-muted"><small>{{ $movimiento->usuario->email }}</small></p>
                    <hr>
                    <p>
                        <strong>Rol:</strong><br>
                        @if ($movimiento->usuario->esAdmin())
                            <span class="badge bg-danger">Administrador</span>
                        @else
                            <span class="badge bg-primary">Farmacéutica</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Información del Medicamento -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-capsule"></i> Información del Medicamento
                    </h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Nombre:</strong><br>
                        {{ $movimiento->medicamento->nombre }}
                    </p>
                    <p>
                        <strong>Código:</strong><br>
                        {{ $movimiento->medicamento->codigo ?? 'Sin código' }}
                    </p>
                    <p>
                        <strong>Precio:</strong><br>
                        ${{ number_format($movimiento->medicamento->precio, 2) }}
                    </p>
                    <p>
                        <strong>Stock Actual:</strong><br>
                        <span class="badge {{ $movimiento->medicamento->stock <= $movimiento->medicamento->stock_minimo ? 'bg-danger' : 'bg-success' }} fs-6">
                            {{ $movimiento->medicamento->stock }}
                        </span>
                    </p>
                    <p class="mb-0">
                        <strong>Vencimiento:</strong><br>
                        @if ($movimiento->medicamento->fecha_vencimiento)
                            {{ $movimiento->medicamento->fecha_vencimiento->format('d/m/Y') }}
                        @else
                            <span class="text-muted">No especificado</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
