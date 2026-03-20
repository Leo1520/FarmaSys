@extends('layouts.app')

@section('title', 'Verificar correo electrónico')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <!-- Encabezado -->
                    <div class="text-center mb-4">
                        <div style="font-size: 3rem; color: #0d6efd;">
                            <i class="bi bi-envelope-check"></i>
                        </div>
                        <h2 class="mt-3">Verificar correo electrónico</h2>
                        <p class="text-muted">Necesitamos confirmar tu dirección de correo electrónico</p>
                    </div>

                    <!-- Mensajes de Éxito -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Mensajes de Error -->
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

                    <!-- Información -->
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i>
                        <strong>Instrucciones:</strong>
                        <p class="mb-0 mt-2">Hemos enviado un enlace de verificación a <strong>{{ Auth::user()->email }}</strong>. 
                        Revisa tu bandeja de entrada (y spam) y haz clic en el enlace para verificar tu correo.</p>
                    </div>

                    <!-- Formulario para reenviar email -->
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-arrow-repeat"></i> Reenviar email de verificación
                            </button>
                        </div>
                    </form>

                    <!-- Información adicional -->
                    <div class="mt-4 pt-4 border-top text-center text-muted">
                        <small>
                            ¿El enlace expiró? Puedes solicitar uno nuevo en cualquier momento.<br>
                            El enlace vencerá en <strong>24 horas</strong>.
                        </small>
                    </div>

                    <!-- Opción para cambiar email -->
                    <div class="mt-3 text-center">
                        <small>
                            ¿Es incorrecto el email? 
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm p-0">Salir y crear nueva cuenta</button>
                            </form>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Información de Cuenta -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-person-circle"></i> Tu Información</h6>
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
                                @elseif (Auth::user()->esFarmaceutica())
                                    <span class="badge bg-primary">Farmacéutica</span>
                                @else
                                    <span class="badge bg-info">Invitado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Verificado:</strong></td>
                            <td>
                                @if (Auth::user()->hasVerifiedEmail())
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Sí</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass"></i> Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    </table>
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
    
    .btn-link {
        text-decoration: none;
        color: #0d6efd;
    }
    
    .btn-link:hover {
        text-decoration: underline;
    }
</style>
@endsection
