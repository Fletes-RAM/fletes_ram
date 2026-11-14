<?php
declare(strict_types=1);

function inventoryTables(): array {
    $definitions = [];
    foreach ([
        ['ram_inventariosmateriales', 'inventory_materials', 'inventariomaterial_id', 'material_id',
            ['nombre'=>'name', 'descripcion'=>'description'], ['precio'=>'unit_price', 'valor'=>'legacy_value']],
        ['ram_llantas', 'inventory_tires', 'llanta_id', 'tire_id',
            ['clave'=>'code', 'marca'=>'brand', 'medida'=>'size', 'tipo'=>'type'], []],
    ] as [$source, $target, $legacyKey, $key, $fields, $money]) {
        $definitions[$source] = [
            'target'=>$target, 'domain'=>'inventory',
            'fields'=>$fields + ['existencia'=>'legacy_stock'], 'money'=>$money,
        ];
        $movement = [
            'domain'=>'inventory', 'fields'=>['cantidad'=>'quantity', 'observaciones'=>'notes'],
            'relations'=>[$legacyKey=>[$source, $key, false]],
        ];
        $definitions[$source.'entradas'] = $movement + [
            'target'=>$target.'_receipts', 'money'=>['precio'=>'unit_price'],
        ];
        $movement['relations']['unidad_id'] = ['ram_unidades', 'vehicle_id', true];
        $definitions[$source.'salidas'] = $movement + ['target'=>$target.'_issues'];
    }
    return $definitions;
}

function inventoryDefinitions(): array {
    return maintenanceDefinitions() + inventoryTables();
}

function inventoryTransform(string $table, array $row, array $parents, array $context): array {
    $definition = inventoryTables()[$table] ?? null;
    if ($definition === null) {
        return maintenanceTransform($table, $row, $parents, $context);
    }
    if (!preg_match('/^[1-9][0-9]*$/D', (string)$row['id'])) {
        throw new RuntimeException('INVALID_SOURCE_ID');
    }
    return mappedBillingTransform($definition, $row, $parents);
}

function inventoryStockMetrics(PDO $target): array {
    $metrics = [];
    foreach (['inventory_materials'=>'material_id', 'inventory_tires'=>'tire_id'] as $table=>$key) {
        $sql = "WITH movements AS (
            SELECT $key AS item_id, CAST(quantity AS DECIMAL(30,0)) AS effect FROM {$table}_receipts
            UNION ALL
            SELECT $key, -CAST(quantity AS DECIMAL(30,0)) FROM {$table}_issues
        ), balances AS (
            SELECT item_id, SUM(effect) AS stock FROM movements WHERE item_id IS NOT NULL GROUP BY item_id
        )
        SELECT COUNT(*) AS items,
            COUNT(CASE WHEN legacy_stock <> COALESCE(stock,0) THEN 1 END) AS stock_differences,
            COUNT(CASE WHEN stock < 0 THEN 1 END) AS negative_reconstructed_stock
        FROM $table LEFT JOIN balances ON item_id=id";
        $metrics[$table] = array_map('intval', $target->query($sql)->fetch(PDO::FETCH_ASSOC));
    }
    return $metrics;
}
