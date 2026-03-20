@extends('layouts.app')

@section('title', 'Establecer nueva contraseña')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <!-- Encabezado -->
                    <div class="text-center mb-4">
                        <div style="font-size: 3rem; color: #0d6efd;">
                            <i class="bi bi-lock"></i>
                        </div>
                        <h2 class="mt-3">Nueva contraseña</h2>
                        <p class="text-muted">Establece tu nueva contraseña</p>
                    </div>

                    <!-- Mensajes -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form method="POST" action="{{ route('password.reset.store', ['token' => $token]) }}">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password" 
                                   placeholder="••••••••"
                                   required>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Mín. 8 caracteres, incluye mayúsculas, minúsculas, números y símbolos.
                            </small>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation"
                                   name="password_confirmation" 
                                   placeholder="••••••••"
                                   required>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botón Submit -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Establecer contraseña
                            </button>
                        </div>

                        <!-- Info -->
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                Este enlace es válido por 2 horas desde su generación.
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Requisitos de Contraseña -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-shield-lock"></i> Requisitos de Contraseña</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        Tu contraseña debe contener:
                    </small>
                    <ul class="mb-0 small text-muted">
                        <li>✓ Mínimo 8 caracteres</li>
                        <li>✓ Al menos una mayúscula (A-Z)</li>
                        <li>✓ Al menos una minúscula (a-z)</li>
                        <li>✓ Al menos un número (0-9)</li>
                        <li>✓ Al menos un símbolo (!@#$%...)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .alert {
        border-radius: 8px;
    }
    
    .card {
        border-radius: 12px;
    }
</style>
@endsection
