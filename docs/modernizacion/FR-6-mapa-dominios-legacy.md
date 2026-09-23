# FR-6 — Mapa de dominios del esquema legacy

> Documento de análisis derivado del baseline reproducible. **No es una migración ejecutable y no modifica producción.**

## Objetivo

Clasificar las **49 tablas base y 23 vistas** del baseline real por dominio funcional para que la modernización pueda avanzar por bloques sin perder dependencias ni convertir estructuras históricas en arquitectura nueva.

## Criterios

- **Conservar**: entidad o concepto que seguirá existiendo, aunque cambien nombres, tipos o relaciones.
- **Transformar**: concepto válido cuyo diseño legacy requiere normalización.
- **Revisar**: no se toma todavía una decisión canónica; requiere análisis funcional o de datos.
- **Infraestructura legacy**: estructura ligada al framework/autenticación/historial de migraciones y no asumida como dominio moderno.
- Las vistas se consideran consultas/proyecciones legacy. No se presupone que deban convertirse en vistas SQL modernas.

## Tablas por dominio

### 1. Núcleo operativo, rutas y combustible — 15

| Tabla legacy | Tratamiento FR-6 |
| --- | --- |
| `ram_asignaciones` | Transformar: preservar IDs e historial; evitar cascadas destructivas. |
| `ram_asignaciones_combustibles` | Transformar: InnoDB, DECIMAL, fechas nullable, sentinela gasolinera 0 y huérfanos conciliados explícitamente. |
| `ram_asignaciones_especiales` | Revisar convergencia con combustible ordinario; preservar separada mientras no se confirme. |
| `ram_clientes` | Transformar a entidad canónica clientes. |
| `ram_codigos` | Conservar como catálogo geográfico auxiliar; revisar sustitución futura. |
| `ram_costos_combustibles` | Revisar semántica y relación con combustible antes de diseñar destino. |
| `ram_cotizaciones` | Conservar/transformar; diseño detallado en bloque de cotizaciones/facturación. |
| `ram_detalles_rutas` | Transformar como segmentos/detalles de ruta. |
| `ram_diesel_autorizado` | Transformar: actualmente MyISAM; perfilar tipos y reglas antes de constraints. |
| `ram_gasolineras` | Conservar como catálogo; no crear ID ficticio para “Otra”. |
| `ram_nombres_rutas` | Transformar como entidad ruta. |
| `ram_operadores` | Transformar: sentinelas unidad/cliente 0 -> NULL y revisar separación de estado mutable. |
| `ram_tipos_de_unidades` | Conservar como catálogo de tipos de unidad. |
| `ram_unidades` | Transformar conservando ID legacy y datos documentales/operativos válidos. |
| `ram_origenes` | Revisar relación con rutas/cotizaciones antes de fijar modelo canónico. |

### 2. Facturación, proveedores y comprobantes — 8

| Tabla legacy | Tratamiento FR-6 |
| --- | --- |
| `ram_cat_proveedores` | Revisar convivencia/duplicidad conceptual con `ram_proveedores`. |
| `ram_comprobantes_combustible` | Revisar función frente a cargas y comprobación de proveedor. |
| `ram_comprobantes_gastos` | Conservar concepto; normalizar importes, relaciones y clasificación. |
| `ram_controles_vehiculares` | Revisar como operación facturable/ingreso asociado a unidad. |
| `ram_facturas` | Conservar/transformar; creación ausente en migraciones históricas y baseline real manda. |
| `ram_facturas_controles` | Transformar; `control_id=0` es comportamiento legacy real y no debe convertirse en FK ciega. |
| `ram_facturas_cotizaciones` | Revisar función de enlace/flujo frente a `ram_facturas` y `ram_cotizaciones`. |
| `ram_proveedores` | Revisar: su uso incluye comprobantes; no asumir equivalencia con catálogo de proveedores. |

### 3. Bancos y finanzas — 7

`ram_bancos`, `ram_bancos_categorias`, `ram_bancos_movimientos`, `ram_bancos_periodos`, `ram_bancos_prestamos`, `ram_bancos_saldos` y `ram_bancos_subcategorias`.

**Tratamiento:** conservar el dominio, pero rediseñar tipos monetarios/periodos y revisar relaciones antes de migraciones modernas. Las vistas bancarias se evaluarán como consultas o servicios, no se copiarán automáticamente.

### 4. Mantenimiento, inventarios y llantas — 10

