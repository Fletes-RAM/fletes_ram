# FR-6 — Esquema canónico de base de datos v1

> Documento de diseño consolidado. **No es una migración ejecutable y no modifica producción.**

## Objetivo y garantía de rescate

El Fletes RAM modernizado partirá de la información real existente. La base legacy no se reemplaza por una base vacía: FR-7 migrará los datos históricos al esquema moderno mediante un proceso repetible, conciliable y probado sobre copias antes del corte.

La base real auditada es fuente de verdad del legado. Se preservarán IDs legacy durante la transición y se generarán reportes de conciliación. Ninguna anomalía histórica se “arreglará” inventando información.

## Fuentes de verdad y reglas

1. La base de datos real auditada es la fuente de verdad para la estructura legacy; las migraciones Laravel 4.2 sirven como evidencia histórica, no como representación completa.
2. Los IDs legacy se preservarán durante la transición para permitir conciliación y rollback.
3. Los valores centinela como `0` se convertirán en `NULL` o en conceptos explícitos sólo cuando su semántica esté confirmada.
4. Fechas imposibles o anómalas sin evidencia documental suficiente se migrarán como `NULL`; no se inventarán fechas a partir de `created_at`.
5. El esquema destino usará InnoDB. Las FK se incorporarán después de validar y sanear los datos que las violan.
6. Valores derivados no se persistirán salvo que exista una necesidad funcional demostrada.
7. Views y procedimientos legacy sólo se conservarán si aportan valor frente a consultas/servicios del sistema moderno.

## Hallazgos estructurales que condicionan el diseño

- Inventario auditado: **49 tablas base y 23 vistas**.
- El repositorio contiene 45 migraciones de aplicación y el historial de `ram_migrations` registra esas 45 más 7 migraciones antiguas de paquetes.
- `ram_facturas` existe en la base real pero su creación no está representada por las migraciones conservadas.
- Dos migraciones históricas intentan crear `facturas_controles`.
- Sólo una parte de las columnas `*_id` tiene FK real; por eso no se agregarán relaciones automáticamente.
- Nueve tablas legacy usan MyISAM; el destino será InnoDB.
- El procedimiento `ram_deudas` existe en producción pero su cuerpo no pudo recuperarse con los privilegios actuales. No se reconstruirá por inferencia.

## Bloque 1 — Núcleo operativo

Este primer bloque cubre usuarios, clientes, unidades, operadores, rutas/asignaciones y combustible.

### Identidad y usuarios

La autenticación moderna deberá desacoplarse del esquema Sentry/Laravel 4.2. Durante la migración se conservará el ID legacy del usuario para relacionar registros históricos. La estrategia concreta de credenciales/roles se cerrará con la modernización de aplicación (FR-8); FR-6 no debe copiar ciegamente `groups`, `permissions`, `users_groups` y `throttle` al nuevo dominio.

### Clientes

Entidad canónica `clientes`:

- `id` — conserva el identificador legacy durante la transición.
- `nombre` — procede de `cliente`.
- `nombre_contacto`, `email`, `telefono`, `observaciones`.
- `gasto_administrativo` — normalizar a tipo decimal después de perfilar los valores reales de `gasto_admon`.
- timestamps y baja lógica cuando siga siendo requerida.

No se cambia todavía la semántica de los datos; el cambio de nombres pertenece al esquema destino.

### Tipos de unidad y unidades

`tipos_unidad` conserva el catálogo de `tipos_de_unidades`.

`unidades` conserva:
- ID legacy.
- identificador/nombre de unidad.
- relación con tipo de unidad.
- placas, serie, póliza, aseguradora y observaciones.
- kilometraje inicial.
- vigencia documental cuando exista evidencia válida.
- timestamps/baja lógica.

Los kilometrajes son numéricos. No se introducirán cascadas destructivas hasta revisar el comportamiento requerido para historial operativo.

### Operadores

El legacy creó `operadores.user_id` y posteriormente añadió datos documentales y las columnas `unidad_id`, `cliente_id`, origen, destino y estatus.

En el destino:
- el operador mantiene relación con su identidad/usuario cuando corresponda;
- `unidad_id=0` se interpreta como **sin unidad** y migrará a `NULL`;
- `cliente_id=0` se interpreta como **sin cliente asignado** y migrará a `NULL`;
- no se crearán registros ficticios con ID 0;
- asignaciones actuales y datos históricos deberán separarse si el análisis funcional confirma que esos campos representan estado mutable y no atributos permanentes del operador.

### Rutas

`nombres_rutas` y `detalles_rutas` se modelarán como ruta + segmentos/detalles.

Campos legacy como `total_km` y `km`, almacenados como texto, son candidatos a decimal. La conversión sólo se hará después de perfilar valores no numéricos. `codigos` se considera catálogo geográfico auxiliar y no parte del agregado principal de rutas.

### Asignaciones

La asignación conecta cotización, operador/usuario y unidad. Se preservará el ID legacy.

No se mantendrá una política de borrado en cascada que destruya evidencia operacional. El historial deberá sobrevivir a la baja de catálogos o usuarios.

