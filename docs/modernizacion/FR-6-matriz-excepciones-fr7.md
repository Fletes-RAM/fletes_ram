# FR-6 - Matriz de excepciones para FR-7

Esta matriz define tratamientos de migracion ya sustentados por la auditoria. No modifica datos legacy.

| Dominio | Caso legacy | Tratamiento destino | Conciliacion |
|---|---|---|---|
| Operadores | unidad_id=0 | NULL | contar transformados |
| Operadores | cliente_id=0 | NULL | contar transformados |
| Combustible | gasolinera_id=0 = Otra | NULL + indicador/concepto explicito | conservar cantidad |
| Combustible | asignacion inexistente | assignment nullable + excepcion de migracion | preservar los 52 registros auditados |
| Fechas | valor imposible/anomalo sin evidencia | NULL + excepcion | contar por tabla |
| Facturacion control | control_id=0 usado en factura multiple | relacion invoice_sources | conciliar factura y controles |
| Mantenimiento | proveedor_id=0 | NULL | contar transformados |
| Inventario | valor almacenado no coincide | preservar evidencia; recalcular estado moderno desde regla canonica | reporte de diferencias |
| Inventario | stock de apertura inferido | registrar saldo/movimiento de apertura cuando sea necesario para reconciliar | existencia final igual |
| Llantas | clave repetida | preservar; no imponer UNIQUE | conteo y movimientos |
| Llantas | precio historico 0 | preservar ausencia de costo; no inventar | conteo |
| Bitacora | kilometraje textual | odometro NULL + nota legacy | preservar 3 casos conocidos |
| Asistencia | persona+fecha | migrar y luego UNIQUE tras validar | conteo/duplicados |
| Sueldos | modelo usa user_id pero tabla operador_id | mapear operador_id real | 0 referencias faltantes |
| Foraneos | monto y tp ambiguos | normalizar direccion/importe conservando original para conciliacion | saldo/movimientos |
| Bancos | IDs magicos | mapear a cuentas/clasificaciones explicitas | saldo por cuenta/periodo |
| Procedimiento | ram_deudas sin cuerpo recuperable | no inferir; validar resultado funcional en CxC | prueba funcional |

## Regla de cierre

Cada excepcion de FR-7 debe quedar en uno de estos estados: transformada por regla confirmada, conciliada manualmente con evidencia, preservada como excepcion historica o bloqueante. Nunca se descarta silenciosamente.
