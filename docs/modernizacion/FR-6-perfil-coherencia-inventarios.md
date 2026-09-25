# FR-6 — Perfil de coherencia de inventarios legacy

> Resultados de consultas de solo lectura sobre `fletes_ram_local`. Producción no fue modificada.

## Resumen

### Materiales / refacciones
- 123 artículos.
- 0 existencias actuales negativas.
- Para los 123 artículos, la existencia inicial inferida por `existencia_actual - entradas + salidas` es 0.
- Esto demuestra que, en el estado actual, la existencia almacenada es reconstruible como **entradas - salidas**.
- 2 artículos tienen discrepancia entre `valor` almacenado y `existencia × precio`.
- No hay entradas/salidas huérfanas.
- No hay salidas asociadas a unidades inexistentes.

### Discrepancias de valor
1. ID 7: existencia 7, precio 10.00, valor almacenado 65.00, valor calculado 70.00.
2. ID 90: existencia actual 0, entradas 10, salidas 20 y valor almacenado -16.90, mientras el valor derivado actual es 0.00.

El ID 90 evidencia que históricamente se permitieron más salidas que entradas. La existencia almacenada actual fue llevada a 0, pero el valor negativo quedó como residuo inconsistente. No se corregirá el legacy silenciosamente.

### Llantas
- 62 registros de catálogo.
- 0 existencias actuales negativas.
- En los 62 registros, existencia inicial inferida = 0.
- La existencia actual es reconstruible como **entradas - salidas**.
- No hay entradas/salidas huérfanas.
- No hay salidas asociadas a unidades inexistentes.
- El perfil confirma además que el catálogo no representa necesariamente una llanta física individual: hay registros con existencia mayor que 1 y claves repetidas.

## Consecuencia para el esquema canónico

### Inventario
Los movimientos deben convertirse en la fuente auditable del stock. La existencia puede mantenerse como saldo materializado por rendimiento, pero deberá actualizarse dentro de la misma transacción que el movimiento y ser verificable/reconstruible.

No se trasladará `valor` como una segunda fuente de verdad. Es derivable y el legacy ya demuestra que puede quedar desincronizado.

### Existencias negativas
El legacy contiene evidencia histórica de sobre-salida aunque la existencia actual ya no sea negativa. La política moderna inicial será impedir que una operación normal deje stock negativo, salvo que se defina explícitamente un flujo autorizado de ajuste. La migración conservará el historial tal como existe y documentará anomalías.

### Llantas
El sistema actual funciona como inventario por **tipo/lote/catálogo + cantidad**, no como seguimiento inequívoco de cada neumático físico. FR-6 no inventará serialización individual. Si el negocio desea posteriormente controlar posición, kilometraje, montaje/desmontaje, renovaciones o vida útil, eso será una ampliación funcional explícita.

## Decisiones

1. Movimiento de inventario = hecho auditable.
2. Saldo/existencia = estado reconstruible y, si se materializa, protegido transaccionalmente.
3. `valor` no será fuente independiente de verdad.
4. Cantidades e importes usarán tipos numéricos apropiados; dinero con DECIMAL.
5. No se importarán inconsistencias derivadas como si fueran reglas.
6. No se fusionarán registros duplicados por nombre/clave automáticamente.
7. Las relaciones con unidad se conservarán.
8. Correcciones futuras deberán generar ajuste/reversión trazable en lugar de reescribir silenciosamente el historial.

## Estado

Con esta evidencia queda suficientemente caracterizado el comportamiento de inventarios para el esquema canónico v1. Los detalles de migración y conciliación fila por fila pertenecen a FR-7.
