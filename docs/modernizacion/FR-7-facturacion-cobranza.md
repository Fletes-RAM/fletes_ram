# FR-7 — Facturación, cobranza histórica y evidencia bancaria (0.6)

## Alcance

Perfil `--billing`, acumulativo sobre el núcleo operativo/rutas, pero separado
del perfil de cargas `--fuel`. Siempre usa otro destino vacío. No es una
actualización de la aplicación Laravel ni un corte de producción.

| Origen | Destino |
|---|---|
| ram_bancos | financial_accounts |
| ram_bancos_categorias | financial_categories |
| ram_bancos_subcategorias | financial_subcategories |
| ram_bancos_movimientos | financial_movements |
| ram_controles_vehiculares | vehicle_controls |
| ram_facturas | invoices (legacy_source=quotation) |
| ram_facturas_controles | invoices (legacy_source=control) |
| Relaciones de factura/control/cotización | invoice_sources |
| Indicador pagada de cada factura | collections (legacy_paid_summary) |
| Firmas bancarias coincidentes | collection_movement_candidates |

Cuentas, categorías y movimientos se incorporan como soporte de la trazabilidad
de cobros. El dominio financiero completo sigue pendiente: préstamos/anticipos,
saldos/cierres, presupuestos, periodos normalizados, reglas/capacidades de las
cuentas y orígenes de los movimientos ajenos a cobranza.

## Identidad y conservación

- Facturas conservan legacy_id y legacy_source. ID moderno: 2*ID para cotización,
  2*ID+1 para control. No se confunden dos facturas con el mismo ID de origen.
- No se impone unicidad sobre folios históricos.
- Subtotal, maniobras, otros, IVA, retención y total se copian como DECIMAL,
  sin recalcular fórmulas ni emitir documentos fiscales nuevos.
- Fecha de pago inválida: NULL, texto original y excepción. Una factura pagada
  sin fecha válida continúa marcada pagada; no se inventa un día.
- Un indicador de pago desconocido conserva su original y normaliza a NULL.
- Controles preservan usuario legacy, origen textual, porcentaje y pagado
  original. Este último no se interpreta como el pago de la factura.
- Cantidad, tarifa, toneladas e IVA textuales conservan también el texto,
  incluso si no se obtiene un decimal exacto sin inferir separadores/unidades.
- Los datos bancarios sensibles sólo se escriben en el destino de ensayo:
  no se incorporan al código, informes ni mensajes de error.

## Orígenes de facturación

Factura de cotización: una relación con su cotización; si el padre falta,
la relación conserva el ID original y FK nullable.

Factura de control:
- control_id distinto de cero aporta una relación directa (evidence_flags=1);
- factura_id del control aporta una relación inversa (evidence_flags=2);
- ambas fuentes coincidentes se combinan en una sola arista (flags=3);
- control_id=0 no crea un control ficticio; se recuperan las aristas inversas;
- si no hay aristas para un marcador agrupado, se registra la excepción;
- si ambas direcciones apuntan a facturas distintas, se conservan ambas y se
  registra el conflicto. No se elige una factura mediante suposiciones.

Los mapas de invoice_sources usan un identificador compuesto de arista:
`ID_factura_legacy:tipo_origen:ID_origen_legacy`. No se presenta ese compuesto
como si fuera un ID de fila original. La metadata documenta sus componentes.
Los mapas normales de facturas y controles siguen usando sus IDs originales.

## Pago declarado y evidencia bancaria

`collections.record_kind=legacy_paid_summary` conserva el estado pagada de
la factura y el importe total declarado. **No crea un ingreso bancario nuevo,
no reconstruye pagos parciales inexistentes ni constituye un segundo libro
que deba sumarse a los movimientos financieros.**

El código legacy no guarda una FK entre factura y movimiento. Se generan
candidatos exclusivamente con la firma exacta observada en sus controladores:
- contexto: `Pago Factura <folio>` o `Pago Factura CV<folio>`;
- folio completo no vacío, sin aproximaciones;
- fecha de pago válida y misma fecha de movimiento;
- mismo importe, movimiento de ingreso y tipo legacy exactamente 1.

Se enlaza sólo si factura y movimiento tienen una correspondencia mutuamente
única. El vínculo `linked` significa **firma legacy única reconstruida**, no
una FK recuperada del origen ni una revisión contable manual. Los candidatos
se conservan para auditoría y las coincidencias ambiguas no se seleccionan.

Estados: linked, ambiguous, missing, invalid_payment_date, empty_reference.
Todos los resúmenes se preservan aunque no pueda demostrarse un vínculo.
Resolver los casos históricos y validar su presentación funcional sigue siendo
trabajo posterior; un run passed no afirma que esos casos estén corregidos.

## Libro bancario

Se conserva el efecto económico `cantidad * tipo`. El importe normalizado es
la magnitud decimal exacta y la dirección refleja ambos signos, incluyendo
cantidad negativa con tipo -1. Cero tiene dirección none. Un signo desconocido
se conserva y se registra sin inventar dirección.

Se concilian campos, mapas y SUM exactos, además del efecto firmado por cuenta
y periodo textual para los signos conocidos. Esto no certifica saldos finales
porque aún no incluye préstamos ni snapshots de cierre.

## Ejecución y memoria

`php tools/fr7/migrate.php --inspect --billing`
`php tools/fr7/migrate.php --dry-run --billing`
`php tools/fr7/migrate.php --execute --billing`

Usa las mismas variables de conexión e identidades verificadas. Exige origen
InnoDB, conexiones separadas, destino vacío sin tablas/triggers/routines/events,
bloqueo por destino y SQL estricto. La fuente permanece en snapshot read-only.

La lectura usa cursor y un plan temporal por tabla con tmpfile. Se mantienen en
memoria IDs de padres y el índice pequeño cotización/cliente, no todas las filas.
Se cierra cada plan al finalizar o fallar. Datos, mapas, excepciones, aristas,
resúmenes y candidatos se confirman juntos. Ante error de lote se revierten y
el run queda failed. El bootstrap DDL queda fuera de esa transacción.

La conciliación de IDs combina igualdad indexable en la collation real de la
columna con comparación binaria exacta; no acepta alias de IDs ni depende de
la collation de conexión. La prueba de 12,000 filas verifica que las lecturas
de índice crezcan linealmente. Los mapas derivados también se verifican por
contenido, no sólo por conteo.

## Validación y límites

Evidencia detallada: tools/fr7/tests/RESULTS.md.
Pruebas: billing-unit.php y mapping-index-integration.php (esta última incluye
las suites de core/operación/billing y exige servidor de fixtures nuevo).

Para la comprobación pendiente de este nuevo bloque en Laragon:
`verify-mysql84.ps1 -Suite billing`. Crea once esquemas temporales nuevos y el
usuario de SELECT de fixtures, ejecuta 111 controles de integración y limpia
sólo esos recursos si estaban ausentes al iniciar. Usa exclusivamente datos
sintéticos; no importa ni cambia fletes_ram_local.

MySQL 8.4 ya aprobó el perfil anterior (68 controles). El SQL y flujo nuevos de
billing requieren su propia ejecución: no se presentan como probados en ese
motor hasta recibir ese resultado. Los ensayos reales de este bloque son
MariaDB 11.8.6/PHP 8.4.26 con el dump aportado del 28/10/2026.

No se reconstruye ram_deudas. Adjuntos físicos, proveedores/CxP, demás dominios,
FK globales, autenticación FR-8 y equivalencia funcional FR-9 siguen pendientes.
