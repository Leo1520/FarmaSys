@component('mail::message')
# Comprobante de {{  $tipoMovimiento }}

¡Hola {{ $usuario->name }}!

Adjunto encontrarás el comprobante del movimiento de inventario realizado en FarmaSys.

## Detalles del Comprobante

| Concepto | Valor |
|----------|-------|
| **Comprobante #** | {{ $movimiento->id }} |
| **Fecha** | {{ $movimiento->created_at->format('d \\de F \\de Y') }} |
| **Medicamento** | {{ $medicamento->nombre }} |
| **Tipo de Movimiento** | {{ ucfirst($movimiento->tipo) }} |
| **Razón** | {{ ucfirst($movimiento->razon) }} |
| **Cantidad** | {{ $movimiento->cantidad }} unidades |
| **Precio Unitario** | ${{ number_format($movimiento->precio_unitario ?? 0, 2) }} |
| **Total** | ${{ number_format($total, 2) }} |

## Información Adicional

@if ($movimiento->descripcion)
**Descripción:** {{ $movimiento->descripcion }}
@endif

**Usuario que realiza:** {{ $usuario->name }}
**Email:** {{ $usuario->email }}

---

El PDF del comprobante está adjunto a este correo.

@component('mail::button', ['url' => config('app.url')])
Ver en FarmaSys
@endcomponent

Gracias,
**FarmaSys**
@endcomponent
