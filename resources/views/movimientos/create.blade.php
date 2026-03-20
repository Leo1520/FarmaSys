@extends('layouts.app')

@section('title', 'Crear Movimiento de Inventario')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-arrow-left-right"></i> Registrar Movimiento
            </h1>
            <p class="text-muted">Registra entrada o salida de medicamentos</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('movimientos.store') }}">
                        @csrf

                        <!-- Medicamento -->
                        <div class="mb-3">
                            <label for="medicamento_id" class="form-label">
                                <i class="bi bi-capsule"></i> Medicamento
                            </label>
                            <select name="medicamento_id" 
                                    class="form-select @error('medicamento_id') is-invalid @enderror"
                                    id="medicamento_id"
                                    required>
                                <option value="">-- Selecciona un medicamento --</option>
                                @foreach ($medicamentos as $med)
                                    <option value="{{ $med->id }}" @selected(old('medicamento_id') == $med->id)>
                                        {{ $med->nombre }} (Stock: {{ $med->stock }}</option>
                                    </option>
                                @endforeach
                            </select>
                            @error('medicamento_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo de Movimiento -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Movimiento</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipo" id="tipo_entrada" 
                                       value="entrada" @checked(old('tipo') === 'entrada')>
                                <label class="btn btn-outline-success" for="tipo_entrada">
                                    <i class="bi bi-box-seam"></i> Entrada (📥)
                                </label>

                                <input type="radio" class="btn-check" name="tipo" id="tipo_salida" 
                                       value="salida" @checked(old('tipo') === 'salida')>
                                <label class="btn btn-outline-danger" for="tipo_salida">
                                    <i class="bi bi-box-seam"></i> Salida (📤)
                                </label>
                            </div>
                            @error('tipo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Razón -->
                        <div class="mb-3">
                            <label for="razon" class="form-label">Razón</label>
                            <select name="razon" 
                                    class="form-select @error('razon') is-invalid @enderror"
                                    id="razon"
                                    required>
                                <option value="">-- Selecciona una razón --</option>
                                <option value="compra" @selected(old('razon') === 'compra')>🛒 Compra</option>
                                <option value="devolución" @selected(old('razon') === 'devolución')>↩️ Devolución</option>
                                <option value="ajuste" @selected(old('razon') === 'ajuste')>⚙️ Ajuste</option>
                                <option value="venta" @selected(old('razon') === 'venta')>💳 Venta</option>
                                <option value="pérdida" @selected(old('razon') === 'pérdida')>❌ Pérdida</option>
                                <option value="transferencia" @selected(old('razon') === 'transferencia')>↔️ Transferencia</option>
                                <option value="otro" @selected(old('razon') === 'otro')>📝 Otro</option>
                            </select>
                            @error('razon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cantidad -->
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" 
                                   name="cantidad" 
                                   class="form-control @error('cantidad') is-invalid @enderror"
                                   id="cantidad"
                                   placeholder="Ingresa la cantidad"
                                   value="{{ old('cantidad') }}"
                                   min="1"
                                   required>
                            @error('cantidad')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Precio Unitario (opcional) -->
                        <div class="mb-3">
                            <label for="precio_unitario" class="form-label">Precio Unitario (Opcional)</label>
                            <input type="number" 
                                   name="precio_unitario" 
                                   class="form-control @error('precio_unitario') is-invalid @enderror"
                                   id="precio_unitario"
                                   placeholder="Ej: 5.50"
                                   value="{{ old('precio_unitario') }}"
                                   step="0.01"
                                   min="0">
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Útil para registrar el costo del movimiento
                            </small>
                            @error('precio_unitario')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                            <textarea name="descripcion" 
                                      class="form-control @error('descripcion') is-invalid @enderror"
                                      id="descripcion"
                                      rows="3"
                                      placeholder="Ingresa detalles adicionales del movimiento">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('movimientos.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Registrar Movimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Adicional -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Tipos de Movimiento
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-success">📥 Entrada</h6>
                    <ul class="small mb-3">
                        <li><strong>Compra:</strong> Medicamentos comprados</li>
                        <li><strong>Devolución:</strong> Productos devueltos</li>
                        <li><strong>Ajuste:</strong> Corrección de inventario</li>
                    </ul>

                    <h6 class="text-danger">📤 Salida</h6>
                    <ul class="small">
                        <li><strong>Venta:</strong> Medicamentos vendidos</li>
                        <li><strong>Pérdida:</strong> Productos dañados/vencidos</li>
                        <li><strong>Transferencia:</strong> A otra sucursal</li>
                    </ul>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-lightbulb"></i>
                <strong>Consejo:</strong> El stock se actualizará automáticamente al registrar el movimiento.
            </div>
        </div>
    </div>
</div>
@endsection
