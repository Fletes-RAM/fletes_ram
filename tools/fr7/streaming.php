<?php
declare(strict_types=1);
function spoolWrite($stream,array $record):void {
    $line=json_encode($record,JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE)."\n";
    if(fwrite($stream,$line)!==strlen($line))throw new RuntimeException('TEMPORARY_PLAN_WRITE_FAILED');
}
function spoolRecords($stream):Generator {
    rewind($stream);
    while(($line=fgets($stream))!==false)yield json_decode($line,true,512,JSON_THROW_ON_ERROR);
    if(!feof($stream))throw new RuntimeException('TEMPORARY_PLAN_READ_FAILED');
}
function spoolValues($stream):Generator {foreach(spoolRecords($stream) as $record)yield $record['values'];}
function billingSourceColumns(string $table,array $definition):string {
    $columns=$table==='ram_users'?'id':'*';
    if($table==='ram_unidades')$columns.=',CAST(km_inicial AS CHAR) AS km_inicial';
    foreach($definition['money']??[] as $col=>$unused)$columns.=',CAST(`'.$col.'` AS CHAR) AS `'.$col.'`';
    return $columns;
}
function verifyLedgerGroups(PDO $source,PDO $target):int {
    $s=$source->query("SELECT bancos_id AS account_id,CAST(periodo AS BINARY) AS period_key,CAST(SUM(CAST(cantidad AS DECIMAL(30,2))*CAST(tipo AS SIGNED)) AS DECIMAL(40,2)) AS total
      FROM ram_bancos_movimientos WHERE CAST(tipo AS BINARY) IN (CAST('1' AS BINARY),CAST('-1' AS BINARY)) GROUP BY bancos_id,CAST(periodo AS BINARY) ORDER BY bancos_id,CAST(periodo AS BINARY)");
    $t=$target->query("SELECT legacy_account_id AS account_id,CAST(legacy_period AS BINARY) AS period_key,CAST(SUM(CASE direction WHEN 'outflow' THEN -amount WHEN 'inflow' THEN amount ELSE 0 END) AS DECIMAL(40,2)) AS total
      FROM financial_movements WHERE direction IS NOT NULL GROUP BY legacy_account_id,CAST(legacy_period AS BINARY) ORDER BY legacy_account_id,CAST(legacy_period AS BINARY)");
    $groups=0;
    while(($row=$s->fetch())!==false){$actual=$t->fetch();if($actual===false)throw new RuntimeException('LEDGER_GROUP_MISSING');foreach($row as $k=>$v)if((string)$actual[$k]!== (string)$v)throw new RuntimeException('LEDGER_GROUP_MISMATCH');$groups++;}
    if($t->fetch()!==false)throw new RuntimeException('LEDGER_GROUP_EXTRA');
    $s->closeCursor();$t->closeCursor();return $groups;
}
function billingMain(array $argv,string $profile='billing'):int {
    $banking=$profile==='banking';
    $payables=$banking||$profile==='payables';
    if(!in_array($profile,['billing','payables','banking'],true))throw new RuntimeException('UNKNOWN_PROFILE');
    $source=$target=null;$locked=false;$runId=null;$spools=[];$stage='preflight';$diagnostic=null;
    try {
        if(count($argv)!==2||!in_array($argv[1],['--inspect','--dry-run','--execute'],true))throw new RuntimeException('MODE_REQUIRED');
        if(getenv('FR7_ALLOW_LOCAL_MIGRATION')!=='YES')throw new RuntimeException('LOCAL_OPT_IN_REQUIRED');
        $source=connectDatabase('FR7_SOURCE');$target=connectDatabase('FR7_TARGET');$sid=identity($source);$tid=identity($target);
        if($sid===$tid)throw new RuntimeException('SAME_DATABASE');
        if($argv[1]==='--inspect'){echo json_encode(['source'=>$sid,'target'=>$tid],JSON_THROW_ON_ERROR).PHP_EOL;return 0;}
        foreach(['FR7_SOURCE'=>$sid,'FR7_TARGET'=>$tid] as $prefix=>$id)if(!hash_equals(envRequired($prefix.'_SERVER_IDENTITY'),$id['server_identity'])||$id['db']!==envRequired($prefix.'_DB'))throw new RuntimeException('IDENTITY_MISMATCH');
        $stage='target_preflight';$q=$target->prepare('SELECT GET_LOCK(?,0)');$q->execute(['fr7:'.$tid['db']]);$locked=(int)$q->fetchColumn()===1;
        if(!$locked)throw new RuntimeException('TARGET_BUSY');
        if((int)$target->query('SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE()')->fetchColumn()!==0)throw new RuntimeException('TARGET_NOT_EMPTY');
        foreach(['TRIGGERS'=>'TRIGGER_SCHEMA','ROUTINES'=>'ROUTINE_SCHEMA','EVENTS'=>'EVENT_SCHEMA'] as $catalog=>$col)if((int)$target->query('SELECT COUNT(*) FROM information_schema.'.$catalog.' WHERE '.$col.'=DATABASE()')->fetchColumn()!==0)throw new RuntimeException('TARGET_PROGRAMS_FORBIDDEN');
        $source->exec("SET time_zone='+00:00'");$target->exec("SET time_zone='+00:00'");
        $target->exec("SET SESSION sql_mode='STRICT_ALL_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        $definitions=match($profile){'banking'=>bankingDefinitions(),'payables'=>payablesDefinitions(),default=>billingDefinitions()};
        $transform=$profile.'Transform';$tables=array_merge(array_keys($definitions),['ram_users']);
        $stage='source_schema';
        foreach($tables as $table){
            $q=$source->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?');$q->execute([$table]);if($q->fetchColumn()!=='InnoDB')throw new RuntimeException('SOURCE_ENGINE_UNSUPPORTED');
            if($table==='ram_users')continue;
            $q=$source->prepare('SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?');$q->execute([$table]);$actual=$q->fetchAll(PDO::FETCH_COLUMN);$declared=declaredSourceColumns($table,$definitions[$table]);
            if(array_diff($actual,$declared)!==[]||array_diff($declared,$actual)!==[])throw new RuntimeException('SOURCE_SCHEMA_UNREVIEWED');
        }
        $source->exec('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');$source->exec('SET TRANSACTION READ ONLY');$source->beginTransaction();
        $parents=[];
        foreach($tables as $table){$parents[$table]=[];$q=$source->query('SELECT id FROM `'.$table.'` ORDER BY id');while(($id=$q->fetchColumn())!==false)$parents[$table][(string)$id]=true;$q->closeCursor();}
        $context=['quotation_clients'=>[]];$q=$source->query('SELECT id,cliente_id FROM ram_cotizaciones ORDER BY id');while($r=$q->fetch())$context['quotation_clients'][(string)$r['id']]=(string)$r['cliente_id'];$q->closeCursor();
        if($payables)foreach(['ram_asignaciones_combustibles','ram_asignaciones_especiales'] as $fuelTable){$q=$source->query('SELECT id,gasolinera_id FROM '.$fuelTable.' ORDER BY id');while($r=$q->fetch())$context['fuel_stations'][$fuelTable][(string)$r['id']]=(string)$r['gasolinera_id'];$q->closeCursor();}
        $hash=hash_init('sha256');hash_update($hash,'fr7:'.$profile.':v1:');$counts=[];$exceptionCounts=[];$total=0;$exceptionsTotal=0;
        foreach($tables as $table){
            $stage='source_plan:'.$table;hash_update($hash,strlen($table).':'.$table);$counts[$table]=0;$exceptionCounts[$table]=0;
            if($table!=='ram_users'){$spools[$table]=tmpfile();if($spools[$table]===false)throw new RuntimeException('PRIVATE_TEMPORARY_PLAN_UNAVAILABLE');}
            $source->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,false);$cursor=null;
            try {
                $cursor=$source->query('SELECT '.billingSourceColumns($table,$definitions[$table]??[]).' FROM `'.$table.'` ORDER BY id');
                while($row=$cursor->fetch()){
                    $json=json_encode($row,JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE);hash_update($hash,strlen($json).':'.$json);$counts[$table]++;
                    if($table==='ram_users')continue;
                    [$values,$items]=$transform($table,$row,$parents,$context);spoolWrite($spools[$table],['values'=>$values,'exceptions'=>$items]);
                    $total++;$exceptionCounts[$table]+=count($items);$exceptionsTotal+=count($items);
                }
            }finally{if($cursor!==null)$cursor->closeCursor();$source->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);}
        }
        $fingerprint=hash_final($hash);unset($parents,$context);
        if($argv[1]==='--dry-run'){$source->commit();echo json_encode(['mode'=>'dry-run','source'=>$total,'exceptions'=>$exceptionsTotal,'fingerprint'=>$fingerprint],JSON_THROW_ON_ERROR).PHP_EOL;return 0;}
        $stage='bootstrap';
        $bootstrapFiles=['001_bootstrap.sql','002_core.sql','003_operations.sql','005_billing.sql'];if($payables)$bootstrapFiles=array_merge($bootstrapFiles,['004_fuel.sql','006_payables.sql']);
        if($banking)$bootstrapFiles[]='007_banking.sql';
        foreach($bootstrapFiles as $file){$sql=file_get_contents(__DIR__.'/'.$file);if($sql===false)throw new RuntimeException('BOOTSTRAP_UNREADABLE');foreach(explode(';',$sql) as $stmt)if(trim($stmt)!=='')$target->exec($stmt);}
        $targetTables=array_merge(['migration_runs','migration_id_map','migration_exceptions','invoice_sources','collections','collection_movement_candidates'],array_values(array_unique(array_column($definitions,'target'))));
        if($payables)$targetTables=array_merge($targetTables,['supplier_invoice_candidates','supplier_invoices','supplier_invoice_items','supplier_payments']);
        foreach($targetTables as $table){$q=$target->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?');$q->execute([$table]);if($q->fetchColumn()!=='InnoDB')throw new RuntimeException('TARGET_ENGINE_UNSUPPORTED');}
        $uuid=uuidv4();$target->prepare('INSERT INTO migration_runs (uuid,source_fingerprint,source_label,started_at,status,migrator_version) VALUES (?,?,?,NOW(),?,?)')->execute([$uuid,$fingerprint,'local-'.$profile,'running',$banking?'fr7-v0.8':($payables?'fr7-v0.7':'fr7-v0.6')]);$runId=(string)$target->lastInsertId();
        $target->beginTransaction();$stage='migration';
        $map=$target->prepare('INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action) VALUES (?,?,?,?,?,?,?)');
        $exception=$target->prepare("INSERT INTO migration_exceptions (migration_run_id,domain,source_table,source_id,code,severity,resolution,status,target_table,target_id) VALUES (?,?,?,?,?,'warning',?,?,?,?)");
        foreach($definitions as $table=>$d){
            $stage='migration:'.$d['target'];$insert=null;$columns=null;
            foreach(spoolRecords($spools[$table]) as $record){
                $values=$record['values'];$items=$record['exceptions'];$rowColumns=array_keys($values);
                if($insert===null){$columns=$rowColumns;$insert=$target->prepare('INSERT INTO `'.$d['target'].'` (`'.implode('`,`',$columns).'`) VALUES ('.implode(',',array_fill(0,count($columns),'?')).')');}
                if($columns!==$rowColumns)throw new RuntimeException('INCONSISTENT_TRANSFORM_SHAPE');
                $insert->execute(array_values($values));$domain=$d['domain']??'operation';$map->execute([$runId,$domain,$table,$values['legacy_id'],$d['target'],$values['id'],$items!==[]?'transformed':'inserted']);
                foreach($items as $item){$preserved=$item['code']!=='INVALID_DATE_TO_NULL'&&$item['code']!=='ZERO_REFERENCE_TO_NULL';$exception->execute([$runId,$domain,$table,$values['legacy_id'],$item['code'],$item['field'].': '.$item['code'],$preserved?'preserved':'transformed',$d['target'],$values['id']]);}
            }
        }
        $metrics=[];
        foreach($definitions as $table=>$d){
            $stage='reconciliation:'.$d['target'];
            if(!reconcileStoredDomain($target,spoolValues($spools[$table]),$table,$d,$runId,$diagnostic,$counts[$table]))throw new RuntimeException('CONTENT_RECONCILIATION_FAILED');
            $key=isset($d['scope'])?$d['target'].'.'.$d['scope']:$d['target'];$metrics[$key]=['source'=>$counts[$table],'target'=>$counts[$table],'mapped'=>$counts[$table],'exceptions'=>$exceptionCounts[$table],'content_equal'=>true];
            foreach($d['money']??[] as $column=>$targetColumn){
                $a=$source->query('SELECT CAST(COALESCE(SUM(CAST(`'.$column.'` AS DECIMAL(30,2))),0) AS DECIMAL(40,2)) FROM `'.$table.'`')->fetchColumn();
                $b=$target->query('SELECT CAST(COALESCE(SUM(`'.$targetColumn.'`),0) AS DECIMAL(40,2)) FROM `'.$d['target'].'`'.fuelScope($d))->fetchColumn();
                if((string)$a!==(string)$b)throw new RuntimeException('AMOUNT_RECONCILIATION_FAILED');$metrics[$key]['amount_totals'][$targetColumn]=(string)$b;
            }
        }
        $stage='billing_derivation';$metrics['derived']=deriveBilling($target,$runId);
        $stage='payables_derivation';if($payables)$metrics['payables']=derivePayables($target,$runId);
        $relations=$payables?payablesRelations():billingRelations();
        if($banking)foreach(['legacy_bank_loans','legacy_bank_closings'] as $table)$relations[]=[$table,'account_id','financial_accounts'];
        $stage='relation_reconciliation';foreach($relations as [$child,$column,$parent])if((int)$target->query('SELECT COUNT(*) FROM `'.$child.'` c LEFT JOIN `'.$parent.'` p ON p.id=c.`'.$column.'` WHERE c.`'.$column.'` IS NOT NULL AND p.id IS NULL')->fetchColumn()!==0)throw new RuntimeException('RELATION_RECONCILIATION_FAILED');
        $stage='ledger_reconciliation';$metrics['ledger_groups_reconciled']=verifyLedgerGroups($source,$target);
        if($banking){$stage='bank_closing_reconciliation';$metrics['bank_closings']=bankClosingMetrics($target);}
        $source->commit();$stage='commit';$target->prepare("UPDATE migration_runs SET finished_at=NOW(),status='passed',metrics=? WHERE id=?")->execute([json_encode($metrics,JSON_THROW_ON_ERROR),$runId]);$target->commit();
        echo 'FR-7 '.$profile.' run '.$uuid.': PASSED; source_rows='.$total.PHP_EOL;return 0;
    }catch(Throwable $e){
        $cleanupFailed=false;foreach([$source,$target] as $p){try{if($p instanceof PDO&&$p->inTransaction())$p->rollBack();}catch(Throwable $ignored){$cleanupFailed=true;}}
        if($runId!==null){try{$target->prepare("UPDATE migration_runs SET finished_at=NOW(),status='failed',notes=? WHERE id=?")->execute(['FAILED_STAGE:'.$stage,$runId]);}catch(Throwable $ignored){$cleanupFailed=true;}}
        $detail='';if($e instanceof PDOException){$state=(string)($e->errorInfo[0]??$e->getCode());if(preg_match('/^[A-Z0-9]{5}$/D',$state))$detail.='; SQLSTATE='.$state;if(isset($e->errorInfo[1])&&is_numeric($e->errorInfo[1]))$detail.='; DRIVER_CODE='.(int)$e->errorInfo[1];}
        elseif(str_starts_with($stage,'reconciliation:')&&is_array($diagnostic))foreach($diagnostic as $k=>$v)if(preg_match('/^[a-z_]+$/D',(string)$v))$detail.='; '.$k.'='.$v;
        fwrite(STDERR,'FR-7 BILLING FAILED; stage='.$stage.$detail.($cleanupFailed?'; AUDIT_OR_ROLLBACK_FAILED':'').PHP_EOL);return 1;
    }finally{
        foreach($spools as $spool)if(is_resource($spool))fclose($spool);
        if($locked&&$target instanceof PDO){try{$target->query('SELECT RELEASE_LOCK('.$target->quote('fr7:'.envRequired('FR7_TARGET_DB')).')');}catch(Throwable $ignored){fwrite(STDERR,"FR-7 LOCK_RELEASE_FAILED\n");}}
    }
}
