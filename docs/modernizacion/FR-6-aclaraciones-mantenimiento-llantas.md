# FR-6 — Aclaraciones de mantenimiento y llantas

> Evidencia adicional del código legacy. No modifica producción.

## Descuento en mantenimiento

Se confirmó que `descuento` **sí participa en el cálculo del importe** al relacionar notas de mantenimiento con una factura/pago.

Por cada nota:
```
descuento_importe = cantidad * descuento / 100
subtotal = cantidad - descuento_importe
```

El total inicial de la factura/pago es la suma de esos subtotales.

La interfaz permite modificar el porcentaje y recalcula el subtotal/total en cliente. En el diseño moderno el cálculo deberá realizarse/validarse también en servidor; no se confiará en importes calculados únicamente en JavaScript.

## Plazo

`plazo` representa **días**. La pantalla de pendientes calcula el vencimiento como:

```
fecha de la nota + plazo (días)
```

Por tanto, en el esquema canónico debe ser un entero de días (no texto) y la fecha de vencimiento puede derivarse de fecha + plazo, salvo que aparezca evidencia de excepciones manuales.

## Entrada de llantas y precio

La migración legacy creó `llantasentradas.precio` y el modelo todavía admite el campo. Sin embargo:
- el campo Precio está comentado en el formulario de entrada;
- el controlador fuerza `precio = 0` antes de guardar.

Esto demuestra que el precio formó parte del diseño original, pero **el flujo vigente fue desactivado deliberadamente o quedó abandonado**. El repositorio no aporta evidencia suficiente para distinguir cuál de las dos razones ocurrió.

### Decisión

No eliminar el dato del diseño de migración hasta perfilar la BD real y confirmar si existen entradas históricas con precio distinto de cero. Tampoco presentar precio como función activa del sistema actualizado sin esa validación.

## Hallazgo adicional: defaults financieros en mantenimiento

La pantalla legacy de pago de mantenimiento preselecciona mediante JavaScript:
- banco ID 2;
- categoría ID 18;
- subcategoría ID 20.

Esto es otra dependencia por IDs históricos. Banco 2 ya fue identificado como BANAMEX y categoría 18 como GASTOS según evidencia previa; la subcategoría 20 debe identificarse en la copia local antes de convertir el default en una regla moderna.

## Riesgo de cálculo legacy

El JavaScript que recalcula el total utiliza `parseInt` sobre subtotales. Eso puede truncar decimales durante la edición del descuento.

La actualización debe usar aritmética monetaria exacta en servidor con DECIMAL y validación del total.

## Resultado para el usuario

Se preservará el comportamiento visible —plazo en días, descuento por nota, agrupación y pago— pero el sistema moderno calculará y validará importes de manera consistente y sin depender de IDs ocultos o cálculos monetarios del navegador.
