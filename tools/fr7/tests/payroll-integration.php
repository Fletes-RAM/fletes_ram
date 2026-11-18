<?php
declare(strict_types=1);
require __DIR__.'/logbook-integration.php';

foreach (['ram_asistencias','ram_sueldos'] as $table) {
    if (!preg_match('/CREATE TABLE `'.$table.'`[\s\S]*?;/', $baseline, $match)) {
        throw new RuntimeException('Missing payroll fixture schema');
    }
    $a->exec($match[0]);
}
foreach ([[1,1,'Asistencia','2026-01-01'],[2,1,'Falta','2026-01-01'],[3,-1,'Otro','0000-00-00']] as [$id,$user,$type,$date]) {
    insertFixture($a,'ram_asistencias',['id'=>$id,'user_id'=>$user,'tipo'=>$type,'asistencia'=>$date]+$ts);
}
insertFixture($a,'ram_sueldos',['id'=>1,'operador_id'=>1,'fecha_inicio'=>'2026-02-02','fecha_fin'=>'2026-01-01']+$ts);
$start=$count;
$a->exec('CREATE DATABASE fr7_payroll_test');
putenv('FR7_TARGET_DB=fr7_payroll_test');
coreCheck(main(['migrate.php','--dry-run','--payroll'])===0,'payroll dry run');
coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_payroll_test'")->fetchColumn()===0,'payroll dry run leaves target empty');
coreCheck(main(['migrate.php','--execute','--payroll'])===0,'payroll execute');
$target=connectDatabase('FR7_TARGET');
$rows=$target->query('SELECT * FROM legacy_attendance ORDER BY id')->fetchAll();
coreCheck(count($rows)===3 && $rows[0]['attendance_type']==='present' && $rows[1]['attendance_type']==='absent','duplicate attendance retained');
coreCheck((int)$rows[2]['legacy_user_id']===-1 && (int)$rows[2]['user_reference_exists']===0 && $rows[2]['attendance_on']===null && $rows[2]['attendance_type']===null,'orphan unknown attendance preserved');
$period=$target->query('SELECT * FROM legacy_settlement_periods')->fetch();
coreCheck((int)$period['legacy_user_id']===1 && (int)$period['user_reference_exists']===1 && $period['starts_on']==='2026-02-02' && $period['ends_on']==='2026-01-01','settlement references user and retains reversed period');
coreCheck((int)$target->query("SELECT COUNT(*) FROM migration_exceptions WHERE code='REVERSED_SETTLEMENT_PERIOD'")->fetchColumn()===1,'reversed period audited');
echo 'PAYROLL INTEGRATION PASSED: '.($count-$start).' additional checks'.PHP_EOL;
