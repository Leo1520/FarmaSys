<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 750px;
            margin: 0 auto;
            border: 2px solid #2c3e50;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .comprobante-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        .info-item {
            padding: 10px 0;
        }
        .info-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .info-value {
            font-size: 16px;
            color: #2c3e50;
            font-weight: 600;
            margin-top: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .table th {
            background-color: #34495e;
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .table td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
        }
        .table tr:last-child td {
            border-bottom: 2px solid #34495e;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-entrada {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-salida {
            background-color: #f8d7da;
            color: #721c24;
        }
        .total-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            padding: 20px;
            background-color: #ecf0f1;
            border-radius: 6px;
            margin: 20px 0;
        }
        .total-text {
            font-size: 14px;
            color: #7f8c8d;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
        }
        .footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #ecf0f1;
            font-size: 12px;
            color: #7f8c8d;
        }
        .description-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            margin: 20px 0;
            border-radius: 4px;
        }
        .description-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .description-text {
            font-size: 14px;
            color: #2c3e50;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏥 COMPROBANTE DE MOVIMIENTO</h1>
            <p>FarmaSys - Sistema de Gestión Farmacéutica</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Comprobante Info -->
            <div class="comprobante-info">
                <div class="info-item">
                    <div class="info-label">Nº Comprobante</div>
                    <div class="info-value">#{{ $movimiento->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tipo de Movimiento</div>
                    <div class="info-value">
                        @if ($movimiento->tipo === 'entrada')
                            <span class="badge badge-entrada">📥 Entrada</span>
                        @else
                            <span class="badge badge-salida">📤 Salida</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de Registro</div>
                    <div class="info-value">{{ $movimiento->created_at->format('d \\de F \\de Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Hora</div>
                    <div class="info-value">{{ $movimiento->created_at->format('H:i:s') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Razón</div>
                    <div class="info-value">{{ ucfirst($movimiento->razon) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Usuario Registrador</div>
                    <div class="info-value">{{ $movimiento->usuario->name }}</div>
                </div>
            </div>

            <!-- Medicamento Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Medicamento</th>
                        <th>Código</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $movimiento->medicamento->nombre }}</td>
                        <td>{{ $movimiento->medicamento->codigo ?? 'N/A' }}</td>
                        <td>{{ $movimiento->cantidad }} unid.</td>
                        <td>${{ number_format($movimiento->precio_unitario ?? 0, 2) }}</td>
                        <td>${{ number_format(($movimiento->precio_unitario ?? 0) * $movimiento->cantidad, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Total -->
            <div class="total-row">
                <div class="total-text">TOTAL A {{ strtoupper($movimiento->tipo) }}</div>
                <div class="total-amount">${{ number_format(($movimiento->precio_unitario ?? 0) * $movimiento->cantidad, 2) }}</div>
            </div>

            <!-- Description if exists -->
            @if ($movimiento->descripcion)
            <div class="description-section">
                <div class="description-label">Notas / Descripción</div>
                <div class="description-text">{{ $movimiento->descripcion }}</div>
            </div>
            @endif

            <!-- Información de Medicamento -->
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Información del Medicamento</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Nombre</strong></td>
                        <td>{{ $movimiento->medicamento->nombre }}</td>
                    </tr>
                    <tr>
                        <td><strong>Código</strong></td>
                        <td>{{ $movimiento->medicamento->codigo ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Presentación</strong></td>
                        <td>{{ $movimiento->medicamento->presentacion ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Stock Actual</strong></td>
                        <td>{{ $movimiento->medicamento->stock }} unid.</td>
                    </tr>
                    <tr>
                        <td><strong>Stock Mínimo</strong></td>
                        <td>{{ $movimiento->medicamento->stock_minimo ?? 'N/A' }} unid.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este documento fue generado automáticamente por FarmaSys y tiene validez como comprobante interno.</p>
            <p style="margin-top: 10px; font-size: 11px;">
                Generado: {{ now()->format('d/m/Y H:i:s') }} 
                <br>
                Empresa: FarmaSys
            </p>
        </div>
    </div>
</body>
</html>
