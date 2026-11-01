# FR-7 migrador — incremento 0.2

Ejecutable PHP 8.4 + PDO MySQL, independiente del Laravel legacy. Sólo copias
locales aisladas. No configura Laragon ni cambia producción.

## Conexiones y modos

Configurar mediante variables de entorno (no guardar contraseñas en Git):

- `FR7_ALLOW_LOCAL_MIGRATION=YES`
- `FR7_SOURCE_HOST=127.0.0.1`, `FR7_SOURCE_PORT`, `FR7_SOURCE_DB`, `FR7_SOURCE_USER`, `FR7_SOURCE_PASS`
- `FR7_TARGET_HOST=127.0.0.1`, `FR7_TARGET_PORT`, `FR7_TARGET_DB`, `FR7_TARGET_USER`, `FR7_TARGET_PASS`

Origen permitido: `fletes_ram_local` o `fr7_source_test`. Destino permitido:
`fr7_[a-z0-9_]+_test`, creado vacío previamente. Usar usuario de origen con
SELECT únicamente. Destino con permisos de DDL/DML limitados a su esquema.
No abrir túneles hacia servidores productivos: loopback por sí solo no demuestra
que un servidor sea una copia local.

1. `php tools/fr7/migrate.php --inspect`: consulta identidades sin escribir.
   Comprobar que ambos endpoints pertenecen al servidor local de ensayo.
2. Fijar `FR7_SOURCE_SERVER_IDENTITY` y `FR7_TARGET_SERVER_IDENTITY` con los hashes
   inspeccionados. Representan hostname, datadir, server_id, puerto y versión
   reportados por el servidor; la base se verifica separadamente. Un cambio de
   configuración/versión exige volver a comprobar la identidad.
3. `php tools/fr7/migrate.php --dry-run`: transforma y calcula huella/excepciones;
   no crea tablas ni escribe en destino.
4. `php tools/fr7/migrate.php --execute`: crea tablas, migra, concilia y audita.

No se admite el modo implícito de la versión 0.1. El origen debe usar InnoDB;
se lee en una transacción REPEATABLE READ, READ ONLY, en UTC. Se conserva el
nombre exactamente, sin recortar espacios. Fechas inválidas se convierten a
NULL y generan excepciones por ID/campo sin copiar contenido sensible. Cualquier
marcador de borrado no nulo conserva el registro inactivo, incluso si su fecha
es inválida; no se reactiva por normalizar una fecha. Porcentaje entero legacy
se representa exactamente como DECIMAL(7,2).

## Garantías y límites

- Identidad real y esquema separados; servidores remotos/DSN arbitrarios se rechazan.
- Bloqueo de ejecución concurrente por destino antes del bootstrap.
- Se rechazan tablas ajenas, triggers y destino con datos/mapeos/excepciones previos.
- Bootstrap ejecutado por sentencia con multi-statements deshabilitado.
- Hash SHA-256 de todas las columnas leídas en orden de ID, con límites por fila.
  Su alcance es **este catálogo**, no toda la fotografía de la BD legacy.
- Conciliación por campo, ID legacy y enlace a ID destino, además de conteos.
- Filas, mapeos, excepciones y estado passed se confirman juntos.
- Un fallo revierte el lote y guarda failed por etapa sin mensajes SQL ni valores.
  Fallos secundarios de rollback/auditoría se anuncian explícitamente.
- Es reproducible desde destinos vacíos; **no** es un upsert ni una reanudación
  idempotente sobre un destino poblado. El script nunca borra ni recrea bases.

`--inspect` y los errores anteriores a crear un run no generan auditoría de BD.
Ante caída abrupta puede quedar un run running: no equivale a passed. Ante fallo
incierto de conexión durante COMMIT se debe inspeccionar la transacción efectiva
antes de reintentar. No se implementa recuperación automática de ese caso.
La tabla `vehicle_types` y las tablas de auditoría conservan el esquema 0.1.

## Pruebas

`php tools/fr7/tests/unit.php`

Integración exclusivamente en un servidor **desechable y vacío**, con PDO MySQL:

```
FR7_TEST_CREATE_FIXTURES=YES
FR7_TEST_PORT=13307
FR7_TEST_USER=<administrador-del-servidor-desechable>
FR7_TEST_PASS=<secreto-local>
php tools/fr7/tests/integration.php
```

Las líneas anteriores describen variables; usar la sintaxis de entorno de tu
shell. La prueba crea `fr7_source_test`, `fr7_a_test`, `fr7_b_test`,
`fr7_failure_test` y el usuario `fr7_fixture_reader`. Rechaza esquemas existentes;
no elimina bases. No utilizar tu instancia habitual para esta prueba.

Evidencia de ejecución y límites: `tests/RESULTS.md`.

## Núcleo operativo 0.3

`php tools/fr7/migrate.php --inspect --core`
`php tools/fr7/migrate.php --dry-run --core`
`php tools/fr7/migrate.php --execute --core`

