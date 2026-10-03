# FR-6 - Revision final de aceptacion

## Resultado

FR-6 cumple el objetivo de diseñar el esquema canónico v1 a partir de la base real auditada, sin usar las migraciones legacy como arquitectura destino.

## Evidencia de cumplimiento

- Baseline real reproducible: 49 tablas base y 23 vistas.
- Mapa completo por dominios.
- Tipos y reglas modernas: InnoDB, DECIMAL para dinero, fechas nullable cuando el legado sea inválido.
- Relaciones explícitas diseñadas sin fabricar FK donde la semántica/datos no lo permiten.
- Sentinelas legacy caracterizados y estrategia de transformación documentada.
- Modelo físico canónico v1 definido.
- Matriz de excepciones preparada para FR-7.
- 23 vistas clasificadas y tratamiento moderno definido.
- Anomalías relevantes de inventario, combustible, bitácora, bancos y relaciones preservadas como evidencia.
- Estrategia de rescate: respaldo, migraciones de ensayo, mapeo legacy, conciliación y corte sólo tras validación.
- Producción no modificada.

## No bloqueantes trasladados

- ram_deudas: cuerpo no recuperable; no se inferirá. Su resultado funcional se validará/rediseñará en CxC/reportes.
- Autenticación/credenciales: FR-8.
- Scripts de migración, conciliación fila por fila y tratamiento ejecutable de excepciones: FR-7.
- Reimplementación funcional de reportes/vistas: FR-9.
- Estrategia de corte: FR-10.

## Criterio de salida

El diseño contiene información suficiente para iniciar FR-7 sin modificar la base legacy y sin decidir durante la importación qué significa cada anomalía conocida. Cualquier nueva anomalía descubierta por el migrador deberá registrarse como excepción, no resolverse silenciosamente.

FR-6 puede cerrarse.
