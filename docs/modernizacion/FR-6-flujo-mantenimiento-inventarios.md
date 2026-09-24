# FR-6 — Flujo funcional de mantenimiento e inventarios

> Análisis del comportamiento legacy. No ejecuta migraciones ni modifica producción.

## Qué parte de Fletes RAM estamos actualizando

Este bloque cubre tres procesos relacionados con las unidades:
1. facturas/notas de mantenimiento y refacciones;
2. inventario de materiales/refacciones;
3. inventario de llantas.

## 1. Mantenimiento / Refacciones

La interfaz denomina este módulo tanto “Mantenimiento de Unidades” como “Refacciones”. `ram_mantenimientos` registra factura, fecha, plazo, cantidad, descuento, unidad y proveedor.

El alta crea una nota/factura vinculada a una unidad. Los registros pendientes se muestran con `status = ''`.

El pago permite seleccionar varias notas, marca cada una como `Pagado`, asigna proveedor y crea un movimiento bancario de egreso con:
- movimiento `Pago Factura <factura>`;
- `tipo = -1`;
- importe capturado como valor de factura;
- observaciones con los tickets/notas incluidos.

### Riesgo legacy

La actualización de las notas y la creación del movimiento bancario no están envueltas en una transacción única. En la versión moderna el pago completo deberá ser atómico y conservar vínculo estructurado entre pago, notas y movimiento financiero.

El valor legacy `proveedor_id=0` ya fue detectado en datos como “Sin Proveedor Seleccionado”; el modelo moderno no creará un proveedor ficticio 0.

## 2. Inventario de materiales/refacciones

El catálogo `ram_inventariosmateriales` mantiene nombre, descripción, precio, existencia y valor.

### Entrada
Al registrar una entrada:
1. se crea `ram_inventariosmaterialesentradas`;
2. el precio actual del material se reemplaza por el precio de la entrada;
3. la existencia aumenta;
4. `valor = existencia * precio`.

### Salida
Al registrar una salida:
1. se crea `ram_inventariosmaterialessalidas`;
2. se vincula la salida con una unidad;
3. la existencia disminuye;
4. el valor vuelve a calcularse como `existencia * precio`.

Por tanto, las entradas/salidas son el historial de movimientos, mientras `existencia` y `valor` en el catálogo funcionan como estado acumulado/cache.

### Riesgos legacy
- registro del movimiento y actualización de existencia no son atómicos;
- el código permite aritmética directa de existencia sin una protección visible contra inconsistencias/concurrencia;
- `valor` es derivable de existencia y precio;
- el precio actual se sustituye por el de la última entrada; no se debe reinterpretar automáticamente como costo promedio;
- ya existe evidencia de al menos un valor negativo anómalo en datos legacy; no se corregirá silenciosamente.

## 3. Inventario de llantas

`ram_llantas` contiene clave, marca, medida, tipo y existencia.

### Entrada
- crea `ram_llantasentradas`;
- incrementa existencia;
- el controlador actual fuerza `precio = 0`.

### Salida
- crea `ram_llantassalidas`;
- relaciona la salida con una unidad;
- disminuye existencia.

El histórico por llanta presenta sus entradas y salidas.

### Decisión importante

Las llantas tienen un flujo casi idéntico al inventario de materiales, pero no se fusionarán automáticamente. Las llantas pueden requerir identidad, posición, vida útil, kilometraje u otras reglas que el legacy actual no representa. FR-6 conservará ambos conceptos separados hasta validar el uso real.

Las claves duplicadas detectadas en el legacy no se convertirán en UNIQUE ni se fusionarán sin evidencia funcional.

## 4. Modelo conceptual

```text
PROVEEDOR
   |
MANTENIMIENTO / NOTA ---- UNIDAD
   |
PAGO
   |
MOVIMIENTO FINANCIERO

MATERIAL / REFACCIÓN
   |---- ENTRADA
   |---- SALIDA --------- UNIDAD
   |
 EXISTENCIA

LLANTA
   |---- ENTRADA
   |---- SALIDA --------- UNIDAD
   |
 EXISTENCIA
```

## 5. Qué conservamos y qué modernizamos

| Legacy | Canonicalización |
| --- | --- |
| Nota/factura de mantenimiento por unidad | Conservar |
| Pago agrupado de varias notas | Conservar |
| Pago -> egreso bancario | Conservar y volver transaccional/trazable |
| proveedor_id=0 | Convertir a relación nullable/estado explícito |
| Entradas/salidas de material | Conservar como historial |
| Existencia acumulada | Conservar comportamiento; definir estrategia consistente |
| Valor = existencia × último precio | Tratar como derivado salvo requisito funcional |
| Precio sustituido por última entrada | Preservar como comportamiento legacy; no llamarlo costo promedio |
| Salida vinculada a unidad | Conservar |
| Entradas/salidas de llantas | Conservar |
| precio=0 en entrada de llanta | Revisar antes de canonizar |
| Clave de llanta duplicable | No imponer UNIQUE todavía |
| Escrituras inventario no atómicas | Modernizar con transacciones |

## 6. Qué notará el usuario

El objetivo no es cambiar la forma de trabajar por capricho: seguirá pudiendo registrar mantenimiento/refacciones, pagar notas y controlar entradas/salidas por unidad. La diferencia deberá estar principalmente debajo: inventarios más consistentes, pagos trazables y menor riesgo de que un movimiento quede registrado a medias.

## Pendientes

1. Perfilar inventarios para detectar existencias negativas y diferencias entre historial y existencia almacenada.
2. Confirmar si `descuento` modifica el importe real del mantenimiento o es solo informativo en algún flujo.
3. Revisar significado real de `plazo` y fecha de vencimiento.
4. Confirmar por qué las entradas de llantas fuerzan precio cero.
5. Revisar si las llantas se administran solo por tipo/cantidad o si el negocio necesita seguimiento individual.
6. Diseñar estrategia de corrección/reversión de movimientos de inventario.
