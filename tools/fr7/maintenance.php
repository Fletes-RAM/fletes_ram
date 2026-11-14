<?php
declare(strict_types=1);

function maintenanceDefinitions(): array {
    return bankingDefinitions() + [
        'ram_mantenimientos' => [
            'target' => 'maintenance_notes',
            'domain' => 'maintenance',
            'fields' => [
                'factura' => 'legacy_invoice_reference',
                'plazo' => 'legacy_term_days',
                'descuento' => 'legacy_discount',
                'status' => 'legacy_status',
            ],
            'dates' => ['fecha' => ['issued_on', true]],
            'money' => ['cantidad' => 'amount'],
            'relations' => [
                'unidad_id' => ['ram_unidades', 'vehicle_id', true],
                'proveedor_id' => ['ram_cat_proveedores', 'supplier_id', true],
            ],
        ],
    ];
}

function maintenanceTransform(string $table, array $row, array $parents, array $context): array {
    if ($table !== 'ram_mantenimientos') {
        return bankingTransform($table, $row, $parents, $context);
    }
    if (!preg_match('/^[1-9][0-9]*$/D', (string)$row['id'])) {
        throw new RuntimeException('INVALID_SOURCE_ID');
    }
    [$values, $exceptions] = mappedBillingTransform(maintenanceDefinitions()[$table], $row, $parents);
    $values['payment_status'] = match ($row['status']) {
        '' => 'pending',
        'Pagado' => 'paid',
        default => null,
    };
    if ($values['payment_status'] === null) {
        $exceptions[] = ['code' => 'UNKNOWN_MAINTENANCE_STATUS', 'field' => 'status'];
    }
    return [$values, $exceptions];
}
