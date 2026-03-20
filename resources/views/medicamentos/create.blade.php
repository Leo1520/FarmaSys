@extends('layouts.app')

@section('title', 'Nuevo Medicamento')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Agregar Nuevo Medicamento</h1>
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
                    <form action="{{ route('medicamentos.store') }}" method="POST">
                        @csrf

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
                                   value="{{ old('nombre') }}"
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
                                   value="{{ old('codigo') }}"
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
                                   value="{{ old('precio') }}"
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
                                           value="{{ old('stock', '0') }}"
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
                                           value="{{ old('stock_minimo', '10') }}"
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
                                   value="{{ old('fecha_vencimiento') }}">
                            @error('fecha_vencimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Crear Medicamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Ayuda -->
        <div class="col-md-4">
            <div class="card bg-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle"></i> Ayuda
                    </h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2">
                            <strong>Nombre:</strong> Campo obligatorio que identifica el medicamento
                        </li>
                        <li class="mb-2">
                            <strong>Código:</strong> Identificador único opcional (ej: código de barras)
                        </li>
                        <li class="mb-2">
                            <strong>Precio:</strong> Valor unitario del medicamento
                        </li>
                        <li class="mb-2">
                            <strong>Stock Mínimo:</strong> Cantidad que genera alerta automática
                        </li>
                        <li>
                            <strong>Vencimiento:</strong> Fecha límite de uso del medicamento
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
