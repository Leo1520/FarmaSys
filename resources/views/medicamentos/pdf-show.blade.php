<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de {{ $medicamento->nombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #2c5aa0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background-color: #2c5aa0;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 3px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #2c5aa0;
            border-radius: 3px;
        }

        .info-item label {
            display: block;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .info-item value {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .alert {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 3px;
            border-left: 4px solid;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table-info tr {
            border-bottom: 1px solid #ddd;
        }

        .table-info td {
            padding: 12px;
            font-size: 12px;
        }

        .table-info td:first-child {
            font-weight: bold;
            width: 40%;
            color: #2c5aa0;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .price-large {
            font-size: 28px;
            color: #28a745;
            font-weight: bold;
        }

        .timestamps {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 3px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💊 {{ $medicamento->nombre }}</h1>
        <p><strong>Reporte Detallado del Medicamento</strong></p>
        <p>Generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="content">
        <!-- Alertas -->
        @if ($medicamento->stock <= $medicamento->stock_minimo)
            <div class="alert alert-danger">
                <strong>⚠️ Advertencia:</strong> Stock bajo. Actual: {{ $medicamento->stock }} | Mínimo: {{ $medicamento->stock_minimo }}
            </div>
        @endif

        @if ($medicamento->fecha_vencimiento && $medicamento->fecha_vencimiento < now())
            <div class="alert alert-danger">
                <strong>⛔ Crítico:</strong> Medicamento vencido desde {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
            </div>
        @elseif ($medicamento->fecha_vencimiento && $medicamento->fecha_vencimiento <= now()->addDays(30))
            <div class="alert alert-warning">
                <strong>⚠️ Aviso:</strong> Medicamento próximo a vencer ({{ $medicamento->fecha_vencimiento->format('d/m/Y') }})
            </div>
        @endif

        <!-- Información Básica -->
        <div class="section">
            <div class="section-title">ℹ️ Información Básica</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Código del Medicamento</label>
                    <value>{{ $medicamento->codigo ?? 'No especificado' }}</value>
                </div>
                <div class="info-item">
                    <label>ID en Sistema</label>
                    <value>#{{ $medicamento->id }}</value>
                </div>
            </div>
        </div>

        <!-- Información Financiera -->
        <div class="section">
            <div class="section-title">💰 Información Financiera</div>
            <table class="table-info">
                <tr>
                    <td>Precio Unitario</td>
                    <td><span class="price-large">${{ number_format($medicamento->precio, 2) }}</span></td>
                </tr>
            </table>
        </div>

        <!-- Información de Inventario -->
        <div class="section">
            <div class="section-title">📦 Información de Inventario</div>
            <table class="table-info">
                <tr>
                    <td>Stock Actual</td>
                    <td>
                        @if ($medicamento->stock <= $medicamento->stock_minimo)
                            <span class="badge badge-danger">{{ $medicamento->stock }} unidades</span>
                        @else
                            <span class="badge badge-success">{{ $medicamento->stock }} unidades</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Stock Mínimo</td>
                    <td>{{ $medicamento->stock_minimo }} unidades</td>
                </tr>
                <tr>
                    <td>Diferencia</td>
                    <td>
                        @if ($medicamento->stock > $medicamento->stock_minimo)
                            <span class="badge badge-success">+{{ $medicamento->stock - $medicamento->stock_minimo }}</span>
                        @else
                            <span class="badge badge-danger">-{{ $medicamento->stock_minimo - $medicamento->stock }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Información de Vencimiento -->
        <div class="section">
            <div class="section-title">📅 Información de Vencimiento</div>
            <table class="table-info">
                <tr>
                    <td>Fecha de Vencimiento</td>
                    <td>
                        @if ($medicamento->fecha_vencimiento)
                            @if ($medicamento->fecha_vencimiento < now())
                                <span class="badge badge-danger">{{ $medicamento->fecha_vencimiento->format('d/m/Y') }} (VENCIDO)</span>
                            @elseif ($medicamento->fecha_vencimiento <= now()->addDays(30))
                                <span class="badge badge-warning">{{ $medicamento->fecha_vencimiento->format('d/m/Y') }} (Próximo a vencer)</span>
                            @else
                                <span class="badge badge-success">{{ $medicamento->fecha_vencimiento->format('d/m/Y') }}</span>
                            @endif
                        @else
                            No especificado
                        @endif
                    </td>
                </tr>
                @if ($medicamento->fecha_vencimiento)
                    <tr>
                        <td>Días Restantes</td>
                        <td>
                            @php
                                $dias = now()->diffInDays($medicamento->fecha_vencimiento, false);
                            @endphp
                            @if ($dias < 0)
                                <span class="badge badge-danger">{{ abs($dias) }} días (Vencido)</span>
                            @elseif ($dias <= 30)
                                <span class="badge badge-warning">{{ $dias }} días</span>
                            @else
                                <span class="badge badge-success">{{ $dias }} días</span>
                            @endif
                        </td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Registro Temporal -->
        <div class="timestamps">
            <strong>Creado:</strong> {{ $medicamento->created_at->format('d/m/Y H:i:s') }}<br>
            <strong>Última actualización:</strong> {{ $medicamento->updated_at->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="footer">
        <p>FarmaSys © 2026 - Sistema de Gestión de Inventario para Farmacias</p>
        <p>Este documento fue generado automáticamente por el sistema</p>
    </div>
</body>
</html>
