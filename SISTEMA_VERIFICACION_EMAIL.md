# Sistema de Verificación de Correo Electrónico - FarmaSys

## 📋 Descripción General

Sistema completo de verificación de emails para FarmaSys. Los usuarios nuevos se registran y luego deben verificar su dirección de correo antes de acceder a las funcionalidades principales del sistema.

---

## 🔄 Flujo de Verificación

```
1. Usuario se registra
   ↓
2. Sistema envía link de verificación a su email
   ↓
3. Usuario hace clic en el link (válido por 24 horas)
   ↓
4. Email se marca como verificado
   ↓
5. Puede acceder a dashboard y funcionalidades
```

---

## 📁 Componentes Implementados

### 1. Mailable (Email Template)

**Archivo:** `app/Mail/VerifyEmailMail.php`
- Clase que define el contenido del email de verificación
- Incluye enlace de verificación con token

**Vista:** `resources/views/emails/verify-email.blade.php`
- Template de email con instrucciones
- Enlace clickeable + fallback manual

### 2. Controlador de Verificación

**Archivo:** `app/Http/Controllers/Auth/VerificationController.php`

**Métodos:**
- `show()` - Mostrar página de verificación
- `send($request)` - Enviar email de verificación (después de login)
- `verify($request)` - Verificar token y marcar como verificado
- `resend($request)` - Reenviar email de verificación

**Características:**
- Token con hash SHA256
- Validez de 24 horas
- Almacenamiento temporal en sesión
- Manejo de errores con mensajes claros

### 3. Middleware de Protección

**Archivo:** `app/Http/Middleware/EnsureEmailIsVerified.php`

**Funcionalidad:**
- Redirige a usuarios no verificados a página de verificación
- Permite acceso a rutas específicas (logout, verificación)
- Se aplica a rutas protegidas

**Rutas permitidas sin verificación:**
- `email.verify.show` - Página de verificación
- `verification.send` - Enviar email
- `verification.resend` - Reenviar email
- `email.verify` - Verificar token
- `logout` - Cerrar sesión

### 4. Modelo User (Actualizado)

**Métodos nuevos:** `app/Models/User.php`

```php
public function hasVerifiedEmail(): bool
// Verifica si el email fue verificado

public function markEmailAsVerified(): bool
// Marca el email como verificado
```

### 5. Controlador de Registro (Actualizado)

**Archivo:** `app/Http/Controllers/Auth/RegisterController.php`

**Cambio:**
- Después del registro, redirige a página de verificación (no al dashboard)
- Mensaje: "Por favor verifica tu correo electrónico"

---

## 🛣️ Rutas Implementadas

### Rutas Públicas
```
GET  /login                    // Login
POST /login                    // Procesar login
GET  /register                 // Registro
POST /register                 // Procesar registro
```

### Rutas de Verificación (Requieren Auth)
```
GET  /email/verify             // Página de verificación
POST /email/verify/send        // Enviar email
POST /email/verify/resend      // Reenviar email
GET  /verify-email/{token}    // Verificar con token
```

### Rutas Protegidas (Requieren Auth + Email Verificado)
```
GET  /dashboard                // Dashboard
... (todas las demás rutas)
```

---

## 🔐 Seguridad

### Token de Verificación
- **Tipo:** Hash SHA256
- **Componentes:** `user_id + email + timestamp`
- **Almacenamiento:** Sesión del usuario
- **Duración:** 24 horas

### Validaciones
- ✅ Email debe ser válido
- ✅ Debe haber email del usuario en base de datos
- ✅ Token debe coincidir exactamente
- ✅ Token no debe estar expirado
- ✅ Usuario debe estar autenticado

### Prevención de Ataques
- Token único por sesión
- Expiración después de 24 horas
- No se reutilizan tokens
- Validación de usuario autenticado

---

## 📧 Configuración de Email

