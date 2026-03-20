<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Medicamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #2c5aa0;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .info-bar {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background-color: #2c5aa0;
            color: white;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .stock-bajo {
            color: #dc3545;
            font-weight: bold;
        }

        .stock-ok {
            color: #28a745;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
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

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .resumen {
            margin-top: 20px;
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
        }

        .resumen-item {
            display: inline-block;
            margin-right: 30px;
            font-size: 12px;
        }

        .resumen-item strong {
            color: #2c5aa0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Reporte de Medicamentos</h1>
        <p><strong>FarmaSys - Sistema de Gestión de Inventario</strong></p>
        <p>Generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @if ($search)
        <div class="info-bar">
            <strong>Búsqueda:</strong> {{ $search }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 25%">Nombre</th>
                <th style="width: 12%">Código</th>
                <th style="width: 12%">Precio</th>
                <th style="width: 12%">Stock</th>
                <th style="width: 15%">Mín.</th>
                <th style="width: 19%">Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($medicamentos as $medicamento)
                <tr>
                    <td class="text-center">{{ $medicamento->id }}</td>
                    <td><strong>{{ $medicamento->nombre }}</strong></td>
                    <td>{{ $medicamento->codigo ?? '-' }}</td>
                    <td class="text-right">${{ number_format($medicamento->precio, 2) }}</td>
                    <td class="text-center">
                        @if ($medicamento->stock <= $medicamento->stock_minimo)
                            <span class="badge badge-danger">{{ $medicamento->stock }}</span>
                        @else
                            <span class="badge badge-success">{{ $medicamento->stock }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $medicamento->stock_minimo }}</td>
                    <td class="text-center">
                        @if ($medicamento->fecha_vencimiento)
                            @if ($medicamento->fecha_vencimiento < now())
                                <span class="badge badge-danger">{{ $medicamento->fecha_vencimiento->format('d/m/Y') }}</span>
                            @elseif ($medicamento->fecha_vencimiento <= now()->addDays(30))
                                <span class="badge badge-warning">{{ $medicamento->fecha_vencimiento->format('d/m/Y') }}</span>
                            @else
                                {{ $medicamento->fecha_vencimiento->format('d/m/Y') }}
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay medicamentos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="resumen">
        <div class="resumen-item">
            <strong>Total de Medicamentos:</strong> {{ count($medicamentos) }}
        </div>
        <div class="resumen-item">
            <strong>Stock Bajo:</strong> {{ $medicamentos->where('stock', '<=', collect($medicamentos)->pluck('stock_minimo')->average())->count() }}
        </div>
    </div>

    <div class="footer">
        <p>FarmaSys © 2026 - Sistema de Gestión de Inventario para Farmacias</p>
    </div>
</body>
</html>
