<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmaSys - Registro</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #2c5aa0 0%, #1f3f61 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
        }

        .register-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #2c5aa0 0%, #1f3f61 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .register-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .register-header .icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .register-body {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #2c5aa0;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #2c5aa0;
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.15);
            outline: none;
        }

        .btn-register {
            background: linear-gradient(135deg, #2c5aa0 0%, #1f3f61 100%);
            border: none;
            color: white;
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 5px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(44, 90, 160, 0.3);
            color: white;
            text-decoration: none;
        }

        .register-footer {
            text-align: center;
            padding: 20px 40px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .register-footer p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .register-footer a {
            color: #2c5aa0;
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 5px;
            border: none;
            margin-bottom: 20px;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .info-small {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="icon">
                    <i class="bi bi-capsule"></i>
                </div>
                <h1>FarmaSys</h1>
                <p class="mb-0">Crear Nueva Cuenta</p>
            </div>

            <div class="register-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        Por favor, corrije los siguientes errores:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="bi bi-person"></i> Nombre Completo
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Tu nombre completo"
                               required 
                               autofocus>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="tu@correo.com"
                               required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="info-small">Usarás este email para iniciar sesión</div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Contraseña
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password"
                               placeholder="••••••••"
                               required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="info-small">Mínimo 8 caracteres, con mayúsculas, minúsculas y números</div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-check"></i> Confirmar Contraseña
                        </label>
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               placeholder="••••••••"
                               required>
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-check-circle"></i> Crear Cuenta
                    </button>
                </form>
            </div>

            <div class="register-footer">
                <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
