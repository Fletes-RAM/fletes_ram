# FR-6 — Flujo funcional de cotización, facturación y proveedores

> Análisis del comportamiento legacy para preservar funcionalidad durante la actualización. **No ejecuta migraciones ni modifica producción.**

## Propósito

Antes de diseñar las tablas modernas de este bloque, documentamos qué representa realmente cada estructura y qué acciones de negocio realiza hoy Fletes RAM. El objetivo de la actualización es conservar el comportamiento necesario sin copiar literalmente nombres, sentinelas o acoplamientos del sistema legacy.

## 1. Ingreso: cotización -> operación -> factura -> cobro

### Cotización

Una cotización relaciona cliente, ruta, tipo de unidad y rendimiento. Captura kilómetros, costo de combustible, propuesta, utilidad, sueldo de operador, gastos administrativos, otros gastos, combustible, caseta y observaciones.

Al guardar:
- utilidad = propuesta × porcentaje de utilidad;
- sueldo del operador = propuesta × porcentaje;
- gastos administrativos = propuesta × porcentaje;
- se genera un folio `C-YYYYMMDD-######`;
- puede producirse PDF y enviarse al correo del cliente.

### Elegibilidad para facturar

La pantalla de facturación presenta cotizaciones que:
1. ya tienen una asignación operativa; y
2. todavía no aparecen en `ram_facturas`.

Esto confirma que la factura de este flujo nace de una cotización que ya pasó a operación.

### Factura de cotización

`ram_facturas` representa la factura emitida al cliente vinculada a una cotización. Maneja subtotal, maniobras, IVA, retención, folio/número de factura, total, observaciones, estado pagada y fecha de pago.

La creación histórica de esta tabla no está representada correctamente por las migraciones preservadas; el baseline real es la referencia estructural.

### Cobro

Al cobrar una factura el sistema:
1. asigna `fecha_pago`;
2. marca `pagada = 1`;
3. guarda observaciones;
4. crea un movimiento bancario de ingreso (`tipo = 1`) por el total de la factura.

**Regla a preservar:** el cobro y su movimiento financiero forman parte de una misma operación funcional. En el sistema moderno deberán ejecutarse transaccionalmente.

## 2. Ingreso alterno: control vehicular -> factura -> cobro

Los controles vehiculares constituyen un segundo origen de facturación.

El sistema permite seleccionar varios controles y generar una sola `FacturaControl`. Para una factura agrupada:
- crea `ram_facturas_controles` con `control_id = 0`;
- después coloca el ID de esa factura en `factura_id` de cada control seleccionado.

Por tanto, el valor `control_id = 0` no puede tratarse como simple corrupción: es una representación legacy de una relación uno-a-muchos.

Al cobrar una factura de control vehicular se repite el patrón del flujo anterior:
- fecha de pago;
- `pagada = 1`;
- observaciones;
- movimiento bancario de ingreso por el total.

### Decisión canónica

El sistema moderno no debe usar un ID 0 para representar agrupación. La factura debe relacionarse explícitamente con uno o varios documentos/orígenes facturables mediante una relación normalizada.

Debe preservarse la trazabilidad de los IDs legacy durante la migración.

## 3. Egreso: tickets de combustible -> factura de proveedor -> pago

Este flujo es distinto a la facturación de clientes.

La pantalla “Pendientes por Pagar” se alimenta de `vista_comprobantes_proveedores`. Los tickets seleccionados pueden provenir de:
- combustible de asignación (`a-ID`);
- combustible especial (`e-ID`).

El sistema valida que los tickets seleccionados pertenezcan a la misma gasolinera.

Existen dos comportamientos legacy relacionados:

### Asignación de factura del proveedor

La funcionalidad añadida en el código actual permite asignar a varios tickets:
- `factura_proveedor`;
- `fecha_limite_pago`.

La operación se ejecuta dentro de una transacción.

### Registro de pago

El flujo legacy de pago crea filas en `ram_proveedores` por los tickets incluidos y después crea un movimiento bancario de egreso (`tipo = -1`) con el importe de la factura.

Esto confirma que:

- `ram_cat_proveedores` es el **catálogo de proveedores**;
- `ram_proveedores` **no es el catálogo**: representa registros asociados al pago/relación de comprobantes.

El nombre `ram_proveedores` es ambiguo y no debe trasladarse literalmente al modelo moderno.

## 4. Comprobantes de combustible y gastos del operador

`ram_comprobantes_combustible` relaciona un operador/usuario con registros de combustible seleccionados para comprobación. El código evita volver a presentar combustibles ya relacionados.

`ram_comprobantes_gastos` se usa como comprobación de gastos del operador y participa en los reportes de sueldos/gastos.

Estos conceptos no deben mezclarse automáticamente con la factura de proveedor: son evidencias/comprobaciones operativas con usos propios.

## 5. Modelo conceptual propuesto

Sin fijar todavía nombres físicos definitivos:

```text
CLIENTE
  |
COTIZACIÓN ----> OPERACIÓN / ASIGNACIÓN
  |                       |
  +---- origen facturable-+
              |
           FACTURA
              |
            COBRO
              |
      MOVIMIENTO BANCARIO


CONTROL VEHICULAR (1..N)
              |
       origen facturable
              |
           FACTURA
              |
            COBRO
              |
      MOVIMIENTO BANCARIO


PROVEEDOR / GASOLINERA
              |
      TICKETS / CONSUMOS
              |
     FACTURA DE PROVEEDOR
              |
             PAGO
              |
      MOVIMIENTO BANCARIO
```

## 6. Qué conservamos y qué corregimos

| Comportamiento | Actualización |
| --- | --- |
| Cotización con cálculo económico y folio | Conservar. |
| PDF/envío de cotización | Conservar funcionalidad; implementación se moderniza. |
| Facturar una cotización operada | Conservar. |
| Facturar varios controles vehiculares | Conservar. |
| `control_id = 0` para multi-control | Sustituir por relación explícita. |
| Estado pagada + fecha de pago | Conservar concepto; fecha nullable cuando el legacy no permite recuperar una fecha real. |
| Cobro genera movimiento bancario | Conservar y ejecutar transaccionalmente. |
| Tickets de proveedor de una misma gasolinera | Conservar la regla mientras negocio no indique lo contrario. |
| Factura/fecha límite de proveedor en múltiples tickets | Conservar el comportamiento. |
| Pago de proveedor genera egreso bancario | Conservar y ejecutar transaccionalmente. |
| `cat_proveedores` | Mantener como concepto catálogo de proveedores. |
| `proveedores` | Renombrar/modelar según su función real, no como catálogo. |
| Vistas SQL legacy | Usarlas como evidencia; decidir caso por caso si pasan a query, servicio, reporte o vista. |

## 7. Decisiones que NO se toman todavía

- No se fusionan facturas de cotización y facturas de control sin comprobar todos sus campos y reglas.
- No se fusionan combustible ordinario y especial todavía.
- No se elimina información histórica.
- No se convierte cada vista legacy en una vista moderna.
- No se inventa la lógica de `ram_deudas`.
- No se decide todavía el diseño final de cuentas por cobrar/pagar hasta revisar su dependencia con bancos y reportes.

## Resultado

Este bloque confirma tres procesos que la actualización debe preservar:

1. **Cotización/operación -> factura a cliente -> cobro.**
2. **Control vehicular -> factura a cliente -> cobro.**
3. **Tickets/consumos -> factura de proveedor -> pago.**

El siguiente paso de FR-6 es traducir estos procesos a entidades canónicas y relaciones explícitas, y después contrastarlos con el dominio de bancos antes de congelar el diseño.
