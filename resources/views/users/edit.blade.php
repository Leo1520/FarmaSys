@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="bi bi-pencil-square"></i> Editar Usuario
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Usuarios
            </a>
        </div>
    </div>

    <!-- Formulario de Edición -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select name="rol" 
                                    class="form-select @error('rol') is-invalid @enderror"
                                    id="rol"
                                    required>
                                <option value="admin" @selected(old('rol', $user->rol) === 'admin')>
                                    <i class="bi bi-shield-check"></i> Administrador
                                </option>
                                <option value="farmaceutica" @selected(old('rol', $user->rol) === 'farmaceutica')>
                                    <i class="bi bi-person"></i> Farmacéutica
                                </option>
                            </select>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Admin:</strong> Acceso completo a gestión de usuarios
                                <br>
                                <strong>Farmacéutica:</strong> Acceso a medicamentos y listas de compra
                            </small>
                            @error('rol')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información Adicional -->
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i>
                            <strong>Información del usuario:</strong>
                            <ul class="mb-0 mt-2 ms-3">
                                <li>Registrado: {{ $user->created_at->format('d/m/Y H:i') }}</li>
                                <li>Último acceso: 
                                    @if ($user->ultimo_acceso)
                                        {{ $user->ultimo_acceso->format('d/m/Y H:i') }}
                                    @else
                                        Sin acceso registrado
                                    @endif
                                </li>
                            </ul>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Información del Usuario (Sidebar) -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i> Perfil
                    </h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Nombre:</strong><br>
                        {{ $user->name }}
                    </p>
                    <p>
                        <strong>Email:</strong><br>
                        <code>{{ $user->email }}</code>
                    </p>
                    <p>
                        <strong>Rol Actual:</strong><br>
                        @if ($user->esAdmin())
                            <span class="badge bg-danger">Administrador</span>
                        @else
                            <span class="badge bg-primary">Farmacéutica</span>
                        @endif
                    </p>
                    <p class="mb-0">
                        <strong>Estado:</strong><br>
                        <span class="badge bg-success">Activo</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
