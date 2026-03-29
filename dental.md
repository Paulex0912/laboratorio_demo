# 🦷 Sistema Integral — Laboratorio Dental
## Plan de Desarrollo SCRUM

> **Stack:** PHP 8.2 · Laravel 11 · MySQL 8 · PWA / API REST  
> **Metodología:** SCRUM — Sprints quincenales (2 semanas)  
> **Duración total:** 6 Sprints · 12 semanas · 95 Story Points  
> **Versión:** 1.0 — Marzo 2026

---

## Tabla de Contenidos

1. [Visión del Producto](#1-visión-del-producto)
2. [Módulos del Sistema](#2-módulos-del-sistema)
3. [Equipo SCRUM](#3-equipo-scrum)
4. [Ceremonias](#4-ceremonias)
5. [Product Backlog Priorizado](#5-product-backlog-priorizado)
6. [Sprint 1 — Infraestructura, Auth & Configuración](#6-sprint-1--infraestructura-auth--configuración)
7. [Sprint 2 — Módulo Laboratorio & Caja](#7-sprint-2--módulo-laboratorio--caja)
8. [Sprint 3 — Bancos & Tesorería](#8-sprint-3--bancos--tesorería)
9. [Sprint 4 — Facturación](#9-sprint-4--facturación)
10. [Sprint 5 — Rendiciones de Gastos](#10-sprint-5--rendiciones-de-gastos)
11. [Sprint 6 — Formularios Móvil, Dashboard & Reportes](#11-sprint-6--formularios-móvil-dashboard--reportes)
12. [Arquitectura Técnica](#12-arquitectura-técnica)
13. [Entidades de Base de Datos](#13-entidades-de-base-de-datos)
14. [Automatizaciones del Sistema](#14-automatizaciones-del-sistema)
15. [Definición de Done (DoD)](#15-definición-de-done-dod)
16. [Roadmap de Entrega](#16-roadmap-de-entrega)
17. [Gestión de Riesgos](#17-gestión-de-riesgos)

---

## 1. Visión del Producto

El sistema busca **digitalizar y automatizar la operación completa de un laboratorio dental**, integrando la gestión clínica (órdenes de trabajo, pacientes, técnicos) con el módulo financiero (caja, bancos, tesorería, rendiciones y cancelación de facturas).

Además, genera **formularios responsivos accesibles desde dispositivos móviles**, sincronizados en tiempo real con el backend central mediante una API REST.

### Objetivos Clave

- Centralizar toda la operación clínica y financiera en un solo sistema.
- Eliminar el registro manual en papel o Excel de órdenes y movimientos de caja.
- Automatizar alertas, cierres, proyecciones y notificaciones sin intervención humana.
- Permitir al técnico operar desde su celular sin necesidad de estar en el laboratorio.
- Cumplir con los requisitos de facturación electrónica SUNAT (Perú).

---

## 2. Módulos del Sistema

| Módulo | Funciones Clave | Automatizaciones |
|--------|----------------|-----------------|
| 🦷 **Laboratorio** | Registro de pacientes, órdenes de trabajo, estados, entrega, técnicos asignados | Alertas de vencimiento, cambio de estado automático, historial clínico |
| 💰 **Caja & Bancos** | Ingresos/egresos diarios, conciliación bancaria, saldos en tiempo real | Cuadre diario automático, alertas de saldo mínimo |
| 🏦 **Tesorería** | Flujo de caja proyectado, gestión de cheques, cartera por cobrar | Proyección automática 30/60/90 días, recordatorios de pago |
| 📄 **Facturación** | Emisión, cancelación y seguimiento de facturas con fecha | Cálculo automático IGV 18%, control de vencimientos, alertas por cobrar |
| 📊 **Rendiciones** | Registro de gastos, aprobación por niveles, liquidación | Flujo de aprobación automático, notificaciones por email/push |
| 📱 **Formularios Móvil** | Órdenes de trabajo, recepciones, pagos desde celular | Sincronización en tiempo real con el sistema central (API REST) |

---

## 3. Equipo SCRUM

| Rol | Persona | Responsabilidades |
|-----|---------|------------------|
| **Product Owner** | Dueño / Director Lab. | Prioriza el Product Backlog, acepta entregables, valida requisitos de negocio |
| **Scrum Master** | Tech Lead / Consultor | Elimina impedimentos, facilita ceremonias, asegura el marco Scrum |
| **Dev Backend** | Desarrollador PHP/Laravel | Modelos, controladores, API REST, autenticación, reglas de negocio |
| **Dev Frontend** | Desarrollador JS/Blade | Vistas Blade, componentes Alpine.js, dashboards, formularios responsive |
| **DBA / DevOps** | Especialista MySQL | Diseño BD, migraciones, backups, despliegue en servidor |
| **QA Tester** | Tester funcional | Pruebas unitarias, de integración, UAT con usuarios reales |

---

## 4. Ceremonias

| Ceremonia | Frecuencia / Duración | Objetivo |
|-----------|-----------------------|---------|
| 🗓 **Sprint Planning** | Inicio de sprint · 3 h | Seleccionar ítems del backlog, definir Sprint Goal y tareas técnicas |
| ☀️ **Daily Scrum** | Cada día · 15 min | ¿Qué hice? ¿Qué haré? ¿Qué me bloquea? |
| 🎯 **Sprint Review** | Fin de sprint · 1 h | Demo del incremento funcional al Product Owner y stakeholders |
| 🔄 **Retrospectiva** | Fin de sprint · 1 h | Mejora continua: qué salió bien, qué mejorar, acciones concretas |
| 📋 **Backlog Refinement** | Mid-sprint · 1 h | Estimar y detallar historias futuras con Planning Poker |

---

## 5. Product Backlog Priorizado

> Estimación en Story Points (Fibonacci). Prioridad: **Must** = obligatorio · **Should** = importante · **Could** = deseable.

| # | Historia de Usuario | Módulo | Prioridad | Sprint | Pts |
|---|---------------------|--------|-----------|--------|-----|
| 1 | Como admin, quiero registrar usuarios con roles (admin, recepción, técnico, tesorero) para controlar el acceso | Auth | **Must** | S1 | 3 |
| 2 | Como admin, quiero configurar datos del laboratorio (RUC, logo, series de factura) | Configuración | **Must** | S1 | 2 |
| 3 | Como recepción, quiero registrar pacientes con historial dental y documentos adjuntos | Laboratorio | **Must** | S1 | 5 |
| 4 | Como técnico, quiero ver mis órdenes asignadas con prioridad y fecha de entrega | Laboratorio | **Must** | S1 | 3 |
| 5 | Como recepción, quiero crear órdenes de trabajo con tipo de trabajo, material y monto | Laboratorio | **Must** | S2 | 5 |
| 6 | Como técnico, quiero actualizar el estado de la orden (pendiente → proceso → terminado) | Laboratorio | **Must** | S2 | 3 |
| 7 | Como cajero, quiero registrar ingresos y egresos con comprobante y categoría | Caja | **Must** | S2 | 5 |
| 8 | Como cajero, quiero hacer el cierre de caja diario con resumen automático | Caja | **Must** | S2 | 3 |
| 9 | Como tesorero, quiero registrar cuentas bancarias y movimientos con saldo en tiempo real | Bancos | **Must** | S3 | 5 |
| 10 | Como tesorero, quiero conciliar el estado de cuenta bancario vs registros del sistema | Bancos | Should | S3 | 5 |
| 11 | Como tesorero, quiero ver el flujo de caja proyectado a 30/60/90 días con alertas | Tesorería | **Must** | S3 | 5 |
| 12 | Como tesorero, quiero gestionar cheques (emitidos, cobrados, protestados) | Tesorería | Should | S3 | 3 |
| 13 | Como admin, quiero emitir facturas con cálculo automático de IGV (18%) | Facturación | **Must** | S4 | 5 |
| 14 | Como admin, quiero registrar la cancelación de facturas con fecha, monto y forma de pago | Facturación | **Must** | S4 | 3 |
| 15 | Como admin, quiero ver el estado de facturas por cobrar con alertas de vencimiento | Facturación | **Must** | S4 | 3 |
| 16 | Como admin, quiero anular facturas con motivo y trazabilidad completa | Facturación | Should | S4 | 2 |
| 17 | Como empleado, quiero crear una rendición de gastos con comprobantes adjuntos | Rendiciones | **Must** | S5 | 5 |
| 18 | Como jefe, quiero aprobar/rechazar rendiciones con comentarios por flujo de trabajo | Rendiciones | **Must** | S5 | 3 |
| 19 | Como tesorero, quiero liquidar rendiciones aprobadas y registrar el desembolso | Rendiciones | **Must** | S5 | 3 |
| 20 | Como técnico móvil, quiero llenar formularios de orden de trabajo desde mi celular | Móvil | **Must** | S6 | 8 |
| 21 | Como cliente, quiero recibir un formulario de conformidad de entrega por link/SMS | Móvil | Should | S6 | 5 |
| 22 | Como admin, quiero ver un dashboard con KPIs financieros y de producción en tiempo real | Dashboard | **Must** | S6 | 5 |
| 23 | Como admin, quiero exportar reportes a Excel/PDF (ingresos, deudas, órdenes) | Reportes | Should | S6 | 3 |

**Total: 23 historias · 95 story points**

---

## 6. Sprint 1 — Infraestructura, Auth & Configuración

> **Semanas:** 1 – 2 · **Story Points:** 13  
> **Sprint Goal:** Sentar las bases del proyecto: servidor configurado, autenticación segura con roles y los primeros módulos operativos (pacientes y vista del técnico).

### Historias y Tareas

#### US-1 · Control de acceso por roles `3 pts`

**Como** admin, **quiero** registrar usuarios con roles **para** controlar el acceso al sistema.

**Tareas técnicas:**
- Instalar Laravel 11, configurar `.env`, base de datos y estructura inicial del proyecto.
- Instalar y configurar `spatie/laravel-permission` para roles y permisos.
- Crear seeders de roles: `admin`, `recepcion`, `tecnico`, `tesorero`.
- Implementar middleware de autorización por rol en todas las rutas.
- Crear vistas de login, recuperación de contraseña y panel de usuarios.

**Automatización:** Redireccionamiento automático al dashboard correspondiente según el rol del usuario al iniciar sesión.

**Criterios de aceptación:**
- Un usuario con rol `tecnico` no puede acceder al módulo de caja.
- Un usuario con rol `admin` puede crear, editar y desactivar usuarios.
- Las contraseñas se almacenan con bcrypt.

---

#### US-2 · Configuración del laboratorio `2 pts`

**Como** admin, **quiero** configurar los datos del laboratorio **para** que aparezcan en documentos y comprobantes.

**Tareas técnicas:**
- Crear tabla `settings` con clave-valor para parámetros globales.
- CRUD de ajustes: razón social, RUC, dirección, logo, IGV, series de factura.
- Panel de configuración en el área de administración.

**Criterios de aceptación:**
- Los cambios se reflejan inmediatamente en PDFs y cabeceras del sistema.
- Solo el rol `admin` puede acceder a esta sección.

---

#### US-3 · Registro de pacientes `5 pts`

**Como** recepción, **quiero** registrar pacientes con su historial dental y documentos adjuntos **para** tener toda la información centralizada.

**Tareas técnicas:**
- Crear modelo `Patient` con migración completa (nombre, DNI, teléfono, email, notas dentales).
- Formulario de registro y edición con validaciones frontend y backend.
- Carga de documentos adjuntos (radiografías, recetas) en storage local o S3.
- Búsqueda en tiempo real por nombre, DNI o teléfono.
- Vista de perfil del paciente con historial de órdenes de trabajo.

**Automatización:** Generación automática de número de expediente correlativo al registrar un nuevo paciente.

**Criterios de aceptación:**
- El DNI debe ser único en el sistema.
- Los documentos adjuntos aceptan PDF, JPG y PNG hasta 5 MB.
- La búsqueda responde en menos de 300 ms.

---

#### US-4 · Órdenes asignadas al técnico `3 pts`

**Como** técnico, **quiero** ver mis órdenes de trabajo asignadas con prioridad y fecha de entrega **para** organizar mi trabajo diario.

**Tareas técnicas:**
- Dashboard del técnico con listado de órdenes filtradas por su usuario.
- Filtros por estado (pendiente, en proceso, terminado) y rango de fechas.
- Indicador visual de urgencia según fecha prometida.
- Notificación push básica al asignar una nueva orden al técnico.

**Automatización:** Notificación push automática al técnico cuando se le asigna una nueva orden de trabajo.

**Criterios de aceptación:**
- Las órdenes próximas a vencer (menos de 24 h) se muestran en rojo.
- El técnico solo ve sus propias órdenes, no las de otros.

---

#### INFRA · Infraestructura & DevOps

**Tareas técnicas:**
- Configurar servidor Ubuntu 22.04 LTS con Nginx, PHP 8.2-fpm, MySQL 8 y Redis.
- Configurar entorno de staging separado del de producción.
- Configurar GitHub Actions para CI/CD: lint → tests → deploy automático al hacer merge a `main`.
- Configurar variables de entorno y secretos en GitHub.
- Documentar el proceso de despliegue en el `README.md` del repositorio.

**Automatización:** Deploy automático a staging al hacer merge en la rama `develop`.

---

### Entregables del Sprint 1

- [ ] Sistema de login funcional con roles diferenciados.
- [ ] Panel de configuración del laboratorio.
- [ ] Módulo de pacientes con búsqueda y adjuntos.
- [ ] Dashboard del técnico con sus órdenes.
- [ ] Servidor en producción con CI/CD activo.

---

## 7. Sprint 2 — Módulo Laboratorio & Caja

> **Semanas:** 3 – 4 · **Story Points:** 16  
> **Sprint Goal:** Operación completa del laboratorio: crear y gestionar órdenes de trabajo, y llevar el control de caja con cierre diario automatizado.

### Historias y Tareas

#### US-5 · Crear orden de trabajo `5 pts`

**Como** recepción, **quiero** crear órdenes de trabajo **para** registrar los trabajos encargados al laboratorio.

**Tareas técnicas:**
- Crear modelo `WorkOrder` con migración: tipo de trabajo, material, monto, fecha prometida, estado, técnico asignado, paciente.
- Formulario multi-paso con validaciones por paso.
- Selector de paciente con búsqueda en vivo (AJAX).
- Selector de técnico disponible.
- Vista de detalle de la orden con línea de tiempo de estados.

**Automatización:** Generación automática de correlativo de orden al momento de guardar (ej. `OT-2026-0001`).

**Criterios de aceptación:**
- No se puede crear una orden sin paciente asignado.
- El monto debe ser mayor a cero.
- La fecha prometida no puede ser anterior a hoy.

---

#### US-6 · Cambio de estado de orden `3 pts`

**Como** técnico, **quiero** actualizar el estado de la orden **para** mantener informado al laboratorio sobre el avance del trabajo.

**Tareas técnicas:**
- Implementar máquina de estados con Eloquent: `pendiente → en_proceso → terminado → entregado`.
- Registrar cada cambio en tabla `work_order_logs` con timestamp, usuario y comentario opcional.
- Botones de acción visibles según el estado actual y el rol del usuario.

**Automatización:** Cada cambio de estado registra automáticamente fecha, hora y usuario responsable en el historial.

**Criterios de aceptación:**
- No se puede pasar de `pendiente` a `entregado` sin pasar por `terminado`.
- El historial de estados es visible para admin y recepción.

---

#### US-7 · Ingresos y egresos de caja `5 pts`

**Como** cajero, **quiero** registrar ingresos y egresos **para** llevar el control del efectivo diario.

**Tareas técnicas:**
- Crear modelo `CashMovement` con campos: tipo (ingreso/egreso), monto, categoría, referencia, fecha, notas, comprobante adjunto.
- Tabla `cash_categories` configurable por el admin.
- Vista de movimientos del día con totales parciales.
- Carga de comprobante (PDF o imagen) por movimiento.

**Automatización:** El saldo de caja se actualiza en tiempo real tras cada registro de movimiento, sin necesidad de refrescar la página.

**Criterios de aceptación:**
- El cajero no puede eliminar un movimiento ya registrado, solo anularlo.
- Los adjuntos aceptan PDF, JPG y PNG hasta 5 MB.

---

#### US-8 · Cierre de caja diario `3 pts`

**Como** cajero, **quiero** hacer el cierre de caja diario **para** tener un resumen formal de los movimientos del día.

**Tareas técnicas:**
- Comando Artisan `caja:cerrar` programado a las 23:59 con Laravel Scheduler.
- Crear modelo `CashClosure` con saldo inicial, total ingresos, total egresos y saldo final.
- Generar PDF del resumen de cierre con DomPDF.
- Enviar PDF por email al admin automáticamente al cerrar.
- Vista de historial de cierres con descarga de PDF.

**Automatización:** El sistema cierra la caja automáticamente cada día a las 23:59 y envía el PDF resumen por email al administrador.

**Criterios de aceptación:**
- No se pueden registrar movimientos en una caja ya cerrada.
- El cajero puede visualizar y descargar el PDF del cierre en cualquier momento.

---

### Entregables del Sprint 2

- [ ] Módulo completo de órdenes de trabajo con estados.
- [ ] Control de caja con ingresos, egresos y adjuntos.
- [ ] Cierre automático de caja a las 23:59 con PDF por email.
- [ ] Historial de cambios de estado de órdenes.

---

## 8. Sprint 3 — Bancos & Tesorería

> **Semanas:** 5 – 6 · **Story Points:** 18  
> **Sprint Goal:** Control financiero avanzado: cuentas bancarias con conciliación, flujo de caja proyectado a 90 días y gestión de cheques.

### Historias y Tareas

#### US-9 · Cuentas bancarias y movimientos `5 pts`

**Como** tesorero, **quiero** registrar cuentas bancarias y sus movimientos **para** controlar el dinero en bancos.

**Tareas técnicas:**
- Modelos `BankAccount` y `BankMovement` con migraciones.
- CRUD de cuentas bancarias (banco, tipo, número, moneda, saldo inicial).
- Registro manual de movimientos bancarios.
- Importación de extracto bancario en formato CSV/Excel.
- Vista de saldo actual por cuenta con gráfica de evolución.

**Automatización:** El saldo de cada cuenta bancaria se recalcula automáticamente al registrar o importar un nuevo movimiento.

**Criterios de aceptación:**
- Se pueden gestionar múltiples cuentas en soles y dólares.
- La importación de extracto detecta y omite movimientos duplicados.

---

#### US-10 · Conciliación bancaria `5 pts`

**Como** tesorero, **quiero** conciliar el estado de cuenta bancario vs el sistema **para** detectar diferencias y errores.

**Tareas técnicas:**
- Algoritmo de match automático por importe + fecha (tolerancia de ±1 día).
- Pantalla de diferencias: movimientos en banco no registrados en sistema y viceversa.
- Marcado manual de movimientos conciliados.
- Reporte de conciliación exportable en PDF.

**Automatización:** El sistema intenta hacer match automático entre el extracto importado y los movimientos del sistema por importe y fecha.

**Criterios de aceptación:**
- Los movimientos conciliados no pueden modificarse.
- El reporte de conciliación muestra claramente las partidas en descuadre.

---

#### US-11 · Flujo de caja proyectado `5 pts`

**Como** tesorero, **quiero** ver el flujo de caja proyectado a 30/60/90 días **para** anticipar problemas de liquidez.

**Tareas técnicas:**
- Cálculo automático: ingresos esperados (facturas por cobrar con fecha de vencimiento) vs egresos programados.
- Gráfica de barras con Chart.js por semana/mes.
- Alerta automática si el flujo proyectado es negativo en algún período.
- Cálculo recalculado automáticamente todos los lunes a las 8:00 AM.

**Automatización:** Proyección recalculada cada lunes. Alerta automática al tesorero si se detecta un período con flujo negativo.

**Criterios de aceptación:**
- La proyección considera facturas pendientes de cobro y rendiciones aprobadas pendientes de pago.
- El tesorero puede ajustar manualmente egresos programados.

---

#### US-12 · Gestión de cheques `3 pts`

**Como** tesorero, **quiero** gestionar cheques emitidos y recibidos **para** controlar su estado y fecha de cobro.

**Tareas técnicas:**
- Modelo `Cheque` con estados: emitido, cobrado, protestado, anulado.
- Registro de cheques emitidos (a proveedores) y recibidos (de clientes).
- Alerta automática 2 días antes de la fecha de cobro.
- Vista de cartera de cheques por estado y fecha.

**Automatización:** Notificación automática por email al tesorero 2 días antes de la fecha de cobro o vencimiento de cada cheque.

**Criterios de aceptación:**
- Un cheque protestado genera una alerta crítica inmediata.
- Se puede registrar la cuenta bancaria asociada a cada cheque.

---

### Entregables del Sprint 3

- [ ] Gestión de múltiples cuentas bancarias con importación de extracto.
- [ ] Conciliación bancaria semi-automática.
- [ ] Dashboard de flujo de caja proyectado a 90 días.
- [ ] Módulo de cheques con alertas automáticas.

---

## 9. Sprint 4 — Facturación

> **Semanas:** 7 – 8 · **Story Points:** 13  
> **Sprint Goal:** Facturación completa con IGV automático, control de cancelaciones por fecha, cartera por cobrar con semáforo y anulaciones con trazabilidad total.

### Historias y Tareas

#### US-13 · Emisión de facturas `5 pts`

**Como** admin, **quiero** emitir facturas **para** formalizar el cobro de los trabajos realizados.

**Tareas técnicas:**
- Modelo `Invoice` con campos: serie, número, paciente, subtotal, IGV, total, fecha de emisión, fecha de vencimiento, estado.
- Cálculo automático del IGV al 18% al ingresar el subtotal.
- Correlativo automático por serie (F001, F002…).
- Vista previa de la factura antes de emitir.
- Descarga e impresión en PDF con DomPDF (logo, RUC, datos del laboratorio).

**Automatización:** El IGV se calcula automáticamente al ingresar el monto. El número correlativo se asigna automáticamente al emitir.

**Criterios de aceptación:**
- No se puede emitir una factura sin paciente y sin al menos una línea de detalle.
- La factura emitida no puede editarse, solo anularse.
- El PDF incluye código de barras o QR con datos de la factura.

---

#### US-14 · Cancelación de facturas `3 pts`

**Como** admin, **quiero** registrar la cancelación (pago) de una factura **para** actualizar su estado y saldo pendiente.

**Tareas técnicas:**
- Modelo `InvoicePayment` vinculado a `Invoice`.
- Formulario de pago: fecha, monto, forma de pago (efectivo, transferencia, cheque), referencia.
- Soporte para pagos parciales: una factura puede pagarse en múltiples cuotas.
- Actualización automática del saldo pendiente de la factura tras cada pago.
- Registro del pago en los movimientos de caja o banco correspondiente.

**Automatización:** Al registrar un pago, el saldo pendiente de la factura se actualiza automáticamente y, si el pago es total, el estado cambia a "pagada".

**Criterios de aceptación:**
- El monto de pago no puede superar el saldo pendiente de la factura.
- Cada pago genera un recibo descargable en PDF.

---

#### US-15 · Cartera por cobrar `3 pts`

**Como** admin, **quiero** ver el estado de mis facturas por cobrar **para** gestionar el cobro a tiempo.

**Tareas técnicas:**
- Dashboard de cartera con semáforo: verde (al día), amarillo (por vencer en 3 días), rojo (vencida).
- Scheduler 8:00 AM: marcar automáticamente facturas vencidas y enviar alerta al admin.
- Scheduler: notificación 3 días y 1 día antes del vencimiento.
- Exportar cartera a Excel con Maatwebsite.

**Automatización:** Marcado automático de facturas vencidas cada día a las 8:00 AM con alerta por email al administrador. Notificación previa 3 y 1 día antes del vencimiento.

**Criterios de aceptación:**
- La cartera muestra el total por cobrar, el total vencido y el total por vencer.
- El Excel exportado incluye todos los filtros aplicados en pantalla.

---

#### US-16 · Anulación de facturas `2 pts`

**Como** admin, **quiero** anular una factura **para** corregir errores de emisión con trazabilidad completa.

**Tareas técnicas:**
- Flujo de anulación con campo de motivo obligatorio.
- Registro en `audit_logs` con estado anterior, nuevo estado, motivo y usuario que anuló.
- Opción de generar nota de crédito asociada.
- La factura anulada se muestra tachada en listados con badge "ANULADA".

**Automatización:** Registro automático en el log de auditoría con todos los datos del usuario, fecha y motivo al anular una factura.

**Criterios de aceptación:**
- No se puede anular una factura ya pagada (total).
- La anulación requiere aprobación del admin (si lo hace un usuario con otro rol).

---

### Entregables del Sprint 4

- [ ] Emisión de facturas en PDF con IGV automático y correlativo.
- [ ] Registro de pagos parciales y totales con recibo.
- [ ] Dashboard de cartera por cobrar con semáforo y alertas automáticas.
- [ ] Anulación de facturas con trazabilidad en audit log.

---

## 10. Sprint 5 — Rendiciones de Gastos

> **Semanas:** 9 – 10 · **Story Points:** 14  
> **Sprint Goal:** Flujo completo de rendición de gastos: creación con adjuntos, aprobación por niveles jerárquicos, liquidación y auditoría en todos los módulos del sistema.

### Historias y Tareas

#### US-17 · Crear rendición de gastos `5 pts`

**Como** empleado, **quiero** crear una rendición de gastos **para** solicitar el reembolso o la justificación de gastos realizados.

**Tareas técnicas:**
- Modelo `ExpenseReport` (cabecera) y `ExpenseLine` (líneas de detalle).
- Formulario dinámico: agregar/quitar líneas de gasto con categoría, monto, descripción y adjunto de comprobante por línea.
- Cálculo automático del total de la rendición.
- Estados: borrador, enviada, en revisión, aprobada, rechazada, liquidada.
- Vista de mis rendiciones con historial de estados.

**Criterios de aceptación:**
- Una rendición en estado "borrador" puede editarse; una vez enviada, no.
- Cada línea debe tener su comprobante adjunto obligatorio (boleta o factura).
- El total de la rendición se calcula automáticamente al agregar líneas.

---

#### US-18 · Flujo de aprobación `3 pts`

**Como** jefe, **quiero** aprobar o rechazar rendiciones **para** controlar los gastos del personal.

**Tareas técnicas:**
- Notificación automática por email al aprobador al recibir una rendición en estado "enviada".
- Vista del aprobador con detalle de la rendición y todos sus comprobantes.
- Botones de Aprobar y Rechazar con campo de comentario obligatorio al rechazar.
- Scheduler semanal: recordatorio al empleado si tiene rendiciones en "borrador" sin enviar.

**Automatización:** Notificación automática al aprobador al enviar una rendición. Recordatorio semanal al empleado si tiene rendiciones sin enviar.

**Criterios de aceptación:**
- Solo el jefe o admin puede aprobar rendiciones.
- El empleado recibe notificación por email con el resultado (aprobada o rechazada + comentario).
- Una rendición rechazada puede corregirse y reenviarse.

---

#### US-19 · Liquidación de rendición `3 pts`

**Como** tesorero, **quiero** liquidar rendiciones aprobadas **para** registrar el desembolso y cerrar el proceso.

**Tareas técnicas:**
- Vista de rendiciones aprobadas pendientes de liquidación.
- Formulario de liquidación: cuenta de caja o banco origen, fecha, referencia.
- Creación automática del movimiento de egreso en caja o banco al liquidar.
- Cambio automático de estado a "liquidada" y generación de PDF de liquidación.

**Automatización:** Al liquidar, se crea automáticamente el movimiento de egreso en el módulo de caja o banco correspondiente y la rendición se cierra.

**Criterios de aceptación:**
- Solo se pueden liquidar rendiciones en estado "aprobada".
- El PDF de liquidación incluye todas las líneas de gasto y el total desembolsado.

---

#### AUDIT · Auditoría General del Sistema `3 pts`

**Tareas técnicas:**
- Implementar trait `Auditable` en todos los modelos clave del sistema.
- Tabla `audit_logs`: usuario, modelo, acción (create/update/delete), valores anteriores, valores nuevos, IP, timestamp.
- Visor de auditoría en el panel de admin: filtro por modelo, usuario y rango de fechas.
- Exportar log de auditoría a Excel.

**Automatización:** Cada acción de creación, edición o eliminación en cualquier modelo clave queda registrada automáticamente en el log de auditoría.

---

### Entregables del Sprint 5

- [ ] Módulo de rendiciones con formulario dinámico y adjuntos por línea.
- [ ] Flujo de aprobación con notificaciones email.
- [ ] Liquidación vinculada a caja o banco.
- [ ] Log de auditoría en todos los módulos con visor y exportación.

---

## 11. Sprint 6 — Formularios Móvil, Dashboard & Reportes

> **Semanas:** 11 – 12 · **Story Points:** 21  
> **Sprint Goal:** Entrega final: formularios móvil con modo offline (PWA), dashboard ejecutivo con KPIs actualizados en tiempo real y exportación de reportes financieros en Excel/PDF.

### Historias y Tareas

#### US-20 · Formularios móvil — PWA `8 pts`

**Como** técnico móvil, **quiero** llenar formularios de orden de trabajo desde mi celular **para** registrar avances sin ir al laboratorio.

**Tareas técnicas:**
- API REST protegida con Laravel Sanctum (autenticación por token).
- Formulario PWA de orden de trabajo con Blade + Alpine.js.
- Modo offline: guardar datos en IndexedDB cuando no hay red.
- Sincronización automática al recuperar la conexión a internet.
- Manifest y Service Worker para instalar la PWA en el celular.

**Automatización:** Los formularios completados offline se sincronizan automáticamente con el servidor al recuperar la conexión a internet.

**Criterios de aceptación:**
- La PWA funciona correctamente con red 3G o inferior.
- Los datos no se pierden si se cierra el navegador antes de sincronizar.
- El técnico puede ver el estado actual de sus órdenes desde el celular.

---

#### US-21 · Conformidad de entrega `5 pts`

**Como** cliente, **quiero** recibir un formulario de conformidad de entrega por link **para** confirmar que recibí el trabajo conforme.

**Tareas técnicas:**
- Generación de link único por orden de trabajo (token firmado con expiración).
- Formulario público (sin login): datos de la orden, campo de observaciones y firma digital con canvas.
- Al firmar, cambio automático del estado de la orden a "entregada" con timestamp.
- Registro de la firma como imagen adjunta a la orden.

**Automatización:** Al recibir la firma del cliente, la orden se marca automáticamente como "entregada" y se registra la fecha y hora de conformidad.

**Criterios de aceptación:**
- El link expira a los 7 días de generado.
- El link de conformidad no puede usarse más de una vez.
- El laboratorio recibe notificación cuando el cliente firma.

---

#### US-22 · Dashboard KPIs `5 pts`

**Como** admin, **quiero** ver un dashboard con KPIs financieros y de producción **para** tomar decisiones rápidas.

**Tareas técnicas:**
- Widgets: ventas del mes, saldo de caja, facturas vencidas, órdenes en proceso, cheques por cobrar.
- Gráfica de ventas por semana (Chart.js, barras).
- Gráfica de estado de órdenes (Chart.js, dona).
- Actualización automática de los datos cada 5 minutos (polling AJAX).
- Vista responsive adaptada a tablets y escritorio.

**Automatización:** El dashboard se actualiza automáticamente cada 5 minutos sin necesidad de recargar la página.

**Criterios de aceptación:**
- El dashboard carga en menos de 2 segundos.
- Cada widget tiene un enlace de acceso rápido al módulo correspondiente.
- El admin puede personalizar qué widgets ver (configuración guardada por usuario).

---

#### US-23 · Exportación de reportes `3 pts`

**Como** admin, **quiero** exportar reportes a Excel y PDF **para** analizar la información fuera del sistema.

**Tareas técnicas:**
- Reportes disponibles: ingresos/egresos por período, cartera por cobrar, órdenes por técnico, rendiciones liquidadas.
- Generación en background con Laravel Queue (Job) para reportes pesados.
- Descarga en Excel con Maatwebsite/Laravel-Excel.
- Descarga en PDF con DomPDF.
- Notificación al usuario cuando el reporte está listo para descargar.

**Automatización:** Los reportes se generan en segundo plano (Job en cola) y el usuario recibe una notificación cuando el archivo está listo para descargar.

**Criterios de aceptación:**
- El usuario puede seguir navegando el sistema mientras el reporte se genera.
- Los reportes incluyen los filtros de fecha y módulo aplicados por el usuario.
- Los reportes se eliminan del servidor automáticamente después de 24 horas.

---

### Entregables del Sprint 6

- [ ] PWA instalable en celular con modo offline funcional.
- [ ] Formulario de conformidad de entrega con firma digital.
- [ ] Dashboard ejecutivo con 5 KPIs y actualización automática.
- [ ] Exportación de 4 reportes en Excel y PDF con generación en background.

---

## 12. Arquitectura Técnica

| Capa | Tecnología | Decisión de diseño |
|------|-----------|-------------------|
| **Backend Framework** | Laravel 11 · PHP 8.2 | MVC + Service Layer + Repository Pattern |
| **Base de Datos** | MySQL 8 | Migraciones Eloquent, índices en FK y campos de búsqueda |
| **Autenticación** | Laravel Sanctum + Spatie | Tokens API + roles y permisos granulares |
| **Formularios Móvil** | PWA + API REST JSON | Blade + Alpine.js, modo offline con IndexedDB |
| **Colas & Scheduler** | Laravel Queue · Redis | Emails, PDFs y reportes pesados; cierres automáticos |
| **Generación PDF** | DomPDF (barryvdh) | Facturas, reportes y cierres de caja |
| **Exportación Excel** | Maatwebsite Laravel-Excel | Reportes financieros descargables en XLSX |
| **Notificaciones** | Laravel Notifications | Email (SMTP/SES), base de datos y broadcast (Pusher) |
| **Frontend** | Blade + Tailwind CSS + Alpine.js | Chart.js / Recharts para gráficas |
| **Storage** | Laravel Storage | Local en desarrollo · S3-compatible en producción |
| **Testing** | PHPUnit + Pest + Dusk | Unitarios, funcionales y E2E en flujos críticos |
| **CI/CD** | GitHub Actions | Lint, tests, deploy automático a staging y producción |

### Estructura de Carpetas Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── Lab/          (WorkOrder, Patient)
│   │   ├── Finance/      (Cash, Bank, Invoice, Treasury)
│   │   ├── Reports/
│   │   └── Api/          (endpoints para PWA)
│   └── Middleware/
├── Models/
├── Services/             (lógica de negocio)
├── Repositories/         (acceso a datos)
├── Jobs/                 (generación de PDFs, reportes)
├── Notifications/        (emails y push)
└── Console/Commands/     (tareas programadas)
```

---

## 13. Entidades de Base de Datos

| Tabla | Campos Clave | Relaciones |
|-------|-------------|-----------|
| `patients` | id, name, dni, phone, email, dental_notes, created_by | `has_many work_orders` |
| `work_orders` | id, patient_id, technician_id, status, type, amount, due_date, delivered_at | `belongs_to patient, user` · `has_many payments` |
| `work_order_logs` | id, work_order_id, from_status, to_status, user_id, comment, created_at | `belongs_to work_order, user` |
| `cash_movements` | id, type (in/out), amount, category_id, ref_doc, cashier_id, date, notes | `belongs_to user, cash_category` |
| `cash_closures` | id, date, opening_balance, total_in, total_out, closing_balance, user_id | `has_many cash_movements` |
| `bank_accounts` | id, bank_name, account_number, currency, balance | `has_many bank_movements` |
| `bank_movements` | id, bank_account_id, type, amount, description, date, reconciled | `belongs_to bank_account` |
| `invoices` | id, series, number, patient_id, subtotal, igv, total, status, due_date | `has_many invoice_payments` · `has_one work_order` |
| `invoice_payments` | id, invoice_id, amount, paid_at, payment_method, reference | `belongs_to invoice` |
| `expense_reports` | id, user_id, title, status, total, approved_by, approved_at | `has_many expense_lines` |
| `expense_lines` | id, report_id, category_id, amount, description, receipt_path | `belongs_to expense_report` |
| `audit_logs` | id, user_id, model_type, model_id, action, old_values, new_values, ip | Polymorphic en todos los modelos |
| `settings` | id, key, value, group | — |
| `cheques` | id, type, amount, bank_account_id, date_issued, date_due, status | `belongs_to bank_account` |

---

## 14. Automatizaciones del Sistema

> Todas las automatizaciones se implementan con **Laravel Scheduler** (`app/Console/Kernel.php`) y **Jobs en cola** con Redis.

| Automatización | Disparador | Acción | Canal |
|---------------|-----------|--------|-------|
| **Cierre de caja diario** | Scheduler 23:59 cada día | Genera registro de cierre + PDF + envía resumen | Email + BD |
| **Alerta facturas por vencer** | Scheduler 8:00 AM diario | Notifica facturas que vencen en 3 y 1 días | Email + Push |
| **Marca facturas vencidas** | Scheduler 8:00 AM diario | Actualiza estado a "vencida", alerta al tesorero | Email + BD |
| **Proyección flujo de caja** | Scheduler lunes 8:00 AM | Recalcula proyección 90 días y actualiza dashboard | BD + Push |
| **Alerta cheques a cobrar** | Scheduler 8:00 AM diario | Notifica cheques con cobro en 2 días | Email |
| **Alerta saldo mínimo de caja** | Event: `CashMovementCreated` | Si saldo < umbral configurado, alerta inmediata | Push + Email |
| **Notificación de aprobación** | Event: `ExpenseReportSubmitted` | Notifica al aprobador que tiene rendición pendiente | Email |
| **Recordatorio de rendición** | Scheduler semanal (viernes) | Recuerda rendiciones en borrador sin enviar | Email |
| **Orden marcada como entregada** | Event: `ConformidadFirmada` | Marca orden como "entregada" y registra timestamp | BD |
| **Backup automático BD** | Scheduler 2:00 AM diario | Dump MySQL comprimido cargado a S3/FTP | Storage |
| **Sync datos offline** | Event: `DeviceReconnected` | Sincroniza formularios guardados en IndexedDB | API REST |
| **Refresh dashboard KPIs** | Scheduler cada 5 min | Recalcula métricas del dashboard | BD + Cache |

---

## 15. Definición de Done (DoD)

Un ítem del backlog se considera **TERMINADO** cuando cumple **todos** los siguientes criterios:

| # | Criterio | Cómo se verifica |
|---|----------|-----------------|
| ✅ 1 | Código en rama `feature/` integrado a `develop` sin conflictos | Git merge sin errores |
| ✅ 2 | Tests unitarios y de integración escritos y en verde | PHPUnit / Pest en CI |
| ✅ 3 | Validaciones del formulario implementadas (frontend + backend) | Manual + test |
| ✅ 4 | Responsive: funciona en 375 px (móvil) y 1280 px+ (escritorio) | Chrome DevTools |
| ✅ 5 | Migraciones ejecutadas en entorno staging sin errores | `php artisan migrate` |
| ✅ 6 | Permisos de rol aplicados correctamente (no acceso no autorizado) | Test de roles |
| ✅ 7 | Auditoría registrada en `audit_logs` para acciones críticas | Revisión manual BD |
| ✅ 8 | Demo aprobada por Product Owner en Sprint Review | Acta de aceptación firmada |
| ✅ 9 | Documentación de API actualizada si aplica endpoint nuevo | Postman Collection |
| ✅ 10 | Sin errores críticos en Sentry / log de producción staging | Monitor de logs |

---

## 16. Roadmap de Entrega

| Sprint | Tema Principal | Entregable Demo | Semanas | Story Pts |
|--------|---------------|-----------------|---------|-----------|
| **S1** | Auth + Config + Pacientes | Login con roles, ficha paciente, BD base, CI/CD | 1 – 2 | 13 |
| **S2** | Laboratorio + Caja | Órdenes de trabajo, cierre diario de caja | 3 – 4 | 16 |
| **S3** | Bancos + Tesorería | Conciliación, flujo proyectado, cheques | 5 – 6 | 18 |
| **S4** | Facturación completa | Emitir, cobrar y anular facturas con IGV | 7 – 8 | 13 |
| **S5** | Rendiciones | Crear, aprobar y liquidar rendiciones | 9 – 10 | 14 |
| **S6** | Móvil + Dashboard + Reportes | PWA offline, KPIs en tiempo real, Excel/PDF | 11 – 12 | 21 |
| **TOTAL** | — | Sistema completo en producción | 12 semanas | **95 pts** |

### Valor entregado por sprint

```
S1 ████░░░░░░░░░░░░░░░░  13% (13 pts) — Base del sistema
S2 ████████░░░░░░░░░░░░  17% (16 pts) — Operación diaria activa
S3 ██████████░░░░░░░░░░  19% (18 pts) — Control financiero avanzado
S4 ██████░░░░░░░░░░░░░░  14% (13 pts) — Facturación formal
S5 ███████░░░░░░░░░░░░░  15% (14 pts) — Control de gastos
S6 ███████████░░░░░░░░░  22% (21 pts) — Cierre e integración móvil
```

---

## 17. Gestión de Riesgos

| Riesgo | Probabilidad | Impacto | Plan de Mitigación |
|--------|-------------|---------|-------------------|
| Cambio de requisitos frecuente por el PO | Alta | Media | Sprint Reviews cortas y frecuentes. Backlog refinado cada semana. Cambios solo se incorporan en el siguiente sprint. |
| Problemas de rendimiento en conciliación bancaria | Media | Alta | Índices de BD desde el inicio. Paginación obligatoria. Proceso ejecutado como Job en cola (no en request HTTP). |
| Conectividad móvil limitada del técnico | Alta | Alta | Diseño offline-first en PWA con IndexedDB. Sincronización automática al recuperar la red. |
| Pérdida de datos financieros críticos | Baja | Crítico | Backup automático diario a las 2:00 AM. Replicación MySQL en tiempo real. Ambiente de restore testeado mensualmente. |
| Integración con SUNAT (facturación electrónica) | Media | Alta | Evaluar en Sprint 4. Usar proveedor OSE certificado (Nubefact o SUNAT directo). Dejar módulo de facturación preparado para el envío XML. |
| Rotación del equipo de desarrollo | Baja | Alta | Documentación técnica actualizada en cada sprint. Code review obligatorio en todos los pull requests. Wiki de arquitectura en GitHub. |

---

## Notas Finales

- Este documento es la **única fuente de verdad** del proyecto y debe mantenerse actualizado al cierre de cada sprint.
- Los story points pueden reestimarse en el **Backlog Refinement** de cada sprint.
- Cualquier historia no completada en un sprint se devuelve al backlog y se reprioriza con el Product Owner.
- El sistema está diseñado para escalar a **múltiples sucursales** en una fase posterior.

---

*Sistema Integral — Laboratorio Dental · Planificación SCRUM v1.0 · Marzo 2026*  
*PHP 8.2 · Laravel 11 · MySQL 8 · PWA · API REST*
