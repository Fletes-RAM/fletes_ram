<?php
declare(strict_types=1);

function logbookChecks(): array {
    return [
        'llanta_eje_direccion_der',
        'llanta_eje_inter_der',
        'llanta_eje_matriz_der',
        'llanta_eje_direccion_izq',
        'llanta_eje_inter_izq',
        'llanta_eje_matriz_izq',
        'balata_eje_direccion_der',
        'balata_eje_inter_der',
        'balata_eje_matriz_der',
        'balata_eje_direccion_izq',
        'balata_eje_inter_izq',
        'balata_eje_matriz_izq',
        'filtro_aire',
        'filtro_aceite',
        'filtro_diesel',
        'filtro_agua',
        'filtro_aceite_hidraulico',
        'filtro_diesel_separador_agua',
        'filtro_diesel_separador_condimentos',
        'filtro_adblue',
        'filtro_secador_aire',
        'aceite_motor',
        'aceite_caja_diferencial',
        'aceite_caja',
        'aceite_hidraulico',
        'aceite_liquido_frenos',
        'anticongelante',
    ];
}

function logbookDefinitions(): array {
    return inventoryDefinitions() + [
        'ram_bitacoras' => [
            'target'=>'vehicle_logbook', 'domain'=>'logbook',
            'fields'=>['titulo'=>'title', 'observaciones'=>'notes',
                'llantas_marca'=>'tire_brand', 'reparacion_especial'=>'special_repair'],
            'dates'=>['fecha'=>['occurred_on', true]],
            'money'=>['costo'=>'cost'],
            'decimal_text'=>['kilometraje'=>'odometer'],
            'relations'=>[
                'unidad_id'=>['ram_unidades','vehicle_id',true],
                'proveedor_id'=>['ram_cat_proveedores','supplier_id',true],
            ],
            'extra_columns'=>logbookChecks(),
        ],
    ];
}

function logbookTransform(string $table, array $row, array $parents, array $context): array {
    if ($table !== 'ram_bitacoras') {
        return inventoryTransform($table, $row, $parents, $context);
    }
    if (!preg_match('/^[1-9][0-9]*$/D', (string)$row['id'])) {
        throw new RuntimeException('INVALID_SOURCE_ID');
    }
    [$values, $exceptions] = mappedBillingTransform(logbookDefinitions()[$table], $row, $parents);
    $checks = [];
    foreach (logbookChecks() as $field) {
        $checks[$field] = (int)$row[$field];
        if (!in_array($checks[$field], [0,1], true)) {
            $exceptions[] = ['code'=>'UNKNOWN_LOGBOOK_CHECK', 'field'=>$field];
        }
    }
    $values['legacy_checks'] = json_encode($checks, JSON_THROW_ON_ERROR);
    return [$values, $exceptions];
}
