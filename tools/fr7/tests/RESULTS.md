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

## Probado con copia real — catálogo de tipos de unidad

Fecha: 2026-10-30. Código ejecutado: contenido del incremento ebc0e21.
Origen: exportación aportada `fletesram_labaseram_28_10_26.sql`.
Se extrajeron exclusivamente CREATE, INSERT, índice y AUTO_INCREMENT de
`ram_tipos_de_unidades`; no se ejecutó el dump completo ni sus vistas/rutinas.
Importación en una instancia local desechable MariaDB 11.8.6, PHP 8.4.26.
Zona de sesión UTC durante importación y lectura. Origen InnoDB con usuario
SELECT únicamente; usuario destino limitado a los dos esquemas de ensayo.

- Inspección y dry-run correctos; simulación sin tablas creadas en destino.
- Dos destinos vacíos independientes: 11 origen = 11 destino = 11 mapeos.
- Ambos runs `passed`; 0 excepciones de fechas; conciliación de campos e IDs.
- Contenido moderno idéntico entre ensayos; huella del catálogo idéntica.
- Huella del origen antes/después idéntica; espacio final de nombre conservado.
- Run A: `de004d79-999e-4223-b999-e64b5f111095`.
- Run B: `702de2c5-1769-4d6f-833e-8020fba66e82`.
- SHA-256 del contenido origen según algoritmo fr7:v2:
  `98061535ff67dd2070b3bf716285afb45015d4522788457024dba7826418a2e2`.

No se incorporaron filas del respaldo, credenciales ni el dump al repositorio.
No se requirieron cambios en el migrador para completar este ensayo real.

## Pendiente de validación

MySQL 8.4 de Laragon continúa pendiente. La copia real validada cubre solamente
`ram_tipos_de_unidades`; no certifica toda la BD ni los demás dominios.
Este catálogo real no contiene fechas inválidas ni borrados lógicos; esos casos
siguen cubiertos con fixtures sintéticos, no con evidencia real de este dump.
No se simularon caída abrupta durante COMMIT, fallo del servidor durante rollback
ni contención entre procesos simultáneos. FR-6 permanece cerrado; FR-7 en curso.

## Núcleo operativo 0.3 — 2026-11-01

Implementado perfil --core: clients, vehicle_types, vehicles y operators;
IDs preservados, relaciones verificadas, auditoría por dominio, transacción
compartida y huella de las cuatro tablas completas más ram_users.id.

Pruebas sintéticas: 26 comprobaciones unitarias nuevas y 30 de integración.
Además siguen pasando las 22 unitarias y 25 de integración anteriores.
Se prueban IDs dispersos, referencias huérfanas, centinelas, porcentajes
ambiguos, fecha de baja inválida sin reactivación y permiso SELECT del origen.
Un fallo provocado en el último dominio revierte las cuatro tablas, mapeos y
excepciones; run failed con etapa sanitizada. Dos destinos producen lo mismo.

Copia real aportada, PHP 8.4.26 / MariaDB 11.8.6, sesiones UTC:

| Tabla destino | Origen | Destino | Mapeos | Excepciones |
|---|---:|---:|---:|---:|
| clients | 58 | 58 | 58 | 3 |
| vehicle_types | 11 | 11 | 11 | 0 |
| vehicles | 49 | 49 | 49 | 0 |
| operators | 166 | 166 | 166 | 152 |

Dos ensayos passed, con igualdad de contenido y de huella. Dry-run sin DDL.
Excepciones: 3 porcentajes no convertibles conservados como texto, 3 fechas
inválidas de operadores a NULL y 149 referencias 0 normalizadas a NULL.
Sin referencias huérfanas en estas cuatro tablas de esta copia.
Runs: 5d6b8d0f-26d8-4658-ab35-2b371df00426 y
98421f0d-dab9-4e85-9038-3251fb35d101.
Huella core: b7b2108ffb4d7e5b3a8ad180849949dc8c560efba2ced5e0a2b40af1ec89f6e6.
No se incorporan filas reales, credenciales ni dumps al repositorio.

Este perfil no cubre todavía rutas/cotizaciones/asignaciones/combustible,
ni migra usuarios modernos. MySQL 8.4 permanece pendiente de validación.

## Operación y combustible 0.5 — 2026-11-01

