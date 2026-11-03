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


## Reintento MySQL 8.4.3 — 2026-11-03

Ejecución aportada desde Laragon/Windows sobre 70ba7e0: engine 8.4.3,
fallo stage=reconciliation en la primera migración de fixtures core. No se
cuenta como prueba aprobada. CREATED_FIXTURES_CLEANED=YES confirmado.
La salida previa ocultaba el SQLSTATE, código del driver y dominio afectado;
no permite afirmar por sí sola una causa exacta.

Ajuste de la unión ID/mapa a comparación binaria de dígitos canónicos para
eliminar dependencia de collations de conexión/servidor. Se añade diagnóstico
por tabla y motivo/campo de divergencia, o SQLSTATE/código numérico del driver,
sin imprimir valores de fila ni mensajes SQL. El launcher PowerShell usa
mensajes ASCII para evitar mojibake en Windows PowerShell 5.1/Cmder.

Regresión local: integración core 30 + operación 14 + combustible 24 = 68
comprobaciones aprobadas en MariaDB 11.8.6. Cuatro controles nuevos cubren
collations diferentes, diagnóstico sin valores, rechazo de ID con ceros a la
izquierda y reutilización del cursor tras una divergencia. Total acumulado:
152 controles distintos, manteniendo las comprobaciones anteriores.
El reintento MySQL 8.4 sigue pendiente; esta corrección no se presenta como
validada en el motor de Laragon hasta recibir una nueva ejecución.


## MySQL 8.4.3 / Laragon — validación sintética aprobada (2026-11-03)

Ejecución aportada por el usuario desde Cmder/Windows, PHP 8.4.12, servidor
MySQL 8.4.3 local. Commit ejecutado: 9e51c11fd2c7bd8f38792b909ae2d6dc91c57dc9.

- CORE INTEGRATION PASSED: 30 checks.
- OPERATIONS INTEGRATION PASSED: 14 additional checks.
- FUEL INTEGRATION PASSED: 24 additional checks.
- CREATED_FIXTURES_CLEANED=YES.
- MYSQL84_VERIFICATION=PASSED; synthetic only; 68 integration checks.

Los mensajes FAILED observados en target_preflight, migration (SQLSTATE 22001,
driver 1406) y preflight son fallos deliberados comprobados por las pruebas:
destino poblado, inserción demasiado larga con rollback y origen MyISAM.
No son fallos del resultado global. No se necesita repetir estas pruebas
mientras no cambie el código relevante o aparezca otra causa de regresión.

La comparación binaria permite completar este ensayo; la salida original no
conservaba el error SQL y por ello no demuestra por sí sola su causa exacta.
Esta evidencia sustituye la condición pendiente del reintento sintético MySQL
de la sección anterior. No certifica una migración con datos reales en MySQL:
los dos ensayos con la copia real siguen siendo los ejecutados en MariaDB.
Facturación/cobranza, demás dominios, adjuntos físicos y constraints globales
continúan pendientes. FR-7 sigue En curso; producción no fue modificada.


## Facturación y cobranza 0.6 — 2026-11-03

Implementado --billing: núcleo/rutas, soporte bancario, controles vehiculares,
facturas de cotización/control, invoice_sources, resúmenes de pago declarado y
candidatos bancarios. Conservación de IDs, lectura consistente SELECT, planes
temporales por cursor y transacción de todo el lote. No es una aplicación
moderna ni un corte productivo; no crea ingresos bancarios adicionales.

Nuevas pruebas: 15 unitarias + 65 integración billing + 2 de índice = 82.
Total histórico distinto: 234 (152 previas + 82 nuevas). Regresiones catálogo,
core, operación y combustible siguen pasando. El test de escala concilia 12,000
filas con 12,000 lecturas de índice, sin cronometrar tiempos frágiles.
Se cubren dos namespaces de factura con mismo ID, agrupación con control_id=0,
ambas aristas coincidentes/contradictorias, origen huérfano, pago sin fecha,
folio repetido, varios movimientos para una factura y un movimiento candidato
a varias facturas. Corrupción de un mapa derivado con igual conteo es detectada.
Un fallo en el último dominio revierte todas las filas/mapas/excepciones;
se conserva el run failed. Columna fuente no revisada se rechaza antes de DDL.

