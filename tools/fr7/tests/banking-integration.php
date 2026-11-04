<?php
declare(strict_types=1);
require __DIR__.'/payables-integration.php';

$a->exec("UPDATE fr7_source_test.ram_proveedores SET observaciones='' WHERE id=7");
$a->exec('ALTER TABLE fr7_source_test.ram_proveedores MODIFY observaciones TEXT NOT NULL');
foreach (['ram_bancos_periodos','ram_bancos_prestamos','ram_bancos_saldos'] as $table) {
    if (!preg_match('/CREATE TABLE `'.preg_quote($table,'/').'`[\s\S]*?;/', $baseline, $match)) {
        throw new RuntimeException('Missing banking source schema');
    }
    $a->exec($match[0]);
}
insertFixture($a, 'ram_bancos_periodos', ['id'=>1,'periodo'=>'fixture']+$ts);
insertFixture($a, 'ram_bancos_prestamos', [
    'id'=>1,'bancos_id'=>1,'periodo'=>'fixture','categoria_id'=>1,'subcategoria_id'=>'1',
    'movimiento'=>'Loan','folio'=>'L','fecha'=>'2026-01-01','user_id'=>1,'tipo'=>'-1',
    'cantidad'=>'25.50','observaciones'=>'',
]+$ts);
foreach ([1=>'74.50',2=>'75.00'] as $id=>$closing) {
    insertFixture($a, 'ram_bancos_saldos', [
        'id'=>$id,'bancos_id'=>1,'periodo'=>'fixture','saldo_inicial'=>'100.00',
        'saldo_final'=>$closing,'cerrado'=>1,
    ]+$ts);
}

$start=$count;
$a->exec('CREATE DATABASE fr7_banking_test');
putenv('FR7_TARGET_DB=fr7_banking_test');
coreCheck(main(['migrate.php','--dry-run','--banking'])===0,'banking dry run');
coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_banking_test'")->fetchColumn()===0,'banking dry run leaves target empty');
coreCheck(main(['migrate.php','--execute','--banking'])===0,'banking execute');
$metrics=json_decode($a->query('SELECT metrics FROM fr7_banking_test.migration_runs')->fetchColumn(),true,512,JSON_THROW_ON_ERROR);
coreCheck($metrics['bank_closings']['closed_rows']===2
    && $metrics['bank_closings']['unknown_direction']===0
    && $metrics['bank_closings']['matched']===1
    && $metrics['bank_closings']['different']===1,'closing comparison retains multiple snapshots');
coreCheck((int)$a->query("SELECT COUNT(*) FROM fr7_banking_test.migration_id_map WHERE domain='banking'")->fetchColumn()===4,'all banking source rows mapped');

$bankingTarget=connectDatabase('FR7_TARGET');
$bankingTarget->exec("INSERT INTO financial_movements
    (id,legacy_id,legacy_account_id,legacy_period,description,reference,notes,
     legacy_category_id,legacy_subcategory_id,legacy_business_date,
     legacy_quantity,legacy_type,amount,direction)
    VALUES (999999,999999,1,'fixture','Zero movement','','',1,'1','2026-01-01',0,'1',0,'none')");
$zeroMetrics=bankClosingMetrics($bankingTarget);
coreCheck($zeroMetrics['matched']===1 && $zeroMetrics['different']===1
    && $zeroMetrics['unknown_direction']===0,'zero movement has no closing effect');
$bankingTarget->exec('UPDATE financial_movements SET amount=1 WHERE id=999999');
coreCheck(bankClosingMetrics($bankingTarget)['unknown_direction']===2,
    'nonzero movement without direction remains unresolved');
$bankingTarget->exec('UPDATE financial_movements SET amount=0,direction=NULL WHERE id=999999');
coreCheck(bankClosingMetrics($bankingTarget)['unknown_direction']===2,
    'zero movement with unknown direction remains unresolved');

echo 'BANKING INTEGRATION PASSED: '.($count-$start).' additional checks'.PHP_EOL;
