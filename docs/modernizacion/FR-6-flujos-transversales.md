# FR-6 — Flujos transversales: foráneos, bitácora, asistencia y sueldos

> Análisis del código legacy. No ejecuta migraciones ni modifica producción.

## Foráneos

El módulo mantiene un catálogo separado de operadores foráneos y un historial de movimientos por operador/unidad.

Cada movimiento contiene concepto, tipo, monto, signo (`tp`) y saldo acumulado. Los tipos legacy determinan el signo:
- Pago de Fletes: +1
- Transferencia: -1
- Pago a Proveedores: -1
- Aportaciones: +1
- Retiros: -1
- Otros: +1

A partir del segundo movimiento, el saldo se calcula desde el último registro del operador. Después se crea además un movimiento bancario con folio `MF-<id>` y clasificación financiera hardcodeada (categoría 13/subcategoría 93).

### Riesgos
- saldo persistido en cada movimiento puede desincronizarse;
- creación de movimiento foráneo + movimiento bancario no está envuelta en una transacción;
- categoría/subcategoría financieras dependen de IDs mágicos;
- el primer movimiento sigue una rama distinta y no crea movimiento bancario en ese retorno temprano, diferencia que debe validarse funcionalmente.

### Canonical
Conservar operador foráneo + movimientos, modelar signo/tipo explícitamente y hacer el saldo derivable. La integración financiera deberá ser transaccional y trazable por origen.

## Bitácora de unidad

La bitácora pertenece a una unidad y registra fecha, kilometraje, título, proveedor, costo, observaciones y numerosos indicadores de mantenimiento: llantas por posición/eje, balatas, filtros, aceites, anticongelante y reparación especial.

Es un historial de mantenimiento/inspección distinto del inventario de refacciones.

### Canonical
Conservar el evento de bitácora como entidad histórica. No copiar ciegamente la tabla ancha de booleanos: evaluar un modelo evento + conceptos/checks que permita evolucionar sin agregar una columna por cada nuevo componente. Kilometraje deberá tiparse numéricamente si los datos reales lo permiten. Costo será DECIMAL.

## Asistencia

El módulo actual registra por usuario y fecha uno de tres estados visibles:
- Asistencia
- Falta
- Vacaciones

El controlador actualmente obtiene varios grupos Sentry, pero termina presentando solo `AdminsSueldos` por una asignación posterior. Impide una segunda captura si ya existe cualquier asistencia para la fecha actual.

### Riesgos
- la prevención de duplicados es global por fecha, no una constraint usuario+fecha;
- dependencia directa de grupos Sentry;
- diferencia entre grupos recopilados y grupo finalmente mostrado sugiere comportamiento a validar, no una regla a canonizar.

### Canonical
Si asistencia permanece en alcance, conservar fecha + persona + estado con unicidad por persona/fecha y desacoplarla de Sentry. No asumir todavía qué roles/personas deben aparecer.

## Sueldos

`ram_sueldos` conserva operador y un intervalo `fecha_inicio/fecha_fin`. El flujo de reportes obtiene el sueldo de operador desde las asignaciones/cotizaciones (`sueldo_ope`) y permite guardar periodos pagados/procesados.

El modelo legacy contiene una relación `user()` por `user_id`, aunque su fillable utiliza `operador_id`; esta inconsistencia debe resolverse con el esquema real y el controlador que guarda sueldos antes de definir FK moderna.

### Canonical
Tratar inicialmente `sueldos` como marca/periodo de liquidación de servicios del operador, no como nómina completa, hasta confirmar datos y flujo.

## Pendientes de datos

1. Identificar categoría 13/subcategoría 93 de movimientos foráneos.
2. Perfilar movimientos foráneos y verificar saldo secuencial, incluyendo primeros movimientos.
3. Perfilar bitácoras: volumen, kilometraje no numérico, proveedor 0/null y uso de checks.
4. Perfilar asistencias por tipo, duplicados usuario+fecha y usuarios inexistentes.
5. Verificar columnas reales y uso de `ram_sueldos`, especialmente `operador_id` vs `user_id`.
