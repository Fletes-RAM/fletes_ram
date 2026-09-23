# FR-6 — Flujo funcional de bancos y finanzas

> Análisis del comportamiento legacy para preservar la operación financiera durante la actualización. **No ejecuta migraciones ni modifica producción.**

## Propósito

El módulo bancario no es un catálogo aislado: funciona como el punto de convergencia de cobros, pagos, gastos, mantenimiento, foráneos, préstamos y movimientos capturados manualmente. Antes de fijar el esquema moderno debemos separar los hechos financieros de los saldos/reportes derivados.

## 1. Cuentas bancarias

`ram_bancos` representa cuentas/medios financieros y conserva banco, número de cuenta, CLABE y observaciones.

El código actual excluye los IDs 2, 4, 6 y 8 en determinadas pantallas de captura de movimientos y préstamos. Esa exclusión es una regla legacy que debe investigarse por significado antes de migrarla como IDs fijos.

**Decisión:** conservar las cuentas, pero reemplazar reglas por ID fijo por una propiedad/rol explícito si el análisis funcional confirma esa necesidad.

## 2. Movimiento financiero

`ram_bancos_movimientos` es el registro principal de ingresos/egresos. Maneja:
- cuenta;
- periodo;
- categoría/subcategoría;
- descripción;
- folio;
- fecha;
- tipo/signo;
- cantidad;
- observaciones.

Además de captura manual, múltiples módulos generan movimientos automáticamente: cobro de facturas, facturas de control vehicular, pagos a proveedores, mantenimiento y foráneos.

**Regla canónica:** el movimiento debe conservar trazabilidad de su origen. La nueva versión no debería depender únicamente de texto como “Pago Factura ...” para saber qué operación lo generó.

## 3. Signo ingreso/egreso

El legacy calcula el efecto financiero como `cantidad * tipo`. Los flujos revisados usan:
- `tipo = 1` para ingreso;
- `tipo = -1` para egreso.

**Decisión:** preservar la semántica económica, pero el modelo moderno podrá expresar dirección/tipo mediante un concepto explícito y validado. Los importes monetarios se modelarán con DECIMAL.

## 4. Préstamos

`ram_bancos_prestamos` mantiene movimientos de préstamo asociados a cuenta, periodo, categoría/subcategoría, usuario, fecha, signo e importe.

En ciertos subtipos de préstamos de salario el código genera adicionalmente un movimiento bancario automático con signo invertido. Actualmente la regla depende de IDs de subcategoría concretos (48, 106, 74, 86, 107).

**Decisión:** conservar el comportamiento si sigue vigente, pero no transportar esos números como regla de negocio. Deben sustituirse por clasificación explícita/configurable.

## 5. Periodos y saldos

El sistema mantiene un periodo bancario activo en `ram_bancos_periodos`. Al cambiarlo:
1. calcula el total de movimientos y préstamos del periodo por banco;
2. obtiene el saldo inicial;
3. guarda el saldo final = saldo inicial + movimientos;
4. marca el saldo anterior como cerrado;
5. crea el saldo inicial del nuevo periodo;
6. actualiza el periodo activo.

`ram_bancos_saldos` funciona, por tanto, como cierre/snapshot por cuenta y periodo.

**Decisión:** conservar cierres históricos y saldo inicial/final cuando sean hechos de cierre, pero distinguirlos de saldos que pueden calcularse. El cambio de periodo deberá ser atómico/transaccional en la versión moderna.

## 6. Categorías y subcategorías

`ram_bancos_categorias` y `ram_bancos_subcategorias` clasifican movimientos, préstamos, presupuestos y otros flujos.

Existe acoplamiento funcional: al crear clientes, el sistema también puede crear subcategorías bancarias. Esto debe revisarse antes de decidir si una subcategoría moderna representa realmente un cliente o si debe existir una relación explícita.

## 7. Vistas y reportes financieros

El baseline contiene siete vistas bancarias:
- `ram_bancos_detalle`
- `ram_bancos_list`
- `ram_bancos_movimientos_sum`
- `ram_bancos_movimientos_sum_det`
- `ram_bancos_presupuesto`
- `ram_bancos_presupuesto_detalle`
- `ram_bancos_reporte_presupuesto`

`ram_bancos_movimientos_sum` combina movimientos y préstamos y suma `cantidad * tipo` por cuenta/periodo.

Estas vistas son evidencia del resultado esperado, pero no se copiarán automáticamente. En el sistema actualizado podrán resolverse como consultas, servicios, reportes o vistas SQL según rendimiento y claridad.

## 8. Modelo conceptual propuesto

```text
CUENTA FINANCIERA
      |
      +---- MOVIMIENTO FINANCIERO <---- COBRO DE FACTURA
      |             ^             <---- PAGO A PROVEEDOR
      |             |             <---- MANTENIMIENTO
      |             |             <---- FORÁNEOS
      |             |             <---- CAPTURA MANUAL
      |             |
      |         ORIGEN TRAZABLE
      |
      +---- PRÉSTAMO / ANTICIPO
      |
      +---- CIERRE DE PERIODO
                 |
          saldo inicial/final

CATEGORÍA
   |
SUBCATEGORÍA
   |
clasificación de movimientos
```

## 9. Qué conservamos y qué corregimos

| Comportamiento legacy | Actualización |
| --- | --- |
| Cuentas bancarias/financieras | Conservar. |
| Movimientos manuales | Conservar con permisos/auditoría. |
| Movimientos automáticos desde otros módulos | Conservar. |
| `tipo = ±1` | Conservar semántica; hacerla explícita. |
| Categoría/subcategoría | Conservar concepto y revisar relaciones. |
| Periodos y cierres | Conservar funcionalidad. |
| Saldo inicial/final histórico | Conservar cuando represente cierre. |
| Sumas derivadas en vistas | Recalcular/query salvo razón para persistir. |
| IDs 2/4/6/8 excluidos | No copiar como números mágicos; identificar significado. |
| IDs 48/106/74/86/107 en préstamos | No copiar como números mágicos; convertir a regla explícita. |
| Texto como única referencia al origen | Añadir trazabilidad estructurada. |
| Eliminación directa de movimientos | Revisar; para información financiera moderna se favorecerá reversión/auditoría sobre borrado destructivo. |

## 10. Consistencia transaccional

Los procesos que cambian un documento y crean su movimiento financiero deben quedar dentro de una sola transacción: o se completan ambos pasos o no se completa ninguno. Laravel moderno soporta transacciones que confirman el conjunto al terminar y revierten los cambios si ocurre una excepción.

Aplicará, entre otros, a:
- cobro de factura;
- cobro de factura de control;
- pago de proveedor;
- movimientos automáticos de préstamos;
- cierre/cambio de periodo.

## 11. Pendientes antes de congelar el modelo

1. Identificar el significado funcional de los bancos IDs 2, 4, 6 y 8.
2. Identificar la semántica de las subcategorías 48, 106, 74, 86 y 107.
3. Revisar presupuesto bancario y sus vistas como función, no solo estructura.
4. Definir estrategia de reversión/corrección de movimientos.
5. Decidir si préstamos/anticipos permanecen como entidad propia o como documento financiero que genera movimientos.
6. Definir cómo se enlazará cada movimiento con su documento origen.

## Resultado

El dominio financiero queda conectado con el bloque anterior: **facturar/pagar no termina en la factura; termina en un hecho financiero trazable**. El esquema moderno deberá preservar ese vínculo y eliminar dependencias de textos e IDs mágicos.
