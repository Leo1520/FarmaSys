<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Compra #{{ $listaCompra->id }}</title>
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
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .info-bar {
            background-color: #f0f0f0;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 12px;
        }

        .info-bar-item {
            display: inline-block;
            margin-right: 40px;
        }

        .info-bar-item strong {
            color: #2c5aa0;
        }

        .estado-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 3px;
            font-weight: bold;
            color: white;
            font-size: 11px;
        }

        .estado-pendiente {
            background-color: #ffc107;
            color: #333;
        }

        .estado-comprada {
            background-color: #28a745;
        }

        .estado-cancelada {
            background-color: #dc3545;
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

        tfoot {
            background-color: #2c5aa0;
            color: white;
            font-weight: bold;
        }

        tfoot td {
            padding: 15px 12px;
            border-bottom: none;
        }

        .notas {
            background-color: #e9ecef;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 12px;
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
            font-size: 12px;
        }

        .resumen-item {
            display: inline-block;
            margin-right: 30px;
        }

        .resumen-item strong {
            color: #2c5aa0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛒 Lista de Compra #{{ $listaCompra->id }}</h1>
        <p><strong>FarmaSys - Sistema de Gestión de Inventario</strong></p>
        <p>Generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info-bar">
        <div class="info-bar-item">
            <strong>Fecha de Creación:</strong> {{ $listaCompra->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="info-bar-item">
            <strong>Estado:</strong>
            @if ($listaCompra->estado === 'pendiente')
                <span class="estado-badge estado-pendiente">Pendiente</span>
            @elseif ($listaCompra->estado === 'comprada')
                <span class="estado-badge estado-comprada">Comprada</span>
            @else
                <span class="estado-badge estado-cancelada">Cancelada</span>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 35%">Medicamento</th>
                <th style="width: 12%">Código</th>
                <th style="width: 15%">Cantidad</th>
                <th style="width: 15%">Precio Unit.</th>
                <th style="width: 23%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detalles as $detalle)
                <tr>
                    <td>
                        <strong>{{ $detalle->medicamento->nombre }}</strong>
                    </td>
                    <td>{{ $detalle->medicamento->codigo ?? '-' }}</td>
                    <td class="text-center"><strong>{{ $detalle->cantidad_sugerida }}</strong></td>
                    <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-right"><strong>${{ number_format($detalle->subtotal(), 2) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay medicamentos en esta lista</td>
                </tr>
            @endforelse
        </tbody>
        @if ($detalles->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL ESTIMADO:</td>
                    <td class="text-right">${{ number_format($listaCompra->totalEstimado(), 2) }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    @if ($listaCompra->notas)
        <div class="notas">
            <strong>Notas:</strong><br>
            {{ $listaCompra->notas }}
        </div>
    @endif

    <div class="resumen">
        <div class="resumen-item">
            <strong>Total de Medicamentos:</strong> {{ $detalles->count() }}
        </div>
        <div class="resumen-item">
            <strong>Total Estimado:</strong> ${{ number_format($listaCompra->totalEstimado(), 2) }}
        </div>
        <div class="resumen-item">
            <strong>Última Actualización:</strong> {{ $listaCompra->updated_at->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="footer">
        <p>FarmaSys © 2026 - Sistema de Gestión de Inventario para Farmacias</p>
        <p>Este documento fue generado automáticamente por el sistema</p>
    </div>
</body>
</html>
