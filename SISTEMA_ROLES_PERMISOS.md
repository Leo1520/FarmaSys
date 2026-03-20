# Sistema de Roles y Permisos - FarmaSys

## 📋 Descripción General

El sistema de roles y permisos de FarmaSys implementa un control de acceso basado en roles (RBAC) con aprobación de usuarios pendientes. Cada usuario tiene un **rol** que determina sus permisos, y un **estado** que indica si su cuenta está aprobada.

---

## 🎯 Componentes del Sistema

### 1. Modelos y Migraciones

#### Migración: `2026_03_20_000009_add_guest_role_and_approval_system.php`
- **Propósito:** Agregar campos de estado y rol invitado a la tabla de usuarios
- **Cambios:**
  - Añade enum `rol`: `'admin'`, `'farmaceutica'`, `'invitado'`
  - Añade enum `estado`: `'pendiente'`, `'activo'`, `'rechazado'`, `'inactivo'`
  - Añade campo `razon_rechazo` para registrar motivos de rechazo
- **Estado:** ✅ Ejecutada

#### Modelo: `app/Models/User.php`
- **Añadidos:**
  - Campos fillable: `'rol'`, `'estado'`, `'razon_rechazo'`
  - Métodos: `esInvitado()`, `estaActivo()`, `estaPendiente()`, `puede($permiso)`
  - Scopes: `scopePendientes()`, `scopeActivos()`

---

### 2. Servicios

#### `app/Services/PermissionService.php`
**Propósito:** Matriz centralizada de permisos por rol

**Matriz de Permisos:**
```
                    Admin  Farmacéutica  Invitado
medicamentos.ver      ✓          ✓           ✓
medicamentos.crear    ✓          ✓           ✗
medicamentos.editar   ✓          ✓           ✗
medicamentos.eliminar ✓          ✗           ✗
movimientos.ver       ✓          ✓           ✗
movimientos.crear     ✓          ✓           ✗
movimientos.editar    ✓          ✓           ✗
movimientos.eliminar  ✓          ✗           ✗
lista-compra.ver      ✓          ✓           ✗
lista-compra.crear    ✓          ✓           ✗
lista-compra.editar   ✓          ✓           ✗
lista-compra.eliminar ✓          ✗           ✗
usuarios.*            ✓          ✗           ✗
historial.*           ✓          ✓           ✗
dashboard.*           ✓          ✓           ✗
configuracion.*       ✓          ✗           ✗
```

**Métodos Principales:**
- `getPermissions($rol)` - Obtener matriz de permisos para un rol
- `can($permiso, $rol)` - Verificar si un rol tiene un permiso
- `userCan($permiso)` - Verificar si el usuario autenticado tiene un permiso

---

### 3. Middleware

#### `app/Http/Middleware/CheckRoleAndPermission.php`
**Propósito:** Proteger rutas basándose en rol y estado de cuenta

**Funcionalidad:**
- Valida que el usuario tenga el rol requerido
- Verifica que la cuenta esté en estado `'activo'`
- Retorna mensajes específicos 403 para cada caso:
  - `'pendiente'` → "Tu cuenta está pendiente de aprobación"
  - `'rechazado'` → "Tu solicitud fue rechazada"
  - `'inactivo'` → "Tu cuenta ha sido desactivada"

**Uso en Rutas:**
```php
Route::middleware('role:admin,estado:activo')->group(function () {
    // Rutas solo para admins activos
});
```

---

### 4. Controladores

#### `app/Http/Controllers/ApprovalController.php`
**Propósito:** Gestionar aprobación/rechazo de usuarios pendientes

**Métodos:**
- `pendientes()` - Listar usuarios pendientes
- `aprobar($user)` - Aprobar usuario y asignar rol
- `rechazar($user)` - Rechazar usuario con motivo
- `cambiarRol($user)` - Cambiar rol de usuario
- `desactivar($user)` - Desactivar usuario
- `reactivar($user)` - Reactivar usuario

**Características:**
- Registra todas las acciones en `HistorialAccion`
- Captura cambios de rol y estado
- Valida que el usuario esté en estado pendiente

