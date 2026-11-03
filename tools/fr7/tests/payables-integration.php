<?php
declare(strict_types=1);
require __DIR__.'/billing-integration.php';
$a->exec('ALTER TABLE fr7_source_test.ram_facturas DROP unreviewed_field');
foreach(['ram_asignaciones_combustibles','ram_asignaciones_especiales','ram_cat_proveedores','ram_proveedores'] as $table){if(!preg_match('/CREATE TABLE(?: IF NOT EXISTS)? `'.preg_quote($table,'/').'`[\s\S]*?;/',$baseline,$m))throw new RuntimeException('Missing source schema');$a->exec(str_replace('ENGINE=MyISAM','ENGINE=InnoDB',$m[0]));}
$f=['id'=>11,'fecha'=>'2026-01-01','gasolinera_id'=>5,'ticket'=>'T','litros'=>'10','precio'=>'2','total'=>'20','kilometraje'=>'100','rendimiento'=>'2','foto_ticket'=>'','foto_tablero_antes'=>'','foto_tablero_despues'=>'','foto_tablero_km'=>'','created_at'=>null,'updated_at'=>null,'factura_proveedor'=>null,'dias_credito'=>null,'fecha_limite_pago'=>null];
insertFixture($a,'ram_asignaciones_combustibles',['asignacion_id'=>31]+$f);$f['gasolinera_id']=0;insertFixture($a,'ram_asignaciones_especiales',['unidad_id'=>9,'user_id'=>1]+$f);
insertFixture($a,'ram_cat_proveedores',['id'=>8,'proveedor'=>'Synthetic provider','nombre_contacto'=>'C','email'=>'fixture@example.invalid','telefono'=>'','observaciones'=>'','deleted_at'=>null]+$ts);
foreach([[1,'a-11','100','2026-01-01'],[2,'a-11','100','2026-01-01'],[3,'e-11','100','2026-01-01'],[4,'e-11','100','2026-01-01'],[5,'a-999','100','2026-01-01'],[6,'a-011','100','2026-01-01'],[7,'a-11','1,000.00','not-date']] as [$id,$receipt,$amount,$date])insertFixture($a,'ram_proveedores',['id'=>$id,'comprobante_id'=>$receipt,'factura'=>'F','fecha'=>$date,'valor_factura'=>$amount,'banco_id'=>'1','categoria_id'=>'1','subcategoria_id'=>'1','observaciones'=>'']+$ts);
function invokePayables(string $mode):int{ob_start();$r=main(['migrate.php',$mode,'--payables']);ob_end_clean();return $r;}
function payablesSnapshot(PDO $p):string{$h=hash_init('sha256');foreach(array_merge(array_keys(payablesDefinitions()),['ram_users']) as $t){hash_update($h,$t);$q=$p->query('SELECT '.($t==='ram_users'?'id':'*').' FROM '.$t.' ORDER BY id');while($r=$q->fetch()){hash_update($h,json_encode($r,JSON_THROW_ON_ERROR));}}return hash_final($h);}
$before=payablesSnapshot($reader);$all=[];$runs=[];$start=$count;
foreach(['fr7_payables_a_test','fr7_payables_b_test'] as $db){
 $a->exec('CREATE DATABASE '.$db);putenv('FR7_TARGET_DB='.$db);coreCheck(invokePayables('--dry-run')===0,'payables dry run');coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='{$db}'")->fetchColumn()===0,'payables dry run no DDL');coreCheck(invokePayables('--execute')===0,'payables execute');
 $data=[];foreach(['suppliers','legacy_supplier_payment_items','supplier_invoice_candidates','supplier_invoices','supplier_invoice_items','supplier_payments'] as $t)$data[$t]=$a->query('SELECT * FROM '.$db.'.'.$t.' ORDER BY '.($t==='supplier_invoice_candidates'?'candidate_key':'id'))->fetchAll();$all[]=$data;$runs[]=$a->query('SELECT status,source_fingerprint,metrics FROM '.$db.'.migration_runs')->fetch();coreCheck($runs[array_key_last($runs)]['status']==='passed','payables run passed');
}
coreCheck($all[0]===$all[1]&&$runs[0]===$runs[1],'payables reproducible');$d=$all[0];coreCheck(count($d['suppliers'])===1&&count($d['legacy_supplier_payment_items'])===7,'every original row retained');
coreCheck(count($d['supplier_invoice_candidates'])===6,'only candidates grouped, unknown supplier isolated');
foreach(['supplier_invoices','supplier_invoice_items','supplier_payments'] as $t)coreCheck($d[$t]===[],'historical partial payments and headers never invented');
$items=array_column($d['legacy_supplier_payment_items'],null,'id');coreCheck($items[1]['fuel_load_id']==22&&$items[3]['fuel_load_id']==23,'both fuel namespaces resolved');coreCheck($items[5]['fuel_load_id']===null&&$items[5]['fuel_legacy_id']==999,'orphan retained');coreCheck($items[7]['reported_amount']===null&&$items[7]['legacy_reported_amount']==='1,000.00','invalid amount retained');
coreCheck((int)$a->query("SELECT COUNT(*) FROM fr7_payables_a_test.migration_exceptions WHERE code='DUPLICATE_RECEIPT_REFERENCE_PRESERVED'")->fetchColumn()===5,'all repeated receipt rows audited');
coreCheck($before===payablesSnapshot($reader),'payables source unchanged');
putenv('FR7_TARGET_DB=fr7_payables_b_test');$paymentsDb=connectDatabase('FR7_TARGET');
$paymentsDb->exec("INSERT INTO supplier_invoices (id,supplier_id,reference,total_amount) VALUES (1,8,'NEW-SYNTHETIC',1000)");
foreach([1000=>300,1001=>400,1002=>300,1003=>1] as $movement=>$amount){
 $paymentsDb->prepare("INSERT INTO financial_movements (id,legacy_id,legacy_period,description,reference,notes,legacy_account_id,legacy_category_id,legacy_subcategory_id,business_date,legacy_business_date,legacy_quantity,legacy_type,amount,direction) VALUES (?,?,'2026-01','Synthetic installment','NEW','',1,1,'1','2026-01-01','2026-01-01',?,'1',?,'outflow')")->execute([$movement,$movement,-$amount,$amount]);
}
$payInsert=$paymentsDb->prepare("INSERT INTO supplier_payments (supplier_invoice_id,amount,paid_on,financial_movement_id,reference,notes,idempotency_key) VALUES (1,?,'2026-01-01',?,'NEW','',?)");
foreach([1000=>300,1001=>400,1002=>300] as $movement=>$amount)$payInsert->execute([$amount,$movement,'installment-'.$movement]);
$amounts=$paymentsDb->query('SELECT amount FROM supplier_payments WHERE reversed_at IS NULL ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
coreCheck(count($amounts)===3&&supplierInstallmentBalance('1000',$amounts)['state']==='paid','schema stores multiple payments for one invoice');
$paymentsDb->exec('UPDATE supplier_payments SET reversed_at=NOW() WHERE financial_movement_id=1002');
$amounts=$paymentsDb->query('SELECT amount FROM supplier_payments WHERE reversed_at IS NULL ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
coreCheck(supplierInstallmentBalance('1000',$amounts)['balance']==='300.00','balance reads only active payments');
$rejected=false;try{$payInsert->execute([1,1003,'installment-1000']);}catch(PDOException $e){$rejected=true;}coreCheck($rejected,'database idempotency key is unique');
$rejected=false;try{$payInsert->execute([1,1000,'different-key']);}catch(PDOException $e){$rejected=true;}coreCheck($rejected,'one bank movement cannot represent two installments');
$rejected=false;try{$payInsert->execute([0,1003,'zero-amount']);}catch(PDOException $e){$rejected=true;}coreCheck($rejected,'nonpositive installment rejected by schema');
$a->exec('CREATE DATABASE fr7_payables_failure_test');putenv('FR7_TARGET_DB=fr7_payables_failure_test');$a->exec('ALTER TABLE fr7_source_test.ram_proveedores MODIFY observaciones VARCHAR(256) NOT NULL');$a->exec("UPDATE fr7_source_test.ram_proveedores SET observaciones=REPEAT('x',256) WHERE id=7");coreCheck(invokePayables('--execute')===1,'last payables domain fails');
foreach(array_unique(array_column(payablesDefinitions(),'target')) as $t)coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_payables_failure_test.'.$t)->fetchColumn()===0,'all source domains rolled back');
coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_payables_failure_test.migration_id_map')->fetchColumn()===0,'payables maps rolled back');coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_payables_failure_test.migration_exceptions')->fetchColumn()===0,'payables exceptions rolled back');coreCheck($a->query('SELECT status FROM fr7_payables_failure_test.migration_runs')->fetchColumn()==='failed','failed run retained');
echo 'PAYABLES INTEGRATION PASSED: '.($count-$start).' additional checks'.PHP_EOL;
