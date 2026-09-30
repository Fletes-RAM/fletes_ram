# FR-6 — Combustible, rendimiento y parámetros operativos

> Análisis del código legacy en la rama FR-6. No modifica producción.

## Rendimiento de combustible

`rendimientos` es un catálogo por tipo de unidad con un valor denominado `rendimiento`. El formulario legacy lo etiqueta “Rendimiento Litros/Kilometro”, mientras que las cargas reales calculan rendimiento como:

`(kilometraje_actual - kilometraje_anterior) / litros`

Esa fórmula produce **kilómetros por litro**, no litros por kilómetro. Por tanto existe una inconsistencia de etiqueta/unidad en el legacy.

### Canonical
Definir explícitamente unidad y semántica. Para preservar el cálculo real observado, el candidato es `km_por_litro`, usando tipo decimal. No perpetuar una etiqueta contradictoria.

## Costo de combustible

`costos_combustibles` es un catálogo editable de combustible + costo. El controlador actual actualiza el costo directamente desde un valor POST.

### Canonical
Mantener un parámetro de precio/costo por tipo de combustible si sigue siendo utilizado por cotizaciones. Usar DECIMAL y validación servidor. Si se necesita histórico de precios, modelarlo explícitamente; no asumir que la tabla legacy conserva historial.

## Diésel autorizado

`diesel_autorizado` configura litros autorizados por:
- tipo de unidad;
- origen;
- destino.

Origen y destino aquí son texto, no FK al catálogo `origenes`.

### Canonical
Conservar la regla operativa de litros autorizados por combinación. Normalizar lugares/rutas solo cuando pueda hacerse sin perder variantes históricas. Evitar duplicar conceptos de ruta si una relación canónica puede expresarlo correctamente.

## Cargas de combustible

Existen dos contextos:
1. combustible ligado a una asignación de ruta;
2. carga especial **sin asignación de ruta**.

Ambos calculan:
- total = litros × precio;
- rendimiento = (km actual - km inicial de unidad) / litros;
- actualizan `unidad.km_inicial` con la nueva lectura;
- almacenan evidencias fotográficas.

La carga especial además exige ticket único y referencia unidad, usuario y gasolinera.

### Riesgos
- creación de carga + actualización del kilometraje de unidad no está envuelta explícitamente en una transacción;
- archivos se escriben antes de completar la persistencia, por lo que una falla puede dejar evidencia sin registro o estado parcial;
- el rendimiento puede dividir entre cero si no se protege litros > 0;
- el nombre `km_inicial` de unidad funciona realmente como última lectura base para el siguiente cálculo;
- eliminar una carga no revierte automáticamente la lectura base de la unidad;
- precio está comentado en la validación de carga especial aunque participa en total;
- `gasolinera_id=0` tiene semántica legacy “Otra” ya identificada.

### Canonical
Modelar cada carga como evento auditable de combustible con contexto opcional de asignación, lectura de odómetro, litros, precio unitario, total y evidencias. La actualización de la lectura vigente de la unidad deberá ser transaccional/derivable. No borrar físicamente eventos financieros/operativos sin estrategia de reversión.

## Catálogo Origen

`origenes` no es el origen general de las rutas. La navegación lo identifica como “Origen Control Vehicular” y se usa en el flujo de control vehicular.

Por separado, `detalles_rutas` almacena origen/destino como texto.

### Canonical
No fusionar automáticamente `origenes` con origen/destino de rutas ni con los textos de `diesel_autorizado`. Son conceptos legacy distintos hasta que los datos demuestren equivalencia.

## Decisiones

1. Resolver la unidad de rendimiento explícitamente; el cálculo real observado es km/l.
2. Litros y precios deben validarse en servidor; precio/total con DECIMAL.
3. Carga de combustible y actualización de odómetro deben quedar consistentes.
4. Mantener carga con y sin asignación como variantes del mismo concepto canónico cuando no se pierda comportamiento.
5. Evidencias fotográficas son adjuntos persistentes; su almacenamiento físico no forma parte del dump de BD.
6. No fusionar catálogos de lugares por similitud nominal.
