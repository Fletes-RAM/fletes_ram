# FR-6 — Perfil de datos transversales

> Resultados de consultas de solo lectura sobre `fletes_ram_local`. Producción no fue modificada.

## Foráneos

La categoría financiera ID 13 corresponde a **FORANEOS**. La subcategoría ID 93 corresponde a **Otros**. Existen además subcategorías con nombres de personas, por lo que la clasificación legacy mezcla conceptos genéricos y personas dentro del mismo catálogo financiero.

Datos activos:
- 5 movimientos.
- 1 operador foráneo.
- periodo observado: 2020-06-13 a 2020-06-16.
- tipos/signos observados:
  - Otros / +1: 2 movimientos.
  - Retiros / -1: 3 movimientos.

El total bruto de `monto` no debe interpretarse directamente como flujo firmado porque existen importes negativos incluso bajo `tp=+1`. El modelo moderno no debe mantener simultáneamente signo en `tp` e importe con signo sin una regla inequívoca.

La baja utilización (5 movimientos históricos activos) no autoriza a eliminar el módulo: se conserva conceptualmente hasta validación funcional.

## Bitácoras

- 1,056 eventos.
- 40 unidades.
- fechas: 2019-07-26 a 2026-08-21.
- 0 proveedor_id=0.
- 0 proveedor NULL.

Se detectaron 3 kilometrajes no numéricos. Son observaciones/estados documentales introducidos en un campo de kilometraje:
- un código alfanumérico;
- indicación de lectura no visible;
- indicación de tablero sin funcionamiento.

### Decisión
El dato legacy debe preservarse. El modelo moderno debe separar, como mínimo, lectura numérica de odómetro y nota/estado de lectura, permitiendo NULL cuando no existe una lectura válida. No convertir esos tres valores a números inventados.

## Asistencia

Distribución:
- Asistencia: 8,607.
- Falta: 820.
- Vacaciones: 116.
- total: 9,543.
- 0 referencias a usuarios inexistentes.

La consulta de duplicados usuario+fecha no devolvió filas, por lo que no se observaron duplicados en los datos actuales.

### Decisión
Si el módulo permanece en alcance, el esquema moderno puede imponer unicidad `persona + fecha` después de la migración y conservar los tres estados legacy como valores iniciales. La selección de qué personas participan debe desacoplarse de grupos Sentry y validarse funcionalmente.

## Sueldos

El esquema real confirma:
- `operador_id` (no `user_id`);
- `fecha_inicio`;
- `fecha_fin`.

Datos:
- 4,724 registros.
- 153 operadores.
- periodo: 2019-05-05 a 2026-09-15.
- 0 `operador_id` sin correspondencia en `ram_users`.

La relación `user()` del modelo legacy por `user_id` es inconsistente con la tabla real y no debe copiarse.

### Decisión
El destino conservará la relación real mediante operador/persona y el intervalo de liquidación. Antes de tratarlo como nómina completa se requeriría evidencia adicional; por ahora se modela como periodo de liquidación/pago de servicios.

## Consecuencias canónicas

1. Foráneos: movimiento financiero trazable; eliminar ambigüedad entre `monto` con signo y `tp`.
2. Bitácora: evento de unidad + lectura de odómetro nullable + nota de lectura + conceptos de mantenimiento.
3. Asistencia: persona + fecha + estado, con unicidad por persona/fecha.
4. Sueldos: periodo de liquidación ligado a operador/persona; no copiar la relación errónea `user_id`.
5. Los IDs 13/93 son evidencia legacy, no configuración canónica.
