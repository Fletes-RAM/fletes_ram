<?php
declare(strict_types=1);
require __DIR__.'/operations-integration.php';
foreach(['ram_asignaciones_combustibles','ram_asignaciones_especiales'] as $t){
 if(!preg_match('/CREATE TABLE(?: IF NOT EXISTS)? `'.preg_quote($t,'/').'`[\s\S]*?;/',$baseline,$m))throw new RuntimeException('Missing fuel fixture schema');
 $a->exec(str_replace('ENGINE=MyISAM','ENGINE=InnoDB',$m[0]));
}
$r=['id'=>11,'asignacion_id'=>999,'fecha'=>'1899-11-30','gasolinera_id'=>0,'ticket'=>'BOTH_CONTEXTS','litros'=>'0.00','precio'=>'2.00','total'=>'3.99','kilometraje'=>'7.00','rendimiento'=>'0.00','foto_ticket'=>'upl/synthetic.jpg','foto_tablero_antes'=>'','foto_tablero_despues'=>'','foto_tablero_km'=>'','created_at'=>null,'updated_at'=>null,'factura_proveedor'=>'DOC','dias_credito'=>0,'fecha_limite_pago'=>'0000-00-00'];
foreach(['ram_asignaciones_combustibles','ram_asignaciones_especiales'] as $t){
 if($t==='ram_asignaciones_especiales'){unset($r['asignacion_id']);$r['unidad_id']=9;$r['user_id']=1;$r['fecha']='2026-11-01';$r['gasolinera_id']=5;$r['litros']='1.25';$r['fecha_limite_pago']=null;}
 $q=$a->prepare('INSERT INTO '.$t.' (`'.implode('`,`',array_keys($r)).'`) VALUES ('.implode(',',array_fill(0,count($r),'?')).')');$q->execute(array_values($r));
}
function fuelSnapshot(PDO $p):array{$r=[];foreach(array_keys(fuelDefinitions()) as $t)$r[$t]=$p->query('SELECT * FROM `'.$t.'` ORDER BY id')->fetchAll();$r['ram_users']=$p->query('SELECT id FROM ram_users ORDER BY id')->fetchAll();return $r;}
function invokeFuel(string $mode):int{ob_start();$r=main(['migrate.php',$mode,'--fuel']);ob_end_clean();return $r;}
$before=coreFingerprint(fuelSnapshot($reader));$all=[];$runs=[];$startCount=$count;
foreach(['fr7_fuel_a_test','fr7_fuel_b_test'] as $db){
 $a->exec('CREATE DATABASE '.$db);putenv('FR7_TARGET_DB='.$db);
 coreCheck(invokeFuel('--dry-run')===0,'fuel dry run');
 coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='{$db}'")->fetchColumn()===0,'fuel dry run no DDL');
 coreCheck(invokeFuel('--execute')===0,'fuel execute');
 $all[]=$a->query('SELECT * FROM '.$db.'.fuel_loads ORDER BY id')->fetchAll();$runs[]=$a->query('SELECT id,source_fingerprint,metrics FROM '.$db.'.migration_runs')->fetch();
 coreCheck((int)$a->query('SELECT COUNT(*) FROM '.$db.'.migration_id_map')->fetchColumn()===15,'all maps');
 coreCheck((int)$a->query("SELECT COUNT(*) FROM {$db}.migration_exceptions WHERE source_table='ram_asignaciones_combustibles' AND target_id=22")->fetchColumn()===5,'ordinary exceptions link correct context');
}
coreCheck($all[0]===$all[1]&&$runs[0]===$runs[1],'fuel reproducible');
coreCheck($all[0][0]['id']==22&&$all[0][1]['id']==23&&$all[0][0]['legacy_id']==$all[0][1]['legacy_id'],'IDs do not collide');
coreCheck($all[0][0]['assignment_id']===null&&$all[0][0]['legacy_assignment_id']==999,'orphan assignment retained');
$metrics=json_decode($runs[0]['metrics'],true);
coreCheck($metrics['fuel_loads.assignment']['amount_totals']['total_amount']==='3.99'&&$metrics['fuel_loads.special']['amount_totals']['total_amount']==='3.99','independent exact context totals');
coreCheck($before===coreFingerprint(fuelSnapshot($reader)),'fuel source unchanged');
// Exercise the production streaming reconciliation against equal-count corruption.
$target=connectDatabase('FR7_TARGET');$definition=fuelDefinitions()['ram_asignaciones_combustibles'];
$raw=$reader->query('SELECT * FROM ram_asignaciones_combustibles')->fetch();$parents=['ram_asignaciones'=>[],'ram_gasolineras'=>[5=>true]];[$expected]=fuelTransform('ram_asignaciones_combustibles',$raw,$parents);
coreCheck(reconcileStoredDomain($target,[$expected],'ram_asignaciones_combustibles',$definition,(string)$runs[1]['id']),'streaming control');
$target->exec("UPDATE fuel_loads SET total_amount=4.00 WHERE id=22");
coreCheck(!reconcileStoredDomain($target,[$expected],'ram_asignaciones_combustibles',$definition,(string)$runs[1]['id']),'same-count content corruption detected');
$target->exec("UPDATE fuel_loads SET total_amount=3.99 WHERE id=22");
$target->exec("UPDATE migration_id_map SET target_id='23' WHERE source_table='ram_asignaciones_combustibles'");
coreCheck(!reconcileStoredDomain($target,[$expected],'ram_asignaciones_combustibles',$definition,(string)$runs[1]['id']),'wrong-context mapping detected');
// A source engine that cannot supply a transactional snapshot is rejected before DDL.
$a->exec('CREATE DATABASE fr7_fuel_engine_test');putenv('FR7_TARGET_DB=fr7_fuel_engine_test');$a->exec('ALTER TABLE fr7_source_test.ram_asignaciones_combustibles ENGINE=MyISAM');
coreCheck(invokeFuel('--execute')===1,'MyISAM source rejected');
coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_fuel_engine_test'")->fetchColumn()===0,'engine rejection no DDL');
echo 'FUEL INTEGRATION PASSED: '.($count-$startCount).' additional checks'.PHP_EOL;
