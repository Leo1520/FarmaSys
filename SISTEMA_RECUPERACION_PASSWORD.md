# Sistema de Recuperación de Contraseña - FarmaSys

## 📋 Descripción General

Sistema completo de recuperación de contraseña por email. Los usuarios que olvidan su contraseña pueden solicitar un enlace de recuperación válido por 2 horas y establecer una nueva contraseña de forma segura.

---

## 🔄 Flujo de Recuperación

```
1. Usuario olvida contraseña
   ↓
2. Clic en "Olvidé mi contraseña" en login
   ↓
3. Ingresa email → Sistema envía enlace
   ↓
4. Usuario recibe email con link
   ↓
5. Clic en link (válido 2 horas)
   ↓
6. Formulario para nueva contraseña
   ↓
7. Nueva contraseña guardada → Puede ingresar
```

---

## 📁 Componentes Implementados

### 1. Mailable (Email Template)

**Archivo:** `app/Mail/ResetPasswordMail.php`
- Clase para enviar emails de recuperación
- Incluye enlace con token de 2 horas

**Vista:** `resources/views/emails/reset-password.blade.php`
- Template profesional
- Instrucciones claras
- Link fallback manual

### 2. Controladores

#### `app/Http/Controllers/Auth/ForgotPasswordController.php`

**Métodos:**
- `show()` - Mostrar formulario "Olvidé mi contraseña"
- `store()` - Generar token y enviar email

**Características:**
- Token SHA256 único
- Guardar en tabla `password_reset_tokens`
- Duración: 2 horas
- Email con link de recuperación

#### `app/Http/Controllers/Auth/ResetPasswordController.php`

**Métodos:**
- `show()` - Mostrar formulario para nueva contraseña
- `store()` - Validar token y actualizar contraseña

**Características:**
- Validación de token
- Verificación de expiración
- Actualización segura de contraseña
- Auditoría automática
- Limpieza de token tras uso

### 3. Vistas

#### `resources/views/auth/forgot-password.blade.php`
- Formulario para ingreso de email
- Validaciones en cliente
- Mensajes de error claros
- Link a login

#### `resources/views/auth/reset-password.blade.php`
- Formulario para nueva contraseña
- Validación de requisitos
- Campo de confirmación
- Información de seguridad

### 4. Rutas

```
GET  /forgot-password              // Mostrar formulario
POST /forgot-password              // Procesar solicitud
GET  /reset-password               // Mostrar form. cambio (con token)
POST /reset-password               // Procesar cambio de contraseña
```

---

## 🔐 Seguridad

### Token
- **Tipo:** SHA256 unique
- **Componentes:** `user_id + email + timestamp + app_key`
- **Almacenamiento:** Base de datos (`password_reset_tokens`)
- **Duración:** 2 horas
- **Valididad:** Una sola vez (eliminado tras uso)

### Validaciones
- ✅ Email debe ser válido
- ✅ Email debe existir en BD
- ✅ Token debe coincidir exactamente
- ✅ Token no debe estar expirado
- ✅ Contraseña debe cumplir requisitos
- ✅ Confirmación debe coincidir