Antes de activar FK estrictas se conciliará el legado, especialmente los combustibles cuyo `asignacion_id` ya no tiene padre.

### Combustible de asignaciones

`asignaciones_combustibles` contiene fecha, gasolinera, ticket, litros, precio, total, kilometraje, rendimiento y foto.

Reglas canónicas:
- `gasolinera_id=0` significa **Otra** en el sistema legacy; no será una FK a ID 0. Se representará mediante `NULL` más un concepto explícito si hace falta conservar la distinción.
- Los 52 registros auditados sin asignación padre se preservarán como evidencia; no se eliminarán para satisfacer una FK.
- Fechas `0000-00-00`, 1899 u otras anomalías sin prueba suficiente migrarán a `NULL`.
- `litros`, `precio`, `total`, `kilometraje` y `rendimiento` usarán DECIMAL apropiado, no FLOAT/DOUBLE para importes.
- `foto_ticket` conserva sólo la referencia/ruta; los archivos históricos de `upl/` no forman parte del repositorio ni del baseline local.

### Combustible especial

`asignaciones_especiales` se preservará como operación de combustible no ligada al mismo agregado de asignación ordinaria, mientras el análisis funcional determine si puede converger con una única tabla de cargas.

Reglas ya confirmadas:
- `gasolinera_id=0` = **Otra** -> nullable/concepto explícito en destino.
- Las tres fechas anómalas conocidas se preservan como operaciones, pero su fecha de negocio será `NULL` salvo recuperación documental.
- No se usará el nombre del archivo de foto como fecha autoritativa.

### Gasolineras

`gasolineras` permanece como catálogo. Una carga marcada como “Otra” no obliga a fabricar una gasolinera falsa.

## Integridad y estrategia de migración

Las FK del esquema moderno se activarán por etapas:

1. importar IDs y datos legacy;
2. transformar sentinelas y fechas inválidas;
3. registrar excepciones/orfandades en un reporte de conciliación;
4. resolver relaciones recuperables;
5. preservar explícitamente las no recuperables;
6. activar constraints sólo donde los datos resultantes las satisfagan.

La migración debe ser repetible sobre una copia de producción. Ninguna corrección del esquema canónico se aplicará directamente a la base legacy.

## Capas de trabajo

- **Legacy local:** copia intacta `fletes_ram_local`.
- **Baseline generado:** representación reproducible del esquema real, usada para comparación; no define la arquitectura final.
- **Canónico:** migraciones nuevas y limpias del Fletes RAM modernizado.

Un generador de migraciones puede ayudar a producir el baseline desde la copia local, pero su salida se tratará como extracción automática y deberá revisarse; no sustituye este diseño.

## Bloques canónicos ya caracterizados

Además del núcleo operativo inicial, los documentos FR-6 complementarios ya caracterizan y fijan decisiones para:
- cotizaciones, facturación, cobranza y proveedores;
- bancos, periodos, movimientos, préstamos/anticipos y trazabilidad financiera;
- mantenimiento y pagos de notas;
- inventarios de materiales/refacciones;
- llantas;
- bitácora de unidad;
- asistencia;
- sueldos/liquidaciones;
- foráneos;
- rendimiento, costos de combustible y diésel autorizado.

Principios consolidados:
- cobros/pagos y su movimiento financiero deben ser transaccionales;
- inventario se reconstruye desde movimientos y `valor` no será segunda fuente de verdad;
- rendimiento real observado es km/l;
- lectura de odómetro y notas de lectura se separarán;
- IDs mágicos se sustituyen por configuración/relaciones explícitas;
- no se inventará seguimiento individual de llantas ni costos históricos inexistentes;
- `control_id=0`, proveedor/gasolinera 0 y otros sentinelas se normalizan según su semántica confirmada.

## Estrategia de rescate y migración

FR-7 deberá ejecutar de forma repetible:
1. respaldo íntegro de producción;
2. extracción a copia de trabajo;
3. creación del esquema moderno;
4. importación por dominios preservando IDs/mapeos legacy;
5. transformación explícita de sentinelas y fechas;
6. registro de excepciones y huérfanos;
7. conciliación de conteos, importes, saldos y relaciones;
8. activación de constraints;
9. migraciones de ensayo;
10. ensayo de corte con datos recientes;
11. respaldo final y migración definitiva sólo tras validación.

La base legacy permanecerá intacta como respaldo y fuente de conciliación durante el proceso.

## Pendientes residuales de FR-6

- cerrar la representación física inicial de tablas y relaciones modernas;
- registrar la matriz explícita de excepciones que FR-7 deberá conciliar;
- decidir el tratamiento de vistas/reportes legacy;
- dejar autenticación/credenciales para FR-8 sin bloquear identidad.

La conciliación fila por fila y los scripts de transformación pertenecen a FR-7.

## Fuera de alcance de este commit

- Ejecutar migraciones.
- Cambiar datos de producción.
- Corregir registros legacy.
- Instalar paquetes en Laravel 4.2.
- Reconstruir `ram_deudas` por inferencia.
