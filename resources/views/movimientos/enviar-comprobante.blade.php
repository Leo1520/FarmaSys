@extends('layouts.app')

@section('title', 'Enviar Comprobante por Email')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-envelope-at"></i> Enviar Comprobante por Email
            </h1>
            <p class="text-muted">Comprobante #{{ $movimiento->id }} - {{ $movimiento->medicamento->nombre }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('movimientos.show', $movimiento->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Formulario de Envío -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form action="{{ route('movimientos.enviar-comprobante', $movimiento->id) }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> Email de Destino
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                placeholder="ejemplo@correo.com"
                                required
                                value="{{ old('email') }}"
                            >
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">El comprobante será enviado a esta dirección de email</small>
                        </div>

                        <!-- Asunto Adicional (Opcional) -->
                        <div class="mb-4">
                            <label for="asunto_adicional" class="form-label fw-bold">
                                <i class="bi bi-chat-dots"></i> Asunto Adicional (Opcional)
                            </label>
                            <textarea 
                                id="asunto_adicional" 
                                name="asunto_adicional" 
                                class="form-control @error('asunto_adicional') is-invalid @enderror"
                                rows="3"
                                placeholder="Agrega un mensaje adicional si lo deseas..."
                                maxlength="200"
                            >{{ old('asunto_adicional') }}</textarea>
                            <small class="text-muted">Máximo 200 caracteres</small>
                            @error('asunto_adicional')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-2">
                            <button 
                                type="submit" 
                                class="btn btn-primary btn-lg flex-grow-1"
                            >
                                <i class="bi bi-send"></i> Enviar Comprobante
                            </button>
                            <a 
                                href="{{ route('movimientos.show', $movimiento->id) }}" 
                                class="btn btn-outline-secondary btn-lg"
                            >
                                <i class="bi bi-x"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información del Comprobante -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Información del Comprobante
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nº Comprobante</label>
                            <p class="h5">{{ $movimiento->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Fecha y Hora</label>
                            <p class="h5">{{ $movimiento->created_at->format('d \\de F \\de Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Medicamento</label>
                            <p class="h5">{{ $movimiento->medicamento->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Cantidad</label>
                            <p class="h5">{{ $movimiento->cantidad }} unidades</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tipo de Movimiento</label>
                            <p>
                                @if ($movimiento->tipo === 'entrada')
                                    <span class="badge bg-success">
                                        <i class="bi bi-box-seam"></i> Entrada
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-box-seam"></i> Salida
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Razón</label>
                            <p class="h5">{{ ucfirst($movimiento->razon) }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Total</label>
                            <p class="h4 text-success">
                                ${{ number_format(($movimiento->precio_unitario ?? 0) * $movimiento->cantidad, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Usuario Registrador</label>
                            <p class="h5">{{ $movimiento->usuario->name }}</p>
                        </div>
                    </div>

                    @if ($movimiento->descripcion)
                    <hr>
                    <div>
                        <label class="form-label text-muted">Descripción</label>
                        <p>{{ $movimiento->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar con opciones -->
        <div class="col-md-4">
            <!-- Alert Info -->
            <div class="alert alert-info border-0 shadow-sm" role="alert">
                <h5 class="alert-heading">
                    <i class="bi bi-lightbulb"></i> Información Útil
                </h5>
                <p class="mb-2">
                    <strong>¿Qué incluye el comprobante?</strong>
                </p>
                <ul class="mb-0" style="font-size: 0.95rem;">
                    <li>Número de comprobante único</li>
                    <li>Fecha y hora del movimiento</li>
                    <li>Datos del medicamento</li>
                    <li>Cantidad y precio unitario</li>
                    <li>Total del movimiento</li>
                    <li>Información del usuario</li>
                    <li>PDF con formato profesional</li>
                </ul>
            </div>

            <!-- Botones de Descarga -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-download"></i> Descargar
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">También puedes descargar el comprobante localmente:</p>
                    <a 
                        href="{{ route('movimientos.exportar-pdf', $movimiento->id) }}" 
                        class="btn btn-outline-primary w-100"
                    >
                        <i class="bi bi-file-pdf"></i> Descargar PDF
                    </a>
                </div>
            </div>

            <!-- Información Security -->
            <div class="alert alert-warning border-0 shadow-sm" role="alert">
                <h5 class="alert-heading">
                    <i class="bi bi-shield-check"></i> Seguridad
                </h5>
                <p class="mb-0" style="font-size: 0.95rem;">
                    El comprobante contiene información confidencial y será enviado con seguridad. 
                    Se registrará un log de auditoría de este envío.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
