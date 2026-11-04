# FR-7. Revisión de cierres bancarios

Validación del 4 de noviembre de 2026 sobre la copia fletesram_labaseram_28_10_26.sql.

La suite banking del commit 22add3b pasó las 161 comprobaciones sintéticas en MySQL 8.4.3 de Laragon, según la salida proporcionada por el usuario.

## Hallazgo

Los 10 cierres pendientes contenían 11 movimientos de importe cero con tipos válidos 1 o -1. El transformador conserva esos movimientos con dirección none. La conciliación omitía esa dirección en su CASE y contabilizaba esas filas como signos desconocidos.

La corrección interpreta none como efecto cero únicamente cuando amount es cero. Una dirección desconocida y un registro none con importe distinto de cero continúan pendientes.

## Resultado

La consulta corregida, ejecutada en MariaDB sobre una proyección decimal de movimientos, préstamos y saldos de la copia, produjo 967 cierres cerrados, 967 coincidentes, cero diferencias y cero signos pendientes. También pasaron las comprobaciones de importe cero, none con importe distinto de cero y dirección desconocida con importe cero.

Este ejercicio valida la consulta de conciliación sobre la copia. La suite completa de MySQL 8.4 con esta corrección queda pendiente.

Los saldos históricos se conservan con sus importes registrados.
