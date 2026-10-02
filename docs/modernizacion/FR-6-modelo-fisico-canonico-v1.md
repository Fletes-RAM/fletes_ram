# FR-6 - Modelo fisico canonico v1

Documento de diseno. No ejecuta migraciones ni modifica produccion.

## Dominios y tablas destino

Identidad: users, roles, permissions y relaciones de autorizacion.

Operacion: clients, vehicle_types, vehicles, operators, routes, route_segments, quotations y assignments.

Combustible: fuel_stations, fuel_loads, fuel_load_attachments, fuel_efficiency_parameters, fuel_cost_parameters y authorized_diesel_rules. fuel_loads admite assignment nullable para cargas especiales y registros historicos cuyo padre legacy ya no existe.

Facturacion: invoices, invoice_sources y collections. invoice_sources sustituye el uso de control_id=0 para facturas multi-control.

Proveedores: suppliers, supplier_invoices, supplier_invoice_items y supplier_payments.

Finanzas: financial_accounts, financial_categories, financial_subcategories, financial_periods, financial_movements, financial_movement_origins, loans_advances y financial_period_balances. Los movimientos tendran direccion e importe inequívocos y origen estructurado.

Mantenimiento: maintenance_notes, maintenance_payments y maintenance_payment_items.

Inventario: inventory_items e inventory_movements. Los movimientos son la fuente auditable de existencia.

Llantas: tire_catalog_items y tire_movements. No se inventa serializacion individual en v1.

Bitacora: vehicle_log_entries y vehicle_log_checks. El odometro numerico es nullable y la nota de lectura se conserva por separado.

Personal: attendance_records y operator_settlement_periods.

Foraneos: external_operators y external_operator_movements.

## Auditoria de FR-7

migration_runs identifica cada ensayo o corte.
migration_id_map relaciona origen legacy con registro moderno.
migration_exceptions registra anomalias, tratamiento y estado.

Esto permite conciliar la migracion fila por fila sin alterar la base legacy.

## Relaciones criticas

client -> quotation -> assignment -> fuel_load
quotation/control -> invoice -> collection -> financial_movement
supplier -> supplier_invoice -> supplier_payment -> financial_movement
vehicle -> maintenance / fuel / inventory / bitacora
financial_movement -> origen de negocio

## Reglas

- InnoDB.
- Dinero DECIMAL.
- IDs legacy trazables.
- Fechas invalidas sin evidencia se migran a NULL.
- Sentinelas 0 se transforman solo con semantica confirmada.
- No cascadas destructivas sobre hechos historicos.
- FK se activan despues de conciliacion.
- Ninguna fila historica desaparece solo porque el destino sea mas estricto.

## Aplazado

Autenticacion concreta: FR-8.
Individualizacion de llantas: fuera de equivalencia v1.
Historico nuevo de precios: mejora futura si se aprueba.
ram_deudas: no se reconstruye sin evidencia.