Ensayos reales, dump aportado 28/10/2026, PHP 8.4.26 / MariaDB 11.8.6 / UTC:
dos destinos vacíos con igualdad de contenido/digests de todas las tablas de
negocio, candidatos y relaciones; misma huella; origen completo del perfil
sin cambios antes/después. Planes y conciliación no retienen todas las filas
en memoria; ambos ensayos pasaron con memory_limit de PHP de 128 MiB.

| Destino | Origen = destino = mapas directos |
|---|---:|
| clients | 58 |
| vehicle_types | 11 |
| vehicles | 49 |
| operators | 166 |
| routes | 801 |
| route_segments | 1,448 |
| fuel_efficiency_parameters | 45 |
| quotations | 2,157 |
| assignments | 1,141 |
| fuel_stations | 27 |
| financial_accounts | 11 |
| financial_categories | 13 |
| financial_subcategories | 112 |
| financial_movements | 98,281 |
| vehicle_controls | 64,684 |
| invoices.quotation | 1,533 |
| invoices.control | 47,560 |
| **Total de filas fuente migradas** | **218,097** |

El perfil billing no vuelve a migrar las cargas de --fuel en estos destinos.
Los IDs de ram_users se leen únicamente como contexto, sin migrar credenciales.

Derivados: 61,865 relaciones invoice_sources, 48,948 resúmenes de pago y 46,537
aristas candidatas de movimiento bancario. Son derivados explícitos, no se
suman como filas fuente. Mapas totales: 328,910 (directos + aristas + resúmenes).
Se verificó además en modo read-only el contenido de los mapas derivados en
ambos destinos reales después de los ensayos, no sólo su número.

Estado de los resúmenes:
- 46,350 linked: firma legacy exacta y mutuamente única reconstruida.
- 126 ambiguous: sin elección automática de movimiento.
- 2 invalid_payment_date: estado pagada preservado, fecha no inventada.
- 2,470 missing: sin coincidencia bancaria bajo la regla conservadora.

Los linked no equivalen a recuperar una FK inexistente ni a revisión contable.
Se conserva el libro bancario por separado; los resúmenes no generan ni duplican
movimientos. Se conciliaron importes por fila/SUM y 590 grupos de efecto firmado
por cuenta/periodo, sin afirmar conciliación de préstamos/saldos de cierre.

Excepciones totales por ensayo: 14,299 eventos (una fila puede generar varios).
Entre ellas: 11,380 valores decimales textuales de controles conservados sin
inferencia; 13 referencias de control sin factura padre; 141 marcadores de
factura agrupada sin fuentes recuperables; 1 control con direcciones de factura
contradictorias; los cobros ambiguos/faltantes y las anomalías previas del núcleo.
Estos casos se preservan; passed certifica esa migración, no su resolución.

Runs passed:
- 23ffa309-3e0b-471b-aadf-49934964ba73.
- 8f31c6db-d90f-4405-9aa3-ee20fc63da73.
Huella de las tablas completas del perfil más ram_users.id:
277e4dae9ce077dc18a4e5a59095986b7b8619b2425b4caa128cbb78afb7c870.

Un intento de prueba previo se interrumpió por una unión de mapas sin uso del
índice; no se cuenta como passed. Se ajustó la igualdad indexable a la collation
real de la columna y se mantuvo el filtro binario exacto, con la regresión de
12,000 filas mencionada arriba. La preparación del servidor aislado se corrigió
para permitir consultar metadata de eventos sin ejecutar el scheduler.

