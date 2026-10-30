# FR-7 — evidencia del endurecimiento 0.2

Fecha: 2026-10-30. Base de código revisada: f77bcec.

## Implementado

Guardas de endpoints/esquemas/identidad, lectura consistente en modo read-only,
simulación sin escrituras, huella de contenido, transformación explícita de
fechas, auditoría sanitizada, conciliación de contenido y mapeos antes del commit,
rechazo de destino poblado y transacción del lote.

## Probado con datos sintéticos

Entorno aislado: PHP 8.4.26, PDO MySQL/mysqlnd, MariaDB 11.8.6 (InnoDB).
No se ejecutó Laravel legacy ni Composer. No se conectó producción ni Laragon.

- Unitarias: 22 comprobaciones.
- Integración: 25 comprobaciones.
- Dos ensayos completos en destinos distintos: 2 filas origen, 2 destino,
  2 mapeos y 2 excepciones de fechas por ensayo; ambos passed.
- Mismo contenido y source_fingerprint en ambos ensayos.
- Usuario origen con SELECT únicamente; contenido origen sin cambios.
- Inspección y simulación sin tablas creadas en destino.
- Identidad incorrecta rechazada sin DDL.
- Destino poblado rechazado sin añadir otro run.
- Error provocado con nombre sintético demasiado largo en la segunda fila:
  rollback de filas y mapeos; run failed con nota de etapa sanitizada.
- Las pruebas unitarias detectan contenido distinto con igual conteo, mapeo
  incorrecto, cambio de huella con iguales IDs y fechas inválidas.

## Pendiente de validación

No probado todavía en MySQL 8.4 de Laragon ni con registros reales de
`fletes_ram_local`. Los resultados anteriores no certifican la migración de la
BD real ni el resto de dominios. La huella cubre sólo ram_tipos_de_unidades.
No se simularon caída abrupta durante COMMIT, fallo del servidor durante rollback
ni contención entre procesos simultáneos.

Para el ensayo real se necesita acceso a una copia local o una exportación del
catálogo. FR-6 permanece cerrado; FR-7 continúa en curso.
