📌 1. NOMBRE DEL SISTEMA

FarmaSys – Sistema de Gestión de Inventario para Farmacias

📖 2. INTRODUCCIÓN

En la actualidad, muchas farmacias pequeñas y medianas aún gestionan sus medicamentos de forma manual o empírica, lo que genera problemas como desorganización, pérdida de productos, falta de control de stock y dificultades al momento de realizar reposiciones.

El presente proyecto propone el desarrollo de FarmaSys, un sistema web que permite registrar, buscar y controlar medicamentos de manera eficiente, adaptado a la forma real de trabajo de una farmacia, donde el personal identifica los productos principalmente por su nombre y no por códigos.

❗ 3. PROBLEMÁTICA

La farmacia objeto de estudio presenta las siguientes dificultades:

No existe un sistema digital para el registro de medicamentos

El control de stock se realiza manualmente

No se detectan productos con bajo stock a tiempo

No hay un listado organizado de medicamentos faltantes

La búsqueda de productos es lenta

No se cuenta con reportes o historial

🎯 4. OBJETIVO GENERAL

Desarrollar un sistema web que permita gestionar el inventario de medicamentos de manera eficiente, facilitando el registro, búsqueda, control de stock y generación de listas de compra.

🎯 5. OBJETIVOS ESPECÍFICOS

Implementar un módulo de registro de medicamentos

Permitir la búsqueda rápida por nombre

Controlar el stock de productos

Detectar medicamentos con bajo stock

Generar listas de compra automáticas

Exportar información en formato digital (PDF)

Implementar una interfaz amigable y responsiva

💡 6. DESCRIPCIÓN DEL SISTEMA

FarmaSys es una aplicación web desarrollada para la gestión de inventario en farmacias. Permite registrar medicamentos, controlar existencias y generar alertas cuando los productos están por agotarse.

El sistema está diseñado para ser simple, rápido y accesible, adaptándose a usuarios sin conocimientos técnicos avanzados.

⚙️ 7. TECNOLOGÍAS UTILIZADAS

Backend: Laravel

Frontend: Blade + Bootstrap

Base de datos: MySQL

Entorno: Laragon

Editor: Visual Studio Code + Copilot

🧱 8. ARQUITECTURA DEL SISTEMA

El sistema sigue el patrón MVC (Modelo – Vista – Controlador):

Modelo: Manejo de datos (Medicamentos, Usuarios)

Vista: Interfaz de usuario (Blade)

Controlador: Lógica del sistema

🧩 9. FUNCIONALIDADES PRINCIPALES
🔹 Gestión de medicamentos

Registro de medicamentos

Edición de datos

Eliminación

🔹 Búsqueda

Búsqueda por nombre

Resultados en tiempo real

🔹 Control de inventario

Registro de stock

Definición de stock mínimo

Alertas de bajo stock

🔹 Lista de compras

Generación automática de medicamentos faltantes

Exportación a PDF

🔹 Seguridad

Inicio de sesión

Control de usuarios

🗄️ 10. MODELO DE DATOS (RESUMIDO)
Tabla: medicamentos

id

nombre

codigo

precio

stock

stock_minimo

fecha_vencimiento

created_at

updated_at

📱 11. DISEÑO RESPONSIVO

El sistema será adaptable a:

📱 Dispositivos móviles

💻 Computadoras

Usando Bootstrap para garantizar una experiencia de usuario óptima.

🔄 12. METODOLOGÍA DE DESARROLLO

Se utilizará Scrum, organizando el desarrollo en sprints:

Sprint 1

CRUD de medicamentos

Búsqueda

Sprint 2

Lista de compras

PDF

Sprint 3

Reportes

Mejoras

📈 13. BENEFICIOS DEL SISTEMA

Mejora el control del inventario

Reduce pérdidas por desabastecimiento

Aumenta la eficiencia del personal

Digitaliza procesos manuales

Facilita la toma de decisiones

🚀 14. POSIBLES MEJORAS FUTURAS

Escáner de código de barras

Integración con proveedores

App móvil

Notificaciones automáticas

Estadísticas avanzadas

🧠 CONCLUSIÓN

FarmaSys representa una solución práctica y accesible para la gestión de inventario en farmacias, enfocándose en la simplicidad y eficiencia. Su implementación permitirá optimizar procesos y mejorar el control de medicamentos, contribuyendo al crecimiento del negocio.


Table medicamentos {
  id_medicamento int [pk, increment]
  nombre varchar(150) [not null]
  codigo varchar(50)
  descripcion text
  precio decimal(10,2)
  stock int
  fecha_vencimiento date
  estado varchar(20)
  fecha_registro timestamp
}

Table movimiento_inventario {
  id_movimiento int [pk, increment]
  tipo varchar(20) // entrada | salida
  cantidad int
  fecha timestamp
  motivo varchar(100)
  id_medicamento int [ref: > medicamentos.id_medicamento]
}

Table historial {
  id_historial int [pk, increment]
  accion varchar(50) // crear, editar, eliminar, venta
  fecha timestamp
  descripcion text
  id_medicamento int [ref: > medicamentos.id_medicamento]
  id_usuario int [ref: > usuarios.id_usuario]
}

Table usuarios {
  id_usuario int [pk, increment]
  nombre varchar(100)
  usuario varchar(50)
  contrasena varchar(255)
  rol varchar(50) // admin | farmaceutica
}

Table lista_compra {
  id_lista int [pk, increment]
  fecha timestamp
  estado varchar(20) // pendiente | comprada
}

Table detalle_lista {
  id_detalle int [pk, increment]
  id_lista int [ref: > lista_compra.id_lista]
  id_medicamento int [ref: > medicamentos.id_medicamento]
  cantidad_sugerida int
}