Pendiente de intervención: ejecutar el nuevo --billing en MySQL 8.4 de Laragon.
El launcher -Suite billing prepara once esquemas temporales nuevos y ejecuta
111 comprobaciones sintéticas de integración (core 30 + operación 14 + billing
65 + índice 2). La aprobación MySQL anterior de 68 controles sigue siendo
evidencia del perfil anterior; no se extiende al SQL nuevo de billing.

Proveedores/CxP, préstamos, cierres/presupuestos, inventario, otros dominios,
adjuntos, constraints globales, FR-8/FR-9 y ram_deudas siguen pendientes.
Producción y rama master no se modificaron. FR-7 continúa En curso.


## MySQL 8.4.3 / Laragon — billing aprobado (2026-11-03)

Evidencia aportada desde Cmder con PHP 8.4.12, commit 300504c:
CORE 30 + OPERATIONS 14 + BILLING 65 + MAPPING INDEX 2 = 111 controles
sintéticos de integración aprobados. Escala: 12,000 filas, 12,001 lecturas de
índice (dentro del límite lineal de la prueba). CREATED_FIXTURES_CLEANED=YES.
MYSQL84_VERIFICATION=PASSED; suite=billing; synthetic only.
Los FAILED de destino poblado, inserción larga (22001/1406) y cambio de esquema
son escenarios negativos esperados. No se requiere repetir este bloque sin
cambios relevantes. Queda cerrada la compatibilidad sintética MySQL pendiente
arriba; los ensayos con datos reales de billing siguen siendo MariaDB.
FR-7 continúa En curso. Siguiente bloque: proveedores y CxP.


## Proveedores/CxP — perfil previo al siguiente incremento (2026-11-03)

Copia aislada del dump aportado: 796 proveedores; 25,748 relaciones ticket/pago;
322 referencias sin combustible padre; 93 claves de ticket repetidas.
No hubo referencias fuera del formato a-ID/e-ID ni importes fuera de la
gramática decimal básica (la escala/rango todavía no se certifica).
6,742 firmas candidatas de captura; 2 abarcan varios días de created_at.
50 combinaciones gasolinera/folio tienen importes distintos y 31 fechas
declaradas distintas. Esto no demuestra por sí mismo pagos parciales.
El controlador repite valor_factura por ticket y genera un egreso por captura:
sumarlo por fila duplicaría importes. La relación gasolinera/catálogo no tiene
una identidad fiscal común demostrada; no se fusionan personas por nombre.
Perfil y reglas: docs/modernizacion/FR-7-proveedores-perfil-migracion.md.
No se presenta este perfil como un migrador CxP ejecutado.
Se necesita confirmar si valor_factura podía ser abono parcial o siempre
total liquidado, antes de cerrar la transformación cabecera/pago/saldo.
Las anomalías y todas las filas se conservan; producción permanece intacta.

## Proveedores 0.7 — 2026-11-03

Dos ensayos MariaDB 11.8.6 conciliaron 270,015 registros por destino, con contenido idéntico y origen intacto. Se preservaron 796 proveedores y 25,748 relaciones históricas. Quedaron 7,632 candidatos sin verificar, 322 referencias sin combustible y 263 filas con tickets repetidos. Se crearon cero cabeceras o abonos por inferencia.

Pasaron 16 pruebas unitarias y 47 comprobaciones nuevas de integración. La suite payables de 156 comprobaciones queda pendiente en MySQL 8.4. Captura y contabilización corresponden a FR-9.

Runs 195b11b8-074a-4af4-b22b-1378fa1a32c4 y 30576e99-21da-49a0-abcc-14fe54db76ef. Huella 80daf63d3722e5ac435e935594dcf67191ecc3d4725e43d77e9075fc2160b472.

## MySQL 8.4.3 en Laragon — payables aprobado

Salida aportada por el usuario para el commit edf3050. Pasaron 156 comprobaciones sintéticas de integración, incluidas 47 de proveedores. El escenario de truncamiento en legacy_supplier_payment_items (22001/1406) comprobó el rollback. La limpieza de los datos de prueba terminó correctamente. Los ensayos con 270,015 registros por destino corresponden a MariaDB.