#### `app/Http/Controllers/UserController.php`
**Cambios:**
- Filtrado por estado en `index()`
- Validación actualizada para aceptar rol `'invitado'` y estados
- Registro de cambios en historial

---

## 🔄 Flujo de Aprobación de Usuarios

```
┌─────────────────────────────────────────────────────────────┐
│  1. Nuevo Usuario se Registra                               │
│     - rol = 'invitado'                                      │
│     - estado = 'pendiente'                                  │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│  2. Admin revisa usuarios pendientes en                     │
│     /approval/pendientes                                    │
└──────────────────────┬──────────────────────────────────────┘
                       │
        ┌──────────────┴──────────────┐
        │                             │
        ▼                             ▼
  /// Aprobado ///            /// Rechazado ///
  - Selecciona rol        - Proporciona razón
  - estado = 'activo'     - estado = 'rechazado'
  - email_verified_at     - razon_rechazo guardado
        │                             │
        └──────────────┬──────────────┘
                       │
                       ▼
            ║ Usuario Activo o Rechazado ║
```

---

## 👥 Roles y Permisos

### Admin 🔴
- **Acceso:** Control total del sistema
- **Permisos:** Todas las operaciones en todas las áreas
- **Responsabilidades:**
  - Gestionar usuarios (roles, estados, aprobaciones)
  - Revisar historial completo del sistema
  - Configurar parámetros del sistema
  - Cambiar roles de otros usuarios

### Farmacéutica 🔵
- **Acceso:** Operaciones de negocio limitadas
- **Permisos:**
  - Ver, crear, editar medicamentos (no eliminar)
  - Ver, crear, editar movimientos (no eliminar)
  - Ver, crear, editar listas de compra (no eliminar)
  - Ver su historial personal
- **Restricciones:**
  - No puede gestionar usuarios
  - No puede ver configuración
  - No puede eliminar medicamentos

### Invitado 👁️
- **Acceso:** Solo lectura de medicamentos
- **Permisos:**
  - Ver medicamentos (solo lectura)
- **Restricciones:** No puede crear, editar, eliminar nada
- **Nota:** Rol temporal para usuarios en proceso de aprobación

---

## 🔐 Estados de Cuenta

| Estado | Descripción | Acceso |
|--------|-------------|--------|
| **pendiente** | Esperando aprobación | ❌ Bloqueado |
| **activo** | Cuenta aprobada y activa | ✅ Acceso permitido |
| **rechazado** | Solicitud denegada | ❌ Acceso denegado |
| **inactivo** | Desactivado temporalmente | ❌ Acceso bloqueado |

---

## 📁 Estructura de Rutas

### Rutas de Autenticación
```php
POST /register          // Registro (nuevo usuario = invitado + pendiente)
GET  /login
POST /login
POST /logout
```

### Rutas de Aprobación (Admin)
```php
GET  /approval/pendientes              // Listar pendientes
PUT  /approval/{user}/aprobar          // Aprobar usuario
PUT  /approval/{user}/rechazar         // Rechazar usuario
```

### Rutas de Usuarios (Admin)
```php
GET    /users                          // Listar todos los usuarios
GET    /users/{user}/edit              // Formulario de edición
PUT    /users/{user}                   // Actualizar usuario
DELETE /users/{user}                   // Eliminar usuario
PUT    /users/{user}/cambiar-rol       // Cambiar rol
PUT    /users/{user}/desactivar        // Desactivar usuario
PUT    /users/{user}/reactivar         // Reactivar usuario
```

---

## 💾 Base de Datos

### Cambios en tabla `users`
```sql
ALTER TABLE users ADD COLUMN rol enum('admin', 'farmaceutica', 'invitado') DEFAULT 'invitado';
ALTER TABLE users ADD COLUMN estado enum('pendiente', 'activo', 'rechazado', 'inactivo') DEFAULT 'pendiente';
ALTER TABLE users ADD COLUMN razon_rechazo text;
```

