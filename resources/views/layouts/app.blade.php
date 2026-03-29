<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmaSys - @yield('title')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #e74c3c;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, var(--primary-color) 0%, #1f3f61 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }

        h1, h2, h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1f3f61;
            border-color: #1f3f61;
        }

        .table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table thead.table-dark {
            background-color: var(--primary-color);
        }

        .table tbody tr:hover {
            background-color: #f5f5f5;
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : route('home') }}">
                <i class="bi bi-capsule"></i>
                FarmaSys
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-house"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('medicamentos.index') }}">
                                <i class="bi bi-box-seam"></i> Medicamentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('movimientos.index') }}">
                                <i class="bi bi-arrow-left-right"></i> Movimientos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lista-compra.index') }}">
                                <i class="bi bi-list-check"></i> Lista de Compra
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('historial.personal') }}">
                                <i class="bi bi-clock-history"></i> Mi Historial
                            </a>
                        </li>
                        @if (Auth::user()->esAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">
                                    <i class="bi bi-people-fill"></i> Usuarios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('historial.index') }}">
                                    <i class="bi bi-shield-exclamation"></i> Auditoría
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">
                                    <i class="bi bi-person"></i> Mi Perfil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Registrarse
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="py-4">
        <!-- Hidden element para pasar mensajes al modal -->
        @if ($message = Session::get('success'))
            <div data-message-success="{{ $message }}" style="display: none;"></div>
        @endif
        @if ($message = Session::get('error'))
            <div data-message-error="{{ $message }}" style="display: none;"></div>
        @endif
        @if ($message = Session::get('warning'))
            <div data-message-warning="{{ $message }}" style="display: none;"></div>
        @endif
        @if ($message = Session::get('info'))
            <div data-message-info="{{ $message }}" style="display: none;"></div>
        @endif

        @yield('content')
    </main>

    <!-- Modal de Mensajes -->
    @include('components.modal-message')

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <p class="mb-0">&copy; 2026 FarmaSys - Sistema de Gestión de Inventario. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