Ejecutar en un destino nuevo: el perfil core migra juntos clientes, tipos,
unidades y operadores dentro de la misma transacción. No se añade a un destino
que ya tenga el catálogo migrado. Se conservan IDs reales y legacy; se concilian
campos, mapeos y padres resueltos. No hay FK destructivas.

Porcentaje de administración: decimal exacto (14,4), sin floats ni inferencias
de separadores; se conserva además el texto original. Valores ambiguos/no
representables quedan con decimal NULL y excepción DECIMAL_TEXT_PRESERVED.
Los campos documentales y contactos conservan su contenido sin normalizaciones.
Fechas inválidas: NULL más excepción; un borrado inválido sigue inactivo.
Referencias 0 de operador: NULL con excepción; referencias sin padre: NULL,
ID original en columna legacy y excepción histórica. Ninguna fila se descarta.

`operators.legacy_user_id` conserva la identidad del usuario legacy, comprobada
contra `ram_users.id` (sólo se lee esa columna). No migra credenciales ni afirma
que autenticación/roles modernos estén resueltos: pertenecen a FR-8.
Las relaciones de unidad/cliente del operador se conservan como estado actual,
sin inventar un historial de asignaciones. FK físicas adicionales quedan para
la fase de activación de constraints, después de conciliación transversal.

Pruebas nuevas: `php tools/fr7/tests/core-unit.php` y
`php tools/fr7/tests/core-integration.php` (misma configuración sintética;
requiere servidor desechable sin los esquemas enumerados en el test).

## Rutas y combustible 0.5

Los perfiles son acumulativos y siempre parten de otro destino vacío:

- `php tools/fr7/migrate.php --execute --operations`: core + rutas, segmentos,
  parámetros de rendimiento, cotizaciones, asignaciones y gasolineras.
- `php tools/fr7/migrate.php --execute --fuel`: operations + cargas ordinarias
  y especiales. Ambos admiten también `--inspect` y `--dry-run`.

El perfil fuel exige una **copia de origen ya restaurada, aislada y congelada
con tablas InnoDB**. Las tablas de combustible del dump aportado son MyISAM:
la preparación del ensayo restauró su DDL como InnoDB en otra instancia
DESECHABLE, antes de dar acceso SELECT al migrador. No se convierte el motor
en producción ni en el legacy habitual de Laragon. El migrador rechaza MyISAM;
no afirma que REPEATABLE READ proteja tablas no transaccionales.

Rutas: los kilómetros textuales tienen decimal nullable más texto original.
Cotizaciones: se conservan importes históricos, sin volver a ejecutar fórmulas;
se comprueban importes fila por fila y SUM exacto por cada columna monetaria.
DOUBLE legacy se lee como CHAR desde SQL y se transforma sin aritmética float.
Un cambio no revisado en las columnas del origen bloquea los perfiles ampliados.

Las dos tablas de combustible confluyen en fuel_loads, distinguidas por
legacy_source (assignment/special) + legacy_id. ID moderno determinista:
ordinary = 2 * ID legacy; special = 2 * ID legacy + 1. No colisionan IDs iguales
entre tablas; migration_id_map guarda el enlace exacto. La reconciliación y
los totales se separan por contexto, sin mezclar cantidades.

- Gasolinera 0: NULL + is_other_fuel_station=1 + ID original + excepción.
- Asignación/unidad sin padre: NULL + ID original + excepción histórica.
- Carga especial: assignment_id NULL; conserva unidad y usuario legacy.
- Fechas cero/imposibles y fechas de negocio <=1900: NULL, valor original de
  fecha de negocio/pago y excepción. No se infieren desde fotos/timestamps.
- Litros cero: conservar y registrar excepción; nunca dividir entre cero.
- Precio, litros, total, odómetro y rendimiento se conservan; no se recalculan.
- Los cuatro campos de evidencia preservan rutas. No se copian ni se validan
  archivos físicos de upl/. La tabla separada de adjuntos queda pendiente.
- factura_proveedor/dias_credito/fecha_limite_pago se preservan; no se inventa
  todavía una factura/pago moderno ni su movimiento bancario.

Conciliación de destino con cursor no bufferizado y comparación por fila/mapa;
se libera la copia de filas fuente tras transformar y calcular su huella.
La lectura/transformación fuente continúa en memoria: el éxito con este bloque
no garantiza memoria suficiente para migrar toda la BD de una vez.
Un cierre abrupto puede dejar un run running: sólo passed certifica el ensayo.

Pruebas adicionales:
`php tools/fr7/tests/operations-integration.php`
`php tools/fr7/tests/fuel-unit.php`
`php tools/fr7/tests/fuel-integration.php`

Las integraciones operations/fuel incluyen las comprobaciones core; ejecutarlas
cada una en un servidor desechable nuevo. Requieren el baseline versionado.
