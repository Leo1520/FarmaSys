@extends('layouts.app')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <!-- Encabezado -->
                    <div class="text-center mb-4">
                        <div style="font-size: 3rem; color: #0d6efd;">
                            <i class="bi bi-key"></i>
                        </div>
                        <h2 class="mt-3">Recuperar contraseña</h2>
                        <p class="text-muted">Ingresa tu correo electrónico para recuperar tu contraseña</p>
                    </div>

                    <!-- Mensajes -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

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
                    <form method="POST" action="{{ route('password.forgot.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="tu@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-envelope"></i> Enviar enlace de recuperación
                            </button>
                        </div>

                        <div class="text-center">
                            <small class="text-muted">
                                ¿Recuerdas tu contraseña? 
                                <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información de Seguridad -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-shield-check"></i> Seguridad</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block">
                        <i class="bi bi-info-circle"></i> Te enviaremos un enlace seguro a tu correo.<br>
                        <i class="bi bi-hourglass"></i> El enlace es válido por 2 horas.<br>
                        <i class="bi bi-lock"></i> Nunca compartimos contraseñas por correo.
                    </small>
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
    
    .link-primary {
        text-decoration: none;
        color: #0d6efd;
    }
    
    .link-primary:hover {
        text-decoration: underline;
    }
</style>
@endsection