### Requisitos de Contraseña
- Mínimo 8 caracteres
- Al menos una mayúscula (A-Z)
- Al menos una minúscula (a-z)
- Al menos un número (0-9)
- Al menos un símbolo (!@#$%...)

---

## 💾 Base de Datos

### Tabla: `password_reset_tokens`
```sql
CREATE TABLE password_reset_tokens (
  email VARCHAR(255) PRIMARY KEY,
  token VARCHAR(255) NOT NULL,
  created_at TIMESTAMP

);
```

**Limpieza:**
- Token se elimina automáticamente tras uso
- Se recomienda job para limpiar tokens expirados (opcional)

---

## 🎯 Flujo Completo de Usuario

### 1. Solicitar Recuperación
```
Usuario en /login
       → Clic "Olvidé mi contraseña"
       → Ingresa email
       → Sistema verifica que email existe
       → Genera token de 2 horas
       → Envía email con link
       → Confirmación visible: "Email enviado"
```

### 2. Validar Token
```
Usuario recibe email
       → Clic en link
       → Sistema valida:
          ✓ Email existe
          ✓ Token es válido
          ✓ Token no está expirado
       → Si todo OK: muestra formulario
       → Si error: redirige a solicitud nueva
```

### 3. Establecer Nueva Contraseña
```
Usuario ve formulario
       → Ingresa nueva contraseña
       → Confirma contraseña
       → Sistema valida:
          ✓ Cumple requisitos
          ✓ Las dos coinciden
       → Actualiza en BD
       → Registra en auditoría
       → Elimina token
       → Redirige a login
```

---

## 📧 Configuración de Email

Requisitos previos (.env):
```env
MAIL_MAILER=smtp
MAIL_HOST=tu_servidor
MAIL_PORT=587
MAIL_USERNAME=tu_email
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmasys.com
MAIL_FROM_NAME="FarmaSys"
```

---

## 🧪 Casos de Uso

### Caso 1: Flujo Exitoso
1. Usuario solicita reset → Email enviado ✓
2. Clic en link → Formulario mostrado ✓
3. Nueva contraseña → BD actualizada ✓
4. Puede ingresar con nueva contraseña ✓

### Caso 2: Token Expirado
1. Usuario no hace clic en 2 horas
2. Intenta usar link → "Token expirado"
3. Redirigido a solicitar nuevo email

### Caso 3: Email Inválido
1. Usuario ingresa email que no existe
2. Mensaje: "No encontramos usuario con ese email"
3. Puede intentar otro email

### Caso 4: Contraseña Débil
1. Usuario ingresa contraseña sin mayúsculas
2. Validación falla → Mostrar requisitos
3. Debe cumplir todos los requisitos

---

## 📊 Auditoría

Cada reset se registra en `historial_accions`:
```php
[
  'usuario_id' => $user->id,
  'accion' => 'reset_password',
  'descripción' => "Usuario X recuperó su contraseña",
  'tabla' => 'users',
  'registro_id' => $user->id,
  'cambios' => ['password' => 'actualizada'],
]
```

---

## 🎨 Interfaz

### Login (Actualizado)
- Enlace "¿Olvidé mi contraseña?" en el formulario
- Link en footer bajo "Registrarse"
- Estilos consistentes con el sistema

### Formularios
- Validación en cliente
- Mensajes de error claros
- Información de requisitos visible
- Diseño responsivo

---

## ⚠️ Consideraciones de Seguridad

### ✅ Implementado
- Token único con hash SHA256
- Expiración de 2 horas
- Un token por email (no múltiples)
- Token eliminado tras uso
- Auditoría de reset
- Validación fuerte de contraseña

### 🔄 Recomendaciones Futuras
- Implementar log de intentos fallidos
- Limitar intentos por IP
- Enviar email de confirmación tras reset
- Invalidar sesiones activas tras cambio
- 2FA (autenticación de dos factores)

---

## 📋 Checklist de Implementación

- [x] Mailable `ResetPasswordMail` creado
- [x] ForgotPasswordController implementado
- [x] ResetPasswordController implementado
- [x] Vista forgot-password.blade.php
- [x] Vista reset-password.blade.php
- [x] Template email reset-password.blade.php
- [x] Rutas configuradas en web.php
- [x] Login actualizado con enlaces
- [x] Auditoría integrada
- [x] Validación de contraseña fuerte
- [x] Tokens en base de datos

---

## 🚀 Uso

### Para Usuario Final

1. **Olvidé contraseña:**
   - Click en "¿Olvidé mi contraseña?" en login
   - Ingresa email registrado
   - Recibe email con link

2. **Resetear contraseña:**
   - Click en link del email
   - Ingresa nueva contraseña (debe cumplir requisitos)
   - Confirma contraseña
   - Click "Establecer contraseña"

### Para Admin (Auditoría)

- Ver en historial todos los resets realizados
- Filtrar por usuario
- Ver cuándo se reseteó
- Registrar en base de datos

---

## 🔍 Testing

### Test 1: Recuperación Exitosa
```
1. Ir a /login
2. Click "¿Olvidé mi contraseña?"
3. Ingresa email válido
4. Recibe email (en pruebas, ver en logs)
5. Clic en link
6. Nueva contraseña
7. Inicia sesión con nueva contraseña ✓
```

### Test 2: Email Inválido
```
1. Ingresa email que no existe
2. Error: "No encontramos usuario"
3. Intenta otro email
```

### Test 3: Token Expirado
```
1. Espera 2+ horas
2. Intenta usar link
3. Error: "Token expirado"
4. Solicita nuevo link
```

---

## 📚 Archivos Modificados

**Nuevos:**
- `app/Mail/ResetPasswordMail.php`
- `app/Http/Controllers/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/Auth/ResetPasswordController.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/emails/reset-password.blade.php`

**Modificados:**
- `routes/web.php` - Rutas agregadas
- `resources/views/auth/login.blade.php` - Enlaces agregados

---

**Última actualización:** 2025-03-20
**Versión:** 3.1.0 (Recuperación de Contraseña)
