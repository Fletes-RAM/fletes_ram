<?php
declare(strict_types=1);
require __DIR__.'/operations-integration.php';
// This suite extends only synthetic fixtures; no production dump is loaded.
foreach(array_diff(array_keys(billingDefinitions()),array_keys(operationDefinitions())) as $table){
 if(!preg_match('/CREATE TABLE(?: IF NOT EXISTS)? `'.preg_quote($table,'/').'`[\s\S]*?;/',$baseline,$match))throw new RuntimeException('Missing billing fixture schema');
 $a->exec($match[0]);
}
function insertFixture(PDO $p,string $table,array $row):void{
 $q=$p->prepare('INSERT INTO `'.$table.'` (`'.implode('`,`',array_keys($row)).'`) VALUES ('.implode(',',array_fill(0,count($row),'?')).')');$q->execute(array_values($row));
}
$ts=['created_at'=>'2026-01-01 00:00:00','updated_at'=>'2026-01-01 00:00:00'];
insertFixture($a,'ram_bancos',['id'=>1,'banco'=>'Synthetic account','no_cuenta'=>'NOT-REAL','clabe'=>'NOT-REAL','observaciones'=>'','deleted_at'=>null]+$ts);
insertFixture($a,'ram_bancos_categorias',['id'=>1,'categoria'=>'Synthetic category']+$ts);
insertFixture($a,'ram_bancos_subcategorias',['id'=>1,'categoria_id'=>1,'subcategoria'=>'Synthetic subcategory']+$ts);
foreach([[100,1],[101,1],[102,999],[103,5]] as [$id,$invoice])insertFixture($a,'ram_controles_vehiculares',['id'=>$id,'user_id'=>1,'factura_id'=>$invoice,'porcentaje'=>15,'fecha'=>'2026-01-01','control_vehicular'=>'CTRL-'.$id,'origen'=>'9','toneladas'=>'2.5','tarifa'=>'10','cantidad'=>'25','iva'=>'4','pagado'=>'No']+$ts);
$commonInvoice=['subtotal'=>'100.25','maniobras'=>'0','otros'=>'0','iva'=>'0','retencion'=>'0','observaciones'=>''];
foreach([[1,'Q-ONE','100.25',1,'2026-01-01'],[2,'Q-AMB','20',1,'2026-01-02'],[3,'Q-AMB','20',1,'2026-01-02'],[4,'Q-ZERO','7',1,'0000-00-00'],[5,'Q-UNPAID','8',0,'2026-01-01'],[6,'Q-SHARED','30',1,'2026-01-03'],[7,'Q-SHARED','30',1,'2026-01-03'],[8,'Q-UNKNOWN','9',2,'0000-00-00']] as [$id,$ref,$total,$paid,$date])insertFixture($a,'ram_facturas',['id'=>$id,'cotizacion_id'=>22,'factura'=>$ref,'total'=>$total,'pagada'=>$paid,'fecha_pago'=>$date]+$commonInvoice+$ts);
foreach([[1,0,'CV-ONE','50',1],[2,0,'CV-EMPTY','8',0],[3,100,'CV-CONFLICT','9',0],[4,999,'CV-MISSING','10',1],[5,103,'CV-BOTH','11',0]] as [$id,$control,$ref,$total,$paid])insertFixture($a,'ram_facturas_controles',['id'=>$id,'control_id'=>$control,'cliente_id'=>8,'factura'=>$ref,'total'=>$total,'pagada'=>$paid,'fecha_pago'=>'2026-01-01']+$commonInvoice+$ts);
foreach([[10,'Q-ONE','2026-01-01','1','100.25','Pago Factura Q-ONE'],[11,'Q-AMB','2026-01-02','1','20','Pago Factura Q-AMB'],[12,'Q-AMB','2026-01-02','1','20','Pago Factura Q-AMB'],[13,'Q-SHARED','2026-01-03','1','30','Pago Factura Q-SHARED'],[14,'CV-ONE','2026-01-01','1','50','Pago Factura CVCV-ONE'],[15,'Q-ONE','2026-01-02','1','100.25','Pago Factura Q-ONE'],[16,'Q-ONE','2026-01-01','-1','100.25','Pago Factura Q-ONE'],[17,'SIGN','2026-01-01','-1','-12.34','Synthetic sign'],[18,'UNKNOWN','2026-01-01','?','1','Synthetic unknown'],[19,'ZERO','2026-01-01','1','0','Synthetic zero']] as [$id,$ref,$date,$type,$amount,$desc])insertFixture($a,'ram_bancos_movimientos',['id'=>$id,'bancos_id'=>1,'periodo'=>'2026-01','categoria_id'=>1,'subcategoria_id'=>'1','movimiento'=>$desc,'folio'=>$ref,'fecha'=>$date,'tipo'=>$type,'cantidad'=>$amount,'observaciones'=>'']+$ts);
function invokeBilling(string $mode):int{ob_start();$r=main(['migrate.php',$mode,'--billing']);ob_end_clean();return $r;}
function syntheticBillingHash(PDO $p):string{
 $h=hash_init('sha256');foreach(array_merge(array_keys(billingDefinitions()),['ram_users']) as $t){hash_update($h,$t);$q=$p->query('SELECT '.($t==='ram_users'?'id':'*').' FROM `'.$t.'` ORDER BY id');while($r=$q->fetch()){$j=json_encode($r,JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE);hash_update($h,strlen($j).':'.$j);}}return hash_final($h);
}
$before=syntheticBillingHash($reader);$startCount=$count;$all=[];$runs=[];
foreach(['fr7_billing_a_test','fr7_billing_b_test'] as $db){
 $a->exec('CREATE DATABASE '.$db);putenv('FR7_TARGET_DB='.$db);
 coreCheck(invokeBilling('--inspect')===0,'billing inspect');coreCheck(invokeBilling('--dry-run')===0,'billing dry run');
 coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='{$db}'")->fetchColumn()===0,'billing no DDL dry run');
 coreCheck(invokeBilling('--execute')===0,'billing execute');
 $data=[];foreach(array_unique(array_column(billingDefinitions(),'target')) as $table)$data[$table]=$a->query('SELECT * FROM '.$db.'.'.$table.' ORDER BY id')->fetchAll();
 foreach(['invoice_sources','collections'] as $table)$data[$table]=$a->query('SELECT * FROM '.$db.'.'.$table.' ORDER BY id')->fetchAll();
 $data['collection_movement_candidates']=$a->query('SELECT * FROM '.$db.'.collection_movement_candidates ORDER BY collection_id,financial_movement_id')->fetchAll();$all[]=$data;
 $runs[]=$a->query('SELECT status,source_fingerprint,metrics FROM '.$db.'.migration_runs')->fetch();coreCheck($runs[array_key_last($runs)]['status']==='passed','billing audit');
 coreCheck(invokeBilling('--execute')===1,'billing populated destination rejected');
 coreCheck((int)$a->query('SELECT COUNT(*) FROM '.$db.'.migration_runs')->fetchColumn()===1,'billing no extra run');
}
coreCheck($all[0]===$all[1]&&$runs[0]===$runs[1],'billing content fingerprint metrics reproducible');
$data=$all[0];$invoices=array_column($data['invoices'],null,'id');$collections=array_column($data['collections'],null,'id');$movements=array_column($data['financial_movements'],null,'id');
coreCheck(count($invoices)===13&&$invoices[2]['legacy_id']==$invoices[3]['legacy_id'],'both invoice namespaces preserved');
coreCheck(count($collections)===8,'only paid flags create summaries');
coreCheck($collections[2]['financial_movement_id']==10&&$collections[2]['record_kind']==='legacy_paid_summary','unique exact ordinary collection linked');
coreCheck($collections[3]['financial_movement_id']==14,'CV context linked independently');
coreCheck($collections[4]['reconciliation_status']==='ambiguous'&&$collections[6]['reconciliation_status']==='ambiguous','multiple movements never auto-selected');
coreCheck($collections[12]['financial_movement_id']===null&&$collections[14]['financial_movement_id']===null,'one movement shared by two invoices not claimed twice');
coreCheck($collections[8]['paid_on']===null&&$collections[8]['reconciliation_status']==='invalid_payment_date','paid without date preserved');
coreCheck(!isset($collections[10])&&!isset($collections[16])&&$invoices[16]['is_paid']===null,'unpaid and unknown paid flag not synthesized');
coreCheck($collections[9]['reconciliation_status']==='missing','missing bank evidence preserved');
coreCheck(count($data['invoice_sources'])===13,'quotation and control origin edges retained');
$group=array_values(array_filter($data['invoice_sources'],fn($s)=>$s['invoice_id']==3));
coreCheck(count($group)===2&&array_column($group,'control_id')===[100,101],'grouped zero converted to actual control edges');
$both=array_values(array_filter($data['invoice_sources'],fn($s)=>$s['invoice_id']==11));coreCheck(count($both)===1&&$both[0]['control_id']==103&&$both[0]['evidence_flags']==3,'agreeing direct and backlink evidence merges only the same edge');
coreCheck(count(array_filter($data['invoice_sources'],fn($s)=>$s['source_type']==='control'&&$s['legacy_source_id']==0))===0,'no invented control zero');
coreCheck(count(array_filter($data['invoice_sources'],fn($s)=>$s['control_id']==100))===2,'conflicting directions both preserved');
coreCheck(count(array_filter($data['invoice_sources'],fn($s)=>$s['invoice_id']==5))===0,'group without sources not filled by guess');
$orphan=array_values(array_filter($data['invoice_sources'],fn($s)=>$s['invoice_id']==9));coreCheck($orphan[0]['control_id']===null&&$orphan[0]['legacy_source_id']==999,'orphan direct origin retained');
coreCheck($movements[17]['amount']==='12.34'&&$movements[17]['direction']==='inflow','negative amount and negative type normalize correctly');
coreCheck($movements[18]['direction']===null&&$movements[18]['legacy_type']==='?','unknown financial sign explicit');
coreCheck($movements[19]['direction']==='none'&&$movements[19]['amount']==='0.00','zero ledger amount preserved');
$metrics=json_decode($runs[0]['metrics'],true);coreCheck($metrics['ledger_groups_reconciled']===1,'signed account-period totals reconciled');
coreCheck($before===syntheticBillingHash($reader),'all billing source tables unchanged');
$q=$a->query("SELECT code,COUNT(*) AS count FROM fr7_billing_a_test.migration_exceptions WHERE code IN ('AMBIGUOUS_COLLECTION_MOVEMENT','GROUPED_INVOICE_WITHOUT_SOURCES','CONFLICTING_CONTROL_INVOICES') GROUP BY code");$codes=$q->fetchAll(PDO::FETCH_KEY_PAIR);
coreCheck($codes['AMBIGUOUS_COLLECTION_MOVEMENT']==4&&$codes['GROUPED_INVOICE_WITHOUT_SOURCES']==1&&$codes['CONFLICTING_CONTROL_INVOICES']==1,'derived anomalies audited');
putenv('FR7_TARGET_DB=fr7_billing_b_test');$mapConnection=connectDatabase('FR7_TARGET');$mapRun=(string)$mapConnection->query('SELECT id FROM migration_runs')->fetchColumn();
coreCheck(validateDerivedBillingMaps($mapConnection,$mapRun),'derived mapping content verified');
$originalMap=$mapConnection->query("SELECT id,target_id FROM migration_id_map WHERE target_table='invoice_sources' ORDER BY id LIMIT 1")->fetch();
$mapConnection->exec("UPDATE migration_id_map SET target_id='999999' WHERE id=".(int)$originalMap['id']);
coreCheck(!validateDerivedBillingMaps($mapConnection,$mapRun),'equal-count derived mapping corruption detected');
$mapConnection->prepare('UPDATE migration_id_map SET target_id=? WHERE id=?')->execute([$originalMap['target_id'],$originalMap['id']]);
coreCheck(validateDerivedBillingMaps($mapConnection,$mapRun),'derived mapping restored');
$a->exec('CREATE DATABASE fr7_billing_failure_test');putenv('FR7_TARGET_DB=fr7_billing_failure_test');
$a->exec('ALTER TABLE fr7_source_test.ram_facturas_controles MODIFY factura VARCHAR(256) NOT NULL');$a->exec("UPDATE fr7_source_test.ram_facturas_controles SET factura=REPEAT('x',256) WHERE id=4");
coreCheck(invokeBilling('--execute')===1,'last billing domain fails atomically');
foreach(array_unique(array_column(billingDefinitions(),'target')) as $table)coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_billing_failure_test.'.$table)->fetchColumn()===0,'billing complete rollback '.$table);
foreach(['invoice_sources','collections','collection_movement_candidates','migration_id_map','migration_exceptions'] as $table)coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_billing_failure_test.'.$table)->fetchColumn()===0,'billing derived/audit rollback '.$table);
$r=$a->query('SELECT status,notes FROM fr7_billing_failure_test.migration_runs')->fetch();coreCheck($r['status']==='failed'&&$r['notes']==='FAILED_STAGE:migration:invoices','billing failed stage sanitized');
$a->exec("UPDATE fr7_source_test.ram_facturas_controles SET factura='CV-MISSING' WHERE id=4");
$a->exec('ALTER TABLE fr7_source_test.ram_facturas ADD unreviewed_field INT NULL');$a->exec('CREATE DATABASE fr7_billing_schema_test');putenv('FR7_TARGET_DB=fr7_billing_schema_test');
coreCheck(invokeBilling('--execute')===1,'unreviewed schema rejected');coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_billing_schema_test'")->fetchColumn()===0,'schema rejection before DDL');
echo 'BILLING INTEGRATION PASSED: '.($count-$startCount).' additional checks'.PHP_EOL;
