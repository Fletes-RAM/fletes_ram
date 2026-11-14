<?php
declare(strict_types=1);

require __DIR__.'/inventory-integration.php';

if (!preg_match('/CREATE TABLE `ram_bitacoras`[\s\S]*?;/', $baseline, $match)) {
    throw new RuntimeException('Missing logbook fixture schema');
}
$a->exec($match[0]);
$checks = array_fill_keys(logbookChecks(), 0);
$checks['filtro_aire'] = 1;
$checks['anticongelante'] = -1;
foreach ([[1,'12500.5000',8,'2026-01-01'], [2,'texto',0,'0000-00-00']] as [$id,$odometer,$supplier,$date]) {
    insertFixture($a,'ram_bitacoras',[
        'id'=>$id,'unidad_id'=>9,'fecha'=>$date,'kilometraje'=>$odometer,'titulo'=>'Service',
        'proveedor_id'=>$supplier,'costo'=>'12.34','observaciones'=>'','llantas_marca'=>'',
        'reparacion_especial'=>'',
    ]+$checks+$ts);
}

$start=$count;
$a->exec('CREATE DATABASE fr7_logbook_test');
putenv('FR7_TARGET_DB=fr7_logbook_test');
coreCheck(main(['migrate.php','--dry-run','--logbook'])===0,'logbook dry run');
coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_logbook_test'")->fetchColumn()===0,'logbook dry run leaves target empty');
coreCheck(main(['migrate.php','--execute','--logbook'])===0,'logbook execute');
$target=connectDatabase('FR7_TARGET');
$events=$target->query('SELECT * FROM vehicle_logbook ORDER BY id')->fetchAll();
coreCheck(count($events)===2 && $events[0]['odometer']==='12500.5000' && $events[0]['cost']==='12.34'
    && (int)$events[0]['vehicle_id']===9 && (int)$events[0]['supplier_id']===8,'valid event reconciled');
coreCheck($events[1]['odometer']===null && $events[1]['legacy_odometer']==='texto'
    && $events[1]['occurred_on']===null && $events[1]['supplier_id']===null,'invalid readings and empty supplier preserved');
coreCheck(json_decode($events[0]['legacy_checks'],true,512,JSON_THROW_ON_ERROR)===$checks,'all historical checks preserved');
echo 'LOGBOOK INTEGRATION PASSED: '.($count-$start).' additional checks'.PHP_EOL;
