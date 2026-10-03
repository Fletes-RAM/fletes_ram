# FR-6 - Tratamiento de vistas legacy

Se inventariaron las 23 vistas del baseline real. En el esquema moderno no se recrearan automaticamente: primero se clasifican por funcion.

## Finanzas - 7

- ram_bancos_detalle
- ram_bancos_list
- ram_bancos_movimientos_sum
- ram_bancos_movimientos_sum_det
- ram_bancos_presupuesto
- ram_bancos_presupuesto_detalle
- ram_bancos_reporte_presupuesto

Destino: consultas/servicios de reporteo financiero y agregaciones sobre movimientos, cuentas y periodos. Conservar una vista SQL solo si una medicion de rendimiento o interoperabilidad lo justifica.

## Operacion - 7

- ram_combustibles
- ram_cotizaciones_list
- ram_estados
- ram_municipios
- ram_operadores_list
- ram_reporte_unidades
- ram_unidades_list

Destino: catalogos/consultas modernas y reportes. Estados/municipios se trataran como catalogos geograficos cuando corresponda; las vistas list/reporte no se copian como tablas.

## Facturacion/proveedores - 4

- ram_facturas_unidades
- ram_facturas_unidades_inicial
- ram_porpagar_facturado
- ram_vista_comprobantes_proveedores

Destino: consultas de facturacion, cuentas por pagar y comprobantes sobre relaciones normalizadas.

## Foraneos - 2

- ram_foraneo_operador_view
- ram_foraneo_view

Destino: saldo/ultimo movimiento calculado desde external_operator_movements. No persistir el saldo como una segunda verdad salvo cache conciliable.

## Reportes/comprobantes - 3

- ram_gastos_unidades
- ram_reporte_unidades_view
- ram_vista_comprobantes_combustible

Destino: consultas/reportes modernos sobre hechos operativos, gastos y evidencias.

## Decision

Las 23 vistas son evidencia funcional, no objetos que deban copiarse 1:1. FR-7 migra las tablas fuente, no filas materializadas de vistas. FR-9 reproducira el resultado funcional mediante consultas/servicios/reportes. Una vista SQL moderna solo se creara si existe una razon concreta de rendimiento, compatibilidad o explotacion externa.

## Validacion

Antes de retirar el legacy, los reportes modernos que sustituyan vistas relevantes se compararan contra resultados de la copia legacy para un conjunto de periodos/casos conocidos.
