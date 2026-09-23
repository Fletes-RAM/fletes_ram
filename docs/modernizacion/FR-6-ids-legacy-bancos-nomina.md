# FR-6 — Resolución de IDs legacy en bancos y nómina

> Evidencia obtenida de la copia local de producción `fletes_ram_local`. Solo lectura; producción no fue modificada.

## Bancos excluidos de determinadas capturas

El código legacy excluye `bancos_id IN (2,4,6,8)` en las pantallas revisadas de captura de movimientos y préstamos.

La copia local identifica:

| ID | Nombre legacy | Cuenta legacy |
| ---: | --- | --- |
| 2 | BANAMEX | 70012768867 |
| 4 | INVERSION BANAMEX | 001234 |
| 6 | TC BANORTE | 4913750002470456 |
| 8 | FONDOS DE INVERSION BNMX | 904601725 |

### Conclusión

Los IDs mezclan instrumentos de distinta naturaleza aparente: cuenta bancaria, inversión/fondo y tarjeta de crédito. **El nombre por sí solo no demuestra la razón exacta de exclusión**, por lo que FR-6 no convertirá la lista `[2,4,6,8]` en una regla canónica todavía.

El modelo moderno deberá poder clasificar explícitamente las cuentas/instrumentos (p. ej. cuenta bancaria, tarjeta, inversión u otro tipo que resulte del análisis) y definir capacidades/uso sin depender de IDs históricos.

## Subcategorías con comportamiento especial en préstamos/salarios

El código de `BancoPrestController::storesalario()` genera un `BancoMov` adicional con signo invertido cuando la subcategoría está en `48,106,74,86,107`.

La copia local identifica:

| ID | Subcategoría | Categoría |
| ---: | --- | --- |
| 48 | IMSS | NOMINA |
| 74 | INFONAVIT | NOMINA |
| 86 | SUBSIDIO | NOMINA |
| 106 | ISR | GASTOS |
| 107 | DESCUENTO | NOMINA |

### Conclusión

La lista no es aleatoria: cuatro conceptos están clasificados como `NOMINA` y uno, `ISR`, como `GASTOS`. El comportamiento especial está vinculado a conceptos de retención/descuento/subsidio dentro del flujo salarial, pero **la evidencia disponible no basta para afirmar una regla fiscal general ni para inferir que cualquier concepto de NOMINA deba comportarse igual**.

### Decisión canónica

No trasladar:

```text
if subcategoria_id in (48,74,86,106,107)
```

En su lugar, durante la migración se conservará la identidad legacy y se modelará una propiedad/regla explícita para indicar qué conceptos salariales generan contrapartida/movimiento financiero automático, una vez validada la semántica completa.

## Estado

Quedan resueltos los nombres detrás de los IDs mágicos. Queda pendiente validar la razón funcional exacta de:
1. exclusión de los cuatro instrumentos financieros;
2. contrapartida automática de los cinco conceptos salariales.

Ninguna de estas reglas se eliminará durante la actualización sin esa validación.
