<?php
declare(strict_types=1);

require __DIR__.'/banking-integration.php';

if (!preg_match('/CREATE TABLE `ram_mantenimientos`[\s\S]*?;/', $baseline, $match)) {
    throw new RuntimeException('Missing maintenance source schema');
}
$a->exec($match[0]);
foreach ([
    [1, 'Pagado', 8, '2026-01-01'],
    [2, '', 0, '2026-01-02'],
    [3, 'Otro', -1, '0000-00-00'],
] as [$id, $status, $supplier, $date]) {
    insertFixture($a, 'ram_mantenimientos', [
        'id'=>$id, 'factura'=>'M-1', 'fecha'=>$date, 'plazo'=>'30', 'cantidad'=>'12.34',
        'descuento'=>'5', 'unidad_id'=>9, 'status'=>$status, 'proveedor_id'=>$supplier,
    ]+$ts);
}

$start=$count;
$a->exec('CREATE DATABASE fr7_maintenance_test');
putenv('FR7_TARGET_DB=fr7_maintenance_test');
coreCheck(main(['migrate.php','--dry-run','--maintenance'])===0,'maintenance dry run');
coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_maintenance_test'")->fetchColumn()===0,'maintenance dry run leaves target empty');
coreCheck(main(['migrate.php','--execute','--maintenance'])===0,'maintenance execute');
$target=connectDatabase('FR7_TARGET');
$notes=$target->query('SELECT * FROM maintenance_notes ORDER BY id')->fetchAll();
coreCheck(count($notes)===3 && array_unique(array_column($notes,'legacy_invoice_reference'))===['M-1'],'duplicate invoice references preserved');
coreCheck($notes[0]['payment_status']==='paid' && (int)$notes[0]['supplier_id']===8 && (int)$notes[0]['vehicle_id']===9,'paid note retains relationships');
coreCheck($notes[1]['payment_status']==='pending' && $notes[1]['supplier_id']===null && (int)$notes[1]['legacy_supplier_id']===0,'zero supplier normalized');
coreCheck($notes[2]['payment_status']===null && $notes[2]['issued_on']===null && $notes[2]['legacy_status']==='Otro' && (int)$notes[2]['legacy_supplier_id']===-1,'unknown values preserved');
coreCheck($target->query('SELECT SUM(amount) FROM maintenance_notes')->fetchColumn()==='37.02','decimal amount reconciles');
echo 'MAINTENANCE INTEGRATION PASSED: '.($count-$start).' additional checks'.PHP_EOL;
