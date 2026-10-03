# FR-7 - Arquitectura del migrador legacy -> moderno

## Objetivo

Construir un proceso repetible, verificable y no destructivo que transforme una copia de la base legacy al esquema moderno. Produccion no es destino de desarrollo ni de pruebas.

## Principio de ejecucion

1. Obtener/restaurar una copia legacy.
2. Crear una base moderna vacia.
3. Crear un migration_run.
4. Migrar por dominios en orden de dependencias.
5. Registrar mapeos legacy -> moderno.
6. Registrar excepciones sin descartarlas silenciosamente.
7. Conciliar conteos, relaciones, importes, saldos y existencias.
8. Marcar el run como passed o failed.
9. Ante correcciones, destruir solamente la base moderna de prueba y repetir desde el mismo origen.

El migrador no corrige la base legacy.

## Orden inicial

1. Catalogos/identidad necesarios para referencias.
2. Clientes, tipos de unidad, unidades y operadores.
3. Rutas, cotizaciones y asignaciones.
4. Combustible.
5. Facturacion/cobranza.
6. Proveedores/CxP.
7. Finanzas.
8. Mantenimiento.
9. Inventarios y llantas.
10. Bitacora.
11. Asistencia/liquidaciones.
12. Foraneos.
13. Conciliacion transversal.

El orden definitivo puede dividirse en fases tecnicas para resolver ciclos, pero no se desactiva integridad para ocultar errores.

## Auditoria

### migration_runs

- id
- uuid
- source_fingerprint
- source_label
- started_at
- finished_at nullable
- status: running, passed, failed, cancelled
- migrator_version
- notes nullable
- metrics JSON nullable

El fingerprint debe permitir demostrar que dos ensayos partieron de la misma fotografia legacy sin almacenar secretos.

### migration_id_map

- id
- migration_run_id
- domain
- source_table
- source_id
- target_table
- target_id
- action: inserted, mapped, transformed
- metadata JSON nullable

Indice/unique por run + source_table + source_id + target_table cuando la cardinalidad lo permita.

### migration_exceptions

- id
- migration_run_id
- domain
- source_table
- source_id nullable
- code
- severity: info, warning, error, blocking
- source_snapshot JSON nullable (sanitizado)
- resolution
- status: detected, transformed, preserved, resolved, blocking
- target_table nullable
- target_id nullable

No se almacenaran credenciales ni identificadores bancarios sensibles en snapshots/logs.

## Repetibilidad e idempotencia

El camino principal de ensayo es reproducible desde una base moderna vacia. Adicionalmente cada run debe impedir duplicar un mismo source_id accidentalmente dentro de la misma ejecucion.

No se usara updateOrCreate indiscriminadamente para ocultar duplicados. Un duplicado inesperado es una excepcion que debe hacerse visible.

## Contrato de un migrador de dominio

Cada bloque debe:

- leer exclusivamente del origen legacy;
- transformar mediante reglas versionadas;
- escribir en destino moderno;
- registrar cada mapeo;
- registrar excepciones;
- producir metricas de entrada/salida;
- poder ejecutarse en modo dry-run cuando sea practico;
- fallar ante una excepcion blocking.

## Conciliacion

Cada dominio entregara un reporte con:
- filas origen;
- filas destino;
- transformadas;
- preservadas como excepcion;
- bloqueantes;
- referencias faltantes;
- totales monetarios cuando aplique;
- saldos/existencias cuando aplique.

El run completo solo puede marcarse passed si las reglas de conciliacion definidas para todos los dominios pasan.

## Seguridad

- nunca apuntar el migrador de desarrollo a produccion;
- conexiones source y target distintas y verificadas;
- target de ensayo debe exigir nombre/flag de entorno permitido;
- sin DELETE/UPDATE sobre source;
- logs sanitizados;
- respaldos fuera del migrador;
- corte real pertenece a FR-10.

## Primer incremento ejecutable

El primer codigo de FR-7 debe implementar:
1. estructura de migration_runs/id_map/exceptions;
2. comando/orquestador de run;
3. guardas source/target;
4. migracion de un catalogo pequeno;
5. conciliacion automatica;
6. prueba que demuestre repetibilidad desde destino vacio.

Solo despues se amplia dominio por dominio.