### Variables de entorno necesarias (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=tu_servidor_smtp
MAIL_PORT=587
MAIL_USERNAME=tu_email@example.com
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmasys.com
MAIL_FROM_NAME="FarmaSys"
```

### Opciones de SMTP
- **Gmail:** `smtp.gmail.com:587`
- **Mailtrap:** `smtp.mailtrap.io:465` (testing)
- **SendGrid:** `smtp.sendgrid.net:587`
- **AWS SES:** `email-smtp.region.amazonaws.com:587`

### Testing en Desarrollo
Usar **Mailtrap** (servicio gratuito para desarrollo):
1. Ir a mailtrap.io
2. Crear cuenta (gratuita)
3. Crear inbox
4. Copiar credenciales SMTP a .env
5. Los emails se capturan en Mailtrap (no se envían realmente)

---

## 🎯 Vistas Implementadas

### Vista de Verificación (`resources/views/auth/verify-email.blade.php`)

**Contenido:**
- Encabezado con ícono
- Mensajes de éxito/error
- Instrucciones sobre verificación
- Botón "Reenviar email"
- Información de cuenta
- Opción para cambiar email (logout)

**Información mostrada:**
- Email del usuario
- Rol asignado
- Estado de verificación
- Botón de reenviada

---

## 🔄 Flujo Completo de Usuario Nuevo

### 1. Registro
```
Usuario → Formulario de registro 
       → Validación
       → Crear usuario con email_verified_at = null
       → Redirigir a /email/verify
```

### 2. Verificación
```
Usuario en /email/verify
       → Clic en "Reenviar email"
       → Email se envía
       → Usuario revisa bandeja
       → Clic en enlace del email
       → Token se valida
       → email_verified_at se actualiza con timestamp
       → Redirigido a dashboard
```

### 3. Dashboard
```
Usuario puede ver:
   ✓ Dashboard
   ✓ Medicamentos
   ✓ Movimientos
   ✓ Listas de compra
   (Si su rol y estado lo permiten)
```

---

## 📊 Base de Datos

### Campo en tabla users
```sql
email_verified_at TIMESTAMP NULL DEFAULT NULL
```

**Estado:**
- `NULL` = No verificado
- `2025-03-20 15:30:00` = Verificado en esa fecha/hora

---

## 🧪 Casos de Uso

### Caso 1: Usuario Verifica Email Correctamente
1. Registra → Recibe email
2. Clic en enlace → Email verificado
3. Puede acceder a dashboard

### Caso 2: Usuario Pierde Email
1. En página de verificación
2. Clic "Reenviar email"
3. Nuevo email con nuevo token válido 24h

### Caso 3: Token Expirado
1. Usuario intenta verificar después de 24h
2. Token rechazado
3. Redirigido a página de verificación
4. Puede solicitar nuevo email

### Caso 4: Usuario Intenta Acceder sin Verificar
1. Intenta ir a /dashboard
2. Middleware `verified` redirige a /email/verify
3. Debe verificar antes de continuar

---

## 🔔 Alertas en Sistema

### Dashboard
- ⚠️ Alerta amarilla si email no está verificado
- Link directo a verificación

### Página de Verificación
- ℹ️ Instrucciones claras
- 📧 Email del usuario mostrado
- ⏰ Validez del token (24 horas)

---

## ⚡ Mejoras Futuras

1. **Email Confirmación Mejorado**
   - Enviar email solo después de verificación
   - Código OTP además de link

2. **Cambio de Email**
   - Permitir cambiar email
   - Verificar nuevo email antes de confirmar

3. **Resend con Cooldown**
   - Limitar reenvíos a 1 cada 5 minutos
   - Evitar spam

4. **Integración con Jobs**
   - Encolar emails para envío asincrónico
   - Mejor performance

5. **Recuperación de Password**
   - Sistema similar para resetear contraseña
   - Link de 24 horas

---

## 📋 Checklist de Implementación

- [x] Mailable `VerifyEmailMail` creado
- [x] VerificationController implementado
- [x] Middleware `EnsureEmailIsVerified` creado
- [x] Métodos en User model
- [x] Rutas configuradas
- [x] Vista de verificación
- [x] Template de email
- [x] RegisterController actualizado
- [x] Bootstrap app.php actualizado
- [x] Dashboard actualizado con alerta
- [x] Middleware registrado

---

## 🚀 Próximos Pasos

1. **Configurar SMTP Real**
   - Usar Gmail, SendGrid, o AWS SES
   - Actualizar .env con credenciales reales

2. **Testing**
   - Registrar usuario
   - Verificar email llega
   - Hacer clic en link
   - Verificar acceso a dashboard

3. **Fase Siguiente: Recuperación de Contraseña**
   - Sistema similar a verificación
   - Link para resetear password
   - Válido por corto tiempo

---

**Última actualización:** 2025-03-20
**Versión:** 3.0.0 (Verificación de Email)
