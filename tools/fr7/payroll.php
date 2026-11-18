<?php
declare(strict_types=1);

function payrollDefinitions(): array {
    return logbookDefinitions() + [
        'ram_asistencias'=>[
            'target'=>'legacy_attendance', 'domain'=>'payroll',
            'fields'=>['user_id'=>'legacy_user_id','tipo'=>'legacy_type'],
            'dates'=>['asistencia'=>['attendance_on',true]],
        ],
        'ram_sueldos'=>[
            'target'=>'legacy_settlement_periods', 'domain'=>'payroll',
            'fields'=>['operador_id'=>'legacy_user_id'],
            'dates'=>['fecha_inicio'=>['starts_on',true],'fecha_fin'=>['ends_on',true]],
        ],
    ];
}

function payrollTransform(string $table, array $row, array $parents, array $context): array {
    if (isset(logbookDefinitions()[$table])) {
        return logbookTransform($table,$row,$parents,$context);
    }
    if (!preg_match('/^[1-9][0-9]*$/D',(string)$row['id'])) {
        throw new RuntimeException('INVALID_SOURCE_ID');
    }
    [$values,$exceptions]=mappedBillingTransform(payrollDefinitions()[$table],$row,$parents);
    $values['user_reference_exists']=isset($parents['ram_users'][(string)$values['legacy_user_id']])?'1':'0';
    if ($values['user_reference_exists']==='0') {
        $exceptions[]=['code'=>'ORPHAN_USER_REFERENCE','field'=>$table==='ram_sueldos'?'operador_id':'user_id'];
    }
    if ($table==='ram_asistencias') {
        $values['attendance_type']=match($row['tipo']) {
            'Asistencia'=>'present', 'Falta'=>'absent', 'Vacaciones'=>'vacation', default=>null,
        };
        if ($values['attendance_type']===null) {
            $exceptions[]=['code'=>'UNKNOWN_ATTENDANCE_TYPE','field'=>'tipo'];
        }
    } elseif ($values['starts_on']!==null && $values['ends_on']!==null && $values['starts_on']>$values['ends_on']) {
        $exceptions[]=['code'=>'REVERSED_SETTLEMENT_PERIOD','field'=>'fecha_fin'];
    }
    return [$values,$exceptions];
}
