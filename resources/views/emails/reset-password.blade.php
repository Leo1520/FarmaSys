@component('mail::message')
# Recuperar contraseña

Hola {{ $user->name }},

Recibimos una solicitud para recuperar tu contraseña. Si no fuiste tú, puedes ignorar este correo.

@component('mail::button', ['url' => $resetUrl])
Establecer nueva contraseña
@endcomponent

Este enlace vencerá en 2 horas.

Si tienes problemas con el botón, copia y pega este enlace en tu navegador:
{{ $resetUrl }}

Por seguridad, nunca solicites a alguien que te envíe tu contraseña.

Saludos,<br>
**Equipo de FarmaSys**
@endcomponent
