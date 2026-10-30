<?php
declare(strict_types=1);
require __DIR__.'/../migrate.php';
$count = 0;
function check(bool $ok, string $label): void {
    global $count;
    if (!$ok) throw new RuntimeException($label);
    $count++;
}
$row = ['id'=>7,'tipo_de_unidad'=>'  Camión ñ  ','porcentaje'=>15,'observaciones'=>'Nota sintética','created_at'=>'0000-00-00 00:00:00','updated_at'=>'2026-02-30 10:00:00','deleted_at'=>null];
[$values,$exceptions] = transformVehicleType($row);
check($values['name'] === $row['tipo_de_unidad'], 'name preserved verbatim');
check($values['percentage'] === '15.00', 'decimal normalized exactly');
check($values['created_at'] === null && $values['updated_at'] === null, 'invalid dates normalized');
check($exceptions === ['created_at','updated_at'], 'invalid fields traced');
check(cleanDate('2024-02-29 23:59:59') === '2024-02-29 23:59:59', 'valid leap day');
check(cleanDate('2025-02-29 23:59:59') === null, 'invalid leap day');
check(cleanDate('2026-10-30') === null && cleanDate('0999-01-01 00:00:00') === null, 'invalid datetime format and range');
check(cleanDate(null) === null, 'nullable datetime');
$deleted = $row; $deleted['deleted_at'] = '2026-01-01 00:00:00';
check(transformVehicleType($deleted)[0]['is_active'] === '0', 'soft delete retained');
$zeroDeleted = $row; $zeroDeleted['deleted_at'] = '0000-00-00 00:00:00';
check(transformVehicleType($zeroDeleted)[0]['is_active'] === '0', 'invalid deletion does not reactivate record');
$actual = $values + ['id'=>99]; $maps = [['source_id'=>7,'target_id'=>99]];
check(reconcile([$values],[$actual],$maps), 'full reconciliation');
$tampered = $actual; $tampered['notes'] = 'Changed';
check(!reconcile([$values],[$tampered],$maps), 'same count content corruption detected');
check(!reconcile([$values],[$actual],[['source_id'=>7,'target_id'=>98]]), 'wrong target mapping detected');
check(!reconcile([$values],[$actual],[]), 'missing mapping detected');
$changed = $row; $changed['observaciones'] = 'Changed';
check(fingerprint([$row]) !== fingerprint([$changed]), 'same count and max id changed fingerprint');
check(fingerprint([$row]) === fingerprint([$row]), 'stable fingerprint');
check(reconcile([],[],[]), 'empty catalog');
foreach (['invalid','2026-01-01 25:00:00','2026-00-10 00:00:00'] as $date) check(cleanDate($date) === null, 'invalid date');
putenv('FR7_SOURCE_HOST=remote.example'); putenv('FR7_SOURCE_DB=fletes_ram_local');
$rejected = false; try { connectionConfig('FR7_SOURCE'); } catch (RuntimeException $e) { $rejected = true; } check($rejected,'remote rejected');
putenv('FR7_SOURCE_HOST=127.0.0.1'); putenv('FR7_SOURCE_DB=production');
$rejected = false; try { connectionConfig('FR7_SOURCE'); } catch (RuntimeException $e) { $rejected = true; } check($rejected,'production rejected');
echo "UNIT PASSED: {$count} checks".PHP_EOL;
