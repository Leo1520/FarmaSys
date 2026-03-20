@component('mail::message')
# Verificar correo electrónico

Hola {{ $user->name }},

Gracias por registrarte en **FarmaSys**. Para completar tu registro, necesitas verificar tu dirección de correo electrónico.

@component('mail::button', ['url' => $verificationUrl])
Verificar correo electrónico
@endcomponent

Este enlace vencerá en 24 horas.

Si no realizaste este registro, puedes ignorar este correo.

Saludos,<br>
**Equipo de FarmaSys**

---

*P.S.* Si tienes problemas con el botón, copia y pega este enlace en tu navegador:
{{ $verificationUrl }}
@endcomponent
