# FR-7 migrador - incremento 0.1

Primer incremento ejecutable e independiente del Laravel legacy. Solo debe ejecutarse en Laragon/copias locales.

## Variables

FR7_ALLOW_LOCAL_MIGRATION=YES
FR7_SOURCE_HOST / PORT / DB / USER / PASS
FR7_TARGET_HOST / PORT / DB / USER / PASS

El destino debe ser una base moderna de prueba vacía. El script rechaza source=target y nombres de destino que parezcan producción.

## Ejecutar

`php tools/fr7/migrate.php`

El incremento crea las tablas de auditoría, migra `ram_tipos_de_unidades` a `vehicle_types`, registra el mapa ID legacy→moderno y concilia conteos.

Para repetir un ensayo completo, recrear únicamente la base destino de prueba y volver a ejecutar. No ejecutar contra producción.

## Criterio de éxito

El run termina PASSED únicamente cuando filas source = filas target = mapeos registrados.
