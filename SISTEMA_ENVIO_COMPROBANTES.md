# Sistema de Envío de Comprobantes por Email - FarmaSys

## 📋 Descripción General

El sistema de **envío de comprobantes por email** permite a los usuarios descargar y enviar comprobantes de movimientos de inventario (entradas/salidas) a direcciones de email. Cada comprobante incluye un PDF profesional con todos los detalles del movimiento.

**Casos de Uso:**
- 📥 Registrar entrada de medicamentos y enviar comprobante de compra
- 📤 Registrar salida/venta y enviar recibo
- 🔄 Enviar comprobantes a clientes, proveedores o administrativos
- 📄 Archivar comprobantes en diferentes cuentas de email

---

## 🔄 Flujo de Funcionamiento

### 1. **Visualización de Movimiento** (movimientos.show)
```
Pantalla de detalle de movimiento
    ↓
[Botón: Descargar PDF] [Botón: Enviar por Email] [Botón: Volver]
```

### 2. **Envío de Comprobante**
```
Usuario hace click en "Enviar por Email"
    ↓
Mostrar formulario con:
  - Email de destino (requerido)
  - Asunto adicional (opcional)
  - Preview del comprobante
    ↓
Usuario completa email y envía
    ↓
Controller valida email
    ↓
Generar PDF del comprobante
    ↓
Enviar email con PDF adjunto (usando Mailable)
    ↓
Registrar en auditoría (HistorialAccion)
    ↓
Mostrar mensaje de éxito/error
```

### 3. **Recepción del Email**
```
Email recibido en bandeja
    ├─ Asunto: "Comprobante #123 - FarmaSys"
    ├─ Cuerpo: Detalles del movimiento en tabla
    └─ Adjunto: PDF con formato profesional
```

---

## 📁 Archivos Creados

### **1. Mailable: `app/Mail/ComprobanteMail.php`**

```php
class ComprobanteMail extends Mailable
{
    public MovimientoInventario $movimiento;
    public string $pdfPath;
    
    // envelope() - Definir asunto del email
    // content() - Template markdown del email
    // attachments() - Adjuntar PDF
}
```

**Funcionalidades:**
- Genera asunto dinámico con nº comprobante
- Pasa datos del movimiento a la plantilla
- Adjunta PDF con nombre descriptivo
- Serializa para queue (si se usa Jobs)

---

### **2. Plantilla Email: `resources/views/emails/comprobante.blade.php`**

Formato Markdown de Laravel con:
- Saludos personalizados al usuario
- Tabla con detalles del comprobante
- Información del medicamento
- Cálculo automático de totales
- Botón "Ver en FarmaSys"
- Fallback con URL manual

**Variables disponibles:**
```php
$usuario            // Usuario que realiza el movimiento
$movimiento         // Objeto MovimientoInventario
$medicamento        // Datos del medicamento
$tipoMovimiento     // 'Compra' o 'Salida/Venta'
$total              // cantidad × precio_unitario
```

---

### **3. Vista PDF: `resources/views/movimientos/pdf-comprobante.blade.php`**

**Diseño profesional con:**
- Header con gradiente y título
- Información de comprobante en grid de 2 columnas
- Tabla de medicamentos con estilos
- Total prominente en letras grandes verdes
- Información completa de medicamento
- Footer con fecha de generación
- Responsive y optimizado para impresión

