# 📦 Módulos Adicionales — Sistema Integral Laboratorio Dental
## Control de Inventarios & Recursos Humanos
### Plan de Desarrollo SCRUM

> **Stack:** PHP 8.2 · Laravel 11 · MySQL 8 · PWA / API REST
> **Metodología:** SCRUM — Sprints quincenales (2 semanas)
> **Módulos nuevos:** 2 · **Sprints adicionales:** 4 · **Story Points:** 86
> **Versión:** 1.0 — Marzo 2026

---

## Tabla de Contenidos

1. [Visión de los Módulos](#1-visión-de-los-módulos)
2. [Product Backlog — Inventarios & RRHH](#2-product-backlog--inventarios--rrhh)
3. [Sprint 7 — Inventario: Maestro de Productos & Stock](#3-sprint-7--inventario-maestro-de-productos--stock)
4. [Sprint 8 — Inventario: Movimientos, Compras & Alertas](#4-sprint-8--inventario-movimientos-compras--alertas)
5. [Sprint 9 — RRHH: Empleados, Asistencia & Contratos](#5-sprint-9--rrhh-empleados-asistencia--contratos)
6. [Sprint 10 — RRHH: Planilla, Vacaciones & Reportes](#6-sprint-10--rrhh-planilla-vacaciones--reportes)
7. [Entidades de Base de Datos](#7-entidades-de-base-de-datos)
8. [Automatizaciones de los Módulos](#8-automatizaciones-de-los-módulos)
9. [Integraciones con Módulos Existentes](#9-integraciones-con-módulos-existentes)
10. [Definición de Done (DoD)](#10-definición-de-done-dod)
11. [Roadmap Actualizado — 10 Sprints](#11-roadmap-actualizado--10-sprints)
12. [Riesgos Específicos](#12-riesgos-específicos)

---

## 1. Visión de los Módulos

### 📦 Control de Inventarios

El módulo de inventarios permite al laboratorio dental **controlar en tiempo real el stock de materiales, insumos y herramientas** necesarios para la producción de trabajos dentales. Cubre desde el registro de productos hasta las órdenes de compra a proveedores, con alertas automáticas cuando el stock baja del mínimo definido.

**Problema que resuelve:** Hoy el laboratorio no sabe cuánto material tiene disponible hasta que se agota, lo que detiene la producción. Con este módulo, el sistema alerta con anticipación y puede generar solicitudes de compra automáticamente.

**Valor entregado:**
- Cero interrupciones de producción por falta de materiales.
- Trazabilidad de qué materiales se usaron en qué orden de trabajo.
- Control de vencimientos de materiales (insumos con fecha de caducidad).
- Reducción de compras de emergencia y sobrestock.

---

### 👥 Recursos Humanos

El módulo de RRHH centraliza **toda la gestión del personal del laboratorio**: desde el registro de empleados y contratos, hasta el control de asistencia, cálculo de planilla y gestión de vacaciones. Está integrado con el módulo de caja/bancos para el pago automático de remuneraciones.

**Problema que resuelve:** El laboratorio lleva el control del personal en hojas de cálculo separadas, sin visibilidad del costo real de personal ni alertas de vencimientos de contratos.

**Valor entregado:**
- Cálculo automático de planilla (sueldo base, AFP/ONP, EsSalud, CTS, gratificaciones).
- Control de asistencia vinculado a la planilla.
- Alertas de contratos próximos a vencer.
- Historial completo de cada empleado en un solo lugar.

---

## 2. Product Backlog — Inventarios & RRHH

> Estimación en Story Points (Fibonacci). Prioridad: **Must** = obligatorio · **Should** = importante · **Could** = deseable.

### 📦 Módulo de Inventarios

| # | Historia de Usuario | Prioridad | Sprint | Pts |
|---|---------------------|-----------|--------|-----|
| INV-1 | Como almacenero, quiero registrar productos/materiales con código, descripción, unidad de medida y stock mínimo | **Must** | S7 | 3 |
| INV-2 | Como almacenero, quiero organizar los productos en categorías y subcategorías configurables | **Must** | S7 | 2 |
| INV-3 | Como almacenero, quiero registrar proveedores con datos de contacto y condiciones de pago | **Must** | S7 | 3 |
| INV-4 | Como almacenero, quiero registrar el stock inicial de cada producto al arrancar el sistema | **Must** | S7 | 2 |
| INV-5 | Como almacenero, quiero registrar ingresos de mercadería (compras) con su respectiva factura del proveedor | **Must** | S8 | 5 |
| INV-6 | Como técnico, quiero registrar la salida de materiales que usaré en una orden de trabajo específica | **Must** | S8 | 5 |
| INV-7 | Como admin, quiero recibir alertas automáticas cuando el stock de un producto baje del mínimo definido | **Must** | S8 | 3 |
| INV-8 | Como admin, quiero generar órdenes de compra a proveedores desde el sistema | Should | S8 | 3 |
| INV-9 | Como admin, quiero ver el Kardex de cada producto con todos sus movimientos de entrada y salida | **Must** | S8 | 3 |
| INV-10 | Como admin, quiero controlar el vencimiento de materiales con fecha de caducidad | Should | S8 | 2 |
| INV-11 | Como admin, quiero realizar inventarios físicos periódicos y registrar diferencias vs sistema | Could | S8 | 3 |
| INV-12 | Como admin, quiero exportar reportes de stock, movimientos y valorización del inventario | Should | S8 | 2 |

**Subtotal Inventarios: 12 historias · 36 story points**

---

### 👥 Módulo de Recursos Humanos

| # | Historia de Usuario | Prioridad | Sprint | Pts |
|---|---------------------|-----------|--------|-----|
| RH-1 | Como admin, quiero registrar empleados con datos personales, cargo, área y fecha de ingreso | **Must** | S9 | 3 |
| RH-2 | Como admin, quiero registrar y gestionar contratos (tipo, fecha inicio, fecha fin, remuneración) | **Must** | S9 | 5 |
| RH-3 | Como admin, quiero registrar la asistencia diaria del personal (entrada, salida, tardanzas) | **Must** | S9 | 5 |
| RH-4 | Como admin, quiero gestionar los documentos del empleado (DNI, contrato, AFP, seguro) | Should | S9 | 3 |
| RH-5 | Como empleado, quiero registrar mi asistencia desde mi celular mediante la PWA | Should | S9 | 5 |
| RH-6 | Como admin, quiero calcular la planilla mensual con sueldo, descuentos y aportes automáticamente | **Must** | S10 | 8 |
| RH-7 | Como admin, quiero gestionar el récord de vacaciones (días disponibles, tomados y pendientes) | **Must** | S10 | 5 |
| RH-8 | Como admin, quiero registrar y aprobar solicitudes de permisos y licencias del personal | Should | S10 | 3 |
| RH-9 | Como admin, quiero recibir alertas de contratos próximos a vencer | **Must** | S10 | 2 |
| RH-10 | Como admin, quiero generar las boletas de pago en PDF y enviarlas por email al empleado | **Must** | S10 | 3 |
| RH-11 | Como admin, quiero exportar el resumen de planilla para el banco (pago masivo) | Should | S10 | 3 |
| RH-12 | Como admin, quiero exportar reportes de asistencia, planilla y costo de personal | Should | S10 | 3 |

**Subtotal RRHH: 12 historias · 48 story points (2 sprints)**

---

**Total módulos adicionales: 24 historias · 84 story points · 4 sprints · 8 semanas**

---

## 3. Sprint 7 — Inventario: Maestro de Productos & Stock

> **Semanas:** 13 – 14 · **Story Points:** 10
> **Sprint Goal:** Tener el catálogo completo de productos y materiales del laboratorio registrado en el sistema, con categorías, proveedores y stock inicial cargado, listo para comenzar a registrar movimientos en el siguiente sprint.

### Historias y Tareas

---

#### INV-1 · Registro de productos y materiales `3 pts`

**Como** almacenero, **quiero** registrar productos y materiales **para** tener un catálogo centralizado de todo lo que usa el laboratorio.

**Tareas técnicas:**
- Crear modelo `Product` con migración completa.
- Campos: código interno, nombre, descripción, unidad de medida (unidad, gramo, ml, caja), stock actual, stock mínimo, stock máximo, precio de costo, imagen.
- Formulario de registro y edición con validaciones.
- Vista de listado con búsqueda por código y nombre.
- Importación masiva de productos desde Excel (Maatwebsite).

**Automatización:** El sistema genera automáticamente un código interno correlativo para cada nuevo producto si el usuario no lo ingresa manualmente.

**Criterios de aceptación:**
- El código de producto debe ser único en el sistema.
- El stock mínimo no puede ser mayor al stock máximo.
- La importación desde Excel muestra un resumen de registros creados vs errores encontrados.

---

#### INV-2 · Categorías y subcategorías de productos `2 pts`

**Como** almacenero, **quiero** organizar los productos en categorías **para** encontrarlos más fácilmente y generar reportes por tipo de material.

**Tareas técnicas:**
- Modelo `ProductCategory` con soporte de árbol (categoría padre → subcategoría).
- CRUD de categorías desde el panel de administración.
- Selector de categoría con búsqueda en el formulario de producto.
- Filtro por categoría en el listado de productos.

**Criterios de aceptación:**
- Se pueden crear hasta 3 niveles de categorías (categoría → subcategoría → sub-subcategoría).
- No se puede eliminar una categoría que tenga productos asignados.

**Ejemplo de categorías para un laboratorio dental:**
```
Materiales de impresión
  ├── Alginatos
  ├── Siliconas
  └── Yesos

Materiales de prótesis
  ├── Acrílicos
  ├── Cerámicas
  └── Metales

Herramientas
  ├── Instrumentos de laboratorio
  └── Fresas y discos

Consumibles
  ├── Artículos de limpieza
  └── Material de embalaje
```

---

#### INV-3 · Registro de proveedores `3 pts`

**Como** almacenero, **quiero** registrar proveedores **para** vincularlos a las compras y órdenes de pedido.

**Tareas técnicas:**
- Modelo `Supplier` con campos: razón social, RUC, contacto, teléfono, email, dirección, condición de pago (contado, crédito 30/60/90 días).
- CRUD completo con validación de RUC (11 dígitos, Perú).
- Vista de perfil del proveedor con historial de compras realizadas.
- Vinculación de productos al proveedor (un producto puede tener múltiples proveedores con precios distintos).

**Criterios de aceptación:**
- El RUC debe ser único y tener exactamente 11 dígitos.
- Se puede registrar el precio de compra histórico por proveedor para comparar cotizaciones.

---

#### INV-4 · Carga de stock inicial `2 pts`

**Como** almacenero, **quiero** registrar el stock inicial de cada producto **para** que el sistema arranque con la información real del almacén.

**Tareas técnicas:**
- Formulario de "Ajuste de inventario inicial" que crea un movimiento de tipo `ingreso_inicial` por cada producto.
- Importación masiva de stock inicial desde plantilla Excel.
- Esta operación solo puede realizarse una vez por producto (o requiere aprobación del admin para repetirla).
- Registro en `audit_logs` de todos los ajustes iniciales con usuario y timestamp.

**Automatización:** Al registrar el stock inicial, el sistema crea automáticamente el primer movimiento del Kardex con tipo "Saldo inicial" y la fecha actual.

**Criterios de aceptación:**
- La importación valida que los productos existan antes de asignar stock.
- Se genera un reporte de la carga inicial para archivo físico (PDF).

---

### Entregables del Sprint 7

- [ ] Catálogo completo de productos con códigos, unidades y stocks mínimos/máximos.
- [ ] Árbol de categorías configurado con los tipos de materiales del laboratorio.
- [ ] Registro de proveedores con historial de precios.
- [ ] Stock inicial cargado y validado en el sistema.

---

## 4. Sprint 8 — Inventario: Movimientos, Compras & Alertas

> **Semanas:** 15 – 16 · **Story Points:** 21
> **Sprint Goal:** El inventario está vivo: ingresos por compras, salidas por uso en órdenes de trabajo, alertas automáticas de stock bajo, Kardex por producto y órdenes de compra generadas desde el sistema.

### Historias y Tareas

---

#### INV-5 · Ingreso de mercadería (compras) `5 pts`

**Como** almacenero, **quiero** registrar el ingreso de mercadería **para** actualizar el stock y tener trazabilidad de las compras.

**Tareas técnicas:**
- Modelo `PurchaseOrder` (orden de compra) y `PurchaseOrderLine` (líneas de detalle).
- Flujo: orden de compra → recepción → actualización de stock.
- Formulario de recepción: proveedor, factura del proveedor, fecha, líneas de producto (cantidad, precio unitario, lote, fecha de vencimiento si aplica).
- Actualización automática del stock al confirmar la recepción.
- Vinculación con el módulo de cuentas por pagar (factura del proveedor registrada en el sistema de facturación).

**Automatización:** Al confirmar la recepción de mercadería, el stock de cada producto se actualiza automáticamente y se crea el movimiento correspondiente en el Kardex con método PEPS (Primero en Entrar, Primero en Salir).

**Criterios de aceptación:**
- No se puede confirmar una recepción con cantidades en cero.
- El sistema aplica el método de valorización PEPS para actualizar el costo promedio.
- La factura del proveedor vinculada aparece en el módulo de cuentas por pagar.

---

#### INV-6 · Salida de materiales por orden de trabajo `5 pts`

**Como** técnico, **quiero** registrar la salida de materiales que usaré **para** descontar el stock y vincular el consumo a la orden de trabajo correspondiente.

**Tareas técnicas:**
- Formulario de salida vinculado a una orden de trabajo específica.
- Selector de producto con stock actual visible en tiempo real.
- Validación: no se puede sacar más stock del disponible.
- Registro de movimiento de salida en el Kardex con referencia a la orden de trabajo.
- Vista de materiales consumidos por orden de trabajo en el detalle de la orden.

**Automatización:** Al registrar la salida, el stock disponible se descuenta automáticamente en tiempo real. Si el stock resultante queda por debajo del mínimo, se dispara la alerta de stock bajo.

**Criterios de aceptación:**
- El sistema muestra un aviso si quedan menos de 3 unidades disponibles al intentar sacar stock.
- La salida queda vinculada a la orden de trabajo y es visible en el historial de la orden.
- El técnico puede registrar salidas parciales (usar parte de una unidad si la unidad es gramos/ml).

---

#### INV-7 · Alertas automáticas de stock bajo `3 pts`

**Como** admin, **quiero** recibir alertas cuando el stock baje del mínimo **para** evitar quedarnos sin materiales durante la producción.

**Tareas técnicas:**
- Event `StockBelowMinimum` disparado al registrar cualquier salida de inventario.
- Listener que envía notificación por email y push al almacenero y al admin.
- Dashboard de alertas de stock: lista de productos con stock bajo o agotado.
- Scheduler diario 7:00 AM: reenvío de alerta si el stock bajo no fue atendido en 24 h.

**Automatización:** Alerta inmediata al registrar una salida que lleva el stock por debajo del mínimo. Recordatorio diario a las 7:00 AM si el producto sigue en stock bajo sin atención.

**Criterios de aceptación:**
- La alerta muestra: producto, stock actual, stock mínimo y proveedor habitual.
- El almacenero puede marcar una alerta como "en proceso de compra" para silenciarla.

---

#### INV-8 · Órdenes de compra `3 pts`

**Como** admin, **quiero** generar órdenes de compra a proveedores desde el sistema **para** formalizar las solicitudes de reposición.

**Tareas técnicas:**
- Generación manual de orden de compra seleccionando proveedor y productos.
- Generación automática sugerida: el sistema propone una OC con los productos que están en stock bajo y su proveedor habitual.
- Estados de OC: borrador, enviada, recibida parcialmente, recibida completa, cancelada.
- PDF de la orden de compra con datos del laboratorio y el proveedor.
- Envío del PDF por email al proveedor directamente desde el sistema.

**Automatización:** El sistema puede sugerir automáticamente una orden de compra con los productos en stock bajo y sus proveedores habituales. El PDF se envía por email al proveedor con un clic.

**Criterios de aceptación:**
- Una orden de compra enviada al proveedor no puede editarse sin cancelarla primero.
- Al recibir la mercadería de una OC, el stock se actualiza automáticamente (vinculación con INV-5).

---

#### INV-9 · Kardex por producto `3 pts`

**Como** admin, **quiero** ver el Kardex de cada producto **para** tener trazabilidad completa de todos sus movimientos.

**Tareas técnicas:**
- Vista de Kardex por producto: todos los movimientos (entradas, salidas, ajustes) en orden cronológico.
- Columnas: fecha, tipo de movimiento, referencia (OC o OT), cantidad entrada, cantidad salida, stock resultante, costo unitario, costo total.
- Filtro por rango de fechas.
- Exportar Kardex a Excel y PDF.

**Criterios de aceptación:**
- El Kardex aplica el método PEPS para el cálculo de costos.
- El stock del Kardex siempre coincide con el stock actual del producto.

---

#### INV-10 · Control de vencimientos `2 pts`

**Como** admin, **quiero** controlar los materiales con fecha de caducidad **para** evitar usar materiales vencidos en trabajos dentales.

**Tareas técnicas:**
- Campo `fecha_vencimiento` y `lote` en los movimientos de entrada de inventario.
- Scheduler diario: alerta 30, 15 y 7 días antes del vencimiento de un lote.
- Lista de materiales próximos a vencer y ya vencidos en el dashboard de inventario.
- Bloqueo automático: el sistema impide registrar salidas de lotes ya vencidos.

**Automatización:** Alertas automáticas 30, 15 y 7 días antes del vencimiento de cada lote. Bloqueo automático de lotes vencidos para que no puedan ser seleccionados en salidas.

**Criterios de aceptación:**
- Si un material tiene varios lotes, el sistema selecciona automáticamente el que vence primero (PEPS).
- Los lotes vencidos aparecen en rojo en el listado de stock.

---

#### INV-11 · Inventario físico periódico `3 pts`

**Como** admin, **quiero** realizar inventarios físicos y registrar diferencias **para** asegurar que el stock del sistema coincida con la realidad.

**Tareas técnicas:**
- Proceso de inventario físico: bloquear movimientos durante el conteo (opcional), ingresar cantidades físicas contadas.
- Comparación automática: stock sistema vs stock físico contado → diferencias positivas y negativas.
- Aprobación del ajuste de inventario (requiere rol admin).
- Registro del ajuste en el Kardex con motivo (merma, rotura, extravío, error de conteo).
- Historial de inventarios físicos realizados con sus ajustes.

**Criterios de aceptación:**
- Solo el admin puede aprobar y aplicar los ajustes de inventario físico.
- Todo ajuste queda registrado en `audit_logs` con usuario, fecha y motivo.

---

#### INV-12 · Reportes de inventario `2 pts`

**Como** admin, **quiero** exportar reportes de inventario **para** analizar el stock, los movimientos y el valor del inventario.

**Tareas técnicas:**
- Reporte de stock actual: producto, categoría, stock actual, stock mínimo, estado (OK / stock bajo / agotado), valor (costo × stock).
- Reporte de movimientos por período: entradas, salidas y ajustes filtrados por fecha y categoría.
- Reporte de valorización del inventario: costo total del stock por categoría.
- Reporte de materiales más consumidos (top 10 por salidas en un período).
- Generación en background con Laravel Queue. Exportación en Excel y PDF.

**Criterios de aceptación:**
- Los reportes se generan en segundo plano y notifican al usuario cuando están listos.
- El reporte de valorización muestra el total en soles y en dólares (si aplica).

---

### Entregables del Sprint 8

- [ ] Registro de ingresos de mercadería vinculados a facturas de proveedor.
- [ ] Registro de salidas de materiales vinculadas a órdenes de trabajo.
- [ ] Alertas automáticas de stock bajo con recordatorio diario.
- [ ] Generación y envío de órdenes de compra por email.
- [ ] Kardex completo por producto con exportación Excel/PDF.
- [ ] Control de vencimientos de lotes con bloqueo automático.
- [ ] Inventario físico con ajustes aprobados.
- [ ] Reportes de stock, movimientos y valorización.

---

## 5. Sprint 9 — RRHH: Empleados, Asistencia & Contratos

> **Semanas:** 17 – 18 · **Story Points:** 21
> **Sprint Goal:** Toda la información del personal centralizada: ficha del empleado, contratos vigentes, control de asistencia diaria (desde el sistema y desde el celular) y gestión de documentos del empleado.

### Historias y Tareas

---

#### RH-1 · Registro de empleados `3 pts`

**Como** admin, **quiero** registrar empleados con todos sus datos **para** tener un expediente digital completo de cada persona.

**Tareas técnicas:**
- Modelo `Employee` con campos: nombre completo, DNI, fecha de nacimiento, sexo, estado civil, teléfono, email, dirección, foto, cargo, área/departamento, fecha de ingreso, modalidad de trabajo (presencial/remoto).
- CRUD completo con validaciones.
- Vista de ficha del empleado con tabs: datos personales, contrato, asistencia, documentos, vacaciones, planilla.
- Búsqueda por nombre, DNI o cargo.
- El empleado puede tener asociado un usuario del sistema (para acceso a la PWA).

**Criterios de aceptación:**
- El DNI debe ser único en el sistema (8 dígitos, Perú).
- La foto del empleado acepta JPG y PNG hasta 2 MB.
- El área/departamento se configura desde el panel de administración.

---

#### RH-2 · Gestión de contratos `5 pts`

**Como** admin, **quiero** registrar y gestionar contratos **para** controlar las condiciones laborales y las fechas de vencimiento.

**Tareas técnicas:**
- Modelo `EmployeeContract` con campos: tipo de contrato (plazo fijo, indeterminado, por servicio), fecha inicio, fecha fin, remuneración bruta, régimen laboral (general, MYPE, CAS), AFP/ONP, asignación familiar.
- Un empleado puede tener múltiples contratos históricos; solo uno activo a la vez.
- Carga del PDF del contrato firmado como adjunto.
- Alerta automática 30, 15 y 7 días antes del vencimiento del contrato.
- Renovación de contrato: crea un nuevo contrato con fecha inicio inmediata al vencimiento del anterior.

**Automatización:** Alertas automáticas al admin y al empleado 30, 15 y 7 días antes del vencimiento del contrato. Si vence sin renovación, el sistema cambia el estado del empleado a "contrato vencido" y genera alerta crítica.

**Criterios de aceptación:**
- No puede haber dos contratos activos para el mismo empleado al mismo tiempo.
- El contrato activo es el que se usa para calcular la planilla del mes.
- Los contratos históricos son de solo lectura una vez que finalizan.

---

#### RH-3 · Control de asistencia diaria `5 pts`

**Como** admin, **quiero** registrar la asistencia diaria del personal **para** controlar puntualidad y generar el reporte mensual para planilla.

**Tareas técnicas:**
- Modelo `Attendance` con campos: empleado, fecha, hora de entrada, hora de salida, tipo (normal, tardanza, falta justificada, falta injustificada, feriado), observaciones.
- Registro manual desde el panel de admin.
- Configuración de horario por cargo/área (hora de entrada esperada, tolerancia en minutos).
- Marcado automático de tardanza si la hora de entrada supera la tolerancia configurada.
- Vista de asistencia mensual estilo calendario por empleado.
- Resumen mensual: días trabajados, tardanzas, faltas justificadas, faltas injustificadas.

**Automatización:** Si un empleado no tiene registro de entrada a la hora configurada + tolerancia, el sistema lo marca automáticamente como "falta pendiente de justificación" y notifica al admin.

**Criterios de aceptación:**
- La hora de entrada y salida se pueden editar solo con rol admin y queda en el log de auditoría.
- El resumen mensual de asistencia es el insumo directo para el cálculo de planilla.

---

#### RH-4 · Gestión de documentos del empleado `3 pts`

**Como** admin, **quiero** gestionar los documentos del empleado **para** tener todo digitalizado y encontrar cualquier documento rápidamente.

**Tareas técnicas:**
- Modelo `EmployeeDocument` con tipo de documento configurable: DNI, contrato, AFP/ONP, seguro de salud, certificados, otros.
- Carga de archivos (PDF, JPG, PNG) hasta 10 MB por documento.
- Vista de documentos del empleado con fecha de carga y usuario que cargó.
- Control de vencimiento para documentos con fecha de expiración (ej: brevetes, certificados médicos).
- Alerta automática 30 días antes del vencimiento de un documento.

**Automatización:** Alerta automática al admin 30 días antes del vencimiento de documentos con fecha de expiración (brevetes, certificados médicos, etc.).

**Criterios de aceptación:**
- Solo admin y RRHH pueden ver los documentos de un empleado.
- Los documentos no pueden eliminarse, solo marcarse como "reemplazados" por una versión más reciente.

---

#### RH-5 · Registro de asistencia desde celular (PWA) `5 pts`

**Como** empleado, **quiero** registrar mi asistencia desde mi celular **para** no necesitar estar en el laboratorio para marcar mi entrada y salida.

**Tareas técnicas:**
- Endpoint API REST `POST /api/attendance/check-in` y `POST /api/attendance/check-out` protegido con Sanctum.
- Validación de geolocalización: el registro solo es válido si el empleado está dentro de un radio configurable del laboratorio (GPS del celular).
- Registro de la coordenada GPS en cada marcación para auditoría.
- Pantalla en la PWA: botón de marcar entrada / marcar salida con confirmación.
- El empleado ve su resumen de asistencia del mes desde la PWA.

**Automatización:** El sistema registra automáticamente la hora exacta y las coordenadas GPS en cada marcación. Si la ubicación está fuera del radio permitido, rechaza el registro y notifica al admin.

**Criterios de aceptación:**
- El radio de geolocalización se configura desde el panel de admin (default: 200 metros).
- Si el GPS del celular no está disponible, el empleado puede solicitar marcación manual al admin.
- La marcación desde celular es indistinguible de la marcación manual en los reportes.

---

### Entregables del Sprint 9

- [ ] Ficha completa del empleado con foto, datos y documentos adjuntos.
- [ ] Gestión de contratos con historial y alertas de vencimiento.
- [ ] Control de asistencia diaria con marcación manual y automática de tardanzas.
- [ ] Registro de asistencia desde celular con validación GPS.
- [ ] Vista mensual de asistencia por empleado con resumen.

---

## 6. Sprint 10 — RRHH: Planilla, Vacaciones & Reportes

> **Semanas:** 19 – 20 · **Story Points:** 27
> **Sprint Goal:** Cierre completo del módulo RRHH: cálculo automático de planilla mensual con todos los componentes remunerativos, gestión de vacaciones y permisos, boletas de pago por email y exportación del archivo de pago masivo para el banco.

### Historias y Tareas

---

#### RH-6 · Cálculo de planilla mensual `8 pts`

**Como** admin, **quiero** calcular la planilla mensual automáticamente **para** pagar a los empleados con precisión y sin errores de cálculo.

**Tareas técnicas:**
- Modelo `Payroll` (cabecera del mes) y `PayrollLine` (línea por empleado).
- Motor de cálculo de planilla basado en el régimen laboral del contrato activo:

  **Régimen General:**
  - Remuneración bruta (del contrato activo)
  - (+) Asignación familiar (si aplica): S/ 102.50
  - (+) Horas extras (si se registraron)
  - (-) Tardanzas y faltas injustificadas (descuento proporcional)
  - (-) AFP o SNP/ONP según afiliación del empleado
  - (-) Impuesto a la Renta 5ta categoría (si supera 7 UIT anuales)
  - (+) Aporte EsSalud del empleador (9%)
  - (+) CTS mensual (8.33% de la remuneración computable)
  - (+) Gratificación proporcional (julio y diciembre: 1/6 mensual)
  - **Neto a pagar** = Bruto − Descuentos AFP/ONP − IR 5ta

  **Régimen MYPE:**
  - Sin gratificaciones dobles
  - CTS: 15 días por año
  - Vacaciones: 15 días por año

- Integración con el módulo de asistencia: descuentos automáticos por faltas injustificadas y tardanzas.
- Vista previa de la planilla antes de aprobarla.
- Aprobación de planilla por el admin (estado: borrador → aprobada → pagada).

**Automatización:** El sistema calcula automáticamente todos los componentes remunerativos y descuentos basándose en el contrato activo y el resumen de asistencia del mes. Los descuentos por faltas y tardanzas se aplican automáticamente.

**Criterios de aceptación:**
- Una planilla aprobada no puede editarse, solo anularse.
- El cálculo de AFP debe soportar AFP Integra, Prima, Profuturo y Habitat con sus tasas vigentes.
- El sistema alerta si algún empleado no tiene contrato activo al momento de procesar la planilla.
- El total de planilla se registra automáticamente como egreso en el módulo de caja o banco.

---

#### RH-7 · Gestión de vacaciones `5 pts`

**Como** admin, **quiero** gestionar el récord de vacaciones **para** controlar los días disponibles, tomados y pendientes de cada empleado.

**Tareas técnicas:**
- Cálculo automático de días de vacaciones acumulados según régimen laboral y fecha de ingreso.
  - Régimen General: 30 días por año.
  - Régimen MYPE: 15 días por año.
- Modelo `VacationRecord`: saldo disponible, días tomados, días pendientes, días por vencer.
- Registro de solicitudes de vacaciones con fecha inicio y fin.
- Flujo de aprobación: empleado solicita → admin aprueba/rechaza.
- Alerta automática de vacaciones vencidas (días acumulados que no fueron tomados en el plazo legal).
- Vista de calendario de vacaciones del equipo para detectar solapamientos.

**Automatización:** Los días de vacaciones se acumulan automáticamente cada mes según el régimen laboral del empleado. Alerta automática al admin cuando un empleado acumula vacaciones próximas a vencer (límite legal: 1 año).

**Criterios de aceptación:**
- No se pueden aprobar vacaciones si el empleado no tiene días disponibles.
- El calendario de vacaciones muestra si hay más de 2 empleados del mismo área de vacaciones simultáneamente.
- Las vacaciones aprobadas descuentan los días del saldo disponible automáticamente.

---

#### RH-8 · Permisos y licencias `3 pts`

**Como** admin, **quiero** registrar y aprobar permisos y licencias **para** tener trazabilidad de las ausencias justificadas del personal.

**Tareas técnicas:**
- Modelo `LeaveRequest` con tipo: permiso con goce, permiso sin goce, licencia por enfermedad, licencia por maternidad/paternidad, otros.
- Formulario de solicitud con fecha, tipo, motivo y adjunto (certificado médico si aplica).
- Flujo de aprobación: empleado solicita (desde la PWA) → admin aprueba/rechaza con comentario.
- Al aprobar, el registro de asistencia de los días correspondientes se actualiza automáticamente.
- Descuento automático en planilla para permisos sin goce de sueldo.

**Automatización:** Al aprobar un permiso o licencia, los días correspondientes se marcan automáticamente en el registro de asistencia con el tipo correcto. Los permisos sin goce descuentan automáticamente de la planilla.

**Criterios de aceptación:**
- La licencia por enfermedad requiere adjunto de certificado médico.
- El empleado recibe notificación por email con el resultado de su solicitud.

---

#### RH-9 · Alertas de contratos por vencer `2 pts`

**Como** admin, **quiero** recibir alertas de contratos próximos a vencer **para** renovarlos a tiempo y no perder a los trabajadores clave.

**Tareas técnicas:**
- Scheduler diario 8:00 AM: verificar contratos con `fecha_fin` en los próximos 30, 15 y 7 días.
- Notificación por email al admin con lista de contratos por vencer.
- Badge de alerta en el panel de RRHH con el número de contratos críticos.
- Los contratos vencidos sin renovación generan alerta crítica diaria hasta que se resuelvan.

**Automatización:** Alertas automáticas diarias al admin con la lista de contratos que vencen en 30, 15 y 7 días. Alerta crítica diaria para contratos ya vencidos sin renovación.

**Criterios de aceptación:**
- La alerta incluye: empleado, cargo, fecha de vencimiento y días restantes.
- El admin puede acceder directamente al contrato desde el email de alerta.

---

#### RH-10 · Boletas de pago en PDF `3 pts`

**Como** admin, **quiero** generar boletas de pago en PDF **para** entregar el comprobante de sueldo a cada empleado de forma formal.

**Tareas técnicas:**
- Generación de PDF de boleta de pago con DomPDF: datos del laboratorio, datos del empleado, período, detalle de ingresos y descuentos, neto a pagar.
- Envío automático por email a cada empleado al aprobar la planilla.
- Descarga individual y masiva (ZIP con todas las boletas del mes) desde el panel de admin.
- Historial de boletas del empleado accesible desde su ficha y desde la PWA.

**Automatización:** Al aprobar la planilla, el sistema genera automáticamente todas las boletas en PDF y las envía por email a cada empleado. El empleado puede ver sus boletas históricas desde la PWA.

**Criterios de aceptación:**
- La boleta incluye la firma digital del empleador (imagen del sello del laboratorio).
- El empleado puede descargar sus boletas anteriores desde la PWA sin necesidad de solicitarlas.

---

#### RH-11 · Exportación para pago masivo bancario `3 pts`

**Como** admin, **quiero** exportar el archivo de planilla en el formato del banco **para** realizar el pago masivo de sueldos con un solo clic.

**Tareas técnicas:**
- Exportación en formato CSV o TXT compatible con los bancos principales en Perú (BCP, Interbank, BBVA, Scotiabank).
- El formato incluye: CCI del empleado, monto neto a pagar, concepto, fecha de pago.
- Vista previa antes de descargar con total a pagar y número de empleados.
- Al marcar la planilla como "pagada", se registra automáticamente el egreso en el módulo de banco correspondiente.

**Automatización:** Al marcar la planilla como "pagada", se crea automáticamente el movimiento de egreso en el módulo de bancos por el total neto pagado.

**Criterios de aceptación:**
- El CCI del empleado es un campo obligatorio para poder incluirlo en el archivo de pago masivo.
- El sistema soporta exportar en distintos formatos según el banco seleccionado.

---

#### RH-12 · Reportes de RRHH `3 pts`

**Como** admin, **quiero** exportar reportes de RRHH **para** analizar el costo de personal, la asistencia y la evolución de la planilla.

**Tareas técnicas:**
- Reporte de asistencia mensual: empleado, días trabajados, tardanzas, faltas, horas totales.
- Reporte de planilla mensual: detalle por empleado de todos los conceptos.
- Reporte de costo total de personal por mes/área (bruto + aportes del empleador).
- Reporte de vacaciones: saldo disponible, tomadas y por vencer por empleado.
- Reporte de contratos: activos, por vencer y vencidos.
- Generación en background con Laravel Queue. Exportación Excel y PDF.

**Criterios de aceptación:**
- El reporte de costo de personal incluye los aportes del empleador (EsSalud, CTS, gratificaciones) para tener el costo real.
- Los reportes tienen filtro por área, cargo y rango de fechas.

---

### Entregables del Sprint 10

- [ ] Motor de cálculo de planilla con régimen general y MYPE.
- [ ] Gestión de vacaciones con calendario del equipo y acumulación automática.
- [ ] Registro y aprobación de permisos y licencias desde la PWA.
- [ ] Alertas automáticas de contratos por vencer.
- [ ] Boletas de pago en PDF enviadas por email al aprobar la planilla.
- [ ] Exportación de planilla en formato bancario (BCP, Interbank, BBVA, Scotiabank).
- [ ] 5 reportes de RRHH con exportación Excel/PDF.

---

## 7. Entidades de Base de Datos

### Módulo de Inventarios

| Tabla | Campos Clave | Relaciones |
|-------|-------------|-----------|
| `products` | id, code, name, description, unit_measure, stock_current, stock_min, stock_max, cost_price, category_id, image_path | `belongs_to product_category` · `has_many inventory_movements` |
| `product_categories` | id, name, parent_id, level | `has_many products` · `belongs_to parent (self)` |
| `suppliers` | id, business_name, ruc, contact_name, phone, email, address, payment_term_days | `has_many purchase_orders` · `has_many product_suppliers` |
| `product_suppliers` | id, product_id, supplier_id, unit_price, lead_time_days, is_preferred | `belongs_to product, supplier` |
| `purchase_orders` | id, supplier_id, status, total, notes, expected_date, created_by | `belongs_to supplier, user` · `has_many purchase_order_lines` |
| `purchase_order_lines` | id, purchase_order_id, product_id, quantity, unit_price, lot, expiry_date | `belongs_to purchase_order, product` |
| `inventory_movements` | id, product_id, type (in/out/adjust), quantity, unit_cost, reference_type, reference_id, lot, expiry_date, user_id, date | `belongs_to product, user` · Polymorphic en `reference` |
| `physical_inventories` | id, date, status, notes, created_by, approved_by, approved_at | `has_many physical_inventory_lines` |
| `physical_inventory_lines` | id, inventory_id, product_id, system_stock, physical_stock, difference, adjust_reason | `belongs_to physical_inventory, product` |

### Módulo de Recursos Humanos

| Tabla | Campos Clave | Relaciones |
|-------|-------------|-----------|
| `employees` | id, name, dni, birthdate, phone, email, address, photo, position, area_id, start_date, user_id | `belongs_to area, user` · `has_many contracts, attendances, documents` |
| `areas` | id, name, description, manager_employee_id | `has_many employees` |
| `employee_contracts` | id, employee_id, type, start_date, end_date, gross_salary, labor_regime, afp_type, family_allowance, is_active | `belongs_to employee` |
| `employee_documents` | id, employee_id, type, file_path, expiry_date, uploaded_by | `belongs_to employee, user` |
| `attendances` | id, employee_id, date, check_in, check_out, type, lat, lng, tardiness_minutes, notes | `belongs_to employee` |
| `attendance_schedules` | id, area_id, position, expected_check_in, tolerance_minutes | `belongs_to area` |
| `payrolls` | id, period_month, period_year, status, total_gross, total_net, total_employer_cost, approved_by, approved_at | `has_many payroll_lines` |
| `payroll_lines` | id, payroll_id, employee_id, gross_salary, family_allowance, overtime, tardiness_discount, afp_discount, ir_discount, net_salary, essalud, cts, gratification | `belongs_to payroll, employee` |
| `vacation_records` | id, employee_id, year, days_accrued, days_taken, days_pending, days_expiring_at | `belongs_to employee` |
| `vacation_requests` | id, employee_id, start_date, end_date, days, status, approved_by, notes | `belongs_to employee, user` |
| `leave_requests` | id, employee_id, type, start_date, end_date, days, reason, attachment_path, status, approved_by | `belongs_to employee, user` |

---

## 8. Automatizaciones de los Módulos

> Automatizaciones implementadas con **Laravel Scheduler**, **Events/Listeners** y **Jobs en cola con Redis**.

### 📦 Inventarios

| Automatización | Disparador | Acción | Canal |
|---------------|-----------|--------|-------|
| **Alerta stock bajo** | Event: `StockBelowMinimum` al registrar salida | Notifica al almacenero y admin con producto, stock actual y proveedor | Email + Push |
| **Recordatorio stock bajo** | Scheduler 7:00 AM diario | Reenvía alerta si el producto sigue en stock bajo sin atención | Email |
| **Sugerencia OC automática** | Scheduler semanal (lunes) | Genera borrador de OC con productos en stock bajo y proveedor habitual | BD |
| **Alerta vencimiento de lote** | Scheduler 8:00 AM diario | Alerta 30, 15 y 7 días antes del vencimiento de cada lote | Email + Push |
| **Bloqueo de lotes vencidos** | Scheduler medianoche | Marca como bloqueados los lotes cuya fecha de vencimiento llegó | BD |
| **Actualización stock PEPS** | Event: cualquier movimiento | Recalcula saldo y costo promedio con método PEPS automáticamente | BD |

### 👥 Recursos Humanos

| Automatización | Disparador | Acción | Canal |
|---------------|-----------|--------|-------|
| **Alerta contrato por vencer** | Scheduler 8:00 AM diario | Alerta 30, 15 y 7 días antes del vencimiento de cada contrato | Email + Push |
| **Contrato vencido sin renovación** | Scheduler 8:00 AM diario | Alerta crítica diaria hasta que se renueve o finalice el contrato | Email + Push |
| **Marca tardanza automática** | Scheduler según horario configurado | Si no hay check-in pasada la tolerancia, marca "falta pendiente" | BD + Push |
| **Acumulación de vacaciones** | Scheduler el 1 de cada mes | Calcula y suma los días de vacaciones acumulados del mes | BD |
| **Alerta vacaciones por vencer** | Scheduler 8:00 AM diario | Avisa cuando las vacaciones acumuladas están próximas a vencer (límite legal) | Email + Push |
| **Validación GPS asistencia** | Event: `AttendanceCheckIn` | Verifica coordenadas vs ubicación del laboratorio; rechaza si fuera del radio | BD + Push |
| **Descuento planilla por faltas** | Job: `ProcessPayroll` | Lee el resumen de asistencia y calcula descuentos proporcionales automáticamente | BD |
| **Generación de boletas** | Event: `PayrollApproved` | Genera PDF de boleta y envía por email a cada empleado | Email + Storage |
| **Egreso en banco al pagar** | Event: `PayrollMarkedAsPaid` | Crea movimiento de egreso en el módulo de bancos por el total neto | BD |
| **Alerta documento vencido** | Scheduler 8:00 AM diario | Alerta 30 días antes del vencimiento de documentos con fecha de expiración | Email |

---

## 9. Integraciones con Módulos Existentes

Los módulos de Inventarios y RRHH no son islas: se integran de forma nativa con los módulos ya desarrollados en los Sprints 1–6.

### 📦 Inventarios → Módulos existentes

| Integración | Módulo origen | Módulo destino | Descripción |
|-------------|--------------|----------------|-------------|
| Salida de materiales vinculada a OT | Laboratorio | Inventarios | Al registrar una salida, se selecciona la orden de trabajo a la que corresponde. El costo del material se suma al costo de la OT. |
| Compra de materiales vinculada a factura del proveedor | Inventarios | Facturación | Al registrar una compra, se vincula la factura del proveedor creando una cuenta por pagar en el módulo de facturación. |
| Orden de compra → pago al proveedor | Inventarios | Caja & Bancos | Al pagar una OC, se registra automáticamente el egreso en el módulo de caja o banco seleccionado. |
| Valorización del inventario en balance | Inventarios | Tesorería | El valor del stock aparece como activo en los reportes de tesorería. |

### 👥 RRHH → Módulos existentes

| Integración | Módulo origen | Módulo destino | Descripción |
|-------------|--------------|----------------|-------------|
| Pago de planilla → egreso en banco | RRHH | Caja & Bancos | Al marcar la planilla como "pagada", se crea automáticamente un egreso en el banco seleccionado. |
| Liquidación de rendición → descuento en planilla | Rendiciones | RRHH | Si una rendición tiene adelantos de sueldo, el descuento se aplica automáticamente en la siguiente planilla. |
| Técnico activo en órdenes | RRHH | Laboratorio | El selector de técnico en órdenes de trabajo muestra solo empleados activos del área de producción. |
| Asistencia móvil integrada a PWA | RRHH | Formularios Móvil | El botón de marcar asistencia se integra en la misma PWA que usa el técnico para registrar órdenes. |
| Alerta de técnico sin asistencia | RRHH | Laboratorio | Si un técnico tiene falta el día actual, sus órdenes del día muestran un badge de advertencia al supervisor. |
| Costo de personal en dashboard | RRHH | Dashboard KPIs | El widget de KPIs muestra el costo total de personal del mes actual como indicador. |

---

## 10. Definición de Done (DoD)

Los mismos 10 criterios del documento principal aplican para estos módulos. Se agregan los siguientes específicos:

| # | Criterio adicional | Módulo |
|---|-------------------|--------|
| ✅ 11 | Los cálculos de planilla han sido verificados manualmente con al menos 3 casos de prueba (régimen general, MYPE y empleado con AFP) | RRHH |
| ✅ 12 | El Kardex de inventario cuadra exactamente con el stock actual del producto tras cualquier secuencia de movimientos | Inventarios |
| ✅ 13 | Los movimientos de inventario son irreversibles (solo se corrigen con ajustes trazables, no con eliminaciones) | Inventarios |
| ✅ 14 | La validación GPS de asistencia ha sido probada en campo con el dispositivo móvil real del técnico | RRHH |
| ✅ 15 | Los reportes de planilla y los formatos de pago masivo han sido validados con el banco antes de liberar | RRHH |

---

## 11. Roadmap Actualizado — 10 Sprints

> El plan original de 6 sprints se extiende a 10 sprints para incluir los módulos de Inventarios y RRHH.

| Sprint | Tema Principal | Semanas | Story Pts | Acumulado |
|--------|---------------|---------|-----------|-----------|
| S1 | Auth + Config + Pacientes | 1 – 2 | 13 | 13 |
| S2 | Laboratorio + Caja | 3 – 4 | 16 | 29 |
| S3 | Bancos + Tesorería | 5 – 6 | 18 | 47 |
| S4 | Facturación | 7 – 8 | 13 | 60 |
| S5 | Rendiciones | 9 – 10 | 14 | 74 |
| S6 | Móvil + Dashboard + Reportes | 11 – 12 | 21 | 95 |
| **S7** | **Inventario: Maestro & Stock inicial** | **13 – 14** | **10** | **105** |
| **S8** | **Inventario: Movimientos, Compras & Alertas** | **15 – 16** | **21** | **126** |
| **S9** | **RRHH: Empleados, Asistencia & Contratos** | **17 – 18** | **21** | **147** |
| **S10** | **RRHH: Planilla, Vacaciones & Reportes** | **19 – 20** | **27** | **174** |
| **TOTAL** | **Sistema completo** | **20 semanas** | **179 pts** | — |

### Valor acumulado por sprint

```
S1  ██░░░░░░░░░░░░░░░░░░   7%  (13 pts)
S2  ████░░░░░░░░░░░░░░░░  16%  (16 pts)
S3  ██████░░░░░░░░░░░░░░  27%  (18 pts)
S4  ████████░░░░░░░░░░░░  34%  (13 pts)
S5  █████████░░░░░░░░░░░  41%  (14 pts)
S6  ████████████░░░░░░░░  53%  (21 pts)
S7  █████████████░░░░░░░  59%  (10 pts) ← Inventarios inicia
S8  ████████████████░░░░  72%  (21 pts)
S9  ██████████████████░░  84%  (21 pts) ← RRHH inicia
S10 ████████████████████ 100%  (27 pts)
```

---

## 12. Riesgos Específicos

| Riesgo | Módulo | Probabilidad | Impacto | Mitigación |
|--------|--------|-------------|---------|-----------|
| Errores en el cálculo de planilla (AFP, IR 5ta categoría) | RRHH | Media | Crítico | Validar con contador antes de liberar. Pruebas con casos reales históricos. |
| Tasas de AFP cambian y el sistema queda desactualizado | RRHH | Alta | Alta | Crear tabla `afp_rates` configurable desde el panel de admin sin necesidad de código. |
| El laboratorio trabaja con régimen mixto (algunos empleados MYPE, otros no) | RRHH | Media | Alta | El contrato define el régimen laboral por empleado; la planilla aplica el cálculo correcto por empleado. |
| Diferencias de inventario difíciles de rastrear | Inventarios | Media | Media | Método PEPS estricto, movimientos irreversibles, audit log en todas las transacciones. |
| El técnico no registra las salidas de material correctamente | Inventarios | Alta | Media | Capacitación al usuario. Formulario de salida simplificado en la PWA. Recordatorio al técnico al abrir una OT. |
| El GPS del celular no está disponible o es inexacto | RRHH | Media | Media | Permitir marcación sin GPS con aviso al admin para revisión manual. Radio de tolerancia amplio (200–500 m). |
| El banco no acepta el formato de pago masivo generado | RRHH | Baja | Alta | Validar el formato con el banco antes del Sprint 10. Soportar múltiples formatos (BCP, Interbank, BBVA). |

---

## Notas Finales

- Este documento es el **complemento directo** del archivo `LaboratorioDental_SCRUM.md` y debe leerse junto con él.
- Los Sprints 7–10 continúan la numeración de los Sprints 1–6 del documento principal.
- Los **mismos roles del equipo SCRUM** aplican para estos módulos.
- Las **mismas ceremonias** (Planning, Daily, Review, Retrospectiva) aplican en cada sprint adicional.
- El módulo de RRHH debe ser **validado por un contador o especialista laboral** antes de liberar el Sprint 10 en producción, especialmente el motor de planilla.

---

*Sistema Integral — Laboratorio Dental · Módulos Adicionales: Inventarios & RRHH*
*SCRUM v1.0 · Sprints 7–10 · Semanas 13–20 · Marzo 2026*
*PHP 8.2 · Laravel 11 · MySQL 8 · PWA · API REST*
