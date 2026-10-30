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
