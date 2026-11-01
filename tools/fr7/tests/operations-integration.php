<?php
declare(strict_types=1);
// Reuse the independently asserted sparse-ID core fixture and rollback checks.
require __DIR__.'/core-integration.php';
$a->exec("UPDATE fr7_source_test.ram_operadores SET telefono='' WHERE id=21");
$a->exec('USE fr7_source_test');$a->exec("SET SESSION sql_mode=''");
$baseline=file_get_contents(dirname(__DIR__,3).'/docs/modernizacion/baseline/fletes_ram_legacy_schema.sql');
foreach(array_diff(array_keys(operationDefinitions()),array_keys(coreDefinitions())) as $t){
 if(!preg_match('/CREATE TABLE(?: IF NOT EXISTS)? `'.preg_quote($t,'/').'`[\s\S]*?;/',$baseline,$m))throw new RuntimeException('Missing fixture schema');
 $a->exec($m[0]);
}
$a->exec("INSERT INTO ram_nombres_rutas VALUES (15,'Ruta','12.25','',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00')");
$a->exec("INSERT INTO ram_detalles_rutas VALUES (23,15,'A','Origen','B','Destino','8 km',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00')");
$a->exec("INSERT INTO ram_rendimientos VALUES (2,7,'2.5','2026-01-01 00:00:00','2026-01-01 00:00:00')");
$a->exec("INSERT INTO ram_cotizaciones VALUES (22,'F-22',8,15,7,2,'12.25',1.01,29.99,2.01,3.02,4.03,5.04,6.05,7.06,'',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00')");
$a->exec("INSERT INTO ram_asignaciones VALUES (31,22,1,9,'Ruta Finalizada','2026-01-01 00:00:00','2026-01-01 00:00:00')");
$a->exec("INSERT INTO ram_gasolineras VALUES (5,'Gas','Estación','C','','','',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00')");
function operationSnapshot(PDO $p):array{$r=[];foreach(array_keys(operationDefinitions()) as $t)$r[$t]=$p->query('SELECT * FROM `'.$t.'` ORDER BY id')->fetchAll();$r['ram_users']=$p->query('SELECT id FROM ram_users ORDER BY id')->fetchAll();return $r;}
function invokeOperations(string $mode):int{ob_start();$r=main(['migrate.php',$mode,'--operations']);ob_end_clean();return $r;}
$before=coreFingerprint(operationSnapshot($reader));$all=[];$runs=[];$startCount=$count;
foreach(['fr7_ops_a_test','fr7_ops_b_test'] as $db){
 $a->exec('CREATE DATABASE `'.$db.'`');putenv('FR7_TARGET_DB='.$db);
 coreCheck(invokeOperations('--dry-run')===0,'operation dry-run');
 coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='{$db}'")->fetchColumn()===0,'operation dry-run no writes');
 coreCheck(invokeOperations('--execute')===0,'operation execute');
 $data=[];foreach(operationDefinitions() as $def)$data[$def['target']]=$a->query('SELECT * FROM '.$db.'.'.$def['target'].' ORDER BY id')->fetchAll();$all[]=$data;
 $runs[]=$a->query('SELECT source_fingerprint,metrics FROM '.$db.'.migration_runs')->fetch();
 coreCheck((int)$a->query('SELECT COUNT(*) FROM '.$db.'.migration_id_map')->fetchColumn()===13,'operation maps');
}
coreCheck($all[0]===$all[1]&&$runs[0]===$runs[1],'operation reproducible');
coreCheck($all[0]['assignments'][0]['quotation_id']==22&&$all[0]['assignments'][0]['vehicle_id']==9,'assignment joins');
coreCheck($all[0]['quotations'][0]['client_id']==8&&$all[0]['quotations'][0]['route_id']==15,'quotation joins');
coreCheck($all[0]['route_segments'][0]['kilometers']===null&&$all[0]['route_segments'][0]['legacy_kilometers']==='8 km','text km preserved');
$metrics=json_decode($runs[0]['metrics'],true);coreCheck($metrics['quotations']['amount_totals']['proposed_amount']==='29.99','exact amount total');
coreCheck($before===coreFingerprint(operationSnapshot($reader)),'operation source unchanged');
echo 'OPERATIONS INTEGRATION PASSED: '.($count-$startCount).' additional checks'.PHP_EOL;