Pruebas sintéticas acumuladas: 148 comprobaciones distintas:
22 unitarias catálogo, 26 unitarias core, 11 unitarias combustible,
25 integración catálogo, 30 integración core, 14 adicionales operación,
20 adicionales combustible. Las suites acumulativas repiten core como
preparación: esas repeticiones no se suman a 148.

Se comprueba colisión de igual ID legacy entre ambos tipos de combustible,
separación de mapas/excepciones/totales por contexto, asignación huérfana,
Otros, fecha 1899, litros cero sin división, preservación de total aunque no
sea litros*precio, rutas de fotos y detección de corrupción de contenido/mapa
con igual conteo usando la conciliación con cursor realmente ejecutada.
MyISAM en origen se rechaza antes de DDL. Rollback conjunto continúa probado.

Copia real del dump aportado, restaurada en instancia aislada y desechable.
Las dos tablas MyISAM se crean como InnoDB durante esa restauración previa;
después el migrador sólo tiene SELECT sobre el origen. Usuarios destino
limitados a dos esquemas independientes. PHP 8.4.26 / MariaDB 11.8.6 / UTC.

| Dominio | Origen = destino = mapeos, por ensayo |
|---|---:|
| Clientes | 58 |
| Tipos de unidad | 11 |
| Unidades | 49 |
| Operadores | 166 |
| Rutas | 801 |
| Segmentos | 1,448 |
| Parámetros de rendimiento | 45 |
| Cotizaciones | 2,157 |
| Asignaciones | 1,141 |
| Gasolineras | 27 |
| Cargas ordinarias | 1,326 |
| Cargas especiales | 24,048 |
| **Total** | **31,277** |

Dos ensayos passed con igualdad de todas las filas destino (digest por tabla),
misma huella y SUM exactos iguales a origen para las columnas numéricas de
cotizaciones/cargas. Se verificó la huella de todas las tablas fuente antes y
después: sin cambios. Inspección/dry-run sin tablas creadas en destino.
Runs: b65b2ba8-50c1-4481-a8d7-aecedd5fc906 y
e987dd08-ae9e-4b1d-bbc6-605f28590a9e.
Huella: 6022e88988bc80d0b7979b964bed21892a68cbf0fbfa1b1c050c1822bd322f29.

1,777 excepciones por ensayo:
- 52 cargas ordinarias sin asignación padre, preservadas.
- Gasolinera Otra: 254 ordinarias y 1,305 especiales.
- Fechas inválidas: 6 ordinarias, 3 especiales y 3 operadores.
- Referencias 0 de operadores: 149.
- Texto decimal no convertible: 3 clientes y 2 segmentos, original conservado.

La copia nueva contiene 24,048 cargas especiales y 1,305 Otras; la auditoría
anterior registraba 23,830 y 1,296. Se migra el dump actual completo, sin
recortar para forzar coincidencia con los conteos históricos.

Un intento previo agotó el límite 128 MiB en la conciliación bufferizada; no
se cuenta como passed. Se sustituyó por cursor no bufferizado y se liberaron
filas fuente después de transformarlas. Los dos ensayos finales pasaron con
el límite PHP de 128 MiB. También se corrigió el harness de comparación real
para digerir tablas por cursor en vez de retener dos copias de las cargas.

Pendiente: MySQL 8.4 real, tabla normalizada de adjuntos/archivos físicos,
activación transversal de FK y los dominios posteriores de FR-7.
No se ha certificado equivalencia funcional de FR-9 ni un corte de producción.

## Punto de intervención — MySQL 8.4

Preparados tests/mysql84.php y tests/verify-mysql84.ps1 para la ejecución local
pendiente. PHP lint correcto; guardia probada en MariaDB: rechazo por versión,
con inventario de esquemas idéntico antes/después. El launcher PowerShell está
pendiente de ejecución en Windows. No se contabiliza este preparativo como
pruebas aprobadas en MySQL 8.4 ni se añade al total de 148 controles anteriores.

El verificador ejecutará 64 controles sintéticos de integración sobre nueve
esquemas temporales nuevos, sin usar fletes_ram_local ni el dump real. Esta
comprobación necesita acceso al MySQL 8.4 de Laragon, no disponible desde el
entorno del agente. El ensayo real en MariaDB permanece válido y separado.
