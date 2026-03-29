# 💼 Módulo de Ventas, Cobros & Conciliación Financiera
## Sistema Integral — Laboratorio Dental
### Plan de Desarrollo SCRUM

> **Stack:** PHP 8.2 · Laravel 11 · MySQL 8 · PWA / API REST
> **Metodología:** SCRUM — Sprints quincenales (2 semanas)
> **Módulo:** Ventas, Cobros y Conciliación · **Sprints:** S11 – S12 · **Story Points:** 52
> **Versión:** 1.0 — Marzo 2026

---

## Tabla de Contenidos

1. [Visión del Módulo](#1-visión-del-módulo)
2. [Mapa de Relaciones Financieras](#2-mapa-de-relaciones-financieras)
3. [Flujo Completo: Venta → Cobro → Conciliación](#3-flujo-completo-venta--cobro--conciliación)
4. [Relación Ventas ↔ Compras](#4-relación-ventas--compras)
5. [Product Backlog — Ventas, Cobros & Conciliación](#5-product-backlog--ventas-cobros--conciliación)
6. [Sprint 11 — Ventas, Cotizaciones & Cobros](#6-sprint-11--ventas-cotizaciones--cobros)
7. [Sprint 12 — Conciliación Financiera Integral](#7-sprint-12--conciliación-financiera-integral)
8. [Entidades de Base de Datos](#8-entidades-de-base-de-datos)
9. [Reglas de Negocio Financieras](#9-reglas-de-negocio-financieras)
10. [Automatizaciones](#10-automatizaciones)
11. [Integraciones con Todos los Módulos](#11-integraciones-con-todos-los-módulos)
12. [Roadmap Final — 12 Sprints](#12-roadmap-final--12-sprints)
13. [Riesgos Específicos](#13-riesgos-específicos)

---

## 1. Visión del Módulo

### El problema central

En un laboratorio dental el dinero entra y sale por múltiples canales simultáneamente:

- Un **cliente paga** una orden de trabajo en efectivo, por transferencia o en cuotas.
- El laboratorio **compra materiales** a un proveedor al crédito a 30 días.
- Se pagan **sueldos** por transferencia bancaria.
- Se emiten **facturas** por los trabajos entregados.
- Se reciben **abonos parciales** de clientes que deben el total.

Sin un sistema de conciliación, es imposible saber con certeza **cuánto dinero real tiene el laboratorio**, qué facturas están efectivamente cobradas y cuál es la ganancia real del mes descontando todos los costos.

### Lo que este módulo resuelve

Este módulo conecta los tres flujos financieros del laboratorio:

```
VENTAS (ingresos)      COMPRAS (egresos)      BANCOS (movimientos reales)
       │                      │                          │
       └──────────────────────┴──────────────────────────┘
                              │
                    CONCILIACIÓN FINANCIERA
                              │
               ┌──────────────┴──────────────┐
               │                             │
         Estado real                   Resultados
         de caja/banco              (margen, utilidad)
```

---

## 2. Mapa de Relaciones Financieras

Esta es la relación completa entre todos los módulos del sistema desde el punto de vista financiero:

```
┌─────────────────────────────────────────────────────────────────────┐
│                        FLUJO DE INGRESOS                            │
│                                                                     │
│  Orden de Trabajo ──► Factura de Venta ──► Cobro del Cliente        │
│       │                    │                    │                   │
│  (genera deuda)      (formaliza venta)    (ingresa dinero)          │
│       │                    │                    │                   │
│       └────────────────────┴────────────────────┘                  │
│                            │                                        │
│                     Cuenta por Cobrar                               │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                        FLUJO DE EGRESOS                             │
│                                                                     │
│  Orden de Compra ──► Factura del Proveedor ──► Pago al Proveedor    │
│       │                    │                    │                   │
│  (consume stock)     (genera deuda)       (sale dinero)             │
│       │                    │                    │                   │
│       └────────────────────┴────────────────────┘                  │
│                            │                                        │
│                     Cuenta por Pagar                                │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                     CONCILIACIÓN FINANCIERA                         │
│                                                                     │
│  Cuentas por Cobrar  ◄──────────────────►  Cuentas por Pagar       │
│         +                                        +                 │
│  Saldo en Caja/Banco ◄──────────────────►  Egresos Operativos      │
│         │                                        │                 │
│         └──────────────────┬───────────────────  ┘                 │
│                            │                                        │
│                   POSICIÓN FINANCIERA REAL                          │
│          (Liquidez · Rentabilidad · Flujo de Caja)                  │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 3. Flujo Completo: Venta → Cobro → Conciliación

### Paso a paso en el sistema

```
1. ORDEN DE TRABAJO
   Cliente solicita trabajo dental
   → Se crea WorkOrder con monto estimado
   → Estado: PENDIENTE

2. COTIZACIÓN (opcional)
   El laboratorio emite cotización al cliente
   → Cliente aprueba o negocia
   → Cotización aprobada genera WorkOrder automáticamente

3. ENTREGA DEL TRABAJO
   Técnico marca orden como TERMINADA
   → Sistema notifica a recepción para entregar al cliente
   → Cliente firma conformidad (PWA)
   → Orden pasa a estado ENTREGADA

4. FACTURACIÓN
   Recepción emite factura al cliente
   → Factura vinculada a la WorkOrder
   → Se crea Cuenta por Cobrar por el total
   → IGV calculado automáticamente (18%)

5. COBRO AL CLIENTE
   El cliente paga (total o parcialmente)
   → Se registra el pago con forma de pago
   → La Cuenta por Cobrar se reduce
   → El dinero entra a Caja o Banco según forma de pago:
      - Efectivo → Caja
      - Transferencia → Banco
      - Cheque → Cheques por cobrar → Banco al depositarlo

6. CONCILIACIÓN
   El sistema verifica:
   → ¿El pago registrado coincide con el movimiento bancario real?
   → ¿La factura emitida coincide con lo cobrado?
   → ¿El dinero en banco coincide con el saldo del sistema?
   → Diferencias detectadas → Alerta al tesorero

7. RESULTADO FINANCIERO
   Al cierre del período el sistema calcula:
   → Ingresos por ventas (facturas emitidas)
   → Costo de ventas (materiales usados por OT)
   → Gastos operativos (planilla, alquiler, servicios)
   → Margen bruto y utilidad neta del período
```

---

## 4. Relación Ventas ↔ Compras

Esta es la relación financiera más importante del sistema: **cada venta tiene un costo**, y ese costo viene de las compras de materiales.

### 4.1 Costo de Venta por Orden de Trabajo

Cada orden de trabajo tiene un costo real calculado automáticamente:

```
Costo de la Orden de Trabajo
├── Materiales consumidos (desde Inventarios, método PEPS)
│   ├── Material A: 50g × S/ 0.80/g = S/ 40.00
│   ├── Material B: 2 unid × S/ 15.00 = S/ 30.00
│   └── Total materiales: S/ 70.00
│
├── Mano de obra (desde RRHH, costo por hora del técnico)
│   └── 3 horas × S/ 12.50/h = S/ 37.50
│
└── COSTO TOTAL OT: S/ 107.50

Precio de venta (factura): S/ 250.00
Margen bruto de la OT: S/ 142.50 (57%)
```

### 4.2 Cuentas por Cobrar vs Cuentas por Pagar

El sistema mantiene en todo momento el **saldo neto financiero**:

```
POSICIÓN FINANCIERA EN TIEMPO REAL

Cuentas por Cobrar (lo que nos deben los clientes)
├── Cliente A — Factura F001-0021 — S/ 350.00 — vence en 5 días
├── Cliente B — Factura F001-0019 — S/ 180.00 — VENCIDA hace 3 días
└── Total por cobrar: S/ 530.00

Cuentas por Pagar (lo que debemos a proveedores)
├── Proveedor Dental Express — OC-2026-004 — S/ 420.00 — vence en 12 días
├── Proveedor Materiales SA — OC-2026-002 — S/ 95.00 — VENCIDA hace 1 día
└── Total por pagar: S/ 515.00

POSICIÓN NETA: S/ 530.00 − S/ 515.00 = +S/ 15.00
```

### 4.3 Relación entre Compras y Ventas en el tiempo

```
COMPRA de materiales (hoy)
        │
        ▼
STOCK disponible en almacén
        │
        ▼
CONSUMO en Orden de Trabajo (cuando se produce)
        │
        ▼
COSTO DE VENTA registrado (cuando se entrega)
        │
        ▼
VENTA facturada al cliente
        │
        ▼
COBRO recibido (puede ser inmediato o diferido)
        │
        ▼
PAGO al proveedor (cierra el ciclo de compra)
```

### 4.4 Indicadores clave de la relación Ventas-Compras

El sistema calcula automáticamente los siguientes KPIs:

| Indicador | Fórmula | Frecuencia |
|-----------|---------|-----------|
| **Margen bruto por OT** | (Precio venta − Costo materiales) / Precio venta × 100 | Por orden |
| **Margen bruto del período** | (Total ventas − Total costo de materiales) / Total ventas × 100 | Mensual |
| **Rotación de inventario** | Costo de ventas del período / Stock promedio del período | Mensual |
| **Días de inventario** | 365 / Rotación de inventario | Mensual |
| **Ciclo de conversión de efectivo** | Días cobro clientes + Días inventario − Días pago proveedores | Mensual |
| **Ratio cobro/pago** | Total por cobrar / Total por pagar | Tiempo real |

---

## 5. Product Backlog — Ventas, Cobros & Conciliación

| # | Historia de Usuario | Prioridad | Sprint | Pts |
|---|---------------------|-----------|--------|-----|
| VEN-1 | Como recepción, quiero registrar cotizaciones a clientes que luego se conviertan en órdenes de trabajo | Should | S11 | 3 |
| VEN-2 | Como recepción, quiero registrar el cobro de una orden al momento de la entrega (contado o crédito) | **Must** | S11 | 5 |
| VEN-3 | Como cajero, quiero registrar abonos parciales de clientes con su forma de pago y aplicarlos a facturas específicas | **Must** | S11 | 5 |
| VEN-4 | Como admin, quiero ver el estado de cuenta de cada cliente (facturas pendientes, abonos recibidos, saldo) | **Must** | S11 | 3 |
| VEN-5 | Como admin, quiero gestionar las cuentas por cobrar con semáforo de vencimiento y acciones de cobranza | **Must** | S11 | 5 |
| VEN-6 | Como admin, quiero gestionar las cuentas por pagar a proveedores con fechas de vencimiento y pagos programados | **Must** | S11 | 5 |
| VEN-7 | Como admin, quiero ver el saldo neto en tiempo real (cuentas por cobrar menos cuentas por pagar) | **Must** | S11 | 3 |
| CON-1 | Como tesorero, quiero conciliar los cobros de clientes contra los movimientos reales del banco | **Must** | S12 | 5 |
| CON-2 | Como tesorero, quiero conciliar los pagos a proveedores contra los movimientos reales del banco | **Must** | S12 | 5 |
| CON-3 | Como admin, quiero ver el costo real de cada orden de trabajo (materiales + mano de obra) vs el precio de venta | **Must** | S12 | 5 |
| CON-4 | Como admin, quiero ver el estado de resultados del período (ingresos, costos, gastos, utilidad) | **Must** | S12 | 5 |
| CON-5 | Como admin, quiero exportar el reporte de conciliación financiera completa en Excel y PDF | Should | S12 | 3 |

**Total: 12 historias · 52 story points · 2 sprints**

---

## 6. Sprint 11 — Ventas, Cotizaciones & Cobros

> **Semanas:** 21 – 22 · **Story Points:** 29
> **Sprint Goal:** El ciclo de venta está completo en el sistema: cotización, cobro al contado o crédito, abonos parciales de clientes, estado de cuenta por cliente y visibilidad total de cuentas por cobrar y por pagar.

### Historias y Tareas

---

#### VEN-1 · Cotizaciones a clientes `3 pts`

**Como** recepción, **quiero** registrar cotizaciones **para** que el cliente apruebe el precio antes de iniciar el trabajo.

**Tareas técnicas:**
- Modelo `Quote` con campos: cliente, detalle de trabajos, subtotal, IGV, total, fecha de validez, estado (borrador, enviada, aprobada, rechazada, vencida).
- Formulario de cotización con líneas de detalle: descripción del trabajo, cantidad y precio unitario.
- Generación de PDF de cotización con los datos del laboratorio y del cliente.
- Envío del PDF por email al cliente directamente desde el sistema.
- Al aprobar la cotización: conversión automática a Orden de Trabajo con todos los datos precargados.
- Al vencer sin respuesta: cambio de estado automático a "vencida" con alerta al vendedor.

**Automatización:** Al aprobar la cotización, se crea automáticamente la Orden de Trabajo con todos los datos de la cotización precargados. Las cotizaciones sin respuesta cambian a "vencida" automáticamente al llegar su fecha de validez.

**Criterios de aceptación:**
- Una cotización aprobada no puede editarse; solo se puede generar una nueva versión.
- El historial de cotizaciones es visible en el perfil del cliente.
- La fecha de validez no puede ser anterior a hoy al crear la cotización.

---

#### VEN-2 · Cobro en el momento de la entrega `5 pts`

**Como** recepción, **quiero** registrar el cobro de una orden al entregarla **para** cerrar el ciclo de venta en el momento correcto.

**Tareas técnicas:**
- En la pantalla de entrega de orden, mostrar el resumen de pago: precio de la OT, abonos previos (si los hay) y saldo pendiente.
- Formulario de cobro integrado en la entrega: forma de pago (efectivo, transferencia, tarjeta, cheque), monto, referencia.
- Soporte para pago dividido: parte en efectivo + parte en transferencia en una misma transacción.
- Al cobrar en efectivo: el monto ingresa automáticamente al módulo de Caja como movimiento de ingreso.
- Al cobrar por transferencia: el monto queda en estado "pendiente de confirmar en banco" hasta que se concilie.
- Al cobrar con cheque: se crea un cheque por cobrar en el módulo de Tesorería con la fecha de cobro.
- La orden se marca como "entregada y cobrada" automáticamente al registrar el pago total.

**Automatización:** Según la forma de pago, el sistema enruta automáticamente el ingreso al módulo correcto: efectivo a Caja, transferencia a Banco (pendiente de confirmación), cheque a Tesorería.

**Criterios de aceptación:**
- No se puede registrar un pago mayor al saldo pendiente de la orden.
- Si el pago es parcial, la orden queda como "entregada con saldo pendiente" y se crea una cuenta por cobrar automáticamente.
- El recibo de cobro se genera en PDF y se puede imprimir o enviar por email en el mismo momento.

---

#### VEN-3 · Abonos parciales de clientes `5 pts`

**Como** cajero, **quiero** registrar abonos parciales de clientes **para** gestionar clientes que pagan en cuotas o que tienen deuda pendiente.

**Tareas técnicas:**
- Vista de "Cobro de deudas": buscar cliente y ver todas sus facturas con saldo pendiente.
- Formulario de abono: seleccionar factura(s) a las que aplica el abono, monto, forma de pago, fecha.
- Lógica de aplicación del abono:
  - Aplicación específica: el cajero elige qué factura abona.
  - Aplicación automática: el sistema aplica al documento más antiguo primero (FIFO).
- Registro del abono como movimiento de ingreso en Caja o Banco.
- Actualización automática del saldo pendiente de cada factura abonada.
- Generación de recibo de abono en PDF.

**Automatización:** Si se selecciona "aplicación automática", el sistema distribuye el abono comenzando por la factura más antigua primero. El saldo pendiente de cada factura se actualiza automáticamente al registrar el abono.

**Criterios de aceptación:**
- Un abono no puede superar el saldo pendiente total del cliente.
- El recibo de abono muestra el detalle de a qué facturas se aplicó y cuánto a cada una.
- Si el abono cubre totalmente una factura, ésta cambia automáticamente a estado "pagada".

---

#### VEN-4 · Estado de cuenta por cliente `3 pts`

**Como** admin, **quiero** ver el estado de cuenta de cada cliente **para** saber exactamente cuánto me debe y qué facturas están pendientes.

**Tareas técnicas:**
- Vista de estado de cuenta: todas las facturas del cliente con su estado (pagada, abonada, pendiente, vencida), montos y fechas.
- Resumen al inicio: total facturado, total cobrado, saldo pendiente, saldo vencido.
- Historial de pagos y abonos del cliente en orden cronológico.
- Exportar estado de cuenta en PDF para enviar al cliente.
- Envío del estado de cuenta por email directamente desde el sistema.
- Vista accesible desde el perfil del cliente y desde el módulo de cuentas por cobrar.

**Criterios de aceptación:**
- El estado de cuenta muestra saldos al día de hoy, no a una fecha pasada.
- El PDF del estado de cuenta incluye datos de contacto del laboratorio para que el cliente pueda pagar.

---

#### VEN-5 · Gestión de cuentas por cobrar `5 pts`

**Como** admin, **quiero** gestionar todas las cuentas por cobrar **para** hacer seguimiento activo de los clientes que me deben.

**Tareas técnicas:**
- Dashboard de cuentas por cobrar con semáforo:
  - 🟢 Verde: por vencer en más de 7 días.
  - 🟡 Amarillo: por vencer en 3 a 7 días.
  - 🔴 Rojo: vence en menos de 3 días o vencida.
- Filtros: por cliente, rango de fechas, estado, monto mínimo.
- Acción rápida "Recordatorio de pago": enviar email o WhatsApp al cliente con el detalle de lo que debe.
- Registro de gestión de cobranza: notas de llamadas, acuerdos de pago, compromisos.
- Aging report (cartera por antigüedad): 0–30 días, 31–60 días, 61–90 días, más de 90 días.
- Scheduler: envío automático de recordatorio por email 3 días antes del vencimiento y el día del vencimiento.

**Automatización:** Recordatorio automático por email al cliente 3 días antes del vencimiento y el día que vence la factura. Marcado automático como "vencida" el día siguiente al vencimiento con alerta al admin.

**Criterios de aceptación:**
- El aging report muestra el total y el porcentaje por cada tramo de antigüedad.
- El registro de gestión de cobranza queda visible solo para roles admin y tesorero.
- Se puede generar una "promesa de pago" vinculada a una cuenta por cobrar con fecha y monto acordado.

---

#### VEN-6 · Gestión de cuentas por pagar `5 pts`

**Como** admin, **quiero** gestionar las cuentas por pagar a proveedores **para** pagar a tiempo y no generar problemas con los proveedores clave.

**Tareas técnicas:**
- Dashboard de cuentas por pagar con el mismo semáforo que cuentas por cobrar.
- Origen de la cuenta por pagar: automática desde la recepción de mercadería (módulo Inventarios) o manual.
- Programar pagos: el tesorero define la fecha en que se pagará cada deuda y la cuenta bancaria origen.
- Al registrar el pago: el sistema crea automáticamente el movimiento de egreso en Caja o Banco.
- Historial de pagos a cada proveedor con todos sus comprobantes.
- Aging report de cuentas por pagar: cuánto se debe y cuándo vence.

**Automatización:** Al registrar la recepción de mercadería vinculada a una factura del proveedor, se crea automáticamente la cuenta por pagar con la fecha de vencimiento según las condiciones de crédito del proveedor. Al pagar, se registra el egreso en el banco automáticamente.

**Criterios de aceptación:**
- No se puede registrar el pago de una cuenta por pagar ya pagada.
- La suma de todas las cuentas por pagar pendientes coincide con el total del aging report.

---

#### VEN-7 · Saldo neto en tiempo real `3 pts`

**Como** admin, **quiero** ver el saldo neto financiero en tiempo real **para** saber en cualquier momento la posición real del laboratorio.

**Tareas técnicas:**
- Widget en el dashboard principal con:
  - Total cuentas por cobrar (pendiente de clientes).
  - Total cuentas por pagar (deuda con proveedores).
  - Saldo neto (CxC − CxP).
  - Saldo disponible en caja + bancos.
  - Posición de liquidez total (saldo disponible + saldo neto).
- Mini gráfica de evolución del saldo neto de los últimos 6 meses.
- Drill-down: hacer clic en cada cifra lleva al módulo correspondiente con el detalle.
- Actualización automática cada 5 minutos.

**Automatización:** El saldo neto se recalcula automáticamente en tiempo real al registrar cualquier cobro, pago, factura o abono en cualquier módulo del sistema.

**Criterios de aceptación:**
- El saldo neto del widget coincide exactamente con la suma de los módulos de CxC y CxP.
- La posición de liquidez distingue entre dinero disponible (en caja/banco) y dinero comprometido (en cheques).

---

### Entregables del Sprint 11

- [ ] Cotizaciones con conversión automática a orden de trabajo.
- [ ] Cobro integrado en la entrega con enrutamiento automático por forma de pago.
- [ ] Gestión de abonos parciales con aplicación FIFO automática.
- [ ] Estado de cuenta por cliente en PDF y por email.
- [ ] Dashboard de cuentas por cobrar con semáforo, aging report y recordatorios automáticos.
- [ ] Dashboard de cuentas por pagar con pagos programados.
- [ ] Widget de saldo neto en tiempo real en el dashboard principal.

---

## 7. Sprint 12 — Conciliación Financiera Integral

> **Semanas:** 23 – 24 · **Story Points:** 23
> **Sprint Goal:** Cierre financiero completo: conciliación de cobros de clientes y pagos a proveedores contra los movimientos bancarios reales, costo real de cada orden de trabajo, estado de resultados del período y exportación del reporte de conciliación financiera.

### Historias y Tareas

---

#### CON-1 · Conciliación de cobros de clientes vs banco `5 pts`

**Como** tesorero, **quiero** conciliar los cobros registrados contra los movimientos reales del banco **para** confirmar que todo el dinero que figura como cobrado realmente ingresó al banco.

**Tareas técnicas:**
- Vista de conciliación: dos columnas lado a lado.
  - Columna izquierda: cobros de clientes por transferencia registrados en el sistema.
  - Columna derecha: movimientos de crédito del extracto bancario importado.
- Algoritmo de match automático por monto + fecha (tolerancia ±1 día) + referencia de transferencia.
- Tipos de diferencias detectadas automáticamente:
  - **Cobro registrado sin movimiento bancario:** el cliente dijo que pagó pero no aparece en el banco.
  - **Movimiento bancario sin cobro registrado:** entró dinero al banco pero no está identificado en el sistema.
  - **Diferencia de monto:** el cobro registrado difiere del movimiento bancario.
- Resolución de diferencias: el tesorero puede vincular manualmente, crear el cobro faltante o marcar como "en investigación".
- Registro completo de la conciliación con fecha, usuario, diferencias encontradas y resueltas.

**Automatización:** El algoritmo de match corre automáticamente cada vez que se importa un nuevo extracto bancario. Las diferencias se clasifican y priorizan automáticamente por monto (las más grandes primero).

**Criterios de aceptación:**
- Un cobro conciliado no puede modificarse sin autorización del admin.
- El reporte de conciliación muestra el porcentaje de cobros conciliados vs pendientes.
- Las diferencias mayores a S/ 100 generan una alerta automática al tesorero.

---

#### CON-2 · Conciliación de pagos a proveedores vs banco `5 pts`

**Como** tesorero, **quiero** conciliar los pagos a proveedores contra los movimientos del banco **para** confirmar que todos los pagos salieron correctamente.

**Tareas técnicas:**
- Vista de conciliación de egresos: misma lógica que CON-1 pero para pagos salientes.
  - Columna izquierda: pagos a proveedores registrados en el sistema.
  - Columna derecha: movimientos de débito del extracto bancario.
- Match automático por monto + fecha + referencia de transferencia.
- Tipos de diferencias:
  - **Pago registrado sin débito bancario:** se registró el pago en el sistema pero no salió del banco.
  - **Débito bancario sin pago registrado:** salió dinero del banco sin justificación en el sistema.
  - **Diferencia de monto:** el monto pagado difiere del monto debitado.
- Panel de pagos no identificados: débitos bancarios que no tienen correspondencia en ningún módulo del sistema (pueden ser comisiones bancarias, impuestos retenidos, etc.).
- Registro de comisiones bancarias y gastos financieros detectados en la conciliación.

**Automatización:** Los débitos bancarios sin match se clasifican automáticamente como "pago no identificado" y se notifica al tesorero para su revisión. Las comisiones bancarias se sugieren como categoría automáticamente si el monto coincide con los rangos habituales.

**Criterios de aceptación:**
- Todos los débitos bancarios deben quedar conciliados o marcados como "identificado y justificado" para cerrar la conciliación del período.
- Los pagos duplicados (mismo monto y proveedor en el mismo día) generan una alerta automática.

---

#### CON-3 · Costo real por orden de trabajo `5 pts`

**Como** admin, **quiero** ver el costo real de cada orden de trabajo **para** saber exactamente cuánto me costó producirla y cuál es el margen real.

**Tareas técnicas:**
- Cálculo del costo real de cada OT compuesto por:

  **1. Costo de materiales** (desde Inventarios)
  - Suma de todas las salidas de inventario vinculadas a la OT.
  - Valorizado con el costo del lote según método PEPS.

  **2. Costo de mano de obra** (desde RRHH)
  - Costo por hora del técnico asignado (sueldo bruto + aportes del empleador / horas laborables del mes).
  - Tiempo registrado en la OT (horas desde que se inició hasta que se terminó, o ingreso manual).

  **3. Costos indirectos (overhead)**
  - Porcentaje configurable sobre el costo directo para cubrir alquiler, servicios, depreciación.
  - Default sugerido: 15% sobre costo directo.

- Ficha de rentabilidad por OT:
  ```
  Precio de venta:       S/ 250.00  (100%)
  (-) Costo materiales:  S/  70.00  ( 28%)
  (-) Mano de obra:      S/  37.50  ( 15%)
  (-) Overhead (15%):    S/  16.13  (  6%)
  ─────────────────────────────────────────
  Margen bruto:          S/ 126.37  ( 51%)
  ```
- Dashboard de rentabilidad: top 10 OTs más rentables y top 10 menos rentables del período.
- Alerta si el margen de una OT cae por debajo del umbral mínimo configurado (default: 30%).

**Automatización:** El costo de materiales se calcula automáticamente desde el módulo de Inventarios al registrar las salidas. El costo de mano de obra se calcula automáticamente al cerrar la OT según el tiempo registrado. El overhead se aplica automáticamente con el porcentaje configurado.

**Criterios de aceptación:**
- El costo de materiales de la OT siempre refleja el costo del lote PEPS utilizado, no el precio actual.
- Si una OT no tiene materiales registrados, muestra advertencia de "costo incompleto".
- El margen se puede ver en soles y en porcentaje.

---

#### CON-4 · Estado de resultados del período `5 pts`

**Como** admin, **quiero** ver el estado de resultados **para** conocer la utilidad real del laboratorio en el período.

**Tareas técnicas:**
- Estructura del estado de resultados del sistema:

  ```
  ESTADO DE RESULTADOS — [Período seleccionado]
  ═══════════════════════════════════════════════

  INGRESOS
  (+) Ventas facturadas del período              S/ XX,XXX.XX
  (+) Notas de crédito emitidas (-)              S/    XXX.XX
  ─────────────────────────────────────────────────────────
  = VENTAS NETAS                                 S/ XX,XXX.XX

  COSTO DE VENTAS
  (-) Materiales consumidos en OTs entregadas    S/  X,XXX.XX
  (-) Mano de obra directa (técnicos)            S/  X,XXX.XX
  ─────────────────────────────────────────────────────────
  = UTILIDAD BRUTA                               S/ XX,XXX.XX
    Margen bruto: XX%

  GASTOS OPERATIVOS
  (-) Planilla administrativa                    S/  X,XXX.XX
  (-) Alquiler y servicios                       S/    XXX.XX
  (-) Gastos varios (rendiciones liquidadas)     S/    XXX.XX
  (-) Comisiones bancarias                       S/     XX.XX
  ─────────────────────────────────────────────────────────
  = UTILIDAD OPERATIVA (EBITDA)                  S/  X,XXX.XX
    Margen operativo: XX%

  (-) Impuestos estimados                        S/    XXX.XX
  ─────────────────────────────────────────────────────────
  = UTILIDAD NETA DEL PERÍODO                    S/  X,XXX.XX
    Margen neto: XX%
  ```

- Filtros: período mensual, trimestral o rango personalizado.
- Comparativa: período actual vs período anterior (columnas lado a lado con variación en %).
- Gráfica de evolución de ventas vs costos vs utilidad de los últimos 12 meses.
- Drill-down: hacer clic en cualquier línea muestra el detalle de los documentos que la componen.

**Automatización:** El estado de resultados se recalcula automáticamente cada vez que se aprueba una factura, se registra un pago de planilla, se liquida una rendición o se registra cualquier movimiento financiero relevante.

**Criterios de aceptación:**
- Los números del estado de resultados son consistentes con los módulos de facturación, inventarios, RRHH y rendiciones.
- La utilidad bruta coincide con la suma de márgenes de todas las OTs entregadas en el período.
- El informe muestra una advertencia si hay facturas emitidas pero no cobradas (ingresos devengados vs percibidos).

---

#### CON-5 · Reporte de conciliación financiera exportable `3 pts`

**Como** admin, **quiero** exportar el reporte de conciliación financiera **para** presentarlo al contador y tener respaldo del cierre del período.

**Tareas técnicas:**
- Reporte de conciliación bancaria: movimientos conciliados, pendientes y diferencias por cuenta bancaria.
- Reporte de cuentas por cobrar conciliadas: facturas emitidas vs cobros recibidos vs saldo pendiente.
- Reporte de cuentas por pagar conciliadas: compras registradas vs pagos realizados vs saldo pendiente.
- Reporte de rentabilidad por OT: todas las órdenes del período con su precio, costo y margen.
- Reporte de estado de resultados: versión exportable del informe de CON-4.
- Todos los reportes en Excel (Maatwebsite) y PDF (DomPDF).
- Generación en background con Laravel Queue. Notificación al usuario cuando están listos.

**Criterios de aceptación:**
- El paquete de reportes puede generarse para un período específico (mes, trimestre o personalizado).
- Los reportes están diseñados para ser entregados directamente a un contador sin procesamiento adicional.
- Cada reporte incluye fecha de generación, usuario que lo generó y período cubierto.

---

### Entregables del Sprint 12

- [ ] Conciliación de cobros de clientes vs extracto bancario con match automático.
- [ ] Conciliación de pagos a proveedores vs extracto bancario.
- [ ] Ficha de rentabilidad por orden de trabajo (materiales + mano de obra + overhead).
- [ ] Estado de resultados del período con drill-down por línea.
- [ ] Paquete de reportes de conciliación financiera en Excel y PDF.

---

## 8. Entidades de Base de Datos

### Ventas y Cobros

| Tabla | Campos Clave | Relaciones |
|-------|-------------|-----------|
| `quotes` | id, patient_id, lines_json, subtotal, igv, total, valid_until, status, created_by | `belongs_to patient, user` · `has_one work_order` |
| `accounts_receivable` | id, invoice_id, patient_id, original_amount, paid_amount, balance, due_date, status | `belongs_to invoice, patient` · `has_many payments` |
| `customer_payments` | id, account_receivable_id, amount, payment_method, reference, paid_at, cashier_id, bank_account_id | `belongs_to account_receivable, user, bank_account` |
| `payment_applications` | id, customer_payment_id, invoice_id, amount_applied | `belongs_to customer_payment, invoice` |
| `accounts_payable` | id, purchase_order_id, supplier_id, original_amount, paid_amount, balance, due_date, status | `belongs_to purchase_order, supplier` · `has_many supplier_payments` |
| `supplier_payments` | id, account_payable_id, amount, payment_method, reference, paid_at, bank_account_id, user_id | `belongs_to account_payable, bank_account, user` |
| `collection_notes` | id, account_receivable_id, note, contact_type, next_action_date, user_id | `belongs_to account_receivable, user` |
| `payment_promises` | id, account_receivable_id, promised_date, promised_amount, status, notes | `belongs_to account_receivable` |

### Conciliación Financiera

| Tabla | Campos Clave | Relaciones |
|-------|-------------|-----------|
| `bank_reconciliations` | id, bank_account_id, period_start, period_end, status, total_matched, total_unmatched, created_by | `belongs_to bank_account, user` · `has_many reconciliation_items` |
| `reconciliation_items` | id, reconciliation_id, bank_movement_id, system_movement_id, system_movement_type, matched_at, difference, status | `belongs_to bank_reconciliation` · Polymorphic en `system_movement` |
| `work_order_costs` | id, work_order_id, material_cost, labor_cost, overhead_cost, total_cost, margin_amount, margin_pct | `belongs_to work_order` |
| `work_order_time_logs` | id, work_order_id, technician_id, start_time, end_time, hours, cost_per_hour, total_cost | `belongs_to work_order, user` |
| `financial_periods` | id, year, month, status (open/closed), closed_by, closed_at | — |
| `income_statement_snapshots` | id, period_id, gross_sales, returns, net_sales, material_cost, labor_cost, gross_profit, operating_expenses, operating_profit, taxes, net_profit | `belongs_to financial_period` |

---

## 9. Reglas de Negocio Financieras

Estas reglas se implementan como validaciones en la capa de servicio de Laravel (`app/Services/`) y son no negociables en el sistema:

### Reglas de Cobros

```
REGLA 1: Integridad del cobro
Un cobro registrado NO puede eliminarse.
Solo puede anularse con motivo obligatorio y aprobación del admin.
La anulación revierte el movimiento de caja/banco automáticamente.

REGLA 2: Consistencia del saldo
El saldo de una cuenta por cobrar = monto original - suma de todos los cobros aplicados.
Esta operación es atómica: si falla algún paso, se hace rollback completo.

REGLA 3: Cobro mayor al saldo
El sistema rechaza cualquier intento de registrar un pago mayor
al saldo pendiente de la factura o cuenta por cobrar.
```

### Reglas de Pagos a Proveedores

```
REGLA 4: Pago vinculado a deuda
Solo se puede registrar un pago a proveedor si existe
una cuenta por pagar activa para ese proveedor.
No se permiten pagos "en el aire" sin documento de respaldo.

REGLA 5: Doble verificación de pagos altos
Pagos que superen el umbral configurado (default: S/ 1,000)
requieren aprobación de un segundo usuario con rol admin antes de confirmarse.
```

### Reglas de Conciliación

```
REGLA 6: Cierre de período
Un período financiero cerrado NO permite nuevos movimientos.
Para corregir errores en un período cerrado se requiere
una nota de ajuste en el período actual.

REGLA 7: Movimiento bancario único
Un movimiento bancario (del extracto) solo puede ser
conciliado con UN movimiento del sistema.
No se puede usar el mismo movimiento bancario para justificar
dos transacciones distintas del sistema.

REGLA 8: Consistencia banco-sistema
Al cerrar la conciliación de un período, la suma de los movimientos
conciliados debe coincidir exactamente con el saldo del extracto bancario.
Si hay diferencia, no se puede cerrar la conciliación.
```

### Reglas de Rentabilidad

```
REGLA 9: Costo de materiales PEPS
El costo de los materiales de una OT se fija en el momento
en que se registra la salida del inventario (costo del lote PEPS vigente).
No se recalcula aunque el precio del material cambie después.

REGLA 10: Margen mínimo por OT
Si el costo calculado supera el precio de venta (margen negativo),
el sistema muestra una alerta al admin antes de emitir la factura,
pero NO bloquea la facturación (la decisión final es del admin).
```

---

## 10. Automatizaciones

| Automatización | Disparador | Acción | Canal |
|---------------|-----------|--------|-------|
| **Cotización vencida** | Scheduler diario — fecha de validez superada | Cambia estado a "vencida" y notifica al vendedor | Email + BD |
| **OT creada desde cotización** | Event: `QuoteApproved` | Crea WorkOrder con datos de la cotización precargados | BD |
| **Enrutamiento de cobro** | Event: `PaymentRegistered` | Envía ingreso a Caja (efectivo) o Banco (transferencia) o Tesorería (cheque) automáticamente | BD |
| **Saldo CxC actualizado** | Event: `PaymentApplied` | Recalcula saldo pendiente de la factura y cambia estado si es cero | BD |
| **Recordatorio de pago al cliente** | Scheduler 8 AM — 3 días antes y día del vencimiento | Email con estado de cuenta y detalle de lo adeudado | Email |
| **Factura vencida** | Scheduler 8 AM diario | Marca como vencida y alerta al admin | Email + BD |
| **CxP creada desde recepción** | Event: `MerchandiseReceived` | Crea cuenta por pagar con fecha de vencimiento según condiciones del proveedor | BD |
| **Egreso al pagar proveedor** | Event: `SupplierPaymentRegistered` | Crea movimiento de egreso en Banco automáticamente | BD |
| **Match de conciliación** | Event: `BankStatementImported` | Ejecuta algoritmo de match automático entre extracto y sistema | BD |
| **Alerta diferencia de conciliación** | Event: `ReconciliationDifferenceFound` | Notifica al tesorero diferencias mayores a S/ 100 | Email + Push |
| **Costo OT calculado** | Event: `WorkOrderDelivered` | Calcula y guarda el costo real de la OT (materiales + mano de obra + overhead) | BD |
| **Alerta margen negativo** | Event: `WorkOrderCostCalculated` | Alerta al admin si el margen de la OT es menor al umbral mínimo | Push + Email |
| **Estado de resultados** | Scheduler 1° de cada mes — 6 AM | Genera y guarda el snapshot del estado de resultados del mes anterior | BD + Email |
| **Saldo neto actualizado** | Event: cualquier movimiento financiero | Recalcula CxC − CxP y actualiza el widget del dashboard | BD + Cache |

---

## 11. Integraciones con Todos los Módulos

Esta es la tabla completa de integraciones del módulo de Ventas y Conciliación con todos los demás módulos del sistema:

| Integración | Módulo origen | Módulo destino | Qué se conecta |
|-------------|--------------|----------------|----------------|
| OT genera factura | Laboratorio | Facturación / Ventas | Al entregar la OT se genera la factura de venta vinculada |
| Cobro ingresa a caja | Ventas / Cobros | Caja | Cobros en efectivo crean movimiento de ingreso en caja automáticamente |
| Cobro ingresa a banco | Ventas / Cobros | Bancos | Cobros por transferencia crean movimiento pendiente de confirmar en banco |
| Cobro con cheque | Ventas / Cobros | Tesorería | Cheques recibidos se registran en el módulo de cheques por cobrar |
| Compra genera CxP | Inventarios | Cuentas por Pagar | Al recepcionar mercadería con crédito se crea la CxP automáticamente |
| Pago de CxP debita banco | Cuentas por Pagar | Bancos | Al pagar a proveedor se registra el egreso en el banco seleccionado |
| Materiales de OT → costo | Inventarios | Conciliación | Las salidas de inventario de una OT definen su costo de materiales |
| Mano de obra → costo OT | RRHH | Conciliación | El costo por hora del técnico se usa para calcular el costo laboral de la OT |
| Planilla → gasto operativo | RRHH | Estado de Resultados | El total de planilla mensual aparece como gasto en el estado de resultados |
| Rendiciones → gasto operativo | Rendiciones | Estado de Resultados | Las rendiciones liquidadas aparecen como gasto en el estado de resultados |
| Comisiones bancarias | Conciliación | Estado de Resultados | Las comisiones identificadas en la conciliación se registran como gasto financiero |
| Extracto bancario | Bancos | Conciliación | Los extractos importados son la base para la conciliación de cobros y pagos |
| CxC en dashboard | Ventas | Dashboard KPIs | El total de CxC aparece como KPI en el dashboard principal |
| Saldo neto en tesorería | Ventas / Conciliación | Tesorería | El saldo neto CxC − CxP forma parte del flujo de caja proyectado |
| Cotización → OT | Ventas | Laboratorio | La cotización aprobada crea la OT automáticamente con datos precargados |

---

## 12. Roadmap Final — 12 Sprints

> Plan completo del sistema con todos los módulos incluyendo Ventas, Cobros y Conciliación.

| Sprint | Tema Principal | Semanas | Story Pts | Acumulado |
|--------|---------------|---------|-----------|-----------|
| S1 | Auth + Config + Pacientes | 1 – 2 | 13 | 13 |
| S2 | Laboratorio + Caja | 3 – 4 | 16 | 29 |
| S3 | Bancos + Tesorería | 5 – 6 | 18 | 47 |
| S4 | Facturación | 7 – 8 | 13 | 60 |
| S5 | Rendiciones | 9 – 10 | 14 | 74 |
| S6 | Móvil + Dashboard + Reportes | 11 – 12 | 21 | 95 |
| S7 | Inventario: Maestro & Stock | 13 – 14 | 10 | 105 |
| S8 | Inventario: Movimientos, Compras & Alertas | 15 – 16 | 21 | 126 |
| S9 | RRHH: Empleados, Asistencia & Contratos | 17 – 18 | 21 | 147 |
| S10 | RRHH: Planilla, Vacaciones & Reportes | 19 – 20 | 27 | 174 |
| **S11** | **Ventas, Cotizaciones & Cobros** | **21 – 22** | **29** | **203** |
| **S12** | **Conciliación Financiera Integral** | **23 – 24** | **23** | **226** |
| **TOTAL** | **Sistema integral completo** | **24 semanas** | **226 pts** | — |

### Progreso acumulado por sprint

```
S1   ██░░░░░░░░░░░░░░░░░░░░░░   6%  ( 13 pts) Base del sistema
S2   ████░░░░░░░░░░░░░░░░░░░░  13%  ( 16 pts) Operación diaria
S3   ██████░░░░░░░░░░░░░░░░░░  21%  ( 18 pts) Control financiero
S4   ███████░░░░░░░░░░░░░░░░░  27%  ( 13 pts) Facturación formal
S5   ████████░░░░░░░░░░░░░░░░  33%  ( 14 pts) Control de gastos
S6   █████████████░░░░░░░░░░░  42%  ( 21 pts) Móvil + Dashboard
S7   █████████████░░░░░░░░░░░  46%  ( 10 pts) Inventarios base
S8   ████████████████░░░░░░░░  56%  ( 21 pts) Inventarios completo
S9   ██████████████████░░░░░░  65%  ( 21 pts) RRHH base
S10  ████████████████████░░░░  77%  ( 27 pts) RRHH completo
S11  ██████████████████████░░  90%  ( 29 pts) Ventas + Cobros
S12  ████████████████████████ 100%  ( 23 pts) Conciliación total
```

---

## 13. Riesgos Específicos

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|-------------|---------|-----------|
| El estado de resultados no coincide con la realidad por movimientos sin categorizar | Media | Alta | Validación al cierre de período: no se puede cerrar si hay movimientos sin categoría. |
| Diferencias en la conciliación por transferencias que tarda días en aparecer en el banco | Alta | Media | Período de tolerancia de 3 días hábiles para match bancario. Estado "pendiente de banco" para cobros recientes. |
| Clientes que pagan parcialmente sin avisar cuál factura están abonando | Alta | Media | Sistema de aplicación automática FIFO como opción por defecto. El cajero puede sobrescribir manualmente. |
| Costo de OT incompleto porque el técnico no registró los materiales usados | Alta | Alta | Bloqueo soft al cerrar la OT si no tiene materiales registrados. Alerta al supervisor. |
| Discrepancia entre el estado de resultados del sistema y el del contador externo | Media | Alta | Exportar reportes en formato que el contador pueda validar fácilmente. Alinear criterios contables antes del Sprint 12. |
| Pagos al proveedor sin comprobante de respaldo | Media | Alta | Regla de negocio estricta: no se puede registrar un pago a proveedor sin una CxP activa vinculada. |

---

## Notas Finales

- Este documento es el **complemento de** `LaboratorioDental_SCRUM.md` y `LaboratorioDental_Inventarios_RRHH.md`.
- Los Sprints 11–12 continúan la numeración del plan general.
- El módulo de conciliación es el **cierre financiero** del sistema: conecta todos los módulos anteriores en una visión unificada.
- Se recomienda que el **Sprint 12 sea validado con el contador del laboratorio** antes de liberar en producción para asegurar que el estado de resultados y los reportes cumplan con los requerimientos contables y tributarios peruanos.
- El sistema **no reemplaza la contabilidad formal** (que requiere software contable o contador habilitado), sino que provee toda la información financiera operativa organizada para facilitar el trabajo contable externo.

---

*Sistema Integral — Laboratorio Dental · Ventas, Cobros & Conciliación Financiera*
*SCRUM v1.0 · Sprints 11–12 · Semanas 21–24 · Marzo 2026*
*PHP 8.2 · Laravel 11 · MySQL 8 · PWA · API REST*