| Tabla legacy | Tratamiento FR-6 |
| --- | --- |
| `ram_bitacoras` | Conservar concepto; revisar estructura extensa de checks y kilometraje textual. |
| `ram_inventariosmateriales` | Transformar a InnoDB; revisar si `valor` debe calcularse en vez de persistirse. |
| `ram_inventariosmaterialesentradas` | Transformar a InnoDB y relaciones verificadas. |
| `ram_inventariosmaterialessalidas` | Transformar a InnoDB y relaciones verificadas. |
| `ram_llantas` | Transformar a InnoDB; no imponer unicidad de `clave` sin regla funcional. |
| `ram_llantasentradas` | Transformar a InnoDB. |
| `ram_llantassalidas` | Transformar a InnoDB. |
| `ram_mantenimientos` | Transformar; proveedor 0 debe modelarse sin FK ficticia y conservar trazabilidad de anomalías. |
| `ram_rendimientos` | Revisar si es dato fuente, configuración o valor derivado. |
| `ram_sueldos` | Revisar alcance funcional y relación con operadores/servicios. |

### 5. Foráneos — 2

`ram_foraneos` y `ram_foraneos_operadores`.

**Tratamiento:** conservar el concepto mientras se documenta su flujo. Las vistas actuales muestran que existe lógica de saldo/último movimiento; no se copiará esa implementación automáticamente.

### 6. Identidad, asistencia e infraestructura legacy — 7

| Tabla legacy | Tratamiento FR-6 |
| --- | --- |
| `ram_users` | Preservar ID legacy; autenticación moderna se desacopla del esquema Sentry/Laravel 4.2. |
| `ram_groups` | Infraestructura de autorización legacy; estrategia moderna pendiente FR-8. |
| `ram_permissions` | Infraestructura de autorización legacy; no copiar ciegamente. |
| `ram_users_groups` | Infraestructura de autorización legacy. |
| `ram_throttle` | Infraestructura Sentry/seguridad legacy; no asumir equivalente directo. |
| `ram_asistencias` | Dominio funcional ligado a usuario; revisar si pertenece al alcance moderno. |
| `ram_migrations` | Metadato técnico legacy; no es entidad del dominio moderno. |

**Total tablas:** 15 + 8 + 7 + 10 + 2 + 7 = **49**.

## Vistas por dominio

### Bancos — 7
- `ram_bancos_detalle`
- `ram_bancos_list`
- `ram_bancos_movimientos_sum`
- `ram_bancos_movimientos_sum_det`
- `ram_bancos_presupuesto`
- `ram_bancos_presupuesto_detalle`
- `ram_bancos_reporte_presupuesto`

### Operación, rutas, unidades y combustible — 7
- `ram_combustibles`
- `ram_cotizaciones_list`
- `ram_estados`
- `ram_municipios`
- `ram_operadores_list`
- `ram_unidades_list`
- `ram_vista_comprobantes_combustible`

### Facturación/proveedores — 4
- `ram_facturas_unidades`
- `ram_facturas_unidades_inicial`
- `ram_porpagar_facturado`
- `ram_vista_comprobantes_proveedores`

### Foráneos — 2
- `ram_foraneo_operador_view`
- `ram_foraneo_view`

### Reportes operativos/financieros — 3
- `ram_gastos_unidades`
- `ram_reporte_unidades`
- `ram_reporte_unidades_view`

**Total vistas:** 7 + 7 + 4 + 2 + 3 = **23**.

## Comparación baseline -> canónico

El baseline confirma que el esquema destino no debe ser una traducción mecánica:

1. **Motores:** nueve tablas operativas usan MyISAM; el destino será InnoDB.
2. **Tipos monetarios y métricas:** existen numerosos `double`; importes y magnitudes que requieran precisión se perfilarán para DECIMAL.
3. **Fechas legacy:** existen campos `NOT NULL` y defaults cero; el destino admitirá NULL cuando el dato de negocio sea desconocido.
4. **Sentinelas:** varios `*_id=0` representan “sin selección/Otra”, no una entidad real.
5. **Integridad:** índices con nombre de FK no implican una FK real. Las constraints se activarán después de conciliación.
6. **Vistas:** las 23 vistas son evidencia de reglas/reportes actuales; cada una deberá clasificarse como query, servicio, reporte o vista SQL moderna.
7. **Autenticación:** tablas Sentry no determinan el modelo de identidad moderno.
8. **Derivados:** saldos, totales, rendimientos y valores almacenados deberán distinguir dato fuente de dato calculable.
9. **Procedimiento faltante:** `ram_deudas` permanece fuera del baseline y no se reconstruye por inferencia.

## Orden de diseño restante

1. Cerrar núcleo operativo ya iniciado.
2. Diseñar cotizaciones, facturación, proveedores y comprobantes.
3. Diseñar bancos/finanzas.
4. Diseñar mantenimiento, inventarios y llantas.
5. Revisar foráneos, asistencia y reportes transversales.
6. Cerrar decisiones pendientes de autenticación, combustible especial y excepciones de conciliación.

Este mapa es inventario y clasificación. Las decisiones de columnas/constraints de los bloques aún no diseñados se documentarán antes de crear migraciones ejecutables.
