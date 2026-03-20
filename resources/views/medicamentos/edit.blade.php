@extends('layouts.app')

@section('title', 'Editar Medicamento')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Editar Medicamento: <span class="text-primary">{{ $medicamento->nombre }}</span></h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Error en el formulario!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulario -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('medicamentos.update', $medicamento->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Información Básica -->
                        <h5 class="mb-3 text-primary">
                            <i class="bi bi-capsule"></i> Información Básica
                        </h5>

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">
                                Nombre del Medicamento <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $medicamento->nombre) }}"
                                   placeholder="Ej: Paracetamol 500mg"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="codigo" class="form-label fw-bold">Código (Opcional)</label>
                            <input type="text" 
                                   class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" 
                                   name="codigo" 
                                   value="{{ old('codigo', $medicamento->codigo) }}"
                                   placeholder="Ej: PAR-001">
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información Financiera -->
                        <h5 class="mb-3 mt-4 text-primary">
                            <i class="bi bi-currency-dollar"></i> Información Financiera
                        </h5>

                        <div class="mb-3">
                            <label for="precio" class="form-label fw-bold">
                                Precio ($) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('precio') is-invalid @enderror" 
                                   id="precio" 
                                   name="precio" 
                                   value="{{ old('precio', $medicamento->precio) }}"
                                   step="0.01"
                                   min="0.01"
                                   placeholder="0.00"
                                   required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información de Inventario -->
                        <h5 class="mb-3 mt-4 text-primary">
                            <i class="bi bi-box-seam"></i> Información de Inventario
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label fw-bold">
                                        Stock Actual <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" 
                                           name="stock" 
                                           value="{{ old('stock', $medicamento->stock) }}"
                                           min="0"
                                           required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_minimo" class="form-label fw-bold">
                                        Stock Mínimo <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('stock_minimo') is-invalid @enderror" 
                                           id="stock_minimo" 
                                           name="stock_minimo" 
                                           value="{{ old('stock_minimo', $medicamento->stock_minimo) }}"
                                           min="0"
                                           required>
                                    @error('stock_minimo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Se genera alerta cuando el stock llega a este nivel</small>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Vencimiento -->
                        <h5 class="mb-3 mt-4 text-primary">
                            <i class="bi bi-calendar"></i> Información de Vencimiento
                        </h5>

                        <div class="mb-4">
                            <label for="fecha_vencimiento" class="form-label fw-bold">Fecha de Vencimiento (Opcional)</label>
                            <input type="date" 
                                   class="form-control @error('fecha_vencimiento') is-invalid @enderror" 
                                   id="fecha_vencimiento" 
                                   name="fecha_vencimiento" 
                                   value="{{ old('fecha_vencimiento', $medicamento->fecha_vencimiento?->format('Y-m-d')) }}">
                            @error('fecha_vencimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información del registro -->
                        <div class="alert alert-info mt-4">
                            <small>
                                <strong>Creado:</strong> {{ $medicamento->created_at->format('d/m/Y H:i') }} <br>
                                <strong>Última actualización:</strong> {{ $medicamento->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Información -->
        <div class="col-md-4">
            <div class="card bg-light shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle"></i> Información Actual
                    </h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2">
                            <strong>ID:</strong> {{ $medicamento->id }}
                        </li>
                        <li class="mb-2">
                            <strong>Stock:</strong> {{ $medicamento->stock }} unidades
                        </li>
                        <li>
                            <strong>Precio:</strong> ${{ number_format($medicamento->precio, 2) }}
                        </li>
                    </ul>
                </div>
            </div>

            @if ($medicamento->stock <= $medicamento->stock_minimo)
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Advertencia:</strong> Stock bajo
                </div>
            @endif

            @if ($medicamento->fecha_vencimiento && $medicamento->fecha_vencimiento < now())
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-x-circle"></i>
                    <strong>Medicamento vencido</strong>
                </div>
            @elseif ($medicamento->fecha_vencimiento && $medicamento->fecha_vencimiento <= now()->addDays(30))
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <strong>Próximo a vencer</strong>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