### Tabla `historial_accions`
Registra automáticamente:
- Cambios de rol
- Cambios de estado
- Aprobaciones/rechazos
- Desactivaciones/reactivaciones

---

## 🔍 Auditoría y Trazabilidad

Todas las acciones de roles y permisos se registran en la tabla `historial_accions`:

```php
HistorialAccion::create([
    'usuario_id'  => Auth::id(),           // Quién hizo la acción
    'accion'      => 'aprobar_usuario',    // Tipo de acción
    'descripcion' => "Usuario X aprobado", // Detalles
    'tabla'       => 'users',              // Tabla afectada
    'registro_id' => $user->id,            // ID del registro
    'cambios'     => json_encode([...])    // Cambios realizados
]);
```

---

## 📝 Vistas Asociadas

### Dashboard (`resources/views/dashboard.blade.php`)
- Alerta para usuarios pendientes de aprobación
- Alerta para admins cuando hay usuarios pendientes
- Información de cuenta con estado actual

### Gestión de Usuarios (`resources/views/users/`)
- **index.blade.php**
  - Tabla con filtros por rol y estado
  - Botones para desactivar/reactivar usuarios
  - Estados visuales con badges
  
- **edit.blade.php**
  - Campos para cambiar rol y estado
  - Sidebar con información actual del usuario
  - Validación de formulario

### Aprobación de Usuarios (`resources/views/approval/pendientes.blade.php`)
- Tabla de usuarios pendientes
- Modales para aprobar/rechazar
- Selección de rol al aprobar
- Información de roles disponibles

---

## 🚀 Próximas Fases

### Fase 1: Sistema de Email (Identificada)
- [ ] Validación de email real
- [ ] Recuperación de contraseña
- [ ] Notificaciones de aprobación/rechazo
- [ ] Envío de comprobantes de compra
- [ ] Exportación de listas de compra por email

### Fase 2: Permisos Granulares
- [ ] Directivas Blade `@can/@cannot`
- [ ] Gates en Laravel
- [ ] Permisos dinámicos por usuario

### Fase 3: Auditoria Avanzada
- [ ] Reportes de cambios por usuario
- [ ] Trazabilidad completa de acciones
- [ ] Exportación de historial

---

## 🧪 Testing

Para probar el sistema:

1. **Registrar nuevo usuario:**
   - Se crea con rol = 'invitado', estado = 'pendiente'
   - Acceso bloqueado hasta aprobación

2. **Aprobar usuario:**
   - Admin va a `/approval/pendientes`
   - Selecciona rol deseado (farmacéutica o admin)
   - Usuario obtiene acceso inmediatamente

3. **Rechazar usuario:**
   - Admin proporciona motivo
   - Usuario ve alerta en dashboard con el motivo

4. **Cambiar rol:**
   - Admin va a `/users/{id}/edit`
   - Cambia rol y guarda
   - Cambio se registra en historial

---

## 📚 Referencias

- **Middleware:** `CheckRoleAndPermission`
- **Servicio:** `PermissionService`
- **Controladores:**
  - `ApprovalController`
  - `UserController`
  - `RegisterController` (actualizado)
- **Modelo:** `User` (actualizado)
- **Migraciones:** `2026_03_20_000009_add_guest_role_and_approval_system`

---

## ✅ Checklist de Implementación

- [x] Migración de rol invitado y estado
- [x] Modelo User actualizado con métodos helper
- [x] PermissionService con matriz de permisos
- [x] CheckRoleAndPermission middleware
- [x] ApprovalController para gestión de aprobaciones
- [x] UserController actualizado con filtros
- [x] RegisterController actualizado (nuevos = invitado + pendiente)
- [x] Vistas de dashboard actualizadas
- [x] Vista de listado de usuarios mejorada
- [x] Vista de edición de usuarios mejorada
- [x] Vista de aprobación de usuarios pendientes
- [x] Rutas configuradas en web.php
- [x] Middleware registrado en bootstrap/app.php
- [x] Auditoría integrada con HistorialAccion

---

**Última actualización:** 2025-03-20
**Versión del sistema:** 2.1.0 (Roles y Permisos)