**Características CSS:**
- Estilos inline para compatibilidad con PDF
- Badges de colores (entrada/salida)
- Tablas con bordes y sombreado
- Grid layout para información
- Colores corporativos (#2c3e50, #34495e)

---

### **4. Formulario de Envío: `resources/views/movimientos/enviar-comprobante.blade.php`**

**Elementos:**
- Campo email (requerido, con validación)
- Campo asunto adicional (opcional, 200 caracteres máx)
- Botones Enviar/Cancelar
- Panel de información del comprobante
- Sidebar con:
  - Info sobre qué incluye el comprobante
  - Botón para descargar PDF localmente
  - Advertencia de seguridad y auditoría

**Validaciones Front-end:**
- Email válido (tipo="email")
- Asunto máximo 200 caracteres
- Campos required apropiados

---

### **5. Métodos en Controller: `MovimientoInventarioController`**

#### **a) `mostrarFormularioEnvioComprobante(MovimientoInventario $movimiento)`**

```php
// GET /movimientos/{movimiento}/enviar-comprobante
// Retorna vista con formulario de envío
// Carga relaciones necesarias (medicamento, usuario)
```

#### **b) `enviarComprobante(Request $request, MovimientoInventario $movimiento)`**

```php
// POST /movimientos/{movimiento}/enviar-comprobante
// 1. Validar email
// 2. Generar PDF con Pdf::loadView()
// 3. Guardar temporalmente en storage/app/temp
// 4. Enviar email con Mail::to()->send()
// 5. Registrar en HistorialAccion (auditoría)
// 6. Limpiar archivo temporal
// 7. Redirigir con mensaje de éxito
```

**Validaciones:**
- `email` - requerido, formato email válido
- `asunto_adicional` - opcional, máximo 200 caracteres

**Manejo de Errores:**
- Try-catch para excepciones de PDF/Email
- Mensajes de error descriptivos al usuario
- Limpieza de archivos temporales aunque falle

#### **c) `exportarPDF(MovimientoInventario $movimiento)`**

```php
// GET /movimientos/{movimiento}/exportar/pdf
// Genera PDF y lo descarga directamente
// Sin envío por email, solo descarga local
```

---

### **6. Rutas Registradas: `routes/web.php`**

```php
// Mostrar formulario de envío
GET /movimientos/{movimiento}/enviar-comprobante
    → MovimientoInventarioController@mostrarFormularioEnvioComprobante
    → Route name: movimientos.mostrar-envio-comprobante

// Enviar comprobante por email
POST /movimientos/{movimiento}/enviar-comprobante
    → MovimientoInventarioController@enviarComprobante
    → Route name: movimientos.enviar-comprobante

// Descargar PDF
GET /movimientos/{movimiento}/exportar/pdf
    → MovimientoInventarioController@exportarPDF
    → Route name: movimientos.exportar-pdf
```

---

## 🔐 Seguridad

### **1. Validaciones**
- ✅ Email válido (validación Laravel)
- ✅ Movimiento existe (Model binding)
- ✅ Usuario autenticado (middleware 'auth')
- ✅ Rol verificado (middleware 'verified')

### **2. Auditoría**
Cada envío se registra en `historial_acciones`:

```sql
INSERT INTO historial_acciones (
    usuario_id,
    accion,           -- 'enviar_comprobante'
    descripcion,      -- "Comprobante #123 enviado a email@correo.com"
    tabla,            -- 'movimientos_inventario'
    registro_id,      -- ID del movimiento
    cambios,          -- JSON con detalles (email, producto, cantidad)
    created_at
) VALUES (...)
```

### **3. Privacidad de Datos**
- Email no se almacena en base de datos (solo en logs de auditoría)
- PDF se genera temporalmente y se elimina tras envío
- Archivo temporal se guarda en `storage/app/temp/`

### **4. Limitaciones**
- ✅ Ruta requiere autenticación e email verificado
- ✅ Solo usuarios con rol permitido pueden acceder
- ✅ Se valida que el email sea formato válido

---

## 📊 Datos Generados

### **Información incluida en Comprobante:**

| Campo | Fuente | Formato |
|-------|--------|---------|
| Nº Comprobante | movimientos_inventario.id | #123 |
| Fecha | created_at | d de F de Y |
| Hora | created_at | H:i:s |
| Medicamento | medicamento.nombre | String |
| Código Medicamento | medicamento.codigo | String |
| Tipo Movimiento | tipo | 'entrada' o 'salida' |
| Razón | razon | 'compra', 'venta', etc |
| Cantidad | cantidad | Integer |
| Precio Unitario | precio_unitario | Decimal(10,2) |
| Subtotal | cantidad × precio_unitario | Decimal |
| Descripción | descripcion | Text |
| Usuario | usuario.name | String |
| Email Usuario | usuario.email | Email |
| Stock Actual | medicamento.stock | Integer |
| Stock Mínimo | medicamento.stock_minimo | Integer |

---

## 🛠️ Configuración Requerida

### **1. SMTP Configuration (.env)**

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io        # O tu proveedor
MAIL_PORT=2525                     # O puerto correspondiente
MAIL_USERNAME=usuario              # Credenciales
MAIL_PASSWORD=contraseña
MAIL_FROM_ADDRESS=noreply@farmasys.local
MAIL_FROM_NAME="FarmaSys"
```

**Proveedores recomendados:**
- 🆓 Mailtrap (testing, 100 emails/mes)
- 💬 SendGrid (500+ emails)
- 🌩️ AWS SES (económico a escala)
- 📧 Gmail SMTP (cuidado con autenticación)

### **2. Directorios Necesarios**

```bash
storage/app/temp/       # Para archivos PDF temporales
```

El controller crea este directorio automáticamente si no existe.

### **3. DomPDF (Ya instalado)**

```bash
composer require barryvdh/laravel-dompdf
```

Verificar que esté publicado:
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## ✅ Checklist de Prueba

### **Pruebas Básicas**
- [ ] Acceder a detalles de movimiento
- [ ] Click en botón "Enviar" muestra formulario
- [ ] Botón "Descargar PDF" descarga el archivo
- [ ] Formulario valida email vacío
- [ ] Formulario valida email inválido
- [ ] Envío con email válido sin errores

### **Pruebas de Email**
- [ ] Email llega a bandeja (si SMTP configurado)
- [ ] Email contiene PDF adjunto
- [ ] PDF se abre correctamente
- [ ] Datos en email son correctos
- [ ] Asunto incluye nº comprobante

### **Pruebas de Auditoría**
- [ ] Después de enviar, aparece registro en historial_acciones
- [ ] Log contiene email destino
- [ ] Log contiene detalles del movimiento

### **Pruebas Edge Case**
- [ ] Enviar a email con mayúsculas (valida)
- [ ] Enviar con espacios (rechaza)
- [ ] Asunto adicional de 200 caracteres (acepta)
- [ ] Asunto adicional de 201+ caracteres (rechaza)
- [ ] Enviar a email no existente (DomPDF sigue guardando)

---

## 📱 Integración con Sistema Existente

### **Relaciones en Modelos**
```php
// MovimientoInventario.php
public function medicamento(): BelongsTo {}
public function usuario(): BelongsTo {}

// Ya existen, se usan en comprobante
```

### **Trait de Auditoría**
```php
// MovimientoInventario usa RegistraHistorial
// El controller agrega entrada manual en HistorialAccion
```

### **Utilidad Existente**
- Pdf::loadView() - Ya se usa en ListaCompraController
- Mail::to()->send() - Patrón ya usado en VerificationController
- Mailable - VerifyEmailMail, ResetPasswordMail existen

---

## 🚀 Extensiones Futuras

### **Fase 5: Exportación de Listas por Email**
```
Reutilizar ComprobanteMail para listas de compra
Agregar método ListaCompraController::enviarPorEmail()
```

### **Mejoras Opcionales**
```
1. Cola de Jobs para envíos asincronos
   php artisan queue:work
   Mail::to()->queue(new ComprobanteMail());

2. Plantillas de email personalizables por rol
   - Admin ve detalles financieros
   - Farmacéutica ve lo básico
   - Cliente ve solo cantidad/precio

3. Historial de envíos guardado en BD
   CREATE TABLE comprobante_envios
   - movimiento_id, email_destino, fecha_envio, estado

4. Resending PDF automático si falla
   - Retry logic en Mail::to()
   - Notificación al admin si falla permanentemente

5. Generación de ZIP con múltiples comprobantes
   - Seleccionar varios movimientos
   - Descargar ZIP con todos los PDFs

6. Firma digital en comprobante
   - QR con ID único
   - Validación contra base de datos
```

---

## 📝 Ejemplos de Uso

### **API del Sistema**

#### **Mostrar Formulario de Envío**
```php
// En controlador o vista
route('movimientos.mostrar-envio-comprobante', $movimiento->id)
// GET /movimientos/123/enviar-comprobante
```

#### **Enviar Comprobante**
```php
// Desde formulario
form action="{{ route('movimientos.enviar-comprobante', $movimiento->id) }}" 
    method="POST"
    // POST /movimientos/123/enviar-comprobante
    // Requiere CSRF token
```

#### **Descargar PDF**
```php
// Enlace directo
a href="{{ route('movimientos.exportar-pdf', $movimiento->id) }}"
// GET /movimientos/123/exportar/pdf
// Descarga comprobante-123-2026-03-20.pdf
```

---

## 🐛 Troubleshooting

| Problema | Causa | Solución |
|----------|-------|----------|
| Email no se envía | SMTP no configurado | Ver sección Configuración |
| PDF se ve en blanco | DomPDF no instalado | `composer require barryvdh/laravel-dompdf` |
| Error "Directorio no existe" | storage/app/temp/ no creado | Controller lo crea automáticamente |
| Email rechaza adjunto | MIME type incorrecto | Verificar ComprobanteMail::attachments() |
| Datos incorrectos en PDF | Relaciones no cargadas | Controller hace `->load()` automático |

---

## 📚 Documentación Relacionada

- [SISTEMA_ROLES_PERMISOS.md](SISTEMA_ROLES_PERMISOS.md) - Control de acceso
- [SISTEMA_VERIFICACION_EMAIL.md](SISTEMA_VERIFICACION_EMAIL.md) - Verificación de emails
- [SISTEMA_RECUPERACION_PASSWORD.md](SISTEMA_RECUPERACION_PASSWORD.md) - Reset de contraseña

---

## 👤 Autor & Registro

**Implementación:** FarmaSys Phase 4 - Sistema de Comprobantes por Email  
**Fecha:** 20 de Marzo 2026  
**Versión:** 1.0 (Inicial)  
**Framework:** Laravel 12, PHP 8.4  
**Dependencias:** 
- barryvdh/laravel-dompdf (for PDF generation)
- Laravel Mail (built-in)

---

**Status:** ✅ Completo y Listo para Usar
