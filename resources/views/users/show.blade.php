@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-person-circle"></i> Mi Perfil
            </h1>
        </div>
    </div>

    <!-- Información del Perfil -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nombre Completo</label>
                        <p class="h5">{{ $user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="h5">
                            <i class="bi bi-envelope"></i> 
                            <code>{{ $user->email }}</code>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Rol</label>
                        <p>
                            @if ($user->esAdmin())
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-shield-check"></i> Administrador
                                </span>
                            @else
                                <span class="badge bg-primary fs-6">
                                    <i class="bi bi-person"></i> Farmacéutica
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información de Actividad -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información de Cuenta</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Registrado el</label>
                        <p>
                            <i class="bi bi-calendar-event"></i>
                            {{ $user->created_at->format('d \\de F \\de Y \\ a \\l\\a\\s H:i') }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Último Acceso</label>
                        <p>
                            @if ($user->ultimo_acceso)
                                <i class="bi bi-clock-history"></i>
                                {{ $user->ultimo_acceso->format('d \\de F \\de Y \\ a \\l\\a\\s H:i') }}
                            @else
                                <span class="badge bg-warning">Sin acceso registrado</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Acceso Rápido -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    @if (Auth::user()->esAdmin())
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('users.index') }}" class="btn btn-primary">
                                <i class="bi bi-people-fill"></i> Gestión de Usuarios
                            </a>
                        </div>
                    @endif
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Descripción de Roles -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Descripción de Roles</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-danger">
                        <i class="bi bi-shield-check"></i> Administrador
                    </h6>
                    <ul class="small mb-3">
                        <li>Acceso completo al sistema</li>
                        <li>Gestión de usuarios</li>
                        <li>Édición de medicamentos</li>
                        <li>Gestión de listas de compra</li>
                        <li>Visualización de reportes</li>
                    </ul>

                    <h6 class="text-primary">
                        <i class="bi bi-person"></i> Farmacéutica
                    </h6>
                    <ul class="small">
                        <li>Acceso a medicamentos</li>
                        <li>Creación de listas de compra</li>
                        <li>Búsqueda de medicamentos</li>
                        <li>Generación de PDFs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
