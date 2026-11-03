# FR-7 — Proveedores/CxP: perfil y decisión pendiente

Fecha: 2026-11-03. Fuente: copia del dump aportado del 28/10/2026,
restaurada en otro entorno desechable. No modifica producción ni los destinos
ya conciliados. Este documento es perfil y reglas de rescate; no afirma que
el módulo de proveedores ya tenga un migrador canónico completo.

## Evidencia funcional

ProveedorController::store() crea una fila de ram_proveedores por cada ticket
seleccionado y repite en cada una factura, fecha, valor_factura y clasificación
bancaria. Después del bucle crea un único BancoMov de egreso por valor_factura.
Por tanto, sumar valor_factura de cada fila sobrecuenta el importe de una captura
con varios tickets. La fila legacy es evidencia de ticket dentro de una captura,
no una cabecera inequívoca de factura ni un pago independiente por fila.

El catálogo real es ram_cat_proveedores. Los tickets de combustible se vinculan
con gasolineras. No se encontró una FK que permita fusionar automáticamente
una gasolinera con una persona del catálogo por identidad fiscal.

## Perfil verificado de la copia real

| Comprobación | Resultado |
|---|---:|
| Catálogo ram_cat_proveedores | 796 filas |
| Relaciones ticket/pago ram_proveedores | 25,748 filas |
| Referencias de combustible sin padre recuperable | 322 |
| Referencias fuera de a-ID / e-ID canónico | 0 |
| valor_factura no numérico según gramática decimal básica | 0 |
| Referencias de ticket presentes en más de una fila | 93 claves distintas |
| Firmas exactas candidatas de captura | 6,742 |
| Firmas exactas cuyos created_at abarcan varios días | 2 |
| Combinaciones gasolinera/folio con varios importes | 50 |
| Combinaciones gasolinera/folio con varias fechas declaradas | 31 |

Una firma candidata usa gasolinera recuperada, factura, fecha, banco, categoría,
subcategoría y texto original del importe. Su conteo NO se presenta como conteo
de facturas ni de pagos confirmados: esa equivalencia aún no se ha demostrado.
Tampoco se afirma que todos los importes numéricos caben en un decimal destino
sin revisar escala/rango; el perfil sólo verificó la gramática básica.

Dos ejemplos de firmas repetidas en fechas de captura distintas:
- filas 12417 / 12432: capturas 2023-01-27 y 2023-02-08;
- filas 18104 / 18323: capturas 2024-09-12 y 2024-10-09.

El año/folio puede ser reutilizado; gasolinera 0 significa Otra y no demuestra
que distintas capturas pertenezcan a una misma entidad fiscal. Estos resultados
no prueban por sí mismos pagos parciales ni autorizan fusionar documentos.

## Reglas que pueden aplicarse sin nuevas suposiciones

1. Conservar los 796 proveedores y los 25,748 registros de relación, con mapas
   por ID y timestamps/baja lógica según las reglas ya aprobadas de FR-7.
2. No borrar ni deduplicar automáticamente las 93 referencias repetidas.
3. Interpretar únicamente el prefijo explícito a/e para buscar combustible;
   conservar la referencia original y registrar excepción si falta el padre.
4. No fabricar los 322 comprobantes ausentes para satisfacer FK.
5. No sumar valor_factura por ticket como total de egresos.
6. No fabricar un movimiento bancario por fila: el código genera uno por captura.
7. No fusionar gasolineras y catálogo de proveedores por parecido de nombres.
8. No consolidar las 6,742 firmas candidatas como facturas/pagos confirmados.

## Decisión funcional necesaria

Confirmar si, en este módulo, valor_factura siempre representaba el total de
una factura liquidada en una sola captura, o si también se registraban abonos
parciales a una misma factura. La respuesta condiciona cómo distinguir cabecera,
importe total, importes pagados y saldo; no cambia la obligación de rescatar
íntegramente las filas originales ni resuelve automáticamente cada anomalía.

Hasta contar con esa definición, no se publicará como definitiva una regla que
convierta los importes diferentes bajo un mismo folio en facturas separadas,
abonos o duplicados. La consulta al usuario es de semántica de negocio, no una
solicitud de permiso para tocar producción.
