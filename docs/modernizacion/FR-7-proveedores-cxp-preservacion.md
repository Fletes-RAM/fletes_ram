# FR-7 — Preservación de proveedores y base para parcialidades (0.7)

## Entrega

El perfil `--payables` acumula billing, las dos fuentes de combustible, catálogo
de proveedores y relaciones históricas ticket/pago. Exige otro destino vacío y
la copia de origen ya congelada en InnoDB. No modifica el Laravel productivo.

- ram_cat_proveedores -> suppliers: todos los IDs, contacto, notas y baja lógica.
- ram_proveedores -> legacy_supplier_payment_items: todas las filas y campos
  originales, sin convertir una fila en un egreso ni eliminar tickets repetidos.
- Referencias a combustible por prefijo exacto a/e, preservando namespace e ID.
  Si falta el combustible, el FK es NULL y queda la referencia original completa.
- Banco/categoría/subcategoría sólo se vinculan si su ID literal tiene padre;
  se conserva además el texto legacy. No se infieren IDs por nombres.
- Importe y fecha conservan su texto; la conversión fallida queda nullable y
  genera excepción. El importe repetido por ticket no se suma como pagos.
- Las filas con un comprobante repetido se conservan y se documentan.

Las gasolineras y el catálogo de proveedores permanecen como roles separados;
no se fusionan personas por nombre o por coincidencia de folios.

## Cola de revisión, no cabeceras confirmadas

Cada relación conserva `reconciliation_status=unresolved_header`. La firma de
estación/folio/fecha/importe y clasificación bancaria genera un candidate_key
SHA-256 para la cola supplier_invoice_candidates; todas sus filas se conservan.
Las firmas se agrupan sólo como candidatas, con estado unverified.
Gasolinera Otra (0) o combustible ausente no prueban identidad de proveedor:
esas filas usan un discriminador por fila para no fusionar proveedores distintos.
Por ello este conteo de candidatos puede diferir del perfil preliminar de 6,742.

El mapa de cada candidato usa `candidate:<hash>`, identificado como derivado;
la trazabilidad a IDs reales reside en cada fila original conservada y su mapa.
Se concilian conteos, contenido, pertenencia a candidatos, referencias resueltas
y mapas. Este paso no certifica que el candidato sea una factura o un pago.

La migración deja vacías supplier_invoices, supplier_invoice_items y
supplier_payments: no inventa cabeceras, abonos ni saldos históricos para forzar
el nuevo modelo. Las anotaciones factura_proveedor/días/fecha límite de cargas
siguen preservadas en fuel_loads, pendientes de conciliación de documentos.
La definición histórica de valor_factura sigue abierta, pero la conservación
sin reinterpretación permite avanzar sin descartes.

## Base implementada para parcialidades

El esquema nuevo admite una factura con varios pagos, cada uno con fecha,
importe, referencia, notas, clave única de solicitud y movimiento bancario
obligatorio/único. Tiene FK con factura y movimiento, restricciones de importe
positivo y FK de proveedor/estación. No hay cascadas que destruyan historial.

`supplierInstallmentBalance` calcula total pagado vigente, saldo y estado
pending/partial/paid con centavos enteros de 64 bits, nunca floats. Rechaza
importes ambiguos, negativos, cero y abonos que exceden el saldo. Se prueban
300+400 sobre 1,000 (saldo 300), liquidación final y precisión de importes grandes.

En base sintética se comprueba la cardinalidad 1:N, exclusión de pagos marcados
revertidos del cálculo, claves de solicitud y movimientos únicos, e importe
positivo. Marcar revertido en esa prueba no implementa su contrapartida bancaria.

**Pendiente FR-9:** interfaz/API de captura, alta de egreso y abono en una misma
transacción, bloqueo de factura para prevenir sobrepago concurrente, respuesta
idempotente y reversión con contrapartida/auditoría. Las restricciones SQL y la
función de saldo son la base, no una funcionalidad ya desplegada ni una garantía
por sí solas de concurrencia/atomicidad entre operaciones de aplicación.

## Ejecución y pruebas

`php tools/fr7/migrate.php --dry-run --payables`
`php tools/fr7/migrate.php --execute --payables`

Se conservan todas las guardas y planes temporales del perfil streaming, con un
único commit transaccional de datos/mapas/excepciones/derivados. Fallar en el
último registro de proveedor revierte también dominios anteriores.

Pruebas nuevas: payables-unit.php y payables-integration.php. La integración
incluye fixtures/core/operación/billing y exige servidor desechable nuevo.
En Laragon: verify-mysql84.ps1 -Suite payables, con trece esquemas temporales
previamente ausentes. No usa fletes_ram_local ni importa el respaldo real.
La compatibilidad MySQL 8.4 de este SQL nuevo queda pendiente de esa ejecución.
