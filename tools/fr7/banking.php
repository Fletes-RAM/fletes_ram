<?php
declare(strict_types=1);

function bankingDefinitions(): array {
    $account = ['bancos_id' => ['ram_bancos', 'account_id', false]];
    return payablesDefinitions() + [
        'ram_bancos_periodos' => [
            'target' => 'legacy_bank_period_settings', 'domain' => 'banking',
            'fields' => ['periodo' => 'legacy_period'],
        ],
        'ram_bancos_prestamos' => [
            'target' => 'legacy_bank_loans', 'domain' => 'banking',
            'fields' => ['periodo' => 'legacy_period', 'categoria_id' => 'legacy_category_id',
                'subcategoria_id' => 'legacy_subcategory_reference', 'movimiento' => 'description',
                'folio' => 'reference', 'user_id' => 'legacy_user_id', 'tipo' => 'legacy_direction',
                'observaciones' => 'notes'],
            'relations' => $account, 'dates' => ['fecha' => ['occurred_on', true]],
            'money' => ['cantidad' => 'amount'],
        ],
        'ram_bancos_saldos' => [
            'target' => 'legacy_bank_closings', 'domain' => 'banking',
            'fields' => ['periodo' => 'legacy_period', 'cerrado' => 'legacy_closed_flag'],
            'relations' => $account,
            'money' => ['saldo_inicial' => 'opening_balance', 'saldo_final' => 'closing_balance'],
        ],
    ];
}

function bankingTransform(string $table, array $row, array $parents, array $context): array {
    if (isset(payablesDefinitions()[$table])) {
        return payablesTransform($table, $row, $parents, $context);
    }
    if (!preg_match('/^[1-9][0-9]*$/D', (string)$row['id'])) {
        throw new RuntimeException('INVALID_SOURCE_ID');
    }
    return mappedBillingTransform(bankingDefinitions()[$table], $row, $parents);
}

function bankClosingMetrics(PDO $target): array {
    $sql = <<<'SQL'
        WITH activity AS (
            SELECT legacy_account_id, CAST(legacy_period AS BINARY) AS legacy_period, amount,
                CASE direction WHEN 'inflow' THEN 1 WHEN 'outflow' THEN -1 END AS sign
            FROM financial_movements
            UNION ALL
            SELECT legacy_account_id, CAST(legacy_period AS BINARY) AS legacy_period, amount,
                CASE CAST(legacy_direction AS BINARY) WHEN '1' THEN 1 WHEN '-1' THEN -1 END
            FROM legacy_bank_loans
        ), totals AS (
            SELECT legacy_account_id, CAST(legacy_period AS BINARY) AS period_key,
                SUM(amount * sign) AS net, COUNT(*) - COUNT(sign) AS unknown_signs
            FROM activity
            GROUP BY legacy_account_id, CAST(legacy_period AS BINARY)
        )
        SELECT COUNT(*) AS closed_rows,
            COUNT(CASE WHEN COALESCE(t.unknown_signs, 0) > 0 THEN 1 END) AS unknown_direction,
            COUNT(CASE WHEN COALESCE(t.unknown_signs, 0) = 0
                AND c.closing_balance = c.opening_balance + COALESCE(t.net, 0) THEN 1 END) AS matched,
            COUNT(CASE WHEN COALESCE(t.unknown_signs, 0) = 0
                AND c.closing_balance <> c.opening_balance + COALESCE(t.net, 0) THEN 1 END) AS different
        FROM legacy_bank_closings c
        LEFT JOIN totals t ON t.legacy_account_id = c.legacy_account_id
            AND t.period_key = CAST(c.legacy_period AS BINARY)
        WHERE c.legacy_closed_flag = 1
        SQL;
    return array_map('intval', $target->query($sql)->fetch(PDO::FETCH_ASSOC));
}
