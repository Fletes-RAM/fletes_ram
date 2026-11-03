<?php
declare(strict_types=1);
function payablesDefinitions():array {
    return billingDefinitions()+array_diff_key(fuelDefinitions(),operationDefinitions())+[
        'ram_cat_proveedores'=>['target'=>'suppliers','domain'=>'payables','fields'=>['proveedor'=>'name','nombre_contacto'=>'contact_name','email'=>'email','telefono'=>'phone','observaciones'=>'notes'],'extra_columns'=>['deleted_at']],
        'ram_proveedores'=>['target'=>'legacy_supplier_payment_items','domain'=>'payables','fields'=>['comprobante_id'=>'legacy_receipt_reference','factura'=>'legacy_invoice_reference','fecha'=>'legacy_payment_date_text','valor_factura'=>'legacy_reported_amount','banco_id'=>'legacy_account_id','categoria_id'=>'legacy_category_id','subcategoria_id'=>'legacy_subcategory_id','observaciones'=>'notes']],
    ];
}
function payablesTransform(string $table,array $row,array $parents,array $context):array {
    if(isset(billingDefinitions()[$table]))return billingTransform($table,$row,$parents,$context);
    if(isset(fuelDefinitions()[$table]))return fuelTransform($table,$row,$parents);
    if(!preg_match('/^[1-9][0-9]*$/D',(string)$row['id']))throw new RuntimeException('INVALID_SOURCE_ID');
    [$v,$e]=mappedBillingTransform(payablesDefinitions()[$table],$row,$parents);
    if($table==='ram_cat_proveedores')return [$v,$e];
    $v['reported_amount']=coreDecimal((string)$row['valor_factura'],2,15);
    if($v['reported_amount']===null)$e[]=['code'=>'DECIMAL_TEXT_PRESERVED','field'=>'valor_factura'];
    $v['paid_on']=coreDate($row['fecha'],true);
    if($v['paid_on']===null)$e[]=['code'=>'INVALID_DATE_TO_NULL','field'=>'fecha'];
    $v['fuel_load_id']=null;$v['fuel_legacy_source']=null;$v['fuel_legacy_id']=null;$v['fuel_station_legacy_id']=null;
    if(preg_match('/^([ae])-([1-9][0-9]*)$/D',$row['comprobante_id'],$m)){
        $source=$m[1]==='a'?'ram_asignaciones_combustibles':'ram_asignaciones_especiales';
        $v['fuel_legacy_source']=$m[1]==='a'?'assignment':'special';$v['fuel_legacy_id']=$m[2];
        if(isset($parents[$source][$m[2]])){
            $v['fuel_load_id']=(string)((int)$m[2]*2+($m[1]==='e'?1:0));
            $v['fuel_station_legacy_id']=$context['fuel_stations'][$source][$m[2]];
        }else $e[]=['code'=>'ORPHAN_FUEL_REFERENCE_PRESERVED','field'=>'comprobante_id'];
    }else $e[]=['code'=>'UNKNOWN_RECEIPT_REFERENCE','field'=>'comprobante_id'];
    foreach(['banco_id'=>['ram_bancos','account_id'],'categoria_id'=>['ram_bancos_categorias','category_id'],'subcategoria_id'=>['ram_bancos_subcategorias','subcategory_id']] as $src=>[$parent,$dest]){
        $v[$dest]=isset($parents[$parent][(string)$row[$src]])?(string)$row[$src]:null;
        if($v[$dest]===null)$e[]=['code'=>'ORPHAN_REFERENCE_PRESERVED','field'=>$src];
    }
    // Candidate grouping is a review aid, never proof of invoice identity or a payment.
    $identity=$v['fuel_station_legacy_id']!==null&&$v['fuel_station_legacy_id']!=='0'?$v['fuel_station_legacy_id']:('unresolved-row:'.$row['id']);
    $v['candidate_key']=hash('sha256',json_encode([$identity,$row['factura'],$row['fecha'],$row['valor_factura'],$row['banco_id'],$row['categoria_id'],$row['subcategoria_id']],JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE));
    $v['reconciliation_status']='unresolved_header';
    return [$v,$e];
}
function derivePayables(PDO $p,string $runId):array {
    $p->exec("INSERT INTO supplier_invoice_candidates (candidate_key,review_status,item_count) SELECT candidate_key,'unverified',COUNT(*) FROM legacy_supplier_payment_items GROUP BY candidate_key ORDER BY candidate_key");
    $q=$p->prepare("INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action)
        SELECT ?,'payables','ram_proveedores',CONCAT('candidate:',candidate_key),'supplier_invoice_candidates',candidate_key,'mapped' FROM supplier_invoice_candidates ORDER BY candidate_key");$q->execute([$runId]);
    $q=$p->prepare("INSERT INTO migration_exceptions (migration_run_id,domain,source_table,source_id,code,severity,resolution,status,target_table,target_id)
        SELECT ?,'payables','ram_proveedores',p.id,'DUPLICATE_RECEIPT_REFERENCE_PRESERVED','warning','Repeated receipt retained; no payment deduplicated or summed','preserved','legacy_supplier_payment_items',p.id
        FROM legacy_supplier_payment_items p JOIN (SELECT CAST(legacy_receipt_reference AS BINARY) AS receipt FROM legacy_supplier_payment_items GROUP BY CAST(legacy_receipt_reference AS BINARY) HAVING COUNT(*)>1) d ON CAST(p.legacy_receipt_reference AS BINARY)=d.receipt ORDER BY p.id");$q->execute([$runId]);
    if((int)$p->query('SELECT COUNT(*) FROM legacy_supplier_payment_items p LEFT JOIN supplier_invoice_candidates c ON c.candidate_key=p.candidate_key WHERE c.candidate_key IS NULL')->fetchColumn()!==0)throw new RuntimeException('PAYABLE_CANDIDATE_RECONCILIATION_FAILED');
    if((int)$p->query('SELECT COUNT(*) FROM supplier_invoice_candidates c WHERE c.item_count<>(SELECT COUNT(*) FROM legacy_supplier_payment_items p WHERE p.candidate_key=c.candidate_key)')->fetchColumn()!==0)throw new RuntimeException('PAYABLE_CANDIDATE_COUNT_FAILED');
    $q=$p->prepare("SELECT COUNT(*) FROM supplier_invoice_candidates c LEFT JOIN migration_id_map m ON m.migration_run_id=? AND m.domain='payables' AND m.source_table='ram_proveedores' AND m.source_id=CONCAT('candidate:',c.candidate_key) AND m.target_table='supplier_invoice_candidates' AND m.target_id=c.candidate_key WHERE m.id IS NULL");$q->execute([$runId]);if((int)$q->fetchColumn()!==0)throw new RuntimeException('PAYABLE_CANDIDATE_MAPPING_FAILED');
    foreach(['supplier_invoices','supplier_invoice_items','supplier_payments'] as $t)if((int)$p->query('SELECT COUNT(*) FROM '.$t)->fetchColumn()!==0)throw new RuntimeException('UNSUPPORTED_HISTORICAL_PAYMENT_INFERENCE');
    return ['preserved_items'=>(int)$p->query('SELECT COUNT(*) FROM legacy_supplier_payment_items')->fetchColumn(),'unverified_candidates'=>(int)$p->query('SELECT COUNT(*) FROM supplier_invoice_candidates')->fetchColumn(),'unresolved_fuel'=>(int)$p->query('SELECT COUNT(*) FROM legacy_supplier_payment_items WHERE fuel_load_id IS NULL')->fetchColumn(),'historical_headers_and_payments_created'=>0];
}
function payablesRelations():array {
    return array_merge(billingRelations(),[['fuel_loads','assignment_id','assignments'],['fuel_loads','vehicle_id','vehicles'],['fuel_loads','fuel_station_id','fuel_stations'],['legacy_supplier_payment_items','fuel_load_id','fuel_loads'],['legacy_supplier_payment_items','account_id','financial_accounts'],['legacy_supplier_payment_items','category_id','financial_categories'],['legacy_supplier_payment_items','subcategory_id','financial_subcategories']]);
}
function supplierInstallmentBalance(string $total,array $activePayments,?string $newPayment=null):array {
    $toCents=function(string $amount):int{$d=coreDecimal($amount,2,15);if($d===null||str_starts_with($d,'-'))throw new RuntimeException('INVALID_PAYMENT_AMOUNT');return (int)str_replace('.','',$d);};
    $invoice=$toCents($total);$paid=0;
    foreach(array_merge($activePayments,$newPayment===null?[]:[$newPayment]) as $payment){$c=$toCents($payment);if($c===0)throw new RuntimeException('POSITIVE_PAYMENT_REQUIRED');if($c>$invoice-$paid)throw new RuntimeException('PAYMENT_EXCEEDS_BALANCE');$paid+=$c;}
    $format=fn(int $c)=>(string)intdiv($c,100).'.'.str_pad((string)($c%100),2,'0',STR_PAD_LEFT);
    return ['paid'=>$format($paid),'balance'=>$format($invoice-$paid),'state'=>$paid===$invoice?'paid':($paid===0?'pending':'partial')];
}
