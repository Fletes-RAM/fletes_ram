<?php
declare(strict_types=1);
require __DIR__.'/billing-integration.php';
$a->exec('CREATE DATABASE fr7_mapping_index_test');putenv('FR7_TARGET_DB=fr7_mapping_index_test');$indexTarget=connectDatabase('FR7_TARGET');
$sql=file_get_contents(__DIR__.'/../001_bootstrap.sql');foreach(explode(';',$sql) as $statement)if(trim($statement)!=='')$indexTarget->exec($statement);
$indexTarget->prepare('INSERT INTO migration_runs (uuid,source_fingerprint,source_label,started_at,status,migrator_version) VALUES (?,?,?,NOW(),?,?)')->execute([uuidv4(),str_repeat('0',64),'synthetic-index','running','test']);
$indexRun=(string)$indexTarget->lastInsertId();$indexRows=12000;
$indexTarget->beginTransaction();$insert=$indexTarget->prepare('INSERT INTO vehicle_types (id,legacy_id,name) VALUES (?,?,?)');$map=$indexTarget->prepare("INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action) VALUES (?,'operation','ram_tipos_de_unidades',?,'vehicle_types',?,'inserted')");
for($i=1;$i<=$indexRows;$i++){$insert->execute([$i,$i,'Synthetic '.$i]);$map->execute([$indexRun,(string)$i,(string)$i]);}$indexTarget->commit();
$handlerBefore=(int)$indexTarget->query("SHOW SESSION STATUS LIKE 'Handler_read_next'")->fetch(PDO::FETCH_NUM)[1];
$expectedIndexRows=(function()use($indexRows){for($i=1;$i<=$indexRows;$i++)yield ['id'=>(string)$i,'legacy_id'=>(string)$i,'name'=>'Synthetic '.$i,'percentage'=>null,'notes'=>null,'is_active'=>'1','created_at'=>null,'updated_at'=>null];})();
$diagnostic=null;
coreCheck(reconcileStoredDomain($indexTarget,$expectedIndexRows,'ram_tipos_de_unidades',['target'=>'vehicle_types'],$indexRun,$diagnostic,$indexRows),'large streamed reconciliation');
$handlerAfter=(int)$indexTarget->query("SHOW SESSION STATUS LIKE 'Handler_read_next'")->fetch(PDO::FETCH_NUM)[1];
coreCheck($handlerAfter-$handlerBefore<$indexRows*10+1000,'mapping lookups use index rather than quadratic scans');
echo 'MAPPING INDEX INTEGRATION PASSED: 2 additional checks; rows='.$indexRows.'; indexed_reads='.($handlerAfter-$handlerBefore).PHP_EOL;
