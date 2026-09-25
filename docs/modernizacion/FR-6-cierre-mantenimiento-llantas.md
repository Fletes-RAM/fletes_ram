# FR-6 — Cierre de hallazgos: mantenimiento y precio de llantas

> Evidencia combinada del código legacy y consultas de solo lectura sobre `fletes_ram_local`. Producción no fue modificada.

## Default financiero de mantenimiento

Se resolvió la subcategoría legacy ID 20:

- categoría 18: **GASTOS**
- subcategoría 20: **Refacciones**
- banco preseleccionado ID 2: **BANAMEX**

Por tanto, la pantalla legacy de pago de mantenimiento preselecciona conceptualmente **GASTOS / Refacciones** y BANAMEX.

### Decisión canónica

Preservar la conveniencia del default cuando corresponda, pero no mediante IDs numéricos incrustados en JavaScript. La versión moderna deberá resolver la clasificación mediante configuración/identidad estable y permitir cambiar banco/categoría/subcategoría según permisos y reglas funcionales.

## Precio de entradas de llantas

Perfil de `ram_llantasentradas` en la copia local:

- entradas: **72**
- entradas con precio > 0: **0**
- precio mínimo: **0.00**
- precio máximo: **0.00**

Esto coincide con el código vigente:
- la columna `precio` existe;
- el modelo la conserva;
- el campo del formulario está comentado;
- el controlador fuerza `precio = 0`.

### Conclusión

En la evidencia disponible, **precio no es un dato funcional activo del inventario de llantas**. No existe valor histórico distinto de cero que migrar desde las 72 entradas actuales.

### Decisión canónica

No trasladar `precio` como campo obligatorio/funcional de la entrada de llanta en el primer esquema canónico. Durante FR-7 se conservará trazabilidad del dato legacy si se requiere para auditoría, pero no se inventarán costos históricos.

Si posteriormente el negocio requiere costeo de llantas, se diseñará explícitamente como una función nueva y no como supuesta recuperación de comportamiento legacy.

## Estado del bloque

Quedan aclarados:
- `plazo`: días;
- `descuento`: porcentaje que modifica el importe;
- default financiero: BANAMEX / GASTOS / Refacciones;
- precio de entrada de llantas: inactivo en código y 0.00 en las 72 filas actuales.

Pendientes principales del dominio:
1. coherencia entre movimientos de inventario y existencia acumulada;
2. política moderna ante existencias negativas;
3. necesidad de seguimiento individual de llantas vs inventario por tipo/cantidad;
4. estrategia de reversión/corrección de movimientos.
